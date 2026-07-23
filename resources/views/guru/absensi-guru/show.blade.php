@extends('layouts.main')
@section('title','Absensi Guru')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.page-title-content { display: none !important; }
:root { --ms-primary: #16a34a; --ms-primary-dark: #15803d; --ms-primary-light: #dcfce7; --ms-border: #e2e8f0; --ms-text: #1e293b; --ms-text-soft: #64748b; }
.master-absensi-page { font-family: 'Inter', 'Poppins', system-ui, sans-serif; margin-top: 22px; }
.header-icon { width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; box-shadow: 0 4px 14px rgba(22,163,74,.3); flex-shrink: 0; }
.badge-modern { display: inline-flex; align-items: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; white-space: nowrap; }
.badge-ta { background: #f0fdf4; color: #16a34a; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
.info-box { background: #f8fafc; border: 1.5px solid var(--ms-border); border-radius: 14px; padding: 16px; text-align: center; }
.info-box .info-icon { font-size: 24px; margin-bottom: 6px; }
.info-box .info-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.info-box .info-value { font-size: 16px; font-weight: 700; color: var(--ms-text); }
.info-box .info-sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }
.info-box.valid { border-color: #16a34a; background: #f0fdf4; }
.info-box.valid .info-icon, .info-box.valid .info-value { color: #16a34a; }
.info-box.invalid { border-color: #dc2626; background: #fef2f2; }
.info-box.invalid .info-icon, .info-box.invalid .info-value { color: #dc2626; }
.info-box.pending { border-color: #f59e0b; background: #fffbeb; }
.info-box.pending .info-icon, .info-box.pending .info-value { color: #f59e0b; }
.camera-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.04); }
.camera-card .card-body { padding: 20px; }
.video-container { position: relative; width: 100%; max-width: 400px; margin: 0 auto; border-radius: 14px; overflow: hidden; background: #000; aspect-ratio: 3/4; }
.video-container video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
.video-container canvas { display: none; }
.video-container img { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
.video-overlay { position: absolute; top: 12px; left: 12px; background: rgba(0,0,0,.6); color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.camera-placeholder { width: 100%; max-width: 400px; margin: 0 auto; border-radius: 14px; background: #f1f5f9; display: flex; flex-direction: column; align-items: center; justify-content: center; aspect-ratio: 3/4; color: #94a3b8; }
.camera-placeholder i { font-size: 48px; margin-bottom: 12px; }
.camera-placeholder p { font-size: 13px; }
.btn-camera-ms { padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; transition: all .25s; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
.btn-camera-ms.btn-capture { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,.25); }
.btn-camera-ms.btn-capture:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(220,38,38,.35); color: #fff; }
.btn-camera-ms.btn-retake { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 2px 8px rgba(245,158,11,.25); }
.btn-camera-ms.btn-retake:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(245,158,11,.35); color: #fff; }
.btn-camera-ms.btn-start { background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; box-shadow: 0 2px 8px rgba(22,163,74,.25); }
.btn-camera-ms.btn-start:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(22,163,74,.35); color: #fff; }
.btn-simpan-ms { padding: 12px 32px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff; transition: all .25s; box-shadow: 0 4px 14px rgba(22,163,74,.3); display: inline-flex; align-items: center; gap: 8px; }
.btn-simpan-ms:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.4); color: #fff; }
.btn-simpan-ms:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }
.alert-absensi { border-radius: 12px; padding: 14px 20px; font-size: 13px; display: flex; align-items: center; gap: 10px; }
.alert-absensi.alert-success { background: #f0fdf4; color: #166534; border-left: 4px solid #16a34a; }
.alert-absensi.alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626; }
.alert-absensi.alert-warning { background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }
.sudah-absen-card { border: none; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,.06); overflow: hidden; }
.sudah-absen-card .card-body { padding: 24px; }
@media (max-width: 576px) { .info-grid { grid-template-columns: 1fr; } }
</style>

<div class="master-absensi-page">
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon"><i class="fas fa-fingerprint"></i></div>
                <div>
                    <h4 class="mb-1 fw-bold" style="color: var(--ms-text); font-size: 20px;">Absensi Guru</h4>
                    <span class="badge-modern badge-ta">
                        <i class="fas fa-calendar-day me-1"></i>{{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-absensi alert-success mb-4"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-absensi alert-danger mb-4"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif

    @if($absensiHariIni)
    <div class="card sudah-absen-card mb-4">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,#16a34a,#22c55e);box-shadow:0 4px 14px rgba(22,163,74,.3);">
                    <i class="fas fa-check-double text-white" style="font-size:28px;"></i>
                </div>
                <h5 class="fw-bold" style="color:#1e293b;">Anda Sudah Absen Hari Ini</h5>
                <p style="color:#64748b;font-size:13px;">Absensi tercatat pada pukul {{ substr($absensiHariIni->jam_masuk, 0, 5) }} WIB</p>
            </div>

            <div class="row g-4 align-items-center">
                <div class="col-md-5 text-center">
                    <img src="{{ asset('storage/absensi-guru/foto/' . $absensiHariIni->foto_masuk) }}" class="foto-preview" alt="Foto Absensi" style="max-width:250px;">
                </div>
                <div class="col-md-7">
                    <div class="info-grid">
                        <div class="info-box valid">
                            <div class="info-icon"><i class="fas fa-clock"></i></div>
                            <div class="info-label">Jam Masuk</div>
                            <div class="info-value">{{ substr($absensiHariIni->jam_masuk, 0, 5) }} WIB</div>
                        </div>
                        <div class="info-box valid">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="info-label">Jarak</div>
                            <div class="info-value">{{ round($absensiHariIni->jarak_masuk) }}m</div>
                        </div>
                    </div>
                    <div class="detail-row"><span class="detail-label">Latitude</span><span class="detail-value">{{ $absensiHariIni->latitude_masuk }}</span></div>
                    <div class="detail-row"><span class="detail-label">Longitude</span><span class="detail-value">{{ $absensiHariIni->longitude_masuk }}</span></div>
                    @if($absensiHariIni->akurasi_gps)
                    <div class="detail-row"><span class="detail-label">Akurasi GPS</span><span class="detail-value">{{ $absensiHariIni->akurasi_gps }}m</span></div>
                    @endif
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('guru.absensi-guru.riwayat') }}" style="padding:10px 24px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid var(--ms-border);background:#fff;color:#475569;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-history"></i> Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="info-grid mb-3">
        <div class="info-box" id="infoLokasi">
            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="info-label">Lokasi Anda</div>
            <div class="info-value" id="lokasiStatus">Menunggu...</div>
            <div class="info-sub" id="lokasiSub">Klik tombol di bawah untuk mengambil lokasi</div>
        </div>
        <div class="info-box" id="infoJarak">
            <div class="info-icon"><i class="fas fa-ruler"></i></div>
            <div class="info-label">Jarak dari Madrasah</div>
            <div class="info-value" id="jarakValue">--</div>
            <div class="info-sub" id="jarakSub">@if($lokasi) Radius: {{ $lokasi->radius }}m @else Lokasi belum dikonfigurasi @endif</div>
        </div>
    </div>

    <div id="gpsAccuracyBox" style="display:none;" class="alert-absensi mb-3">
        <i class="fas fa-satellite-dish"></i>
        <div>
            <div id="gpsAccuracyText" style="font-weight:600;"></div>
            <div id="gpsAccuracyTip" style="font-size:12px;opacity:.8;margin-top:2px;"></div>
        </div>
    </div>

    <div class="text-center mb-4">
        <button type="button" class="btn-camera-ms btn-start" id="btnAmbilLokasi" onclick="ambilLokasi()">
            <i class="fas fa-crosshairs"></i> Ambil Lokasi
        </button>
    </div>

    <div class="card camera-card mb-4" id="mapCard" style="display:none;">
        <div class="card-body">
            <div class="text-center mb-3">
                <h6 class="fw-bold" style="color:#1e293b;"><i class="fas fa-map me-1" style="color:var(--ms-primary);"></i>Peta Lokasi</h6>
            </div>
            <div id="absensiMap" style="height:350px;border-radius:14px;z-index:0;"></div>
        </div>
    </div>

    <div class="card camera-card mb-4">
        <div class="card-body">
            <div class="text-center mb-3">
                <h6 class="fw-bold" style="color:#1e293b;"><i class="fas fa-camera me-1" style="color:var(--ms-primary);"></i>Kamera Selfie</h6>
            </div>

            <div id="cameraArea">
                <div class="camera-placeholder" id="cameraPlaceholder">
                    <i class="fas fa-camera"></i>
                    <p>Klik tombol di bawah untuk mengaktifkan kamera</p>
                </div>
                <div class="video-container" id="videoContainer" style="display:none;">
                    <video id="videoFeed" autoplay playsinline muted></video>
                    <canvas id="photoCanvas"></canvas>
                    <div class="video-overlay"><i class="fas fa-circle" style="color:#ef4444;font-size:8px;"></i> LIVE</div>
                </div>
                <div id="previewContainer" style="display:none;" class="text-center">
                    <img id="previewImage" style="max-width:100%;max-height:400px;border-radius:14px;border:3px solid var(--ms-border);" alt="Preview">
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2 mt-3" id="cameraButtons">
                <button type="button" class="btn-camera-ms btn-start" id="btnStartCamera" onclick="startCamera()">
                    <i class="fas fa-video"></i> Aktifkan Kamera
                </button>
                <button type="button" class="btn-camera-ms btn-capture" id="btnCapture" onclick="capturePhoto()" style="display:none;">
                    <i class="fas fa-camera"></i> Ambil Foto
                </button>
                <button type="button" class="btn-camera-ms btn-retake" id="btnRetake" onclick="retakePhoto()" style="display:none;">
                    <i class="fas fa-redo"></i> Ulangi
                </button>
            </div>

            <input type="file" id="fotoInput" name="foto" accept="image/*" capture="user" style="display:none;">
        </div>
    </div>

    <div class="text-center">
        <form id="absensiForm" action="{{ route('guru.absensi-guru.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="latitude" id="inputLat">
            <input type="hidden" name="longitude" id="inputLng">
            <input type="hidden" name="akurasi_gps" id="inputAkurasi">
            <input type="hidden" name="foto" id="inputFoto">
            <button type="submit" class="btn-simpan-ms" id="btnSimpan" disabled>
                <i class="fas fa-fingerprint"></i> Ambil Absensi
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var stream = null;
var lokasiValid = false;
var hasFoto = false;
var lokasiData = @json($lokasi);
var map = null;
var userMarker = null;
var mapInitialized = false;

function startCamera() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Browser Anda tidak mendukung akses kamera. Silakan gunakan browser terbaru.');
        return;
    }
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } } })
    .then(function(s) {
        stream = s;
        document.getElementById('videoFeed').srcObject = stream;
        document.getElementById('cameraPlaceholder').style.display = 'none';
        document.getElementById('videoContainer').style.display = 'block';
        document.getElementById('previewContainer').style.display = 'none';
        document.getElementById('btnStartCamera').style.display = 'none';
        document.getElementById('btnCapture').style.display = 'inline-flex';
        document.getElementById('btnRetake').style.display = 'none';
        hasFoto = false;
        updateSimpanBtn();
    })
    .catch(function(err) {
        alert('Gagal mengakses kamera: ' + err.message);
    });
}

function capturePhoto() {
    var video = document.getElementById('videoFeed');
    var canvas = document.getElementById('photoCanvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    var ctx = canvas.getContext('2d');
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0);
    var dataUrl = canvas.toDataURL('image/jpeg', 0.8);
    document.getElementById('previewImage').src = dataUrl;
    document.getElementById('videoContainer').style.display = 'none';
    document.getElementById('previewContainer').style.display = 'block';
    document.getElementById('btnCapture').style.display = 'none';
    document.getElementById('btnRetake').style.display = 'inline-flex';
    if (stream) { stream.getTracks().forEach(function(t) { t.stop(); }); stream = null; }
    dataURIToFile(dataUrl, 'selfie_' + Date.now() + '.jpg');
    hasFoto = true;
    updateSimpanBtn();
}

function retakePhoto() {
    hasFoto = false;
    document.getElementById('inputFoto').value = '';
    document.getElementById('previewContainer').style.display = 'none';
    document.getElementById('btnRetake').style.display = 'none';
    startCamera();
}

function dataURIToFile(dataURI, filename) {
    var byteString = atob(dataURI.split(',')[1]);
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) { ia[i] = byteString.charCodeAt(i); }
    var blob = new Blob([ab], { type: 'image/jpeg' });
    var file = new File([blob], filename, { type: 'image/jpeg' });
    var dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('fotoInput').files = dt.files;
    document.getElementById('inputFoto').value = filename;
}

