<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKehilangan extends Model
{
    protected $table = 'laporan_kehilangan';

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
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}