@extends('layouts.main')
@section('title', 'Penilaian Lomba')
@section('content')
@include('component.admin.ms-style')
<style>
    .btn-header-ms.btn-tambah-ms.btn-compact {
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        border-radius: 8px;
    }

    /* ---- Tab nav ---- */
    .nav-tab-ms {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
        gap: 0;
    }

    .nav-tab-ms .nav-link {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
        padding: 10px 20px;
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all .25s;
        background: transparent;
        border-radius: 8px 8px 0 0;
    }

    .nav-tab-ms .nav-link:hover {
        color: #475569;
        border-bottom-color: #cbd5e1;
    }

    .nav-tab-ms .nav-link.active {
        color: #16a34a;
        border-bottom-color: #16a34a;
        background: transparent;
    }

    .nav-tab-ms .nav-link .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 10px;
        margin-left: 6px;
        vertical-align: middle;
    }

    .nav-tab-ms .nav-link.active .badge {
        background: #dcfce7;
        color: #16a34a;
    }

    .nav-tab-ms .nav-link:not(.active) .badge {
        background: #f1f5f9;
        color: #94a3b8;
    }

    /* ---- Modal Hapus ---- */
    .modal-header-custom {
        padding: 18px 24px;
        border-bottom: none;
    }

    .modal-body-custom {
        padding: 16px 24px 20px;
    }

    html:not(.dark-mode) .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 24px 80px rgba(0, 0, 0, .15);
        overflow: hidden;
    }

    html.dark-mode .modal-content {
        background: #0d2f38 !important;
        border: 2px solid #175265 !important;
        border-radius: 24px !important;
        box-shadow: 0 30px 70px -16px rgba(0, 0, 0, .7) !important;
    }

    html:not(.dark-mode) .modal.fade .modal-dialog {
        transform: scale(0.92) translateY(16px);
        transition: transform .3s cubic-bezier(.2, .8, .2, 1);
    }

    html:not(.dark-mode) .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }

    .delete-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 4px;
    }

    html:not(.dark-mode) .delete-icon-wrap {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        animation: deletePulse 2s ease-in-out infinite;
    }

    html.dark-mode .delete-icon-wrap {
        background: rgba(220, 38, 38, .15);
        box-shadow: 0 0 20px rgba(220, 38, 38, .1);
    }

    .delete-icon-wrap i {
        font-size: 32px;
        color: #dc2626;
    }

    @keyframes deletePulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(220, 38, 38, .15)
        }

        50% {
            box-shadow: 0 0 0 12px rgba(220, 38, 38, 0)
        }
    }

    .delete-info-box {
        border-left: 4px solid #dc2626;
        border-radius: 12px;
        padding: 14px 18px;
    }

    html:not(.dark-mode) .delete-info-box {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1px solid #e2e8f0;
    }

    html.dark-mode .delete-info-box {
        background: rgba(255, 255, 255, .04);
        border: 1px solid rgba(255, 255, 255, .1);
    }

    .delete-info-box .delete-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #94a3b8;
        margin-bottom: 2px;
    }

    .delete-info-box .delete-value {
        font-weight: 700;
        font-size: 16px;
    }

    html:not(.dark-mode) .delete-info-box .delete-value {
        color: var(--ms-text);
    }

    html.dark-mode .delete-info-box .delete-value {
        color: var(--text-primary);
    }

    .btn-delete-final {
        border: none !important;
        border-radius: 10px !important;
        padding: 9px 22px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        transition: all .25s !important;
    }

    html:not(.dark-mode) .btn-delete-final {
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(220, 38, 38, .3) !important;
    }

    html.dark-mode .btn-delete-final {
        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(220, 38, 38, .4) !important;
    }

    .btn-delete-final:hover {
        transform: translateY(-2px) !important;
    }

    .btn-cancel-modal {
        border: none !important;
        border-radius: 10px !important;
        padding: 9px 22px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        transition: all .25s !important;
    }

    html:not(.dark-mode) .btn-cancel-modal {
        background: #f1f5f9 !important;
        color: #475569 !important;
    }

    html:not(.dark-mode) .btn-cancel-modal:hover {
        background: #e2e8f0 !important;
        color: #1e293b !important;
    }

    html.dark-mode .btn-cancel-modal {
        background: rgba(255, 255, 255, .08) !important;
        color: var(--text-secondary) !important;
    }

    html.dark-mode .btn-cancel-modal:hover {
        background: rgba(255, 255, 255, .14) !important;
        color: var(--text-primary) !important;
    }

    .btn-cancel-modal:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .card.border-0.shadow-sm.mb-4 .btn-compact {
            width: fit-content;
            align-self: flex-start;
        }
    }
