<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisatas';

    protected $fillable = [
        'no',
        'nama_wisata',
        'jam_operasional',
        'harga_tiket',
        'fasilitas',
        'lokasi',
        'deskripsi',
        'rating',
        'latitude',
        'longitude',
        'kategori',
        'foto',
    ];

    protected $casts = [
        'rating' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function getRatingStarsAttribute(): string
    {
        $full = floor($this->rating);
        $half = ($this->rating - $full) >= 0.5 ? 1 : 0;
        $empty = 5 - $full - $half;
        return str_repeat('★', $full) . str_repeat('½', $half) . str_repeat('☆', $empty);
    }

    public function getKategoriColorAttribute(): string
    {
        return match ($this->kategori) {
            'Candi & Sejarah' => '#e67e22',
            'Budaya & Sejarah' => '#8e44ad',
            'Pantai & Alam' => '#2980b9',
            'Alam & Petualangan' => '#27ae60',
            'Belanja & Kuliner' => '#e74c3c',
            'Hiburan & Keluarga' => '#f39c12',
            default => '#7f8c8d',
        };
    }
}