function updateSimpanBtn() {
    var btn = document.getElementById('btnSimpan');
    if (btn) { btn.disabled = !(lokasiValid && hasFoto); }
}

function ambilLokasi() {
    var btn = document.getElementById('btnAmbilLokasi');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil Lokasi...';
    var accBox = document.getElementById('gpsAccuracyBox');
    accBox.style.display = 'flex';
    accBox.className = 'alert-absensi mb-3';
    accBox.style.background = '#fffbeb';
    accBox.style.borderLeft = '4px solid #f59e0b';
    accBox.style.color = '#92400e';
    document.getElementById('gpsAccuracyText').textContent = 'Mengambil pembacaan GPS...';
    document.getElementById('gpsAccuracyTip').textContent = 'Pastikan GPS aktif dan berada di area terbuka';
    updateLokasiStatus();
}

function initMap(lat, lng) {
    if (mapInitialized) {
        if (userMarker) {
            userMarker.setLatLng([lat, lng]);
        }
        map.setView([lat, lng], 16);
        return;
    }
    document.getElementById('mapCard').style.display = 'block';
    map = L.map('absensiMap', { zoomControl: false }).setView([lat, lng], 16);
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19
    }).addTo(map);
    var userIcon = L.divIcon({
        html: '<div style="width:20px;height:20px;background:#2563eb;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10],
        className: ''
    });
    userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map).bindPopup('<b>Lokasi Anda</b>');
    if (lokasiData) {
        var madrasahIcon = L.divIcon({
            html: '<div style="width:24px;height:24px;background:#dc2626;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;"><i class="fas fa-mosque" style="color:#fff;font-size:11px;"></i></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12],
            className: ''
        });
        L.marker([parseFloat(lokasiData.latitude), parseFloat(lokasiData.longitude)], { icon: madrasahIcon })
            .addTo(map)
            .bindPopup('<b>Madrasah</b><br>' + lokasiData.nama);
        L.circle([parseFloat(lokasiData.latitude), parseFloat(lokasiData.longitude)], {
            radius: lokasiData.radius,
            color: '#16a34a',
            fillColor: '#16a34a',
            fillOpacity: 0.1,
            weight: 2,
            dashArray: '6 4'
        }).addTo(map);
        var bounds = L.latLngBounds([
            [lat, lng],
            [parseFloat(lokasiData.latitude), parseFloat(lokasiData.longitude)]
        ]);
        map.fitBounds(bounds, { padding: [40, 40] });
    }
    mapInitialized = true;
}

