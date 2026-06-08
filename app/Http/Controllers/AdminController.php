<?php

namespace App\Http\Controllers;


use App\Models\Message;

use Illuminate\Http\Request;
use App\Models\LaporanPending;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;
use App\Models\Konfirmasi;
use App\Models\Kabupaten; // ✅ TAMBAHAN: untuk mengambil data kabupaten dan kecamatan dari database

class AdminController extends Controller
{
    // 🔍 DASHBOARD ADMIN
    public function dashboard(Request $request)
    {
        $search     = $request->search;
        $kategori   = $request->kategori;
        $provinsi   = $request->provinsi;
        $kabupaten  = $request->kabupaten;
        $kecamatan  = $request->kecamatan;

        $qKehilangan = LaporanKehilangan::query();
        $qPenemuan   = LaporanPenemuan::query();

        // 🔍 SEARCH
        if ($search) {
            $qKehilangan->where('nama_barang', 'like', "%$search%");
            $qPenemuan->where('nama_barang', 'like', "%$search%");
        }

        // 🔥 KATEGORI
        if ($kategori && $kategori != 'all') {
            $qKehilangan->where('kategori', $kategori);
            $qPenemuan->where('kategori', $kategori);
        }

        // 🔥 WILAYAH
        if ($provinsi) {
            $qKehilangan->where('provinsi', $provinsi);
            $qPenemuan->where('provinsi', $provinsi);
        }

        if ($kabupaten) {
            $qKehilangan->where('kabupaten', $kabupaten);
            $qPenemuan->where('kabupaten', $kabupaten);
        }

        if ($kecamatan) {
            $qKehilangan->where('kecamatan', $kecamatan);
            $qPenemuan->where('kecamatan', $kecamatan);
        }

        // ================= KEHILANGAN =================
        $kehilangan = $qKehilangan
            ->get()
            ->map(function ($item) {
                $item->jenis = 'kehilangan';
                return $item;
            });

        // ================= PENEMUAN =================
        $penemuan = $qPenemuan
            ->get()
            ->map(function ($item) {
                $item->jenis = 'penemuan';
                return $item;
            });

        // ✅ TAMBAHAN: ambil data kabupaten beserta kecamatannya dari database untuk filter wilayah
        $kabupatens = Kabupaten::with('kecamatans')
            ->orderBy('nama')
            ->get();

        // =====================================================
        // 🔥 BAGIAN PRIORITAS / RANKING LAPORAN ADMIN
        // =====================================================
        // Urutan:
        // 1. Belum ditemukan paling atas
        // 2. Orang lebih penting daripada barang
        // 3. Kehilangan lebih penting daripada penemuan
        // 4. Terbaru paling atas dalam kelompok yang sama
        // 5. Sudah ditemukan turun ke bawah
        // =====================================================
        $laporan = $kehilangan
            ->merge($penemuan)
            ->sortBy(function ($item) {

                // 🔥 PRIORITAS STATUS
                $statusPriority =
                    strtolower($item->status ?? 'belum') == 'ditemukan'
                    ? 5 : 1;

                // 🔥 PRIORITAS KATEGORI
                $kategoriPriority =
                    strtolower($item->kategori ?? 'barang') == 'orang'
                    ? 1 : 2;

                // 🔥 PRIORITAS JENIS
                $jenisPriority =
                    strtolower($item->jenis ?? 'penemuan') == 'kehilangan'
                    ? 1 : 2;

                // 🔥 PRIORITAS WAKTU
                $waktu = strtotime($item->created_at);

                return
                    ($statusPriority * 1000000) +
                    ($kategoriPriority * 100000) +
                    ($jenisPriority * 10000) -
                    $waktu;
            })
            ->values();

        return view('admin.dashboard', compact('laporan', 'kabupatens')); // ✅ TAMBAHAN: kirim kabupatens ke view
    }

    // 🔍 DATA PENDING
    public function verifikasi()
    {
        $laporan = LaporanPending::latest()->get();

        return view('admin.verifikasi', compact('laporan'));
    }

    // ✅ TERIMA
    public function terima($id)
    {
        $data = LaporanPending::findOrFail($id);

        if ($data->type == 'kehilangan') {

            LaporanKehilangan::create([
                'kategori'    => $data->kategori,

                'nama_barang' => $data->nama_barang,
                'deskripsi'   => $data->deskripsi,
                'kronologi'   => $data->kronologi,
                'provinsi'    => $data->provinsi,
                'kabupaten'   => $data->kabupaten,
                'kecamatan'   => $data->kecamatan,
                'no_telp'     => $data->no_telp,
                'gambar'      => $data->gambar,

                'alamat'      => $data->alamat,
                'latitude'    => $data->latitude,
                'longitude'   => $data->longitude,

                'user_id'     => $data->user_id,

                // 🔥 STATUS AWAL
                'status'      => 'belum',
            ]);

        } else {

            LaporanPenemuan::create([
                'kategori'    => $data->kategori,

                'nama_barang' => $data->nama_barang,
                'deskripsi'   => $data->deskripsi,
                'kronologi'   => $data->kronologi,
                'provinsi'    => $data->provinsi,
                'kabupaten'   => $data->kabupaten,
                'kecamatan'   => $data->kecamatan,
                'no_telp'     => $data->no_telp,
                'gambar'      => $data->gambar,

                'alamat'      => $data->alamat,
                'latitude'    => $data->latitude,
                'longitude'   => $data->longitude,

                'user_id'     => $data->user_id,

                // 🔥 STATUS AWAL
                'status'      => 'belum',
            ]);
        }

        // hapus dari pending
        $data->delete();

        return redirect('/admin/verifikasi')->with('success', 'Laporan diterima');
    }

    // ❌ TOLAK
    public function tolak($id)
    {
        $data = LaporanPending::findOrFail($id);
        $data->delete();

        return redirect('/admin/verifikasi')->with('success', 'Laporan ditolak');
    }

    // 🔴 DETAIL PENDING
    public function detail($id)
    {
        $data = LaporanPending::findOrFail($id);

        return view('admin.detail', compact('data'));
    }

    // 📋 DAFTAR
    public function daftar()
    {
        $kehilangan = LaporanKehilangan::latest()->get();
        $penemuan = LaporanPenemuan::latest()->get();

        $laporan = $kehilangan->map(function ($item) {
            $item->type = 'Kehilangan';
            return $item;
        })->merge(
            $penemuan->map(function ($item) {
                $item->type = 'Penemuan';
                return $item;
            })
        );

        return view('admin.daftar', compact('laporan'));
    }

    // 🟢 DETAIL ACC
    public function detailAcc($type, $id)
    {
        if ($type == 'kehilangan') {
            $data = LaporanKehilangan::findOrFail($id);
        } else {
            $data = LaporanPenemuan::findOrFail($id);
        }

        $data->type = $type;

        return view('admin.detail-acc', compact('data'));
    }

    // 🔄 UPDATE STATUS
    public function updateStatus($type, $id)
    {
        if ($type == 'kehilangan') {
            $data = LaporanKehilangan::findOrFail($id);
        } else {
            $data = LaporanPenemuan::findOrFail($id);
        }

        $data->status = 'ditemukan';
        $data->save();

        return back()->with('success', 'Status diubah jadi ditemukan');
    }

    // 🔥 KONFIRMASI
    public function konfirmasi()
    {
        $data = Konfirmasi::latest()->get();

        return view('admin.konfirmasi', compact('data'));
    }

    // ✅ ACC KONFIRMASI
    public function accKonfirmasi($id)
    {
        $k = Konfirmasi::findOrFail($id);

        if ($k->type == 'kehilangan') {
            $laporan = LaporanKehilangan::find($k->laporan_id);
        } else {
            $laporan = LaporanPenemuan::find($k->laporan_id);
        }

        $laporan->status = 'ditemukan';
        $laporan->save();

        $k->status = 'diterima';
        $k->save();

        return back()->with('success', 'Konfirmasi diterima');
    }

    // ❌ TOLAK KONFIRMASI
    public function tolakKonfirmasi($id)
    {
        $k = Konfirmasi::findOrFail($id);

        $k->status = 'ditolak';
        $k->save();

        return back()->with('success', 'Konfirmasi ditolak');
    }



        // //hapus
        // public function hapus($type, $id)
        // {
        //     if ($type == 'kehilangan') {
        //         $laporan = LaporanKehilangan::findOrFail($id);
        //     } elseif ($type == 'penemuan') {
        //         $laporan = LaporanPenemuan::findOrFail($id);
        //     } else {
        //         abort(404);
        //     }
        
        //     $laporan->delete();
        
        //     return redirect('/admin/daftar')->with('success', 'Laporan berhasil dihapus.');
        // }


//hapus
public function hapus($type, $id)
{
    // hapus konfirmasi yang berkaitan dengan laporan
    \App\Models\Konfirmasi::where('laporan_id', $id)
        ->where('type', $type)
        ->delete();

    // hapus pesan/chat jika conversation_id sama dengan id laporan
    \App\Models\Message::where('conversation_id', $id)->delete();

    // hapus laporan kehilangan / penemuan
    if ($type == 'kehilangan') {
        \App\Models\LaporanKehilangan::findOrFail($id)->delete();
    } elseif ($type == 'penemuan') {
        \App\Models\LaporanPenemuan::findOrFail($id)->delete();
    } else {
        abort(404);
    }

    return redirect('/admin/daftar')
        ->with('success', 'Laporan, konfirmasi, dan chat berhasil dihapus.');
}

}