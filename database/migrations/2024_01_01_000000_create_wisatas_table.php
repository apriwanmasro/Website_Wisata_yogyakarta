<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->unique();
            $table->string('nama_wisata');
            $table->string('jam_operasional');
            $table->string('harga_tiket');
            $table->text('fasilitas');
            $table->text('lokasi');
            $table->text('deskripsi');
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('kategori')->default('Budaya');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};