function updateLokasiStatus() {
    var box = document.getElementById('infoLokasi');
    var status = document.getElementById('lokasiStatus');
    var sub = document.getElementById('lokasiSub');
    if (!navigator.geolocation) {
        box.className = 'info-box invalid';
        status.textContent = 'GPS Tidak Tersedia';
        sub.textContent = 'Browser tidak mendukung geolocation';
        return;
    }
    navigator.geolocation.getCurrentPosition(function(pos) {
        var lat = pos.coords.latitude;
        var lng = pos.coords.longitude;
        var acc = pos.coords.accuracy;
        document.getElementById('inputLat').value = lat;
        document.getElementById('inputLng').value = lng;
        document.getElementById('inputAkurasi').value = Math.round(acc);
        box.className = 'info-box valid';
        status.textContent = 'Lokasi Ditemukan';
        sub.textContent = 'Lat: ' + lat.toFixed(6) + ', Lng: ' + lng.toFixed(6);
        initMap(lat, lng);
        resetBtnLokasi(true);
        var accBox = document.getElementById('gpsAccuracyBox');
        var accText = document.getElementById('gpsAccuracyText');
        var accTip = document.getElementById('gpsAccuracyTip');
        if (acc <= 10) {
            accBox.style.background = '#f0fdf4';
            accBox.style.borderLeft = '4px solid #16a34a';
            accBox.style.color = '#166534';
            accText.textContent = 'Akurasi GPS: ' + Math.round(acc) + 'm (Sangat Baik)';
            accTip.textContent = 'Lokasi sangat presisi';
        } else if (acc <= 30) {
            accBox.style.background = '#f0fdf4';
            accBox.style.borderLeft = '4px solid #16a34a';
            accBox.style.color = '#166534';
            accText.textContent = 'Akurasi GPS: ' + Math.round(acc) + 'm (Baik)';
            accTip.textContent = 'Akurasi cukup untuk absensi';
        } else if (acc <= 80) {
            accBox.style.background = '#fffbeb';
            accBox.style.borderLeft = '4px solid #f59e0b';
            accBox.style.color = '#92400e';
            accText.textContent = 'Akurasi GPS: ' + Math.round(acc) + 'm (Kurang)';
            accTip.textContent = 'Coba geser ke dekat jendela atau area terbuka';
        } else {
            accBox.style.background = '#fef2f2';
            accBox.style.borderLeft = '4px solid #dc2626';
            accBox.style.color = '#991b1b';
            accText.textContent = 'Akurasi GPS: ' + Math.round(acc) + 'm (Sangat Rendah)';
            accTip.textContent = 'GPS tidak akurat! Aktifkan WiFi atau keluar ke area terbuka, lalu klik "Coba Lagi"';
        }
        if (!window._lokasiIntervalStarted) {
            setInterval(updateLokasiStatus, 10000);
            window._lokasiIntervalStarted = true;
        }
        if (lokasiData) {
            var jarak = haversine(lat, lng, parseFloat(lokasiData.latitude), parseFloat(lokasiData.longitude));
            var jarakBox = document.getElementById('infoJarak');
            var jarakVal = document.getElementById('jarakValue');
            var jarakSub = document.getElementById('jarakSub');
            jarakVal.textContent = Math.round(jarak) + ' meter';
            if (jarak <= lokasiData.radius) {
                jarakBox.className = 'info-box valid';
                lokasiValid = true;
                jarakSub.textContent = 'Dalam area (maks ' + lokasiData.radius + 'm)';
            } else {
                jarakBox.className = 'info-box invalid';
                lokasiValid = false;
                jarakSub.textContent = 'Di luar area (maks ' + lokasiData.radius + 'm)';
            }
            updateSimpanBtn();
        }
    }, function(err) {
        var box = document.getElementById('infoLokasi');
        var status = document.getElementById('lokasiStatus');
        var sub = document.getElementById('lokasiSub');
        box.className = 'info-box invalid';
        var accBox = document.getElementById('gpsAccuracyBox');
        var accText = document.getElementById('gpsAccuracyText');
        var accTip = document.getElementById('gpsAccuracyTip');
        accBox.style.display = 'flex';
        if (err.code === 1) {
            status.textContent = 'Izin Lokasi Ditolak';
            sub.textContent = 'Aktifkan izin lokasi di pengaturan browser';
            accBox.style.background = '#fef2f2';
            accBox.style.borderLeft = '4px solid #dc2626';
            accBox.style.color = '#991b1b';
            accText.textContent = 'Izin lokasi ditolak';
            accTip.textContent = 'Buka pengaturan browser → Izin Lokasi → Izinkan untuk situs ini';
        } else if (err.code === 2) {
            status.textContent = 'Lokasi Tidak Tersedia';
            sub.textContent = 'Pastikan GPS perangkat aktif';
            accBox.style.background = '#fef2f2';
            accBox.style.borderLeft = '4px solid #dc2626';
            accBox.style.color = '#991b1b';
            accText.textContent = 'Lokasi tidak tersedia';
            accTip.textContent = 'Aktifkan GPS/WiFi di pengaturan hp, lalu coba lagi';
        } else {
            status.textContent = 'Timeout Lokasi';
            sub.textContent = 'Coba lagi atau periksa koneksi GPS';
            accBox.style.background = '#fef2f2';
            accBox.style.borderLeft = '4px solid #dc2626';
            accBox.style.color = '#991b1b';
            accText.textContent = 'Timeout — lokasi gagal diambil';
            accTip.textContent = 'Pastikan GPS aktif dan coba lagi dari area terbuka';
        }
        resetBtnLokasi(false);
    }, { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 });
}

function haversine(lat1, lon1, lat2, lon2) {
    var R = 6371000;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function resetBtnLokasi(success) {
    var btn = document.getElementById('btnAmbilLokasi');
    if (success) {
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Lokasi Ditemukan';
        btn.className = 'btn-camera-ms btn-start';
        btn.style.background = 'linear-gradient(135deg, #16a34a, #22c55e)';
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-crosshairs"></i> Coba Lagi';
    }
}

document.addEventListener('DOMContentLoaded', function() {});
</script>
<style>
.detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
.detail-row:last-child { border-bottom: none; }
.detail-label { color: #64748b; font-weight: 500; }
.detail-value { color: #1e293b; font-weight: 600; }
.foto-preview { max-width: 300px; border-radius: 14px; border: 3px solid var(--ms-border); box-shadow: 0 4px 16px rgba(0,0,0,.1); }
</style>
@endpush
