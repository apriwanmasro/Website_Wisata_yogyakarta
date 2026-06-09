@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', '🏛️ Dashboard Wisata Yogyakarta')

@push('styles')
<style>
    #dashboard-map {
        height: 400px;
        border-radius: 16px;
        border: 2px solid rgba(200, 146, 58, 0.3);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .map-legend-mini {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: white;
        padding: 10px 15px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        font-size: 11px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
    }

    .legend-dot-mini {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
</style>
@endpush

@section('content')

<!-- MAP SECTION - TAMBAHKAN INI -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="table-card" style="position: relative;">
            <div class="table-header">
                <h5><i class="bi bi-map"></i> Peta Sebaran Wisata Yogyakarta</h5>
                <a href="{{ route('wisata.map') }}" style="font-size: 13px; color: var(--jogja-gold); font-weight: 600; text-decoration: none;">
                    Lihat Peta Penuh →
                </a>
            </div>
            <div style="padding: 20px;">
                <div id="dashboard-map"></div>
                <div class="map-legend-mini">
                    <div style="font-weight: 700; margin-bottom: 5px;">🏷️ Kategori</div>
                    <div class="d-flex flex-wrap gap-2">
                        <span><span class="legend-dot-mini" style="background: #e67e22;"></span> Candi</span>
                        <span><span class="legend-dot-mini" style="background: #8e44ad;"></span> Budaya</span>
                        <span><span class="legend-dot-mini" style="background: #2980b9;"></span> Pantai</span>
                        <span><span class="legend-dot-mini" style="background: #27ae60;"></span> Alam</span>
                        <span><span class="legend-dot-mini" style="background: #e74c3c;"></span> Belanja</span>
                        <span><span class="legend-dot-mini" style="background: #f39c12;"></span> Keluarga</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(139,26,26,0.15), rgba(139,26,26,0.05)); color: var(--jogja-maroon);">
                🏛️
            </div>
            <div class="stat-value">{{ $totalWisata }}</div>
            <div class="stat-label">Total Destinasi Wisata</div>
            <div style="margin-top: 12px; font-size: 12px; color: var(--jogja-gold); font-weight: 600;">
                <i class="bi bi-geo-alt-fill me-1"></i> Yogyakarta & Sekitarnya
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); color: #f59e0b;">
                ⭐
            </div>
            <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
            <div class="stat-label">Rata-rata Rating</div>
            <div style="margin-top: 12px; font-size: 12px; color: #f59e0b; font-weight: 600;">
                <i class="bi bi-star-fill me-1"></i> Dari 5.0 Skala Penilaian
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(200,146,58,0.15), rgba(200,146,58,0.05)); color: var(--jogja-gold);">
                🏆
            </div>
            <div class="stat-value" style="font-size: 18px; padding-top: 4px;">
                {{ Str::limit($ratingTertinggi?->nama_wisata ?? '-', 25) }}
            </div>
            <div class="stat-label">Rating Tertinggi</div>
            <div style="margin-top: 12px; font-size: 12px; color: var(--jogja-gold); font-weight: 600;">
                ⭐ {{ $ratingTertinggi?->rating ?? '-' }} / 5.0
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, rgba(5,150,105,0.15), rgba(5,150,105,0.05)); color: #059669;">
                🗺️
            </div>
            <div class="stat-value">{{ $kategoris->count() }}</div>
            <div class="stat-label">Kategori Wisata</div>
            <div style="margin-top: 12px; font-size: 12px; color: #059669; font-weight: 600;">
                <i class="bi bi-tags-fill me-1"></i> Berbagai Jenis Wisata
            </div>
        </div>
    </div>
</div>

