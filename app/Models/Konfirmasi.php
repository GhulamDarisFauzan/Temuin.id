<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konfirmasi extends Model
{
    protected $table = 'konfirmasi';

    protected $fillable = [
        'laporan_id',
        'type',
        'kronologi',
        'bukti',
        'status'
    ];

    // 🔥 RELASI KE KEHILANGAN
    public function kehilangan()
    {
        return $this->belongsTo(LaporanKehilangan::class, 'laporan_id');
    }

    // 🔥 RELASI KE PENEMUAN
    public function penemuan()
    {
        return $this->belongsTo(LaporanPenemuan::class, 'laporan_id');
    }
}