{{-- ============================================================
    LOADING SYSTEM — Templates
    ============================================================ --}}

{{-- PROGRESS BAR --}}
<div id="loading-progress">
    <div class="bar"></div>
</div>

{{-- FULL SCREEN LOADER --}}
<div id="loading-fullscreen" class="loading-fullscreen" aria-hidden="true">
    <div class="loader-inner">
        <div class="loader-spinner"></div>
        <p class="loader-text" id="loader-text">Memuat halaman...</p>
        <div class="loader-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>

{{-- DATATABLE LOADING OVERLAY (empty, cloned by JS) --}}
<template id="dt-loading-template">
    <div class="dt-loading-overlay">
        <div class="dt-spinner">
            <div class="mini-spinner"></div>
            <span>Memuat data...</span>
        </div>
    </div>
</template>
