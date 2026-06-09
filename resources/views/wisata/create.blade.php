@extends('layouts.app')

@section('title', 'Tambah Wisata Baru')
@section('page-title', '➕ Tambah Destinasi Wisata')

@section('content')

<div class="row g-4">
    <div class="col-xl-8">
        <div class="form-card">
            <div class="section-title">📝 Form Input Data Wisata</div>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 28px;">
                Lengkapi semua informasi destinasi wisata di bawah ini dengan benar dan lengkap.
            </p>

            <form action="{{ route('wisata.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- ROW 1: No & Nama -->
                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label class="form-label">No. Urut *</label>
                        <input type="number" name="no" class="form-control @error('no') is-invalid @enderror"
                            value="{{ old('no', $nextNo) }}" required>
                        @error('no')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-10">
                        <label class="form-label">Nama Wisata *</label>
                        <input type="text" name="nama_wisata" class="form-control @error('nama_wisata') is-invalid @enderror"
                            placeholder="Contoh: Candi Borobudur"
                            value="{{ old('nama_wisata') }}" required>
                        @error('nama_wisata')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ROW 2: Jam & Harga -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">⏰ Jam Operasional *</label>
                        <input type="text" name="jam_operasional" class="form-control @error('jam_operasional') is-invalid @enderror"
                            placeholder="Contoh: 06.00 - 17.00 WIB"
                            value="{{ old('jam_operasional') }}" required>
                        @error('jam_operasional')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">💰 Harga Tiket *</label>
                        <input type="text" name="harga_tiket" class="form-control @error('harga_tiket') is-invalid @enderror"
                            placeholder="Contoh: Rp 50.000 (Dewasa) / Rp 25.000 (Anak)"
                            value="{{ old('harga_tiket') }}" required>
                        @error('harga_tiket')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- ROW 3: Kategori & Rating -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">🏷️ Kategori *</label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                            @endforeach
                        </select>
                        @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">⭐ Rating (0-5) *</label>
                        <div class="input-group">
                            <input type="number" name="rating" step="0.1" min="0" max="5"
                                class="form-control @error('rating') is-invalid @enderror"
                                placeholder="4.5" value="{{ old('rating', '4.0') }}" required
                                oninput="updateStars(this.value)">
                            <span class="input-group-text" id="star-display" style="font-size: 16px; min-width: 100px;">⭐⭐⭐⭐</span>
                        </div>
                        @error('rating')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Fasilitas -->
                <div class="mb-3">
                    <label class="form-label">🏗️ Fasilitas *</label>
                    <input type="text" name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror"
                        placeholder="Parkir, Toilet, Mushola, Pemandu Wisata, ..."
                        value="{{ old('fasilitas') }}" required>
                    <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Pisahkan dengan koma (,)</div>
                    @error('fasilitas')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div class="mb-3">
                    <label class="form-label">📍 Lokasi / Alamat *</label>
                    <textarea name="lokasi" rows="2" class="form-control @error('lokasi') is-invalid @enderror"
                        placeholder="Jl. ..., Kecamatan, Kabupaten, Yogyakarta" required>{{ old('lokasi') }}</textarea>
                    @error('lokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label">📖 Deskripsi *</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Deskripsi lengkap tentang destinasi wisata ini..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Koordinat -->
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <div style="background: var(--jogja-light); border: 1.5px dashed var(--jogja-gold); border-radius: 10px; padding: 14px 16px; margin-bottom: 8px;">
                            <div style="font-size: 13px; font-weight: 600; color: var(--jogja-maroon); margin-bottom: 4px;">
                                🗺️ Koordinat Peta (Klik peta di bawah untuk mengisi otomatis)
                            </div>
                            <div style="font-size: 12px; color: #6b7280;">
                                Atau isi manual: cari di Google Maps → klik kanan → "What's here?"
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" step="any" id="input-lat"
                            class="form-control @error('latitude') is-invalid @enderror"
                            placeholder="-7.7956" value="{{ old('latitude') }}">
                        @error('latitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" step="any" id="input-lng"
                            class="form-control @error('longitude') is-invalid @enderror"
                            placeholder="110.3695" value="{{ old('longitude') }}">
                        @error('longitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- MAP PICKER -->
                <div class="mb-4">
                    <div id="map-picker" style="height: 280px; border-radius: 12px; border: 2px solid rgba(200,146,58,0.3); overflow: hidden;"></div>
                    <div style="font-size: 11px; color: #9ca3af; margin-top: 6px; text-align: center;">
                        👆 Klik pada peta untuk memilih lokasi dan mengisi koordinat otomatis
                    </div>
                </div>

                <!-- Foto -->
                <div class="mb-4">
                    <label class="form-label">📸 Foto Wisata (Opsional)</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                        accept="image/*" onchange="previewFoto(this)">
                    <div id="foto-preview" style="margin-top: 10px; display: none;">
                        <img id="preview-img" src="" style="max-height: 150px; border-radius: 10px; border: 2px solid var(--jogja-gold);">
                    </div>
                    @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- SUBMIT -->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle me-2"></i> Simpan Data Wisata
                    </button>
                    <a href="{{ route('wisata.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px; padding: 12px 24px; font-weight: 600;">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- SIDE TIPS -->
    <div class="col-xl-4">
        <div class="form-card" style="position: sticky; top: 80px;">
            <div class="section-title">💡 Panduan Pengisian</div>

            <div style="space-y: 16px;">
                <div class="tip-item">
                    <div class="tip-icon">🔢</div>
                    <div>
                        <strong>No. Urut</strong>
                        <div>Nomor unik destinasi wisata (1-99)</div>
                    </div>
                </div>

                <div class="tip-item">
                    <div class="tip-icon">⏰</div>
                    <div>
                        <strong>Jam Operasional</strong>
                        <div>Format: "06.00 - 17.00 WIB" atau "24 Jam"</div>
                    </div>
                </div>

                <div class="tip-item">
                    <div class="tip-icon">💰</div>
                    <div>
                        <strong>Harga Tiket</strong>
                        <div>Sertakan tiket dewasa & anak jika berbeda</div>
                    </div>
                </div>

                <div class="tip-item">
                    <div class="tip-icon">⭐</div>
                    <div>
                        <strong>Rating</strong>
                        <div>Skala 1.0 - 5.0 (gunakan 1 desimal)</div>
                    </div>
                </div>

                <div class="tip-item">
                    <div class="tip-icon">🗺️</div>
                    <div>
                        <strong>Koordinat</strong>
                        <div>Klik peta atau cari di Google Maps untuk koordinat akurat</div>
                    </div>
                </div>
            </div>

            <div style="background: linear-gradient(135deg, var(--jogja-maroon), #a52020); border-radius: 12px; padding: 16px; margin-top: 24px; color: white;">
                <div style="font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 700; margin-bottom: 8px;">
                    🏛️ Tentang Data Ini
                </div>
                <div style="font-size: 12px; opacity: 0.85; line-height: 1.6;">
                    Data destinasi wisata ini akan tampil di peta interaktif dan dapat diakses oleh semua pengunjung web.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .tip-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-size: 13px;
        color: #374151;
    }

    .tip-item:last-child {
        border-bottom: none;
    }

    .tip-item .tip-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .tip-item strong {
        display: block;
        font-weight: 700;
        color: var(--jogja-dark);
        margin-bottom: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
    // MAP PICKER
    const mapPicker = L.map('map-picker').setView([-7.7956, 110.3695], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mapPicker);

    let marker = null;

    // If old values exist, place marker
    const oldLat = document.getElementById('input-lat').value;
    const oldLng = document.getElementById('input-lng').value;
    if (oldLat && oldLng) {
        marker = L.marker([parseFloat(oldLat), parseFloat(oldLng)]).addTo(mapPicker);
        mapPicker.setView([parseFloat(oldLat), parseFloat(oldLng)], 13);
    }

    mapPicker.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        document.getElementById('input-lat').value = lat;
        document.getElementById('input-lng').value = lng;

        if (marker) mapPicker.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(mapPicker)
            .bindPopup(`📍 Lat: ${lat}<br>Lng: ${lng}`).openPopup();
    });

    // Update marker when inputs change manually
    ['input-lat', 'input-lng'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            const lat = parseFloat(document.getElementById('input-lat').value);
            const lng = parseFloat(document.getElementById('input-lng').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                if (marker) mapPicker.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(mapPicker);
                mapPicker.setView([lat, lng], 13);
            }
        });
    });

    // STAR RATING
    function updateStars(val) {
        const v = parseFloat(val);
        let stars = '';
        if (v >= 4.8) stars = '⭐⭐⭐⭐⭐';
        else if (v >= 4.0) stars = '⭐⭐⭐⭐';
        else if (v >= 3.0) stars = '⭐⭐⭐';
        else if (v >= 2.0) stars = '⭐⭐';
        else stars = '⭐';
        document.getElementById('star-display').textContent = stars;
    }
    updateStars(document.querySelector('[name="rating"]').value);

    // FOTO PREVIEW
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('foto-preview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush