<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;

class ChatController extends Controller
{
    // halaman chat
    public function index($id)
    {
        // cari laporan (kehilangan / penemuan)
        $laporan = LaporanKehilangan::find($id) ?? LaporanPenemuan::find($id);
        if (!$laporan) abort(404);

        // ambil / buat conversation
        $conversation = Conversation::firstOrCreate([
            'laporan_id' => $laporan->id
        ]);

        return view('chat.index', compact('conversation', 'laporan'));
    }

    // kirim pesan (AJAX)
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required',
            'message' => 'required',

            // 🔥 REVISI REPLY CHAT
            'reply_to' => 'nullable|exists:messages,id',
        ]);

        $msg = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => auth()->id(),
            'message' => $request->message,

            // 🔥 REVISI REPLY CHAT
            'reply_to' => $request->reply_to,
        ]);

        // 🔥 REVISI REPLY CHAT
        // Ambil ulang pesan beserta relasi user dan pesan yang dibalas
        $msg = Message::with(['user', 'reply.user'])->find($msg->id);

        return response()->json($msg);
    }

    // ambil pesan (AJAX polling)
    public function fetch($id)
    {
        $messages = Message::with(['user', 'reply.user']) // 🔥 REVISI REPLY CHAT
            ->where('conversation_id', $id)
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}