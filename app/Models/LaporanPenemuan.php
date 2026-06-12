<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenemuan extends Model
{
    protected $table = 'laporan_penemuan';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'kronologi',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'no_telp',
        'gambar',
        'latitude',
        'longitude',
        'user_id',
        'kategori',
        'alamat',
        'status', // 🔥 WAJIB

        // 🔥 TAMBAHAN REVISI 30 HARI
        'status_laporan',
        'expired_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',

        // 🔥 TAMBAHAN REVISI 30 HARI
        'expired_at' => 'datetime',
    ];

    // 🔥 OPTIONAL
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}