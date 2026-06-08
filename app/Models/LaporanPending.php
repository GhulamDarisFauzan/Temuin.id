<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPending extends Model
{
    protected $table = 'laporan_pending';

    protected $fillable = [
        'type',
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