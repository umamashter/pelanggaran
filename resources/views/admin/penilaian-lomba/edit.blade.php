@extends('layouts.main')
@section('title', 'Edit Penilaian Lomba')

@push('css')
<style>
    .page-title-content { display: none !important; }
    .edit-penilaian-page {
        font-family: 'Inter', 'Poppins', system-ui, sans-serif;
        max-width: 640px;
        margin: 22px auto 0;
        padding: 0 16px;
    }
    .breadcrumb-cu { margin-bottom: 20px; }
    .breadcrumb-cu .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-cu .breadcrumb-item { font-size: 13px; }
    .breadcrumb-cu .breadcrumb-item a { color: #64748b; text-decoration: none; }
    .breadcrumb-cu .breadcrumb-item a:hover { color: #16a34a; }
    .breadcrumb-cu .breadcrumb-item.active { color: #1e293b; font-weight: 500; }
    .breadcrumb-cu .breadcrumb-item+.breadcrumb-item::before { color: #cbd5e1; }
    .create-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
    .create-card-header { padding: 24px 28px 20px; border-bottom: 1px solid #f1f5f9; }
    .create-card-body { padding: 24px 28px 28px; }
    .form-label-cu { font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 8px; display: block; }
    .invalid-feedback-cu { display: flex; align-items: center; gap: 6px; margin-top: 6px; font-size: 13px; color: #dc2626; font-weight: 500; }
    .btn-cu { height: 44px; padding: 0 28px; border-radius: 10px; font-size: 14px; font-weight: 600; transition: all .25s; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; border: none; gap: 8px; }
    .btn-cu-secondary { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-cu-secondary:hover { background: #e2e8f0; color: #334155; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.08); }
    .btn-cu-primary { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
    .btn-cu-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); color: #fff; }
    .form-actions-cu { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    select.form-control-cu, input.form-control-cu { border: 1.5px solid #e2e8f0; border-radius: 10px; height: 46px; font-size: 14px; padding: 0 14px; width: 100%; transition: border .2s, box-shadow .2s; }
    select.form-control-cu:focus, input.form-control-cu:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); outline: none; }
    .empty-peserta-alert {
        background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 10px;
        padding: 14px 16px; font-size: 13px; color: #92400e; display: flex; align-items: center; gap: 8px;
    }
    .btn-header-ms.btn-simpan-ms.btn-compact { height: 34px; padding: 0 14px; font-size: 12px; border-radius: 8px; }
    @media (max-width: 768px) {
        .edit-penilaian-page { margin-top: 16px; padding: 0 12px; }
        .create-card-header, .create-card-body { padding: 18px 20px; }
        .form-actions-cu { flex-direction: column; }
        .form-actions-cu .btn-cu { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="edit-penilaian-page">

    <nav aria-label="breadcrumb" class="breadcrumb-cu">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('penilaian-lomba.index') }}">Penilaian Lomba</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Penilaian</li>
        </ol>
    </nav>

    <div class="card create-card">
        <div class="create-card-header">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon" style="width:48px;height:48px;font-size:22px;">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #1e293b; font-size: 18px;">Edit Penilaian Lomba</h4>
                    <span style="font-size: 13px; color: #64748b;">Ubah nilai semua aspek untuk peserta ini.</span>
                </div>
            </div>
        </div>

        <div class="create-card-body">

            @if ($errors->any())
                <div class="alert alert-danger" style="border:none;border-radius:12px;padding:14px 20px;font-size:14px;background:#fef2f2;color:#991b1b;border-left:4px solid #dc2626;margin-bottom:20px;">
                    <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan:
                    <ul class="mt-2 mb-0" style="padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('penilaian-lomba.update', $penilaianLomba->id) }}" method="POST" id="penilaianForm">
                @csrf @method('PUT')

                {{-- Step 1: Sesi (disabled — only display) --}}
                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-calendar-day me-1" style="color:#16a34a;"></i> Sesi Lomba</label>
                    <select id="sesi_lomba_id" class="form-control-cu" disabled>
                        <option value="">-- Pilih Sesi --</option>
                        @foreach($sesiLombas as $s)
                        <option value="{{ $s->id }}" {{ $s->id == $currentSesiId ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Step 2: Lomba (disabled — only display) --}}
                <div class="mb-4">
                    <label class="form-label-cu">
                        <i class="fas fa-trophy me-1" style="color:#16a34a;"></i> Lomba
                        <span class="badge {{ $lombaJenis === 'Tim' ? 'bg-warning text-dark' : 'bg-secondary' }}" style="border-radius:8px;padding:2px 10px;font-size:11px;margin-left:6px;vertical-align:middle;">
                            {{ $lombaJenis ?? '-' }}
                        </span>
                        @if ($lombaJenis === 'Tim' && $currentKelompokId)
                            @php
                                $klEdit = $kelompokLombas->firstWhere('id', $currentKelompokId);
                            @endphp
                            @if ($klEdit)
                                <a href="{{ route('kelompok-lomba.show', $klEdit->id) }}" class="text-decoration-none ms-1" style="vertical-align:middle;" title="Lihat Detail Kelompok">
                                    <i class="fas fa-users me-1" style="font-size:12px;"></i>
                                    <small>{{ $klEdit->nama_kelompok }}</small>
                                </a>
                            @endif
                        @endif
                    </label>
                    <select id="lomba_id" class="form-control-cu" disabled>
                        <option value="">-- Pilih Lomba --</option>
                        @foreach($lombas as $l)
                        <option value="{{ $l->id }}" {{ $l->id == $penilaianLomba->pesertaLomba->lomba_id ? 'selected' : '' }}>{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($lombaJenis === 'Tim')
                {{-- Step 3 (Tim): Kelompok (disabled — hidden for submit) --}}
                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-gavel me-1" style="color:#16a34a;"></i> Juri</label>
                    <select class="form-control-cu" disabled>
                        <option value="">-- Pilih Juri --</option>
                        @foreach($juriLombas as $jl)
                        <option value="{{ $jl->id }}" {{ old('juri_lomba_id', $penilaianLomba->juri_lomba_id) == $jl->id ? 'selected' : '' }}>{{ $jl->guru->nama ?? '-' }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="juri_lomba_id" value="{{ old('juri_lomba_id', $penilaianLomba->juri_lomba_id) }}">
                </div>

                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-users me-1" style="color:#16a34a;"></i> Nama Kelompok</label>
                    <select id="kelompok_lomba_id" class="form-control-cu" disabled>
                        <option value="">-- Pilih Kelompok --</option>
                        @foreach($kelompokLombas as $kl)
                        <option value="{{ $kl->id }}" {{ $currentKelompokId == $kl->id ? 'selected' : '' }}>{{ $kl->kode_kelompok ? $kl->kode_kelompok . ' - ' : '' }}{{ $kl->nama_kelompok }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="kelompok_lomba_id" value="{{ $currentKelompokId }}">
                    <input type="hidden" name="peserta_lomba_id" value="{{ $penilaianLomba->peserta_lomba_id }}">
                </div>
                @else
                {{-- Step 3 (Individu): Juri (disabled — hidden for submit) --}}
                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-gavel me-1" style="color:#16a34a;"></i> Juri</label>
                    <select id="juri_lomba_id" class="form-control-cu" disabled>
                        <option value="">-- Pilih Juri --</option>
                        @foreach($juriLombas as $jl)
                        <option value="{{ $jl->id }}" {{ old('juri_lomba_id', $penilaianLomba->juri_lomba_id) == $jl->id ? 'selected' : '' }}>{{ $jl->guru->nama ?? '-' }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="juri_lomba_id" value="{{ old('juri_lomba_id', $penilaianLomba->juri_lomba_id) }}">
                    @error('juri_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>

                {{-- Step 4 (Individu): Peserta (disabled — hidden for submit) --}}
                <div class="mb-4">
                    <label class="form-label-cu"><i class="fas fa-user-graduate me-1" style="color:#16a34a;"></i> Peserta</label>
                    <select id="peserta_lomba_id" class="form-control-cu" disabled>
                        <option value="">-- Pilih Peserta --</option>
                        @foreach($pesertaLombas as $pl)
                        <option value="{{ $pl->id }}" {{ old('peserta_lomba_id', $penilaianLomba->peserta_lomba_id) == $pl->id ? 'selected' : '' }}>{{ $pl->isIndividu() ? ($pl->student->user->name ?? $pl->student->nama ?? '-') : ($pl->kelompokLomba->nama_kelompok ?? '-') }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="peserta_lomba_id" value="{{ old('peserta_lomba_id', $penilaianLomba->peserta_lomba_id) }}">
                    @error('peserta_lomba_id')<div class="invalid-feedback-cu"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
                @endif

                {{-- Step 5: Aspek + Nilai --}}
                <div id="aspek-table-wrapper">
                    <div class="mb-3">
                        <label class="form-label-cu"><i class="fas fa-clipboard-list me-1" style="color:#16a34a;"></i> Aspek Penilaian</label>
                        <div style="font-size:13px;color:#64748b;margin-bottom:12px;">
                            <i class="fas fa-info-circle me-1"></i> Ubah nilai untuk setiap aspek (0 - 100).
                        </div>
                        <div class="table-responsive" style="border:1.5px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                            <table class="table table-bordered mb-0" style="min-width:420px;">
                                <thead style="background:#f8fafc;">
                                    <tr>
                                        <th style="width:50px;text-align:center;font-size:13px;padding:10px 8px;">No</th>
                                        <th style="font-size:13px;padding:10px 8px;">Aspek</th>
                                        <th style="width:120px;text-align:center;font-size:13px;padding:10px 8px;">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody id="aspek-table-body">
                                    @forelse($aspekPenilaians as $idx => $ap)
                                    <tr>
                                        <td style="text-align:center;vertical-align:middle;padding:10px 8px;font-size:13px;">{{ $idx + 1 }}</td>
                                        <td style="vertical-align:middle;padding:10px 8px;font-size:14px;">{{ $ap->nama_aspek }}</td>
                                        <td style="vertical-align:middle;padding:6px 8px;">
                                            <input type="number" step="0.01" name="nilai[]"
                                                class="form-control form-control-sm aspek-nilai"
                                                style="border:1.5px solid #e2e8f0;border-radius:8px;height:38px;font-size:14px;text-align:center;width:100%;"
                                                placeholder="0" min="0" max="100"
                                                value="{{ old('nilai.' . $idx, isset($allPenilaian[$ap->id]) ? $allPenilaian[$ap->id]->nilai : '') }}">
                                            <input type="hidden" name="aspek_penilaian_id[]" value="{{ $ap->id }}">
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" style="text-align:center;padding:20px;color:#94a3b8;font-size:14px;">Tidak ada aspek penilaian untuk lomba ini</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div id="aspek-error" class="invalid-feedback-cu" style="display:none;margin-top:8px;">
                            <i class="fas fa-exclamation-circle"></i> <span></span>
                        </div>
                    </div>
                </div>

                <div class="form-actions-cu">
                    <a href="{{ route('penilaian-lomba.index') }}" class="btn btn-header-ms btn-simpan-ms btn-compact" style="background:#fef3c7;color:#92400e;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-header-ms btn-simpan-ms btn-compact" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('penilaianForm').addEventListener('submit', function(e) {
        var inputs = document.querySelectorAll('.aspek-nilai');
        var hasValue = false;
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value !== '' && parseFloat(inputs[i].value) >= 0) {
                hasValue = true;
                break;
            }
        }
        if (!hasValue) {
            e.preventDefault();
            var errEl = document.getElementById('aspek-error');
            errEl.querySelector('span').textContent = 'Minimal satu aspek harus diisi nilai.';
            errEl.style.display = 'flex';
            document.getElementById('aspek-table-wrapper').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endpush
