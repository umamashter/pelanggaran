{{-- Context Panel — muncul di sebelah kanan sidebar saat menu tertentu diklik --}}
<div id="contextPanel" class="context-panel" role="dialog" aria-hidden="true">
    <div class="context-panel-backdrop" id="contextPanelBackdrop"></div>
    <div class="context-panel-inner">
        <div class="context-panel-header">
            <h5 class="context-panel-title" id="contextPanelTitle">Panel</h5>
            <button type="button" class="context-panel-close" id="contextPanelClose" aria-label="Tutup panel">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="context-panel-body" id="contextPanelBody">
            {{-- Diisi oleh JavaScript sesuai data-context-panel --}}
        </div>
    </div>
</div>

<script>
    // Data untuk setiap context panel
    var contextPanelData = {
        peserta: {
            title: 'Peserta',
            items: [{
                    title: 'Lomba Individu',
                    route: '{{ route("peserta-lomba.index") }}'
                },
                {
                    title: 'Lomba Kelompok',
                    route: '{{ route("anggota-kelompok.index") }}'
                }
            ]
        }
    };
</script>