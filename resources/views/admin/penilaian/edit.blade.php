@extends('layouts.main')

@section('title','Edit Nilai')

@section('content')

<div class="card shadow">

    <div class="card-header bg-warning">

        <h4>
            Edit Nilai
            {{ $penilaian->jadwal->mapel->nama_mapel }}
        </h4>

    </div>

    <div class="card-body">

        <form action="{{ route('penilaian.update',$penilaian->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>Nama</th>
                        <th>Tugas</th>
                        <th>UH</th>
                        <th>PTS</th>
                        <th>PAS</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($penilaian->details as $detail)

                    <tr>

                        <td>
                            {{ $detail->student->nama }}
                        </td>

                        <td>
                            <input
                                type="number"
                                name="tugas[{{ $detail->id }}]"
                                value="{{ $detail->tugas }}"
                                class="form-control">
                        </td>

                        <td>
                            <input
                                type="number"
                                name="uh[{{ $detail->id }}]"
                                value="{{ $detail->uh }}"
                                class="form-control">
                        </td>

                        <td>
                            <input
                                type="number"
                                name="pts[{{ $detail->id }}]"
                                value="{{ $detail->pts }}"
                                class="form-control">
                        </td>

                        <td>
                            <input
                                type="number"
                                name="pas[{{ $detail->id }}]"
                                value="{{ $detail->pas }}"
                                class="form-control">
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <button
                type="submit"
                class="btn btn-success">

                Update Nilai

            </button>

        </form>

    </div>

</div>

@endsection