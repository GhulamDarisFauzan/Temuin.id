<?php

namespace App\Http\Controllers;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;

use Illuminate\Http\Request;

class KonfirmasiController extends Controller
{
    //

    public function store(Request $request)
{
    $request->validate([
        'laporan_id' => 'required',
        'type' => 'required',
        'kronologi' => 'required',
        'bukti' => 'nullable|image'
    ]);

    $data = $request->only('laporan_id','type','kronologi');

    if ($request->hasFile('bukti')) {
        $file = $request->file('bukti');
        $nama = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $nama);
        $data['bukti'] = 'uploads/'.$nama;
    }

    \App\Models\Konfirmasi::create($data);

    return back()->with('success', 'Konfirmasi berhasil dikirim');
}


public function form($type, $id)
{
    if ($type == 'kehilangan') {
        $data = LaporanKehilangan::findOrFail($id);
    } else {
        $data = LaporanPenemuan::findOrFail($id);
    }

    $data->type = $type;

    return view('user.konfirmasi', compact('data'));
}


public function show($id)
{
    $data = \App\Models\Konfirmasi::with('laporan')
                ->where('laporan_id', $id)
                ->first();

    if (!$data) {
        return back()->with('error', 'Data konfirmasi tidak ditemukan');
    }

    return view('admin.konfirmasi', compact('data'));
}


}




