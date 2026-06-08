<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pending', function (Blueprint $table) {
            $table->id();

            // 🔥 penentu jenis (WAJIB)
            $table->string('type'); // kehilangan / penemuan

            // data utama
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->text('kronologi');

            // lokasi
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');

            // kontak
            $table->string('no_telp');

            // tambahan
            $table->string('gambar')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // user
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pending');
    }
};