<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',

        // 🔥 REVISI REPLY CHAT
        'reply_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // 🔥 REVISI REPLY CHAT
    // Relasi ke pesan yang sedang dibalas
    public function reply()
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }
}