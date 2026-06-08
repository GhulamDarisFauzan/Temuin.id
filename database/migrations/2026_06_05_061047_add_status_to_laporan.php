<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('laporan_kehilangan', function (Blueprint $table) {
        $table->string('status')->default('belum_ditemukan');
    });

    Schema::table('laporan_penemuan', function (Blueprint $table) {
        $table->string('status')->default('belum_ditemukan');
    });
}

public function down(): void
{
    Schema::table('laporan_kehilangan', function (Blueprint $table) {
        $table->dropColumn('status');
    });

    Schema::table('laporan_penemuan', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
