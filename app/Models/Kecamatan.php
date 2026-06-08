<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $fillable = ['kabupaten_id', 'nama'];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }
}