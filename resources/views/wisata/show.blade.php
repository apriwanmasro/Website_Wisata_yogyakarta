@extends('layouts.app')

@section('title', $wisata->nama_wisata)
@section('page-title', '🏛️ ' . $wisata->nama_wisata)

@section('topbar-actions')
<a href="{{ route('wisata.edit', $wisata->id) }}" style="background: linear-gradient(135deg, #059669, #047857); color:white; text-decoration:none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
    <i class="bi bi-pencil"></i> Edit
</a>
@endsection

@section('content')

<div class="row g-4">
    <!-- MAIN INFO -->
    <div class="col-xl-8">

        <!-- HERO CARD -->
        <div class="form-card mb-4" style="padding: 0; overflow: hidden; border-radius: 20px; background: linear-gradient(135deg, #8B1A1A, #5D4037);">
            <div style="padding: 30px 28px; color: white;">
                <div style="font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700; margin-bottom: 8px;">
                    {{ $wisata->nama_wisata }}
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        <i class="bi bi-star-fill"></i> {{ number_format($wisata->rating, 1) }}
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                        {{ $wisata->kategori ?? 'Wisata Lainnya' }}
                    </span>
                </div>
            </div>
        </div>

        <div style="padding: 24px 28px; background: white; border-radius: 20px;">
            <!-- BADGES -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                @php
                $colors = [
                'Candi & Sejarah' => '#e67e22',
                'Budaya & Sejarah' => '#8e44ad',
                'Pantai & Alam' => '#2980b9',
                'Alam & Petualangan' => '#27ae60',
                'Belanja & Kuliner' => '#e74c3c',
                'Hiburan & Keluarga' => '#f39c12'
                ];
                $color = $colors[$wisata->kategori] ?? '#7f8c8d';
                @endphp
                <span style="background: {{ $color }}; color: white; font-size: 13px; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                    {{ $wisata->kategori ?? 'Wisata Lainnya' }}
                </span>
                <span style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                    ⭐ {{ number_format($wisata->rating, 1) }} / 5.0
                </span>
                <span style="background: rgba(5,150,105,0.1); color: #059669; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                    ID Wisata #{{ $wisata->id }}
                </span>
            </div>

            <!-- DESKRIPSI -->
            <div class="section-title" style="font-size: 18px; font-weight: 700; color: #8B1A1A; margin-bottom: 12px;">
                📖 Deskripsi
            </div>
            <p style="font-size: 15px; line-height: 1.8; color: #374151; margin-bottom: 24px;">
                {{ $wisata->deskripsi ?? 'Belum ada deskripsi untuk wisata ini.' }}
            </p>

            <!-- DETAIL GRID -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; border: 1px solid rgba(139,26,26,0.08);">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #C8923A; margin-bottom: 6px;">
                            ⏰ Jam Operasional
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: #2C1810;">{{ $wisata->jam_operasional ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; border: 1px solid rgba(139,26,26,0.08);">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #C8923A; margin-bottom: 6px;">
                            💰 Harga Tiket
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: #059669;">{{ $wisata->harga_tiket ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; border: 1px solid rgba(139,26,26,0.08);">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #C8923A; margin-bottom: 6px;">
                            📍 Lokasi
                        </div>
                        <div style="font-size: 14px; color: #2C1810;">{{ $wisata->lokasi ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; border: 1px solid rgba(139,26,26,0.08);">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #C8923A; margin-bottom: 8px;">
                            🏗️ Fasilitas
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                            $fasilitasList = $wisata->fasilitas ? explode(',', $wisata->fasilitas) : [];
                            @endphp
                            @forelse($fasilitasList as $fasilitas)
                            <span style="background: white; border: 1px solid rgba(139,26,26,0.15); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; color: #5D4037;">
                                {{ trim($fasilitas) }}
                            </span>
                            @empty
                            <span style="color: #9CA3AF; font-size: 12px;">Tidak ada informasi fasilitas</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MINI MAP -->
        @if(!empty($wisata->latitude) && !empty($wisata->longitude))
        <div class="form-card mt-4" style="background: white; border-radius: 20px; padding: 20px;">
            <div class="section-title" style="font-size: 18px; font-weight: 700; color: #8B1A1A; margin-bottom: 12px;">
                🗺️ Lokasi di Peta
            </div>
            <div id="detail-map" style="height: 300px; border-radius: 12px; border: 2px solid rgba(200,146,58,0.3); overflow: hidden;"></div>
            <div style="margin-top: 10px; font-size: 12px; color: #9ca3af; display: flex; gap: 16px; flex-wrap: wrap;">
                <span>📍 Lat: {{ $wisata->latitude }}</span>
                <span>📍 Lng: {{ $wisata->longitude }}</span>
                <a href="https://maps.google.com/?q={{ $wisata->latitude }},{{ $wisata->longitude }}" target="_blank"
                    style="color: #C8923A; font-weight: 600; text-decoration: none;">
                    Buka di Google Maps →
                </a>
            </div>
        </div>
        @endif

    </div>

    <!-- SIDEBAR -->
    <div class="col-xl-4">
        <!-- ACTIONS -->
        <div class="form-card mb-4" style="background: white; border-radius: 20px; padding: 20px;">
            <div class="section-title" style="font-size: 18px; font-weight: 700; color: #8B1A1A; margin-bottom: 16px;">
                ⚡ Aksi
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('wisata.edit', $wisata->id) }}" class="btn-jogja justify-content-center d-flex" style="background: linear-gradient(135deg, #C8923A, #B8860B); color: white; padding: 10px; border-radius: 10px; text-decoration: none; text-align: center;">
                    <i class="bi bi-pencil me-2"></i> Edit Data Wisata
                </a>
                <a href="{{ route('wisata.map') }}" style="background: linear-gradient(135deg, #059669, #047857); color: white; padding: 10px; border-radius: 10px; text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-map me-2"></i> Lihat di Peta Lengkap
                </a>
                <a href="{{ route('wisata.index') }}" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 10px; border-radius: 10px; text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                </a>
                <form action="{{ route('wisata.destroy', $wisata->id) }}" method="POST"
                    onsubmit="return confirm('Hapus wisata {{ addslashes($wisata->nama_wisata) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width: 100%; background: #FEE2E2; color: #DC2626; border: none; padding: 10px; border-radius: 10px; font-weight: 600;">
                        <i class="bi bi-trash me-2"></i> Hapus Data Ini
                    </button>
                </form>
            </div>
        </div>

        <!-- METADATA -->
        <div class="form-card" style="background: white; border-radius: 20px; padding: 20px;">
            <div class="section-title" style="font-size: 18px; font-weight: 700; color: #8B1A1A; margin-bottom: 16px;">
                📊 Informasi
            </div>
            <div style="font-size: 13px; color: #374151;">
                <div style="padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">ID</span>
                    <span style="font-weight: 700;">#{{ $wisata->id }}</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Rating</span>
                    <span style="font-weight: 700; color: #f59e0b;">⭐ {{ number_format($wisata->rating, 1) }}</span>
                </div>
                <div style="padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Kategori</span>
                    <span style="font-weight: 700;">{{ $wisata->kategori ?? 'Wisata Lainnya' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if(!empty($wisata->latitude) && !empty($wisata->longitude))
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('detail-map').setView([{
            {
                $wisata - > latitude
            }
        }, {
            {
                $wisata - > longitude
            }
        }], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        L.marker([{
                {
                    $wisata - > latitude
                }
            }, {
                {
                    $wisata - > longitude
                }
            }])
            .addTo(map)
            .bindPopup('<strong>{{ addslashes($wisata->nama_wisata) }}</strong><br>⭐ {{ number_format($wisata->rating, 1) }}<br>{{ addslashes(Str::limit($wisata->lokasi ?? '
                ', 50)) }}')
            .openPopup();
    });
</script>
@endif
@endpush