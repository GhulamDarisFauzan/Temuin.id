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
        Schema::create('laporan_kehilangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->text('kronologi');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('no_telp');
            $table->string('gambar')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // boleh guest
            $table->timestamps();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kehilangan');
    }
};


