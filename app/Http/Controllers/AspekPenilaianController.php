<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AspekPenilaian;
use App\Models\Lomba;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormPenilaianExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

use Illuminate\Pagination\LengthAwarePaginator;

class AspekPenilaianController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index()
    {
        $all = AspekPenilaian::with('lomba')->latest()->get()
            ->groupBy(fn ($a) => $a->lomba_id);

        $grouped = $all->map(function ($items) {
            $first = $items->first();
            return (object) [
                'lomba_id'       => $first->lomba_id,
                'lomba'          => $first->lomba,
                'jumlah_aspek'   => $items->count(),
                'is_haflah_selesai' => $first->is_haflah_selesai,
                'latest_id'      => $items->max('id'),
            ];
        })->sortByDesc('latest_id')->values();

        $page = request()->input('page', 1);
        $perPage = 10;
        $aspekPenilaians = new LengthAwarePaginator(
            $grouped->slice(($page - 1) * $perPage, $perPage),
            $grouped->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        $lombas = Lomba::orderBy('nama')->get();

        return view('admin.aspek-penilaian.index', compact('aspekPenilaians', 'lombas'));
    }

    public function show($lombaId)
    {
        $lomba = Lomba::findOrFail($lombaId);
        $aspekPenilaians = AspekPenilaian::where('lomba_id', $lombaId)
            ->orderBy('nama_aspek')
            ->get();

        return view('admin.aspek-penilaian.show', compact('lomba', 'aspekPenilaians'));
    }

    public function create()
    {
        $lombas = Lomba::orderBy('nama')->get();

        return view('admin.aspek-penilaian.create', compact('lombas'));
    }

    public function exportForm($lombaId)
    {
        $lomba = Lomba::with(['aspekPenilaians', 'peserta.student.user'])->findOrFail($lombaId);

        if ($lomba->aspekPenilaians->isEmpty()) {
            return redirect()->route('aspek-penilaian.index')
                ->with('error', 'Lomba ini belum memiliki aspek penilaian.');
        }

        if ($lomba->peserta->isEmpty()) {
            return redirect()->route('aspek-penilaian.index')
                ->with('error', 'Lomba ini belum memiliki peserta.');
        }

        $fileName = 'form-penilaian-' . str_replace(' ', '-', $lomba->nama) . '.xlsx';

        return Excel::download(new FormPenilaianExport($lomba), $fileName);
    }

    public function cetakPdf($lombaId)
    {
        $lomba = Lomba::with(['aspekPenilaians', 'peserta.student.user', 'peserta.kelompokLomba'])->findOrFail($lombaId);

        if ($lomba->aspekPenilaians->isEmpty()) {
            return redirect()->route('aspek-penilaian.index')
                ->with('error', 'Lomba ini belum memiliki aspek penilaian.');
        }

        if ($lomba->peserta->isEmpty()) {
            return redirect()->route('aspek-penilaian.index')
                ->with('error', 'Lomba ini belum memiliki peserta.');
        }

        $pdf = Pdf::loadView('admin.aspek-penilaian.pdf', compact('lomba'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('form-penilaian-' . str_replace(' ', '-', $lomba->nama) . '.pdf');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->blockStoreIfHaflahSelesai()) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'nama_aspek' => 'required|array|min:1',
            'nama_aspek.*' => 'required|max:255',
        ]);

        $haflahId = session('haflah_id');
        $data = [];
        foreach ($request->nama_aspek as $nama) {
            $data[] = [
                'lomba_id' => $request->lomba_id,
                'nama_aspek' => $nama,
                'haflah_id' => $haflahId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AspekPenilaian::insert($data);

        return redirect()->route('aspek-penilaian.index')
            ->with('success', count($data) . ' aspek penilaian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ref = AspekPenilaian::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($ref->haflah_id)) {
            return $redirect;
        }

        $lomba = $ref->lomba;
        $aspekPenilaians = AspekPenilaian::where('lomba_id', $ref->lomba_id)
            ->orderBy('nama_aspek')
            ->get();
        $lombas = Lomba::orderBy('nama')->get();

        return view('admin.aspek-penilaian.edit', compact('lomba', 'aspekPenilaians', 'lombas'));
    }

    public function update(Request $request, $id)
    {
        $ref = AspekPenilaian::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($ref->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'lomba_id' => 'required|exists:lombas,id',
            'nama_aspek' => 'required|array|min:1',
            'nama_aspek.*' => 'required|max:255',
        ]);

        $haflahId = session('haflah_id');

        AspekPenilaian::where('lomba_id', $ref->lomba_id)->delete();

        $data = [];
        foreach ($request->nama_aspek as $nama) {
            $data[] = [
                'lomba_id' => $request->lomba_id,
                'nama_aspek' => $nama,
                'haflah_id' => $haflahId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AspekPenilaian::insert($data);

        return redirect()->route('aspek-penilaian.show', $request->lomba_id)
            ->with('success', count($data) . ' aspek penilaian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aspekPenilaian = AspekPenilaian::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($aspekPenilaian->haflah_id)) {
            return $redirect;
        }

        $aspekPenilaian->delete();

        return redirect()->route('aspek-penilaian.index')
            ->with('success', 'Aspek penilaian berhasil dihapus.');
    }

    public function destroyByLomba($lombaId)
    {
        $sample = AspekPenilaian::where('lomba_id', $lombaId)->first();

        if ($sample && $redirect = $this->blockIfHaflahSelesai($sample->haflah_id)) {
            return $redirect;
        }

        $count = AspekPenilaian::where('lomba_id', $lombaId)->delete();

        return redirect()->route('aspek-penilaian.index')
            ->with('success', $count . ' aspek penilaian berhasil dihapus.');
    }
}