<!-- KATEGORI & RECENT TABLE -->
<div class="row g-4">

    <!-- KATEGORI BREAKDOWN -->
    <div class="col-xl-5">
        <div class="table-card h-100">
            <div class="table-header">
                <h5>📊 Sebaran Kategori</h5>
            </div>
            <div style="padding: 20px 24px;">
                @php
                $colors = [
                'Candi & Sejarah' => '#e67e22',
                'Budaya & Sejarah' => '#8e44ad',
                'Pantai & Alam' => '#2980b9',
                'Alam & Petualangan' => '#27ae60',
                'Belanja & Kuliner' => '#e74c3c',
                'Hiburan & Keluarga' => '#f39c12',
                ];
                $totalCat = $kategoris->sum('total');
                @endphp

                @foreach($kategoris as $kat)
                @php
                $pct = $totalCat > 0 ? ($kat->total / $totalCat) * 100 : 0;
                $color = $colors[$kat->kategori] ?? '#7f8c8d';
                @endphp
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div style="font-size: 13px; font-weight: 600; color: var(--jogja-dark);">
                            {{ $kat->kategori }}
                        </div>
                        <div style="font-size: 13px; font-weight: 700; color: {{ $color }};">
                            {{ $kat->total }} wisata
                        </div>
                    </div>
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $pct }}%; background: {{ $color }}; border-radius: 4px; transition: width 0.8s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RECENT WISATA -->
    <div class="col-xl-7">
        <div class="table-card h-100">
            <div class="table-header">
                <h5>🕐 Data Wisata Terbaru</h5>
                <a href="{{ route('wisata.index') }}" style="font-size: 13px; color: var(--jogja-gold); font-weight: 600; text-decoration: none;">
                    Lihat Semua →
                </a>
            </div>
            <div style="overflow-x: auto;">
                <table class="wisata-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 12px; text-align: left;">No</th>
                            <th style="padding: 12px; text-align: left;">Nama Wisata</th>
                            <th style="padding: 12px; text-align: left;">Kategori</th>
                            <th style="padding: 12px; text-align: left;">Rating</th>
                            <th style="padding: 12px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentWisata as $wisata)
                        @php
                        $color = $colors[$wisata->kategori] ?? '#7f8c8d';
                        @endphp
                        <tr style="border-bottom: 1px solid #F3F4F6;">
                            <td style="padding: 12px; vertical-align: middle;">
                                <div style="background: #8B1A1A; color: white; width: 28px; height: 28px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                                    {{ $wisata->no }}
                                </div>
                            </td>
                            <td style="padding: 12px; vertical-align: middle;">
                                <div style="font-weight: 600; color: #2C1810; margin-bottom: 4px;">{{ $wisata->nama_wisata }}</div>
                                <div style="font-size: 11px; color: #6B7280;">
                                    <i class="bi bi-geo-alt" style="font-size: 10px;"></i> {{ Str::limit($wisata->lokasi ?? '-', 40) }}
                                </div>
                            </td>
                            <td style="padding: 12px; vertical-align: middle;">
                                <span style="background: {{ $color }}; color: white; font-size: 11px; padding: 4px 10px; border-radius: 20px; display: inline-block; font-weight: 600;">
                                    {{ $wisata->kategori }}
                                </span>
                            </td>
                            <td style="padding: 12px; vertical-align: middle;">
                                <span style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; font-size: 12px; padding: 4px 10px; border-radius: 20px; display: inline-block; font-weight: 700;">
                                    ⭐ {{ number_format($wisata->rating, 1) }}
                                </span>
                            </td>
                            <td style="padding: 12px; vertical-align: middle; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="{{ route('wisata.show', $wisata->id) }}" style="background: #EFF6FF; color: #3B82F6; width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                                        <i class="bi bi-eye" style="font-size: 14px;"></i>
                                    </a>
                                    <a href="{{ route('wisata.edit', $wisata->id) }}" style="background: #FEF3C7; color: #D97706; width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
                                        <i class="bi bi-pencil" style="font-size: 14px;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- QUICK ACCESS BUTTONS -->
