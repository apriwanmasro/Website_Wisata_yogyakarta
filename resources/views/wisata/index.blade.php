@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Data Wisata')
@section('page-title', '🗺️ Data Wisata Yogyakarta')

@section('topbar-actions')
<a href="{{ route('wisata.map') }}" style="background: linear-gradient(135deg, #059669, #047857); color:white; text-decoration:none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
    <i class="bi bi-map"></i> Lihat Peta
</a>
@endsection

@section('content')

<!-- SEARCH & FILTER -->
<div class="search-bar" style="background: white; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <form method="GET" action="{{ route('wisata.index') }}" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label" style="font-size: 11px; font-weight: 700; color: #8B1A1A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                🔍 Cari Wisata
            </label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama wisata atau lokasi..."
                value="{{ request('search') }}" style="border-radius: 10px; border: 1.5px solid #E5E7EB; font-size: 13px;">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size: 11px; font-weight: 700; color: #8B1A1A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                🏷️ Kategori
            </label>
            <select name="kategori" class="form-select form-select-sm" style="border-radius: 10px; border: 1.5px solid #E5E7EB; font-size: 13px;">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                    {{ $kat }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 11px; font-weight: 700; color: #8B1A1A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                📊 Urutkan
            </label>
            <select name="sort" class="form-select form-select-sm" style="border-radius: 10px; border: 1.5px solid #E5E7EB; font-size: 13px;">
                <option value="no" {{ request('sort') == 'no' ? 'selected' : '' }}>No. Urut</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn-filter" style="background: #C8923A; color: #2C1810; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                <i class="bi bi-search"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'kategori', 'sort']))
            <a href="{{ route('wisata.index') }}" class="btn-reset" style="background: #F3F4F6; color: #6B7280; padding: 8px 12px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i class="bi bi-x"></i> Reset
            </a>
            @endif
        </div>
    </form>
</div>

<!-- TABLE -->
<div class="table-card" style="background: white; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
    <div class="table-header" style="padding: 16px 20px; border-bottom: 2px solid #F3F4F6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h5 style="font-family: 'Playfair Display', serif; font-weight: 700; margin: 0; color: #8B1A1A; font-size: 16px;">
            📋 Daftar {{ $wisatas->total() }} Destinasi Wisata
        </h5>
        <div style="font-size: 12px; color: #6B7280;">
            Halaman {{ $wisatas->currentPage() }} dari {{ $wisatas->lastPage() }}
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background: #F9FAFB; border-bottom: 2px solid #E5E7EB;">
                    <th style="padding: 14px 12px; text-align: left; font-weight: 700; color: #6B7280; font-size: 12px; width: 5%;">NO</th>
                    <th style="padding: 14px 12px; text-align: left; font-weight: 700; color: #6B7280; font-size: 12px; width: 25%;">NAMA WISATA</th>
                    <th style="padding: 14px 12px; text-align: left; font-weight: 700; color: #6B7280; font-size: 12px; width: 15%;">JAM OPERASIONAL</th>
                    <th style="padding: 14px 12px; text-align: left; font-weight: 700; color: #6B7280; font-size: 12px; width: 15%;">HARGA TIKET</th>
                    <th style="padding: 14px 12px; text-align: left; font-weight: 700; color: #6B7280; font-size: 12px; width: 12%;">KATEGORI</th>
                    <th style="padding: 14px 12px; text-align: center; font-weight: 700; color: #6B7280; font-size: 12px; width: 8%;">RATING</th>
                    <th style="padding: 14px 12px; text-align: center; font-weight: 700; color: #6B7280; font-size: 12px; width: 12%;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wisatas as $wisata)
                @php
                $colors = [
                'Candi & Sejarah' => '#e67e22',
                'Budaya & Sejarah' => '#8e44ad',
                'Pantai & Alam' => '#2980b9',
                'Alam & Petualangan' => '#27ae60',
                'Belanja & Kuliner' => '#e74c3c',
                'Hiburan & Keluarga' => '#f39c12',
                ];
                $color = $colors[$wisata->kategori] ?? '#7f8c8d';
                @endphp
                <tr style="border-bottom: 1px solid #F3F4F6; transition: background 0.2s;">
                    <td style="padding: 14px 12px; vertical-align: middle;">
                        <div style="background: linear-gradient(135deg, #8B1A1A, #6B1414); color: white; width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                            {{ ($wisatas->currentPage() - 1) * $wisatas->perPage() + $loop->iteration }}
                        </div>
                    </td>
                    <td style="padding: 14px 12px; vertical-align: middle;">
                        <div style="font-weight: 700; color: #2C1810; font-size: 14px; margin-bottom: 4px;">
                            {{ $wisata->nama_wisata }}
                        </div>
                        <div style="font-size: 11px; color: #9CA3AF; display: flex; align-items: center; gap: 4px;">
                            <i class="bi bi-geo-alt" style="font-size: 10px;"></i> {{ Str::limit($wisata->lokasi ?? '-', 40) }}
                        </div>
                    </td>
                    <td style="padding: 14px 12px; vertical-align: middle;">
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 12px; color: #374151;">
                            <i class="bi bi-clock" style="color: #C8923A; font-size: 12px;"></i>
                            {{ $wisata->jam_operasional ?? '-' }}
                        </div>
                    </td>
                    <td style="padding: 14px 12px; vertical-align: middle;">
                        <div style="font-weight: 600; color: #059669; font-size: 12px;">
                            {{ Str::limit($wisata->harga_tiket ?? '-', 30) }}
                        </div>
                    </td>
                    <td style="padding: 14px 12px; vertical-align: middle;">
                        <span style="background: {{ $color }}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                            {{ $wisata->kategori }}
                        </span>
                    </td>
                    <td style="padding: 14px 12px; vertical-align: middle; text-align: center;">
                        <span style="background: linear-gradient(135deg, #FEF3C7, #FDE68A); color: #92400E; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block;">
                            ⭐ {{ number_format($wisata->rating, 1) }}
                        </span>
                    </td>
                    <td style="padding: 14px 12px; vertical-align: middle; text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="{{ route('wisata.show', $wisata->id) }}" style="background: #EFF6FF; color: #3B82F6; width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#3B82F6'; this.style.color='white'" onmouseout="this.style.backgroundColor='#EFF6FF'; this.style.color='#3B82F6'">
                                <i class="bi bi-eye" style="font-size: 14px;"></i>
                            </a>
                            <a href="{{ route('wisata.edit', $wisata->id) }}" style="background: #FEF3C7; color: #D97706; width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#D97706'; this.style.color='white'" onmouseout="this.style.backgroundColor='#FEF3C7'; this.style.color='#D97706'">
                                <i class="bi bi-pencil" style="font-size: 14px;"></i>
                            </a>
                            <form action="{{ route('wisata.destroy', $wisata->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Hapus wisata {{ addslashes($wisata->nama_wisata) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #FEE2E2; color: #DC2626; width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#DC2626'; this.style.color='white'" onmouseout="this.style.backgroundColor='#FEE2E2'; this.style.color='#DC2626'">
                                    <i class="bi bi-trash" style="font-size: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 48px; margin-bottom: 12px;">🏝️</div>
                        <div style="font-size: 16px; font-weight: 600; color: #4B5563;">Tidak ada data wisata</div>
                        <div style="font-size: 13px; color: #9CA3AF; margin-top: 4px;">
                            <a href="{{ route('wisata.create') }}" style="color: #C8923A; text-decoration: none;">Tambah destinasi pertama →</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($wisatas->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid #F3F4F6; background: #F9FAFB;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="font-size: 12px; color: #6B7280;">
                Menampilkan <span style="font-weight: 600; color: #2C1810;">{{ $wisatas->firstItem() }}</span> -
                <span style="font-weight: 600; color: #2C1810;">{{ $wisatas->lastItem() }}</span>
                dari <span style="font-weight: 600; color: #2C1810;">{{ $wisatas->total() }}</span> data
            </div>
            <div>
                <ul style="display: flex; gap: 4px; list-style: none; margin: 0; padding: 0;">
                    @if($wisatas->onFirstPage())
                    <li style="opacity: 0.5;"><span style="display: inline-block; padding: 6px 10px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 12px;">«</span></li>
                    @else
                    <li><a href="{{ $wisatas->previousPageUrl() }}" style="display: inline-block; padding: 6px 10px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 12px; text-decoration: none; color: #8B1A1A;">«</a></li>
                    @endif

                    @foreach ($wisatas->getUrlRange(1, $wisatas->lastPage()) as $page => $url)
                    @if($page == $wisatas->currentPage())
                    <li><span style="display: inline-block; padding: 6px 12px; background: #8B1A1A; border: 1px solid #8B1A1A; border-radius: 8px; font-size: 12px; color: white; font-weight: 600;">{{ $page }}</span></li>
                    @else
                    <li><a href="{{ $url }}" style="display: inline-block; padding: 6px 12px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 12px; text-decoration: none; color: #6B7280;">{{ $page }}</a></li>
                    @endif
                    @endforeach

                    @if($wisatas->hasMorePages())
                    <li><a href="{{ $wisatas->nextPageUrl() }}" style="display: inline-block; padding: 6px 10px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 12px; text-decoration: none; color: #8B1A1A;">»</a></li>
                    @else
                    <li style="opacity: 0.5;"><span style="display: inline-block; padding: 6px 10px; background: white; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 12px;">»</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection