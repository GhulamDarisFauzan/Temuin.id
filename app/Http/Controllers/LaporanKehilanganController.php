<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanPending;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Auth;

class LaporanKehilanganController extends Controller
{
    // FORM
    public function create()
    {
        $kabupatens = Kabupaten::orderBy('nama')->get();

        return view('user.kehilangan', compact('kabupatens'));
    }

    // AJAX KECAMATAN
    public function getKecamatan($kabupaten_id)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $kabupaten_id)
            ->orderBy('nama')
            ->get();

        return response()->json($kecamatans);
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:barang,orang',
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'kronologi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'no_telp' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'required',
            'longitude' => 'required',
            'alamat' => 'nullable',
        ]);

        $data = $request->only([
            'nama_barang',
            'deskripsi',
            'kronologi',
            'provinsi',
            'kabupaten',
            'kecamatan',
            'no_telp',
            'kategori',
            'alamat',
        ]);

        $data['gambar'] = null;

        if ($request->hasFile('gambar')) {

            $file = $request->file('gambar');
            $namaFile = time() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }

            $file->move(public_path('uploads'), $namaFile);

            $data['gambar'] = 'uploads/' . $namaFile;
        }

        $data['latitude'] = $request->latitude;
        $data['longitude'] = $request->longitude;

        $data['user_id'] = Auth::id();

        $data['type'] = 'kehilangan';

        $data['provinsi'] = 'Lampung';

        LaporanPending::create($data);

        return redirect('/user/dashboard')
            ->with('success', 'Menunggu persetujuan admin');
    }
}