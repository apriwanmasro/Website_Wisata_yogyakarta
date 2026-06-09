@extends('layouts.app')

@section('title', 'Peta Wisata Yogyakarta')
@section('page-title', '🗺️ Peta 15 Destinasi Wisata Yogyakarta')

@push('styles')
<style>
    #main-map {
        height: calc(100vh - 200px);
        min-height: 500px;
        border-radius: 16px;
        border: 3px solid rgba(200, 146, 58, 0.4);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .map-legend {
        background: white;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid rgba(139, 26, 26, 0.1);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 0;
        font-size: 12px;
        font-weight: 600;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .legend-item:last-child {
        border-bottom: none;
    }

    .legend-item:hover {
        opacity: 0.7;
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .wisata-list-item {
        padding: 10px 12px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wisata-list-item:hover {
        background: #F5E6D3;
    }

    .wisata-list-item:last-child {
        border-bottom: none;
    }

    .wisata-num {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #8B1A1A, #a52020);
        color: white;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        border: none;
        padding: 0;
        overflow: hidden;
    }

    .custom-popup .leaflet-popup-content {
        margin: 0;
        min-width: 240px;
    }

    .popup-header {
        background: linear-gradient(135deg, #8B1A1A, #a52020);
        color: white;
        padding: 14px 16px;
    }

    .popup-body {
        padding: 12px 16px;
    }
</style>
@endpush

@section('content')

<div class="row g-4" style="height: calc(100vh - 180px);">

    <!-- SIDEBAR LIST -->
    <div class="col-xl-3" style="overflow-y: auto; max-height: 100%;">

        <!-- LEGEND -->
        <div class="map-legend">
            <div style="font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: #8B1A1A; margin-bottom: 12px;">
                🏷️ Legenda Kategori
            </div>
            @php
            $categories = [
            'Candi & Sejarah' => '#e67e22',
            'Budaya & Sejarah' => '#8e44ad',
            'Pantai & Alam' => '#2980b9',
            'Alam & Petualangan' => '#27ae60',
            'Belanja & Kuliner' => '#e74c3c',
            'Hiburan & Keluarga' => '#f39c12',
            ];
            @endphp
            @foreach($categories as $kat => $col)
            <div class="legend-item" onclick="filterByKategori('{{ $kat }}')">
                <div class="legend-dot" style="background: {{ $col }};"></div>
                <span style="color: #2C1810;">{{ $kat }}</span>
            </div>
            @endforeach
            <div style="margin-top: 10px;">
                <button onclick="resetFilter()" style="width: 100%; background: none; border: 1.5px solid #8B1A1A; color: #8B1A1A; border-radius: 8px; padding: 7px; font-size: 12px; font-weight: 700; cursor: pointer;">
                    🔄 Tampilkan Semua
                </button>
            </div>
        </div>

        <!-- LIST -->
        <div class="map-legend">
            <div style="font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: #8B1A1A; margin-bottom: 12px;">
                📍 {{ $wisatas->count() }} Destinasi
            </div>
            @foreach($wisatas->sortBy('no_urut') as $wisata)
            @php $col = $categories[$wisata->kategori] ?? '#7f8c8d'; @endphp
            <div class="wisata-list-item" onclick="flyToWisata({{ $wisata->latitude ?? -7.8 }}, {{ $wisata->longitude ?? 110.37 }}, {{ $wisata->id }})">
                <div class="wisata-num">{{ $wisata->no_urut ?? $loop->iteration }}</div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 13px; font-weight: 700; color: #2C1810; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $wisata->nama_wisata }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $col }}; flex-shrink: 0;"></span>
                        <span style="font-size: 10px; color: #9ca3af; font-weight: 600;">⭐ {{ number_format($wisata->rating, 1) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <!-- MAP -->
    <div class="col-xl-9">
        <div id="main-map"></div>
    </div>

</div>

@endsection

@push('scripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // All wisata data
    const wisataData = @json($wisatas);

    console.log('Data wisata:', wisataData);

    const categoryColors = {
        'Candi & Sejarah': '#e67e22',
        'Budaya & Sejarah': '#8e44ad',
        'Pantai & Alam': '#2980b9',
        'Alam & Petualangan': '#27ae60',
        'Belanja & Kuliner': '#e74c3c',
        'Hiburan & Keluarga': '#f39c12',
    };

    // Inisialisasi map dengan koordinat Yogyakarta
    const map = L.map('main-map').setView([-7.7956, 110.3695], 11);

    // Tile layers
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '© Esri Satellite',
        maxZoom: 19,
    });

    // Layer control
    L.control.layers({
        '🗺️ Peta Standar': osmLayer,
        '🛰️ Satelit': satelliteLayer,
    }).addTo(map);

    // Scale bar
    L.control.scale({
        metric: true,
        imperial: false,
        position: 'bottomleft'
    }).addTo(map);

    // Custom icon generator
    function createIcon(color, number) {
        return L.divIcon({
            className: '',
            html: `
            <div style="
                width: 38px; height: 38px;
                background: ${color};
                border: 3px solid white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 3px 12px rgba(0,0,0,0.35);
                display: flex; align-items: center; justify-content: center;
                cursor: pointer;
            ">
            </div>
            `,
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -40],
        });
    }

    // Store markers
    let markers = {};
    let markerObjects = {};

    // Add markers to map
    wisataData.forEach(w => {
        // Skip if no coordinates (use default based on lokasi or skip)
        let lat = w.latitude;
        let lng = w.longitude;

        // If no coordinates, skip this marker
        if (!lat || !lng) {
            console.warn(`No coordinates for: ${w.nama_wisata}`);
            return;
        }

        const color = categoryColors[w.kategori] || '#7f8c8d';
        const nomor = w.no_urut || 0;
        const icon = createIcon(color, nomor);

        // Handle fasilitas (if null or not array)
        let fasilitasHtml = '';
        if (w.fasilitas) {
            const fasilitasList = String(w.fasilitas).split(',').slice(0, 4).map(f =>
                `<span style="background: rgba(0,0,0,0.08); padding: 2px 8px; border-radius: 10px; font-size: 11px;">${f.trim()}</span>`
            ).join(' ');
            fasilitasHtml = `
            <div style="margin-bottom: 12px;">
                <div style="font-size: 11px; color: #9ca3af; font-weight: 700; margin-bottom: 6px;">🏗️ FASILITAS</div>
                <div style="display: flex; flex-wrap: wrap; gap: 4px;">${fasilitasList}</div>
            </div>`;
        }

        const popupContent = `
            <div class="popup-header">
                <div style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 700; margin-bottom: 4px;">
                    ${w.nama_wisata}
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <span style="background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">${w.kategori || 'Wisata Lainnya'}</span>
                    <span style="font-size: 13px; font-weight: 700;">⭐ ${Number(w.rating).toFixed(1)}</span>
                </div>
            </div>
            <div class="popup-body">
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 11px; color: #9ca3af; font-weight: 700; margin-bottom: 2px;">⏰ JAM BUKA</div>
                    <div style="font-size: 13px; font-weight: 600; color: #374151;">${w.jam_operasional || '-'}</div>
                </div>
                <div style="margin-bottom: 8px;">
                    <div style="font-size: 11px; color: #9ca3af; font-weight: 700; margin-bottom: 2px;">💰 TIKET</div>
                    <div style="font-size: 13px; font-weight: 600; color: #059669;">${w.harga_tiket || '-'}</div>
                </div>
                ${fasilitasHtml}
                <div style="display: flex; gap: 8px;">
                    <a href="/wisata/${w.id}" style="flex: 1; text-align: center; background: linear-gradient(135deg, #8B1A1A, #a52020); color: white; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; display: block;">
                        👁️ Detail
                    </a>
                    <a href="/wisata/${w.id}/edit" style="flex: 1; text-align: center; background: linear-gradient(135deg, #059669, #047857); color: white; padding: 8px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; display: block;">
                        ✏️ Edit
                    </a>
                </div>
            </div>
        `;

        const marker = L.marker([lat, lng], {
                icon
            })
            .bindPopup(popupContent)
            .addTo(map);

        markers[w.id] = marker;
        markerObjects[w.id] = {
            marker: marker,
            kategori: w.kategori || 'Wisata Lainnya'
        };
    });

    // Fly to wisata function
    window.flyToWisata = function(lat, lng, id) {
        map.flyTo([lat, lng], 15, {
            duration: 1.2
        });
        setTimeout(() => {
            if (markers[id]) {
                markers[id].openPopup();
            }
        }, 1300);
    };

    // Filter by kategori function
    window.filterByKategori = function(kategori) {
        Object.values(markerObjects).forEach(({
            marker,
            kategori: k
        }) => {
            if (k === kategori) {
                if (!map.hasLayer(marker)) map.addLayer(marker);
            } else {
                if (map.hasLayer(marker)) map.removeLayer(marker);
            }
        });
    };

    // Reset filter function
    window.resetFilter = function() {
        Object.values(markerObjects).forEach(({
            marker
        }) => {
            if (!map.hasLayer(marker)) map.addLayer(marker);
        });
    };

    // Fit bounds to all markers
    const bounds = wisataData
        .filter(w => w.latitude && w.longitude)
        .map(w => [w.latitude, w.longitude]);

    if (bounds.length > 0) {
        map.fitBounds(bounds, {
            padding: [50, 50]
        });
    }

    // Tambahan: Responsif resize
    window.addEventListener('resize', function() {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });
</script>
@endpush