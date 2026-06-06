@extends('layouts.admin')

@section('title', 'Lokasi Sekolah')

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    #map {
        height: 86vh;
        width: 100%;
        border-radius: 16px;
        z-index: 1;
    }

    .leaflet-container {
        border-radius: 16px;
    }

    .coord-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .coord-label {
        font-size: 11px;
        font-weight: 600;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .coord-value {
        font-size: 15px;
        font-weight: 700;
        color: #1E3A8A;
        font-family: 'Courier New', monospace;
    }

    .step-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-done {
        background: #DCFCE7;
        color: #166534;
    }

    .step-active {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .step-idle {
        background: #F1F5F9;
        color: #94A3B8;
    }

    .leaflet-draw-toolbar a {
        background-color: white !important;
    }

    #map-hint {
        position: absolute;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(30, 58, 138, 0.88);
        color: white;
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.03em;
        pointer-events: none;
        z-index: 999;
        white-space: nowrap;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(8px);
    }

    .btn-simpan {
        background: linear-gradient(135deg, #1D4ED8 0%, #1E3A8A 100%);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(29, 78, 216, 0.35);
    }

    .btn-simpan:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(29, 78, 216, 0.45);
    }

    .btn-simpan:active {
        transform: translateY(0);
    }

    .btn-simpan:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-reset {
        background: white;
        color: #64748B;
        border: 1.5px solid #E2E8F0;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-reset:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: #475569;
    }

    .btn-draw {
        background: white;
        color: #1D4ED8;
        border: 2px dashed #93C5FD;
        padding: 10px 22px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-draw:hover {
        background: #EFF6FF;
        border-color: #3B82F6;
    }

    .btn-draw.active {
        background: #EFF6FF;
        border-color: #1D4ED8;
        color: #1E3A8A;
    }

    .alert-success-custom {
        background: #F0FDF4;
        border: 1px solid #86EFAC;
        color: #166534;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-error-custom {
        background: #FEF2F2;
        border: 1px solid #FCA5A5;
        color: #991B1B;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13px;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .pulse-dot {
        animation: pulse-dot 1.5s ease infinite;
    }
</style>

<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class='bx bxs-map text-blue-600'></i>
                Pengaturan Lokasi Sekolah
            </h1>
            <p class="text-slate-500 text-sm mt-0.5">
                Tentukan batas area valid absensi siswa dengan menggambar di peta
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-100 rounded-xl px-3 py-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-dot"></span>
            API Terhubung
        </div>
    </div>

    {{-- Alert Session --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <i class='bx bxs-check-circle text-xl text-emerald-500'></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error-custom">
        <i class='bx bxs-error-circle text-xl text-red-400'></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Alert AJAX --}}
    <div id="ajaxAlert" class="hidden"></div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- MAP SECTION --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            {{-- Map Header --}}
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-map text-blue-600 text-lg'></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">Peta Area Sekolah</p>
                        <p class="text-xs text-slate-400" id="mapStatusText">Klik tombol Gambar Area untuk mulai</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="btnDraw" class="btn-draw" onclick="startDrawMode()">
                        <i class='bx bx-edit-alt'></i>
                        Gambar Area
                    </button>
                    <button class="btn-reset text-sm px-3 py-2" onclick="clearRectangle()" title="Hapus gambar">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>

            {{-- Map Container --}}
            <div class="relative">
                <div id="map"></div>
                <div id="map-hint">🖱️ Klik & drag untuk gambar area sekolah</div>
            </div>

            {{-- Steps Guide --}}
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-4 text-xs flex-wrap">
                    <div class="flex items-center gap-2" id="step1El">
                        <div class="step-badge step-idle" id="s1badge">1</div>
                        <span class="text-slate-500" id="s1text">Klik "Gambar Area"</span>
                    </div>
                    <i class='bx bx-chevron-right text-slate-300 text-lg'></i>
                    <div class="flex items-center gap-2" id="step2El">
                        <div class="step-badge step-idle" id="s2badge">2</div>
                        <span class="text-slate-500" id="s2text">Drag di peta untuk membuat kotak area</span>
                    </div>
                    <i class='bx bx-chevron-right text-slate-300 text-lg'></i>
                    <div class="flex items-center gap-2" id="step3El">
                        <div class="step-badge step-idle" id="s3badge">3</div>
                        <span class="text-slate-500" id="s3text">Klik Simpan</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- PANEL KANAN --}}
        <div class="flex flex-col gap-4">

            {{-- Koordinat Cards --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <p class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <i class='bx bxs-pin text-blue-600'></i>
                    Koordinat Area
                </p>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="coord-card">
                        <span class="coord-label">Lat Min</span>
                        <span class="coord-value" id="displayLatMin">—</span>
                    </div>
                    <div class="coord-card">
                        <span class="coord-label">Lat Max</span>
                        <span class="coord-value" id="displayLatMax">—</span>
                    </div>
                    <div class="coord-card">
                        <span class="coord-label">Lng Min</span>
                        <span class="coord-value" id="displayLngMin">—</span>
                    </div>
                    <div class="coord-card">
                        <span class="coord-label">Lng Max</span>
                        <span class="coord-value" id="displayLngMax">—</span>
                    </div>
                </div>

                {{-- Hidden inputs untuk form --}}
                <input type="hidden" id="inputLatMin" name="lat_min">
                <input type="hidden" id="inputLatMax" name="lat_max">
                <input type="hidden" id="inputLngMin" name="lng_min">
                <input type="hidden" id="inputLngMax" name="lng_max">

                {{-- Luas area --}}
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-blue-500 font-semibold uppercase tracking-wider mb-0.5">Estimasi Luas</p>
                    <p class="text-lg font-bold text-blue-800" id="luasArea">—</p>
                </div>
            </div>

            {{-- Nama Lokasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                    <i class='bx bxs-building text-blue-600'></i>
                    Nama Lokasi
                </p>
                <input
                    type="text"
                    id="inputNamaLokasi"
                    value="{{ $lokasi['nama_lokasi'] ?? 'SMKN 8 Medan' }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all"
                    placeholder="Nama lokasi sekolah"
                >
            </div>

            {{-- Info Tersimpan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                    <i class='bx bxs-data text-slate-500'></i>
                    Data Tersimpan
                </p>

                @if($lokasi)
                <div class="text-xs space-y-0">
                    <div class="info-row">
                        <span class="text-slate-400">Nama</span>
                        <span class="font-semibold text-slate-700">{{ $lokasi['nama_lokasi'] ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-slate-400">Lat Min</span>
                        <span class="font-mono font-semibold text-blue-700">{{ $lokasi['lat_min'] ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-slate-400">Lat Max</span>
                        <span class="font-mono font-semibold text-blue-700">{{ $lokasi['lat_max'] ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-slate-400">Lng Min</span>
                        <span class="font-mono font-semibold text-blue-700">{{ $lokasi['lng_min'] ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-slate-400">Lng Max</span>
                        <span class="font-mono font-semibold text-blue-700">{{ $lokasi['lng_max'] ?? '-' }}</span>
                    </div>
                </div>
                @else
                <p class="text-xs text-slate-400 text-center py-3">Belum ada data lokasi</p>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col gap-2">
                <button id="btnSimpan" class="btn-simpan w-full justify-center" onclick="simpanLokasi()" disabled>
                    <i class='bx bxs-save text-lg' id="iconSimpan"></i>
                    <span id="textSimpan">Simpan Lokasi</span>
                </button>
                <button class="btn-reset w-full justify-center" onclick="resetKeDefault()">
                    <i class='bx bx-reset'></i>
                    Reset ke Data Default
                </button>
            </div>

        </div>
    </div>

    {{-- Panduan Info --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class='bx bx-info-circle text-blue-600 text-lg'></i>
            </div>
            <div>
                <p class="font-semibold text-blue-800 text-sm mb-1">Cara Menggunakan</p>
                <p class="text-blue-600 text-xs leading-relaxed">
                    Klik tombol <strong>Gambar Area</strong>, lalu <strong>klik dan drag</strong> di peta untuk membuat kotak area batas sekolah.
                    Koordinat akan otomatis dihitung. Setelah selesai, klik <strong>Simpan Lokasi</strong>.
                    Gunakan <strong>Reset ke Data Default</strong> untuk mengembalikan koordinat awal jika terjadi kesalahan.
                </p>
                <p class="text-blue-500 text-xs mt-2">
                    ⚠️ Area ini menentukan di mana saja siswa diperbolehkan melakukan absensi. Pastikan mencakup seluruh area sekolah.
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// =====================================================
// KONFIGURASI DEFAULT (FALLBACK)
// =====================================================
const DEFAULT = {
    lat_min: 3.565500,
    lat_max: 3.567300,
    lng_min: 98.645900,
    lng_max: 98.647300,
    nama_lokasi: 'SMKN 8 Medan'
};

// Data dari PHP (tersimpan di DB)
const savedLokasi = @json($lokasi);

// Koordinat awal: dari DB jika ada, fallback ke default
const initCoord = {
    lat_min: savedLokasi ? parseFloat(savedLokasi.lat_min) : DEFAULT.lat_min,
    lat_max: savedLokasi ? parseFloat(savedLokasi.lat_max) : DEFAULT.lat_max,
    lng_min: savedLokasi ? parseFloat(savedLokasi.lng_min) : DEFAULT.lng_min,
    lng_max: savedLokasi ? parseFloat(savedLokasi.lng_max) : DEFAULT.lng_max,
};

// =====================================================
// STATE
// =====================================================
let map, currentRect = null, drawMode = false;
let drawStart = null, isDragging = false;
let currentCoords = { ...initCoord };

// =====================================================
// INIT MAP
// =====================================================
function initMap() {
    const centerLat = (initCoord.lat_min + initCoord.lat_max) / 2;
    const centerLng = (initCoord.lng_min + initCoord.lng_max) / 2;

    map = L.map('map', {
        center: [centerLat, centerLng],
        zoom: 18,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 20,
    }).addTo(map);

    // Gambar rectangle dari data tersimpan
    drawSavedRect();

    // =====================================================
    // DRAG TO DRAW RECTANGLE
    // =====================================================
    map.on('mousedown', function(e) {
        if (!drawMode) return;
        e.originalEvent.preventDefault();
        drawStart = e.latlng;
        isDragging = true;

        if (currentRect) {
            map.removeLayer(currentRect);
            currentRect = null;
        }

        document.getElementById('map-hint').style.opacity = '0';
    });

    map.on('mousemove', function(e) {
        if (!isDragging || !drawStart) return;

        const bounds = L.latLngBounds(drawStart, e.latlng);

        if (currentRect) {
            map.removeLayer(currentRect);
        }

        currentRect = L.rectangle(bounds, {
            color: '#1D4ED8',
            weight: 2,
            fillColor: '#3B82F6',
            fillOpacity: 0.18,
            dashArray: '6, 4',
        }).addTo(map);
    });

    map.on('mouseup', function(e) {
        if (!isDragging || !drawStart) return;
        isDragging = false;

        const lat1 = drawStart.lat;
        const lat2 = e.latlng.lat;
        const lng1 = drawStart.lng;
        const lng2 = e.latlng.lng;

        const lat_min = Math.min(lat1, lat2);
        const lat_max = Math.max(lat1, lat2);
        const lng_min = Math.min(lng1, lng2);
        const lng_max = Math.max(lng1, lng2);

        currentCoords = { lat_min, lat_max, lng_min, lng_max };
        updateCoordDisplay(lat_min, lat_max, lng_min, lng_max);

        // Gambar ulang rectangle final (solid)
        if (currentRect) map.removeLayer(currentRect);
        currentRect = drawFinalRect(lat_min, lat_max, lng_min, lng_max);

        drawStart = null;
        stopDrawMode();
        updateStep(3);

        document.getElementById('btnSimpan').disabled = false;
    });

    // Touch support (mobile)
    map.on('touchstart', function(e) {
        if (!drawMode) return;
        const touch = e.originalEvent.touches[0];
        const latlng = map.containerPointToLatLng(
            L.point(touch.clientX - map.getContainer().getBoundingClientRect().left,
                    touch.clientY - map.getContainer().getBoundingClientRect().top)
        );
        drawStart = latlng;
        isDragging = true;
        document.getElementById('map-hint').style.opacity = '0';
    });

    map.on('touchmove', function(e) {
        if (!isDragging || !drawStart) return;
        e.originalEvent.preventDefault();
        const touch = e.originalEvent.touches[0];
        const latlng = map.containerPointToLatLng(
            L.point(touch.clientX - map.getContainer().getBoundingClientRect().left,
                    touch.clientY - map.getContainer().getBoundingClientRect().top)
        );

        const bounds = L.latLngBounds(drawStart, latlng);
        if (currentRect) map.removeLayer(currentRect);
        currentRect = L.rectangle(bounds, {
            color: '#1D4ED8', weight: 2,
            fillColor: '#3B82F6', fillOpacity: 0.18,
            dashArray: '6, 4',
        }).addTo(map);
    });

    map.on('touchend', function(e) {
        if (!isDragging || !drawStart) return;
        isDragging = false;
        const touch = e.originalEvent.changedTouches[0];
        const latlng = map.containerPointToLatLng(
            L.point(touch.clientX - map.getContainer().getBoundingClientRect().left,
                    touch.clientY - map.getContainer().getBoundingClientRect().top)
        );

        const lat1 = drawStart.lat, lat2 = latlng.lat;
        const lng1 = drawStart.lng, lng2 = latlng.lng;

        const lat_min = Math.min(lat1, lat2), lat_max = Math.max(lat1, lat2);
        const lng_min = Math.min(lng1, lng2), lng_max = Math.max(lng1, lng2);

        currentCoords = { lat_min, lat_max, lng_min, lng_max };
        updateCoordDisplay(lat_min, lat_max, lng_min, lng_max);

        if (currentRect) map.removeLayer(currentRect);
        currentRect = drawFinalRect(lat_min, lat_max, lng_min, lng_max);

        drawStart = null;
        stopDrawMode();
        updateStep(3);
        document.getElementById('btnSimpan').disabled = false;
    });
}

// =====================================================
// DRAW HELPERS
// =====================================================
function drawSavedRect() {
    if (currentRect) map.removeLayer(currentRect);

    const r = drawFinalRect(
        initCoord.lat_min, initCoord.lat_max,
        initCoord.lng_min, initCoord.lng_max
    );
    currentRect = r;

    // Tampilkan koordinat awal
    updateCoordDisplay(
        initCoord.lat_min, initCoord.lat_max,
        initCoord.lng_min, initCoord.lng_max
    );
}

function drawFinalRect(lat_min, lat_max, lng_min, lng_max) {
    const bounds = [[lat_min, lng_min], [lat_max, lng_max]];
    const rect = L.rectangle(bounds, {
        color: '#1E3A8A',
        weight: 2.5,
        fillColor: '#3B82F6',
        fillOpacity: 0.15,
    }).addTo(map);

    // Popup koordinat
    rect.bindPopup(
        `<div style="font-family: monospace; font-size: 12px; line-height: 1.8;">
            <b style="font-family: sans-serif; font-size: 13px;">Area Sekolah</b><br>
            Lat Min: <b>${lat_min.toFixed(7)}</b><br>
            Lat Max: <b>${lat_max.toFixed(7)}</b><br>
            Lng Min: <b>${lng_min.toFixed(7)}</b><br>
            Lng Max: <b>${lng_max.toFixed(7)}</b>
        </div>`,
        { closeButton: true }
    );

    // Corner markers
    addCornerMarker(lat_max, lng_min, 'Kiri Atas (NW)');
    addCornerMarker(lat_max, lng_max, 'Kanan Atas (NE)');
    addCornerMarker(lat_min, lng_min, 'Kiri Bawah (SW)');
    addCornerMarker(lat_min, lng_max, 'Kanan Bawah (SE)');

    map.fitBounds(bounds, { padding: [40, 40] });

    return rect;
}

let cornerMarkers = [];
function addCornerMarker(lat, lng, label) {
    const icon = L.divIcon({
        className: '',
        html: `<div style="
            width: 12px; height: 12px;
            background: #1D4ED8;
            border: 2.5px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        "></div>`,
        iconSize: [12, 12],
        iconAnchor: [6, 6],
    });

    const m = L.marker([lat, lng], { icon, draggable: true })
        .addTo(map)
        .bindTooltip(label, { permanent: false, direction: 'top' });

    cornerMarkers.push(m);
}

function clearCornerMarkers() {
    cornerMarkers.forEach(m => map.removeLayer(m));
    cornerMarkers = [];
}

// =====================================================
// DRAW MODE
// =====================================================
function startDrawMode() {
    drawMode = true;
    map.dragging.disable();
    map.getContainer().style.cursor = 'crosshair';

    document.getElementById('btnDraw').classList.add('active');
    document.getElementById('btnDraw').innerHTML = `<i class='bx bx-x'></i> Batal`;
    document.getElementById('btnDraw').onclick = cancelDrawMode;
    document.getElementById('mapStatusText').textContent = 'Drag di peta untuk menggambar area sekolah';
    document.getElementById('map-hint').style.opacity = '1';

    updateStep(2);
}

function stopDrawMode() {
    drawMode = false;
    map.dragging.enable();
    map.getContainer().style.cursor = '';

    document.getElementById('btnDraw').classList.remove('active');
    document.getElementById('btnDraw').innerHTML = `<i class='bx bx-edit-alt'></i> Gambar Area`;
    document.getElementById('btnDraw').onclick = startDrawMode;
    document.getElementById('mapStatusText').textContent = 'Area berhasil digambar';
    document.getElementById('map-hint').style.opacity = '0';
}

function cancelDrawMode() {
    drawMode = false;
    isDragging = false;
    drawStart = null;
    map.dragging.enable();
    map.getContainer().style.cursor = '';

    document.getElementById('btnDraw').classList.remove('active');
    document.getElementById('btnDraw').innerHTML = `<i class='bx bx-edit-alt'></i> Gambar Area`;
    document.getElementById('btnDraw').onclick = startDrawMode;
    document.getElementById('mapStatusText').textContent = 'Klik tombol Gambar Area untuk mulai';
    document.getElementById('map-hint').style.opacity = '0';

    updateStep(1);
}

function clearRectangle() {
    if (currentRect) {
        map.removeLayer(currentRect);
        currentRect = null;
    }
    clearCornerMarkers();
    currentCoords = { ...initCoord };
    updateCoordDisplay(null, null, null, null);
    document.getElementById('btnSimpan').disabled = true;
    document.getElementById('mapStatusText').textContent = 'Klik tombol Gambar Area untuk mulai';
    updateStep(1);
    cancelDrawMode();
}

// =====================================================
// KOORDINAT DISPLAY
// =====================================================
function updateCoordDisplay(lat_min, lat_max, lng_min, lng_max) {
    if (lat_min === null) {
        ['displayLatMin','displayLatMax','displayLngMin','displayLngMax'].forEach(id => {
            document.getElementById(id).textContent = '—';
        });
        document.getElementById('luasArea').textContent = '—';
        document.getElementById('inputLatMin').value = '';
        document.getElementById('inputLatMax').value = '';
        document.getElementById('inputLngMin').value = '';
        document.getElementById('inputLngMax').value = '';
        return;
    }

    document.getElementById('displayLatMin').textContent = lat_min.toFixed(7);
    document.getElementById('displayLatMax').textContent = lat_max.toFixed(7);
    document.getElementById('displayLngMin').textContent = lng_min.toFixed(7);
    document.getElementById('displayLngMax').textContent = lng_max.toFixed(7);

    document.getElementById('inputLatMin').value = lat_min;
    document.getElementById('inputLatMax').value = lat_max;
    document.getElementById('inputLngMin').value = lng_min;
    document.getElementById('inputLngMax').value = lng_max;

    // Hitung luas estimasi (meter)
    const R = 6371000;
    const dLat = (lat_max - lat_min) * Math.PI / 180;
    const dLng = (lng_max - lng_min) * Math.PI / 180;
    const avgLat = ((lat_min + lat_max) / 2) * Math.PI / 180;
    const mLat = dLat * R;
    const mLng = dLng * R * Math.cos(avgLat);
    const luas = Math.abs(mLat * mLng);

    if (luas < 10000) {
        document.getElementById('luasArea').textContent = Math.round(luas).toLocaleString('id') + ' m²';
    } else {
        document.getElementById('luasArea').textContent = (luas / 10000).toFixed(2) + ' ha';
    }
}

// =====================================================
// STEP INDICATOR
// =====================================================
function updateStep(step) {
    const badges = ['s1badge', 's2badge', 's3badge'];
    const texts = ['s1text', 's2text', 's3text'];
    const labels = [
        'Klik "Gambar Area"',
        'Drag di peta untuk membuat kotak area',
        'Klik Simpan'
    ];

    badges.forEach((id, i) => {
        const el = document.getElementById(id);
        const textEl = document.getElementById(texts[i]);
        el.className = 'step-badge ';
        textEl.className = '';

        if (i + 1 < step) {
            el.classList.add('step-done');
            el.innerHTML = '✓';
            textEl.classList.add('text-emerald-600', 'font-semibold');
        } else if (i + 1 === step) {
            el.classList.add('step-active');
            el.textContent = i + 1;
            textEl.classList.add('text-blue-700', 'font-semibold');
        } else {
            el.classList.add('step-idle');
            el.textContent = i + 1;
            textEl.classList.add('text-slate-400');
        }

        textEl.textContent = labels[i];
    });
}

// =====================================================
// SIMPAN LOKASI (AJAX ke FE Controller → API Backend)
// =====================================================
function simpanLokasi() {
    const nama = document.getElementById('inputNamaLokasi').value.trim();
    if (!nama) {
        showAlert('error', 'Nama lokasi tidak boleh kosong.');
        return;
    }

    const { lat_min, lat_max, lng_min, lng_max } = currentCoords;

    if (!lat_min || !lat_max || !lng_min || !lng_max) {
        showAlert('error', 'Koordinat belum lengkap. Gambar area di peta terlebih dahulu.');
        return;
    }

    // Loading state
    const btn = document.getElementById('btnSimpan');
    const icon = document.getElementById('iconSimpan');
    const text = document.getElementById('textSimpan');
    btn.disabled = true;
    icon.className = 'bx bx-loader-alt text-lg animate-spin';
    text.textContent = 'Menyimpan...';

    fetch('{{ route("admin.lokasi.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            _method: 'PUT',
            nama_lokasi: nama,
            lat_min: lat_min,
            lat_max: lat_max,
            lng_min: lng_min,
            lng_max: lng_max,
        })
    })
    .then(res => res.json().then(data => ({ status: res.status, data })))
    .then(({ status, data }) => {
        btn.disabled = false;
        icon.className = 'bx bxs-save text-lg';
        text.textContent = 'Simpan Lokasi';

        if (status >= 200 && status < 300) {
            showAlert('success', data.message ?? 'Lokasi berhasil disimpan!');
            updateStep(1);
            // Reload setelah 1.5 detik supaya panel Data Tersimpan update
            setTimeout(() => location.reload(), 1800);
        } else {
            showAlert('error', data.message ?? 'Gagal menyimpan lokasi.');
            btn.disabled = false;
        }
    })
    .catch(err => {
        btn.disabled = false;
        icon.className = 'bx bxs-save text-lg';
        text.textContent = 'Simpan Lokasi';
        showAlert('error', 'Terjadi kesalahan koneksi: ' + err.message);
    });
}

// =====================================================
// RESET KE DEFAULT
// =====================================================
function resetKeDefault() {
    if (!confirm('Reset koordinat ke nilai default (3.5655 – 3.5673, 98.6459 – 98.6473)?')) return;

    currentCoords = { ...DEFAULT };

    clearCornerMarkers();
    if (currentRect) {
        map.removeLayer(currentRect);
        currentRect = null;
    }

    currentRect = drawFinalRect(
        DEFAULT.lat_min, DEFAULT.lat_max,
        DEFAULT.lng_min, DEFAULT.lng_max
    );

    updateCoordDisplay(DEFAULT.lat_min, DEFAULT.lat_max, DEFAULT.lng_min, DEFAULT.lng_max);
    document.getElementById('inputNamaLokasi').value = DEFAULT.nama_lokasi;
    document.getElementById('btnSimpan').disabled = false;
    document.getElementById('mapStatusText').textContent = 'Koordinat direset ke default';

    showAlert('info', 'Koordinat direset ke nilai default. Klik Simpan untuk menyimpan perubahan.');
}

// =====================================================
// ALERT HELPER
// =====================================================
function showAlert(type, msg) {
    const el = document.getElementById('ajaxAlert');
    const icons = {
        success: 'bxs-check-circle',
        error: 'bxs-error-circle',
        info: 'bxs-info-circle',
    };
    const classes = {
        success: 'alert-success-custom',
        error: 'alert-error-custom',
        info: 'bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl flex items-center gap-3 text-sm font-semibold',
    };

    el.className = classes[type] || classes.info;
    el.innerHTML = `<i class='bx ${icons[type] || icons.info} text-xl'></i>${msg}`;
    el.classList.remove('hidden');

    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    setTimeout(() => el.classList.add('hidden'), 5000);
}

// =====================================================
// BOOT
// =====================================================
document.addEventListener('DOMContentLoaded', function () {
    initMap();
    updateStep(1);

    // Kalau ada data dari DB, langsung tampilkan koordinat di card
    if (savedLokasi) {
        updateCoordDisplay(
            parseFloat(savedLokasi.lat_min),
            parseFloat(savedLokasi.lat_max),
            parseFloat(savedLokasi.lng_min),
            parseFloat(savedLokasi.lng_max)
        );
        document.getElementById('btnSimpan').disabled = false;
        document.getElementById('mapStatusText').textContent = 'Data lokasi dimuat dari database';
    }
});
</script>

@endsection
