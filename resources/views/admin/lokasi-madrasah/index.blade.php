@extends('layouts.main')
@section('title','Lokasi Madrasah')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.master-lokasi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
.badge-aktif { background: #f0fdf4; color: #16a34a; }
.badge-nonaktif { background: #fef2f2; color: #dc2626; }
.info-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.info-card .card-body { padding: 24px; }
.info-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
.info-row:last-child { border-bottom: none; }
.info-label { color: #64748b; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px; }
.info-label i { color: var(--ms-primary); width: 18px; text-align: center; font-size: 15px; }
.info-value { color: #1e293b; font-size: 14px; font-weight: 600; }
.form-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.form-card .card-body { padding: 24px; }
.form-card .form-label { font-weight: 600; font-size: 13px; color: #475569; margin-bottom: 6px; }
.form-card .form-control, .form-card .form-select { border-radius: 10px; border: 1.5px solid var(--ms-border); font-size: 13px; height: 42px; padding: 0 14px; background-color: #f8fafc; transition: all .2s; color: var(--ms-text); }
.form-card .form-control:focus, .form-card .form-select:focus { border-color: var(--ms-primary); box-shadow: 0 0 0 3px rgba(22,163,74,.1); background-color: #fff; }
.form-card .form-text { font-size: 11px; color: #94a3b8; }
.btn-gps-ms { padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1.5px solid var(--ms-primary); background: #fff; color: var(--ms-primary); transition: all .25s; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; height: 42px; }
.btn-gps-ms:hover { background: var(--ms-primary-light); box-shadow: 0 2px 8px rgba(22,163,74,.15); }
.btn-gps-ms:disabled { opacity: .5; cursor: not-allowed; }
.btn-simpan-ms { padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-simpan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-toggle-ms { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; }
.btn-toggle-ms.aktif { background: #fef2f2; color: #dc2626; }
.btn-toggle-ms.aktif:hover { background: #dc2626; color: #fff; }
.btn-toggle-ms.nonaktif { background: #f0fdf4; color: #16a34a; }
.btn-toggle-ms.nonaktif:hover { background: #16a34a; color: #fff; }
.alert-modern { border-radius: 12px; padding: 14px 20px; font-size: 13px; display: flex; align-items: center; gap: 10px; }
.alert-modern.alert-success { background: #f0fdf4; color: #166534; border-left: 4px solid #16a34a; }
.alert-modern.alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
.status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px; }
.status-dot.on { background: #16a34a; box-shadow: 0 0 6px rgba(22,163,74,.4); }
.status-dot.off { background: #dc2626; box-shadow: 0 0 6px rgba(220,38,38,.4); }
.empty-state { text-align: center; padding: 40px 20px; }
.empty-state i { font-size: 48px; color: #cbd5e1; margin-bottom: 12px; }
.empty-state h5 { color: var(--ms-text-soft); font-weight: 600; margin-bottom: 8px; }
.empty-state p { color: #94a3b8; font-size: 13px; max-width: 400px; margin: 0 auto; }
.gps-status { font-size: 12px; margin-top: 6px; padding: 8px 12px; border-radius: 8px; display: none; }
.gps-status.loading { color: #92400e; background: #fffbeb; border: 1px solid #fde68a; }
.gps-status.success { color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; }
.gps-status.warning { color: #92400e; background: #fffbeb; border: 1px solid #fde68a; }
.gps-status.error { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; }
#map { width: 100%; height: 350px; border-radius: 12px; border: 1.5px solid var(--ms-border); z-index: 1; }
.map-hint { font-size: 11px; color: #94a3b8; margin-top: 6px; }
.map-hint i { color: var(--ms-primary); }
@media (max-width: 768px) { .info-card .card-body, .form-card .card-body { padding: 16px; } .info-row { flex-direction: column; align-items: flex-start; gap: 4px; } #map { height: 280px; } }
</style>

<div class="master-lokasi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Lokasi Madrasah</h4>
                        <span class="badge-modern badge-ta"><i class="fas fa-crosshairs me-1"></i>Pengaturan GPS Absensi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-modern alert-success mb-4"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-modern alert-danger mb-4"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card info-card mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color:#1e293b;"><i class="fas fa-info-circle me-1" style="color:var(--ms-primary);"></i>Lokasi Aktif</h6>
                    @if($lokasi)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-tag"></i>Nama</span>
                        <span class="info-value">{{ $lokasi->nama }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-globe"></i>Latitude</span>
                        <span class="info-value" style="font-family:monospace;">{{ $lokasi->latitude }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-globe"></i>Longitude</span>
                        <span class="info-value" style="font-family:monospace;">{{ $lokasi->longitude }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-ruler"></i>Radius</span>
                        <span class="info-value">{{ $lokasi->radius }} meter</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-power-off"></i>Status</span>
                        @if($lokasi->aktif)
                        <span class="badge-modern badge-aktif"><span class="status-dot on"></span>Aktif</span>
                        @else
                        <span class="badge-modern badge-nonaktif"><span class="status-dot off"></span>Nonaktif</span>
                        @endif
                    </div>
                    @if($lokasi->latitude && $lokasi->longitude)
                    <div class="mt-3">
                        <a href="https://www.google.com/maps?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" target="_blank" style="padding:8px 16px;border-radius:10px;font-size:12px;font-weight:600;border:1.5px solid var(--ms-border);background:#fff;color:#475569;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                            <i class="fas fa-map-marked-alt"></i> Lihat di Google Maps
                        </a>
                    </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-map-marker-alt"></i>
                        <h5>Belum Ada Lokasi</h5>
                        <p>Silakan tambahkan lokasi madrasah untuk mengaktifkan sistem absensi GPS guru.</p>
                    </div>
                    @endif
                </div>
            </div>
            @if($lokasi)
            <div class="d-flex gap-2">
                <form action="{{ route('lokasi-madrasah.toggle') }}" method="POST">
                    @csrf
                    @if($lokasi->aktif)
                    <button type="submit" class="btn-toggle-ms aktif" onclick="return confirm('Nonaktifkan lokasi ini? Absensi guru tidak akan berfungsi sampai lokasi diaktifkan kembali.')">
                        <i class="fas fa-power-off"></i> Nonaktifkan
                    </button>
                    @else
                    <button type="submit" class="btn-toggle-ms nonaktif">
                        <i class="fas fa-power-on"></i> Aktifkan
                    </button>
                    @endif
                </form>
            </div>
            @endif
        </div>

        <div class="col-lg-7">
            <div class="card form-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color:#1e293b;"><i class="fas fa-edit me-1" style="color:var(--ms-primary);"></i>{{ $lokasi ? 'Edit Lokasi' : 'Tambah Lokasi' }}</h6>

                    <form action="{{ $lokasi ? route('lokasi-madrasah.update') : route('lokasi-madrasah.store') }}" method="POST" id="formLokasi">
                        @csrf
                        @if($lokasi) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-tag me-1" style="color:var(--ms-primary);"></i>Nama Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $lokasi->nama ?? '') }}" placeholder="Contoh: Madrasah Ibtidaiyah Nurul Ulum" required>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <button type="button" class="btn-gps-ms" id="btnGps" onclick="getMyLocation()">
                                <i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya
                            </button>
                            <span id="gpsAccuracy" style="font-size:12px;color:#64748b;display:none;">
                                <i class="fas fa-signal"></i> Akurasi: <span id="gpsAccuracyVal"></span>
                            </span>
                        </div>
                        <div id="gpsStatus" class="gps-status"></div>
                        <div class="map-hint" style="margin-top:8px;"><i class="fas fa-info-circle me-1"></i>GPS hanya akurat di HP dengan GPS aktif. Di laptop/desktop, koordinat berdasarkan IP/WiFi dan mungkin kurang tepat. <strong>Geser marker pada peta</strong> untuk menyesuaikan posisi.</div>

                        <div class="mb-3 mt-3">
                            <div id="map"></div>
                            <div class="map-hint"><i class="fas fa-hand-pointer me-1"></i>Geser marker atau klik pada peta untuk mengatur posisi. Lingkaran menunjukkan area radius absensi.</div>
                        </div>

                        <div class="row g-3 mb-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-globe me-1" style="color:var(--ms-primary);"></i>Latitude <span class="text-danger">*</span></label>
                                <input type="text" name="latitude" id="inputLat" class="form-control" value="{{ old('latitude', $lokasi->latitude ?? '') }}" placeholder="-7.0746167" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fas fa-globe me-1" style="color:var(--ms-primary);"></i>Longitude <span class="text-danger">*</span></label>
                                <input type="text" name="longitude" id="inputLng" class="form-control" value="{{ old('longitude', $lokasi->longitude ?? '') }}" placeholder="113.6769496" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-ruler me-1" style="color:var(--ms-primary);"></i>Radius (meter) <span class="text-danger">*</span></label>
                            <input type="number" name="radius" id="inputRadius" class="form-control" value="{{ old('radius', $lokasi->radius ?? 40) }}" min="1" max="1000" required>
                            <div class="form-text">Jarak maksimal guru dari lokasi madrasah untuk absensi. Default: 40 meter.</div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn-simpan-ms">
                                <i class="fas fa-save"></i> {{ $lokasi ? 'Simpan Perubahan' : 'Simpan Lokasi' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
var mapReady = false;
var marker, circle, leafletMap;
var inputLat = document.getElementById('inputLat');
var inputLng = document.getElementById('inputLng');
var inputRadius = document.getElementById('inputRadius');

if (typeof L !== 'undefined') {
    try {
        var defaultLat = {{ $lokasi->latitude ?? -7.0746167 }};
        var defaultLng = {{ $lokasi->longitude ?? 113.6769496 }};
        var defaultRadius = {{ $lokasi->radius ?? 40 }};
        var hasLokasi = {{ $lokasi ? 'true' : 'false' }};

        leafletMap = L.map('map', { zoomControl: true }).setView([defaultLat, defaultLng], hasLokasi ? 16 : 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(leafletMap);

        var greenIcon = L.divIcon({
            className: '',
            html: '<div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#22c55e);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;"><i class="fas fa-map-marker-alt" style="color:#fff;font-size:14px;"></i></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -36]
        });

        marker = L.marker([defaultLat, defaultLng], { icon: greenIcon, draggable: true }).addTo(leafletMap);

        circle = L.circle([defaultLat, defaultLng], {
            radius: defaultRadius,
            color: '#16a34a',
            fillColor: '#16a34a',
            fillOpacity: 0.08,
            weight: 2,
            dashArray: '6 4'
        }).addTo(leafletMap);

        if (hasLokasi) {
            leafletMap.setView([defaultLat, defaultLng], 17);
            marker.bindPopup('<b>{{ $lokasi->nama ?? "" }}</b><br>Radius: ' + defaultRadius + 'm').openPopup();
        }

        function syncFromInputs() {
            var lat = parseFloat(inputLat.value);
            var lng = parseFloat(inputLng.value);
            var r = parseInt(inputRadius.value) || 40;
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                leafletMap.setView([lat, lng], leafletMap.getZoom());
            }
            circle.setRadius(r);
        }

        inputLat.addEventListener('input', syncFromInputs);
        inputLng.addEventListener('input', syncFromInputs);
        inputRadius.addEventListener('input', function() {
            circle.setRadius(parseInt(this.value) || 40);
        });

        marker.on('dragend', function() {
            var pos = marker.getLatLng();
            inputLat.value = pos.lat.toFixed(7);
            inputLng.value = pos.lng.toFixed(7);
            circle.setLatLng([pos.lat, pos.lng]);
            leafletMap.setView([pos.lat, pos.lng]);
        });

        leafletMap.on('click', function(e) {
            marker.setLatLng(e.latlng);
            inputLat.value = e.latlng.lat.toFixed(7);
            inputLng.value = e.latlng.lng.toFixed(7);
            circle.setLatLng([e.latlng.lat, e.latlng.lng]);
        });

        mapReady = true;
    } catch (e) {
        console.error('Map init error:', e);
    }
}

function getMyLocation() {
    var btn = document.getElementById('btnGps');
    var status = document.getElementById('gpsStatus');
    var accEl = document.getElementById('gpsAccuracy');
    var accVal = document.getElementById('gpsAccuracyVal');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendapatkan lokasi...';
    status.style.display = 'block';
    status.className = 'gps-status loading';
    status.textContent = 'Meminta akses lokasi GPS...';
    accEl.style.display = 'none';

    if (!navigator.geolocation) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya';
        status.className = 'gps-status error';
        status.textContent = 'Browser tidak mendukung geolocation.';
        return;
    }

    var attempts = 0;
    var maxAttempts = 2;
    var bestResult = null;
    var prevAccuracy = Infinity;

    function tryGetLocation() {
        attempts++;
        status.textContent = 'Mencari sinyal GPS...' + (attempts > 1 ? ' (percobaan ' + attempts + '/' + maxAttempts + ')' : '');

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                var accuracy = pos.coords.accuracy;
                if (!bestResult || accuracy < bestResult.coords.accuracy) {
                    bestResult = pos;
                }
                if (accuracy <= 30) {
                    applyResult(bestResult);
                } else if (attempts >= maxAttempts) {
                    applyResult(bestResult);
                } else if (Math.abs(accuracy - prevAccuracy) < 5) {
                    applyResult(bestResult);
                } else if (accuracy > 200) {
                    applyResult(bestResult);
                } else {
                    prevAccuracy = accuracy;
                    status.textContent = 'Akurasi ' + Math.round(accuracy) + 'm \u2014 mencoba tingkatkan...';
                    setTimeout(tryGetLocation, 2000);
                }
            },
            function(err) {
                if (bestResult) {
                    applyResult(bestResult);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya';
                    status.className = 'gps-status error';
                    if (err.code === 1) {
                        status.textContent = 'Akses lokasi ditolak. Aktifkan izin GPS di pengaturan browser.';
                    } else if (err.code === 2) {
                        status.textContent = 'Lokasi tidak tersedia. Pastikan GPS perangkat aktif.';
                    } else {
                        status.textContent = 'Waktu habis. Coba lagi atau masukkan koordinat manual.';
                    }
                }
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function applyResult(pos) {
        var lat = pos.coords.latitude.toFixed(7);
        var lng = pos.coords.longitude.toFixed(7);
        var accuracy = Math.round(pos.coords.accuracy);
        var src = pos.coords.altitude !== null ? 'GPS hardware' : (accuracy <= 100 ? 'WiFi/GPS' : 'IP-based');

        inputLat.value = lat;
        inputLng.value = lng;

        if (mapReady && marker && circle && leafletMap) {
            marker.setLatLng([parseFloat(lat), parseFloat(lng)]);
            circle.setLatLng([parseFloat(lat), parseFloat(lng)]);
            leafletMap.setView([parseFloat(lat), parseFloat(lng)], 18);
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya';

        accEl.style.display = 'inline';
        accVal.textContent = '\u00B1' + accuracy + ' meter (' + src + ')';

        if (accuracy <= 30) {
            status.className = 'gps-status success';
            status.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi ditentukan: <strong>' + lat + ', ' + lng + '</strong>';
        } else if (accuracy <= 100) {
            status.className = 'gps-status warning';
            status.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Akurasi \u00B1' + accuracy + 'm. <strong>Geser marker pada peta</strong> untuk koreksi posisi yang tepat.';
        } else {
            status.className = 'gps-status error';
            status.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Akurasi sangat buruk (\u00B1' + accuracy + 'm). Lokasi kemungkinan berdasarkan IP/WiFi, bukan GPS. <strong>Geser marker pada peta</strong> ke posisi madrasah yang benar.';
        }
    }

    tryGetLocation();
}
</script>
@endpush
