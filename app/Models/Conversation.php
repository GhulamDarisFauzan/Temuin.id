<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    class Conversation extends Model
    {
        protected $fillable = ['laporan_id'];

        public function messages()
        {
            return $this->hasMany(Message::class);
        }
    }


