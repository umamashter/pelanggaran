<?php

namespace App\Http\Controllers;

use App\Models\StrukturKurikulum;
use Illuminate\Http\Request;
use App\Models\Kurikulum;
use App\Models\Kelas;
use App\Models\MataPelajaran;

class StrukturKurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = StrukturKurikulum::with(
            'kurikulum',
            'kelas',
            'mataPelajaran'
        )->get();

        $kurikulums = Kurikulum::all();

        $kelas = Kelas::all();

        $mapels = MataPelajaran::all();

        return view(
            'admin.struktur-kurikulum.index',
            compact(
                'datas',
                'kurikulums',
                'kelas',
                'mapels'
            )
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
        StrukturKurikulum::create([

            'kurikulum_id' =>
            $request->kurikulum_id,

            'kelas_id' =>
            $request->kelas_id,

            'mata_pelajaran_id' =>
            $request->mata_pelajaran_id,

            'jam_pelajaran' =>
            $request->jam_pelajaran

        ]);

        return back()->with(
            'success',
            'Data berhasil ditambahkan'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StrukturKurikulum  $strukturKurikulum
     * @return \Illuminate\Http\Response
     */
    public function show(StrukturKurikulum $strukturKurikulum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StrukturKurikulum  $strukturKurikulum
     * @return \Illuminate\Http\Response
     */
    public function edit(StrukturKurikulum $strukturKurikulum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StrukturKurikulum  $strukturKurikulum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StrukturKurikulum $strukturKurikulum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StrukturKurikulum  $strukturKurikulum
     * @return \Illuminate\Http\Response
     */
    public function destroy(StrukturKurikulum $strukturKurikulum)
    {
        //
    }
}
