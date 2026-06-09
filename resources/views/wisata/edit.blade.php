@extends('layouts.app')

@section('title', 'Edit ' . $wisata->nama_wisata)
@section('page-title', '✏️ Edit Wisata: ' . $wisata->nama_wisata)

@section('topbar-actions')
<a href="{{ route('wisata.show', $wisata->id) }}" style="background: #6B7280; color:white; text-decoration:none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
@endsection

@section('content')

<div class="row g-4">
    <div class="col-xl-8">
        <div class="table-card" style="background: white; border-radius: 20px; overflow: hidden;">
            <div class="table-header" style="padding: 16px 20px; border-bottom: 2px solid #F3F4F6;">
                <h5 style="margin: 0; color: #8B1A1A;">
                    <i class="bi bi-pencil-square"></i> Edit Data Wisata
                </h5>
            </div>

            <div style="padding: 24px;">
                <form action="{{ route('wisata.update', $wisata->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-2">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">No. Urut *</label>
                            <input type="number" name="no_urut" class="form-control @error('no_urut') is-invalid @enderror"
                                value="{{ old('no_urut', $wisata->no_urut ?? $wisata->id) }}" required>
                            @error('no_urut')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-10">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Nama Wisata *</label>
                            <input type="text" name="nama_wisata" class="form-control @error('nama_wisata') is-invalid @enderror"
                                value="{{ old('nama_wisata', $wisata->nama_wisata) }}" required>
                            @error('nama_wisata')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Jam Operasional</label>
                            <input type="text" name="jam_operasional" class="form-control @error('jam_operasional') is-invalid @enderror"
                                value="{{ old('jam_operasional', $wisata->jam_operasional) }}">
                            @error('jam_operasional')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Harga Tiket</label>
                            <input type="text" name="harga_tiket" class="form-control @error('harga_tiket') is-invalid @enderror"
                                value="{{ old('harga_tiket', $wisata->harga_tiket) }}">
                            @error('harga_tiket')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Rating</label>
                            <input type="number" step="0.1" name="rating" class="form-control @error('rating') is-invalid @enderror"
                                value="{{ old('rating', $wisata->rating) }}" min="0" max="5" step="0.1">
                            @error('rating')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Kategori</label>
                            <input type="text" class="form-control" value="{{ $wisata->kategori ?? 'Otomatis berdasarkan nama' }}" disabled>
                            <small class="text-muted">Kategori ditentukan otomatis berdasarkan nama wisata</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Latitude</label>
                            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                                value="{{ old('latitude', $wisata->latitude ?? '') }}" placeholder="Contoh: -7.6079">
                            @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Koordinat lintang (opsional untuk peta)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 600; color: #2C1810;">Longitude</label>
                            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                                value="{{ old('longitude', $wisata->longitude ?? '') }}" placeholder="Contoh: 110.2038">
                            @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Koordinat bujur (opsional untuk peta)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2C1810;">Lokasi</label>
                        <textarea name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" rows="2"
                            placeholder="Alamat lengkap wisata">{{ old('lokasi', $wisata->lokasi) }}</textarea>
                        @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2C1810;">Fasilitas</label>
                        <textarea name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror" rows="3"
                            placeholder="Pisahkan dengan koma, contoh: Parkir, Toilet, Restoran">{{ old('fasilitas', $wisata->fasilitas) }}</textarea>
                        @error('fasilitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pisahkan setiap fasilitas dengan tanda koma (,)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2C1810;">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5"
                            placeholder="Deskripsikan wisata ini secara lengkap">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn-jogja" style="background: linear-gradient(135deg, #059669, #047857); padding: 10px 24px;">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('wisata.show', $wisata->id) }}" class="btn-outline-jogja" style="padding: 10px 24px;">
                            <i class="bi bi-x"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="table-card" style="background: white; border-radius: 20px; overflow: hidden;">
            <div class="table-header" style="padding: 16px 20px; border-bottom: 2px solid #F3F4F6;">
                <h5 style="margin: 0; color: #8B1A1A;">
                    <i class="bi bi-info-circle"></i> Informasi
                </h5>
            </div>
            <div style="padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 12px; color: #6B7280;">ID Wisata</div>
                    <div style="font-weight: 700; color: #2C1810;">#{{ $wisata->id }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 12px; color: #6B7280;">Rating Saat Ini</div>
                    <div style="font-weight: 700; color: #f59e0b;">⭐ {{ number_format($wisata->rating, 1) }} / 5.0</div>
                </div>
                <hr>
                <div class="text-muted" style="font-size: 11px;">
                    <i class="bi bi-info-circle"></i> Data akan diperbarui setelah menyimpan perubahan
                </div>
            </div>
        </div>
    </div>
</div>

@endsection