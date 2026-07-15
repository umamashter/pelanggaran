@extends('layouts.main')

@section('title', 'Data Alumni')

@section('content')

<div class="card shadow">

    ```
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="fas fa-user-graduate"></i>
            Data Alumni
        </h4>
    </div>

    <div class="card-body">

        {{-- Statistik --}}
        <div class="alert alert-info">
            Total Alumni :
            <strong>{{ $alumni->count() }}</strong>
        </div>

        {{-- Filter --}}
        <form method="GET" class="mb-3">
            <div class="row align-items-end">

                <div class="col-md-4">
                    <label class="form-label">
                        Filter Tahun Ajaran
                    </label>

                    <select
                        name="tahun_ajaran_id"
                        class="form-control"
                        onchange="this.form.submit()">

                        <option value="">
                            Semua Tahun Ajaran
                        </option>

                        @foreach($tahunAjarans as $tahun)
                        <option
                            value="{{ $tahun->id }}"
                            {{ request('tahun_ajaran_id') == $tahun->id ? 'selected' : '' }}>

                            {{ $tahun->tahun_ajaran }}
                            - {{ $tahun->semester ?? '-' }}

                        </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-8 text-end">

                    <a href="{{ route('alumni.index') }}"
                        class="btn btn-secondary">

                        <i class="fas fa-sync"></i>
                        Reset
                    </a>

                    <a href="{{ route('alumni.pdf', [
                    'tahun_ajaran_id' => request('tahun_ajaran_id')
                ]) }}"
                        class="btn btn-danger">

                        <i class="fas fa-file-pdf"></i>
                        Export PDF
                    </a>

                </div>

            </div>
        </form>

        <table
            id="table_alumni"
            class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th width="5%">No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas Terakhir</th>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($alumni as $i => $a)

                <tr>

                    <td>{{ $i + 1 }}</td>

                    <td>{{ $a->nisn }}</td>

                    <td>{{ $a->nama }}</td>

                    <td>
                        {{ $a->kelas->nama_kelas ?? '-' }}
                    </td>

                    <td>
                        {{ $a->tahunAjaran->tahun_ajaran ?? '-' }}
                    </td>

                    <td>
                        <span class="badge bg-success">
                            Alumni
                        </span>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center">
                        Tidak ada data alumni.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>
    ```

</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function() {

        $('#table_alumni').DataTable({

            responsive: true,

            pageLength: 10,

            language: {
                url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json'
            }

        });

    });
</script>

@endpush