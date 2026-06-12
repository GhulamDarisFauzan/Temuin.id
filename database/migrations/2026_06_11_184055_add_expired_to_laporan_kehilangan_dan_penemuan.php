<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_kehilangan', function (Blueprint $table) {
            $table->string('status_laporan')->default('aktif')->after('status');
            $table->timestamp('expired_at')->nullable()->after('status_laporan');
        });

        Schema::table('laporan_penemuan', function (Blueprint $table) {
            $table->string('status_laporan')->default('aktif')->after('status');
            $table->timestamp('expired_at')->nullable()->after('status_laporan');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kehilangan', function (Blueprint $table) {
            $table->dropColumn(['status_laporan', 'expired_at']);
        });

        Schema::table('laporan_penemuan', function (Blueprint $table) {
            $table->dropColumn(['status_laporan', 'expired_at']);
        });
    }
};