</style>

<div class="master-siswa-page">

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-star"></i></div>
                    <div>
                        <h4 class="mb-0 fw-bold" style="color: var(--ms-text); font-size: 20px;">Penilaian Lomba</h4>
                    </div>
                </div>
                <div class="d-flex gap-2" id="tambahBtnWrap">
                    <a href="{{ route('penilaian-lomba.create', ['mode' => 'individu']) }}" class="btn btn-header-ms btn-tambah-ms btn-compact tombol-tambah" id="btnTambahIndividu">
                        <i class="fas fa-user me-1"></i> Tambah Penilaian
                    </a>
                    <a href="{{ route('penilaian-lomba.create', ['mode' => 'tim']) }}" class="btn btn-header-ms btn-tambah-ms btn-compact tombol-tambah" id="btnTambahTim" style="display:none;background:#eff6ff;color:#2563eb;">
                        <i class="fas fa-users me-1"></i> Tambah Penilaian
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-modern-ms alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card table-card">
        <div class="card-body">

            <ul class="nav nav-tab-ms" id="penilaianTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-individu" data-bs-toggle="tab" data-bs-target="#pane-individu" type="button" role="tab">
                        <i class="fas fa-user me-1"></i> Lomba Individu
                        <span class="badge">{{ $individu->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-tim" data-bs-toggle="tab" data-bs-target="#pane-tim" type="button" role="tab">
                        <i class="fas fa-users me-1"></i> Lomba Kelompok
                        <span class="badge">{{ $tim->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="penilaianTabContent">

                {{-- TAB INDIVIDU --}}
                <div class="tab-pane fade show active" id="pane-individu" role="tabpanel">
                    <div class="table-responsive">
                        <table id="table_individu" class="table table-ms display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Lomba</th>
                                    <th>Peserta</th>
                                    <th>Juri</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($individu as $row)
                                @php $lomba = $row->pesertaLomba->lomba ?? null; @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $lomba->nama ?? '-' }}</td>
                                    <td>{{ $row->pesertaLomba->student->user->name ?? '-' }}</td>
                                    <td><span class="badge bg-info" style="border-radius:8px;padding:4px 12px;">{{ $row->jumlah_juri }} juri</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('penilaian-lomba.show', $row->id) }}" class="btn btn-outline-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                            @if($row->is_haflah_selesai || $row->pesertaLomba->hasil)
                                            <span class="btn btn-outline-secondary btn-sm" title="{{ $row->pesertaLomba->hasil ? 'Sudah diproses' : 'Terkunci' }}" style="cursor:not-allowed;opacity:.5;pointer-events:none;"><i class="fas {{ $row->pesertaLomba->hasil ? 'fa-ban' : 'fa-lock' }}"></i></span>
                                            @else
                                            <a href="{{ route('penilaian-lomba.edit', $row->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#hapusModal"
                                                data-nama="{{ $row->pesertaLomba->student->user->name ?? '-' }}"
                                                data-url="{{ route('penilaian-lomba.hapus-semua', $row->pesertaLomba->id) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="padding:32px;color:#94a3b8;">Belum ada penilaian individu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB TIM --}}
                <div class="tab-pane fade" id="pane-tim" role="tabpanel">
                    <div class="table-responsive">
                        <table id="table_tim" class="table table-ms display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Lomba</th>
                                    <th>Kelompok</th>
                                    <th>Juri</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tim as $row)
                                @php $lomba = $row->pesertaLomba->lomba ?? null; @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $lomba->nama ?? '-' }}
                                        @if($row->kelompok)
                                        <a href="{{ route('kelompok-lomba.show', $row->kelompok->id) }}" class="text-decoration-none ms-1" title="Detail Kelompok">
                                            <i class="fas fa-users" style="font-size:11px;color:#6b7280;"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->kelompok)
                                        <i class="fas fa-users me-1" style="color:#6b7280;font-size:12px;"></i>{{ $row->kelompok->nama_kelompok }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info" style="border-radius:8px;padding:4px 12px;">{{ $row->jumlah_juri }} juri</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('penilaian-lomba.show', $row->id) }}" class="btn btn-outline-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                            @if($row->is_haflah_selesai || $row->pesertaLomba->hasil)
                                            <span class="btn btn-outline-secondary btn-sm" title="{{ $row->pesertaLomba->hasil ? 'Sudah diproses' : 'Terkunci' }}" style="cursor:not-allowed;opacity:.5;pointer-events:none;"><i class="fas {{ $row->pesertaLomba->hasil ? 'fa-ban' : 'fa-lock' }}"></i></span>
                                            @else
                                            <a href="{{ route('penilaian-lomba.edit', $row->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                                data-bs-toggle="modal" data-bs-target="#hapusModal"
                                                data-nama="{{ $row->kelompok->nama_kelompok ?? '-' }}"
                                                data-url="{{ route('penilaian-lomba.hapus-semua', $row->pesertaLomba->id) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center" style="padding:32px;color:#94a3b8;">Belum ada penilaian tim</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection

{{-- MODAL HAPUS --}}
<div class="modal fade" id="hapusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header-custom border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom text-center px-4">
                <div class="delete-icon-wrap"><i class="fas fa-trash-alt"></i></div>
                <h5 class="fw-bold mt-3 mb-2">Hapus Penilaian?</h5>
                <p class="text-muted mb-3" style="font-size:14px">Semua penilaian untuk peserta ini akan dihapus.</p>
                <div class="delete-info-box text-start">
                    <div class="delete-label">Peserta</div>
                    <div class="delete-value" id="hapusNama"></div>
                </div>
                <form id="hapusForm" action="" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-delete-final"><i class="fas fa-trash me-1"></i> Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hapusModal = document.getElementById('hapusModal');
        if (hapusModal) {
            hapusModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                document.getElementById('hapusNama').textContent = button.getAttribute('data-nama');
                document.getElementById('hapusForm').action = button.getAttribute('data-url');
                if (hapusModal.parentNode !== document.body) document.body.appendChild(hapusModal);
            });
            hapusModal.addEventListener('hidden.bs.modal', function() {
                var cardBody = document.querySelector('.card-body');
                if (cardBody && hapusModal.parentNode !== cardBody) cardBody.appendChild(hapusModal);
            });
        }

        var dtOpts = {
            pagingType: 'simple_numbers',
            responsive: true,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json',
                paginate: {
                    first: '«',
                    previous: '‹',
                    next: '›',
                    last: '»'
                },
                aria: {
                    paginate: {
                        first: 'First',
                        previous: 'Previous',
                        next: 'Next',
                        last: 'Last'
                    }
                }
            }
        };

        var dtIndividu = $('#table_individu').DataTable(dtOpts);
        var dtTim = $('#table_tim').DataTable(dtOpts);

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
            var isTim = $(this).attr('data-bs-target') === '#pane-tim';
            $('#btnTambahIndividu').toggle(!isTim);
            $('#btnTambahTim').toggle(isTim);
            dtIndividu.columns.adjust().draw();
            dtTim.columns.adjust().draw();
        });
    });
</script>
@endpush