<div class="row mt-3">
    <div class="col-12">
        <div style="background: white; border-radius: 16px; padding: 12px 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <span style="font-weight: 700; color: #8B1A1A;">
                <i class="bi bi-lightning-charge"></i> Akses Cepat:
            </span>
            <a href="{{ route('wisata.create') }}" style="background: #8B1A1A; color: white; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                <i class="bi bi-plus-circle"></i> Tambah Wisata
            </a>
            <a href="{{ route('wisata.map') }}" style="background: #059669; color: white; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                <i class="bi bi-map"></i> Lihat Peta Lengkap
            </a>
            <a href="{{ route('wisata.index') }}" style="background: #2563eb; color: white; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                <i class="bi bi-collection"></i> Semua Data
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data wisata untuk peta
        const wisataData = @json($wisataForMap);
        const categoryColors = {
            'Candi & Sejarah': '#e67e22',
            'Budaya & Sejarah': '#8e44ad',
            'Pantai & Alam': '#2980b9',
            'Alam & Petualangan': '#27ae60',
            'Belanja & Kuliner': '#e74c3c',
            'Hiburan & Keluarga': '#f39c12',
        };

        // Inisialisasi peta
        const map = L.map('dashboard-map').setView([-7.7956, 110.3695], 10);

        // Tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        // Function to create custom icon
        function createMapIcon(color, number) {
            return L.divIcon({
                className: '',
                html: `
                    <div style="
                        width: 32px; height: 32px;
                        background: ${color};
                        border: 2px solid white;
                        border-radius: 50% 50% 50% 0;
                        transform: rotate(-45deg);
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                        display: flex; align-items: center; justify-content: center;
                        cursor: pointer;
                    ">
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -35],
            });
        }

        // Add markers to map
        let markerCount = 0;
        wisataData.forEach(function(wisata, index) {
            if (wisata.latitude && wisata.longitude) {
                markerCount++;
                const color = categoryColors[wisata.kategori] || '#7f8c8d';
                const icon = createMapIcon(color, markerCount);

                // Format fasilitas untuk popup
                let fasilitasHtml = '';
                if (wisata.fasilitas) {
                    const fasilitasList = String(wisata.fasilitas).split(',').slice(0, 3);
                    fasilitasHtml = '<div style="margin-top: 8px;"><strong>🏗️ Fasilitas:</strong><br>';
                    fasilitasList.forEach(function(f) {
                        fasilitasHtml += '<span style="font-size: 11px;">✓ ' + f.trim() + '</span><br>';
                    });
                    fasilitasHtml += '</div>';
                }

                const popupContent = `
                    <div style="min-width: 200px;">
                        <div style="font-weight: 700; font-size: 14px; color: #8B1A1A; margin-bottom: 5px;">
                            ${wisata.nama_wisata}
                        </div>
                        <div style="font-size: 11px; color: #6B7280; margin-bottom: 5px;">
                            <i class="bi bi-geo-alt"></i> ${wisata.lokasi ? wisata.lokasi.substring(0, 60) : '-'}
                        </div>
                        <div style="display: flex; gap: 10px; margin-bottom: 5px;">
                            <span style="background: ${color}; color: white; padding: 2px 8px; border-radius: 10px; font-size: 10px;">${wisata.kategori}</span>
                            <span style="color: #f59e0b; font-weight: 700;">⭐ ${parseFloat(wisata.rating).toFixed(1)}</span>
                        </div>
                        ${fasilitasHtml}
                        <div style="margin-top: 10px;">
                            <a href="/wisata/${wisata.id}" style="background: #8B1A1A; color: white; padding: 4px 10px; border-radius: 6px; text-decoration: none; font-size: 11px;">Lihat Detail</a>
                        </div>
                    </div>
                `;

                L.marker([wisata.latitude, wisata.longitude], {
                        icon
                    })
                    .bindPopup(popupContent)
                    .addTo(map);
            }
        });

        // Fit bounds jika ada marker
        if (markerCount > 0) {
            const bounds = wisataData
                .filter(w => w.latitude && w.longitude)
                .map(w => [w.latitude, w.longitude]);
            if (bounds.length > 0) {
                map.fitBounds(bounds, {
                    padding: [30, 30]
                });
            }
        }
    });
</script>
@endpush