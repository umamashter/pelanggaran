@extends('layouts.main')
@section('title', 'Master Histori')
@section('content')
<div class="row justify-content-center">
    <div class="card shadow px-0">
        <div class="card-header bg-secondary">
            @php
            $totalPoin = $histories->sum(fn($h) => $h->rule->poin);
            @endphp

            <h3 class="fw-bolder mt-2 d-inline text-white">
                Histori {{ $siswa->nama }}
                <span class="badge bg-danger text-white ms-2">
                    Total Keseluruhan Poin: {{ $totalPoin }}
                </span>
            </h3>
            @if ($histories)
            <form action="/master-histori/{{ $siswa->id }}" method="get" id="form_history" class="float-end">
                <input type="date" name="tanggal" id="tanggal" onchange="history()"
                    class="form-control form-control-sm" value="{{ request('tanggal') }}">
            </form>
            @endif
        </div>

        <div class="card-body py-0">
            @php
            $stagePoin = 0; // total poin per tahap
            $chunk = []; // buffer pelanggaran per tahap
            @endphp

            @forelse ($histories as $history)
            @php
            $stagePoin += $history->rule->poin;
            $chunk[] = $history;
            @endphp

            {{-- Jika sudah mencapai 100 poin pada tahap ini atau histori terakhir --}}
            @if ($stagePoin >= 100 || $loop->last)
            <div class="card mb-3 border-danger">
                <div class="card-header bg-danger text-white fw-bold">
                    ⚠️ Pemanggilan Orang Tua —
                    Batas Maksimal Poin 100
                    <span class="float-end">(Total Saat Ini: {{ $stagePoin }} Poin)</span>
                </div>
                <div class="card-body p-2">
                    @foreach ($chunk as $item)
                    <div class="list-group mb-2">
                        <div class="list-group-item flex-column align-items-start py-0"
                            style="background-color:#f8f9fa; border-radius:6px;">
                            <div class="d-flex w-100 mt-1 mb-1 align-items-center justify-content-between flex-wrap">
                                <small><b>{{ $item->siswa->nama }} - {{ $item->kelasSnapshot->nama_kelas ?? '-' }}</b></small>
                                <small>{{ $item->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 position-relative">
                                    <p class="mb-1 h6 text-dark">{{ $item->rule->nama }}</p>
                                    <div class="text-danger d-inline-flex mb-2"><b>+{{ $item->rule->poin }}</b></div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        {{-- Tombol hapus --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#hapusModal{{ $item->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- Tombol WhatsApp --}}
                                        @if ($item->student->poin >= 50
                                        && optional($item->penanganan)->status == 0
                                        && $item->rule->jenis_peraturan_id == 1)
                                        <button id="wa-btn-{{ $item->id }}" class="btn btn-success btn-sm"
                                            onclick="kirimNotif('{{ $item->id }}')">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Konfirmasi Hapus -->
                    <div class="modal fade" id="hapusModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="hapusModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="hapusModalLabel{{ $item->id }}">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus histori pelanggaran
                                    <strong>{{ $item->rule->nama }}</strong>
                                    dari <strong>{{ $item->siswa->nama }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('poin.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- reset buffer dan poin untuk tahap berikutnya --}}
            @php
            $chunk = [];
            $stagePoin = 0;
            @endphp
            @endif
            @empty
            <h5 class="text-secondary text-center py-1 mt-3">Histori tidak ada</h5>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function history() {
        $("form#form_history").submit();
    }

    function kirimNotif(id) {
        Swal.fire({
            title: 'Kirim Notifikasi WhatsApp?',
            text: "Pesan ini akan langsung dikirim ke nomor terkait.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, kirim sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById(`wa-btn-${id}`);
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

                fetch(`/kirim-notifikasi/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            localStorage.setItem(`wa_sent_${id}`, true);
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-secondary');
                            btn.disabled = true;
                            btn.innerHTML = '<i class="fas fa-check-circle"></i> Terkirim';
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fab fa-whatsapp"></i> Kirim WA';
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message
                            });
                        }
                    })
                    .catch(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fab fa-whatsapp"></i> Kirim WA';
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan!',
                            text: 'Terjadi masalah saat mengirim notifikasi.'
                        });
                    });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("[id^='wa-btn-']").forEach(btn => {
            const id = btn.id.replace("wa-btn-", "");
            if (localStorage.getItem(`wa_sent_${id}`)) {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-secondary');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Terkirim';
            }
        });
    });
</script>
@endpush

<!-- SweetAlert2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>