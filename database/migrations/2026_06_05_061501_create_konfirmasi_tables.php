<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konfirmasi', function (Blueprint $table) {
            $table->id();

            // 🔗 relasi ke laporan
            $table->unsignedBigInteger('laporan_id');

            // 🔥 jenis laporan
            $table->string('type'); // kehilangan / penemuan

            // 📝 kronologi user
            $table->text('kronologi');

            // 🖼️ bukti gambar
            $table->string('bukti')->nullable();

            // 🔄 status pengajuan
            $table->enum('status', ['pending', 'diterima', 'ditolak'])
                  ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfirmasi');
    }
};