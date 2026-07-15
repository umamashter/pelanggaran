<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kurikulums = Kurikulum::latest()->get();

        return view(
            'admin.kurikulum.index',
            compact('kurikulums')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Kurikulum::create([
            'nama_kurikulum' => $request->nama_kurikulum,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with(
            'success',
            'Data berhasil ditambahkan'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kurikulum  $kurikulum
     * @return \Illuminate\Http\Response
     */
    public function show(Kurikulum $kurikulum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kurikulum  $kurikulum
     * @return \Illuminate\Http\Response
     */
    public function edit(Kurikulum $kurikulum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kurikulum  $kurikulum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kurikulum $kurikulum)
    {
        $kurikulum->update([
            'nama_kurikulum' => $request->nama_kurikulum,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with(
            'success',
            'Data berhasil diperbarui'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kurikulum  $kurikulum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kurikulum $kurikulum)
    {
        $kurikulum->delete();

        return back();
    }
    public function aktifkan($id)
    {
        Kurikulum::query()->update([
            'aktif' => false
        ]);

        Kurikulum::where('id', $id)
            ->update([
                'aktif' => true
            ]);

        return back()->with(
            'success',
            'Kurikulum berhasil diaktifkan'
        );
    }
}
