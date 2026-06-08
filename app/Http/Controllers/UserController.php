<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;
use App\Models\Kabupaten; // ✅ TAMBAHAN: untuk mengambil data kabupaten dan kecamatan dari database

class UserController extends Controller
{
    // 🔥 DASHBOARD
    public function dashboard(Request $request)
    {
        // 🔍 AMBIL INPUT FILTER
        $search     = $request->search;
        $kategori   = $request->kategori;
        $provinsi   = $request->provinsi;
        $kabupaten  = $request->kabupaten;
        $kecamatan  = $request->kecamatan;

        // ================= KEHILANGAN =================
        $qKehilangan = LaporanKehilangan::query();

        if ($search) {
            $qKehilangan->where('nama_barang', 'like', "%$search%");
        }

        if ($kategori && $kategori != 'all') {
            $qKehilangan->where('kategori', $kategori);
        }

        if ($provinsi) {
            $qKehilangan->where('provinsi', $provinsi);
        }

        if ($kabupaten) {
            $qKehilangan->where('kabupaten', $kabupaten);
        }

        if ($kecamatan) {
            $qKehilangan->where('kecamatan', $kecamatan);
        }

        $kehilangan = $qKehilangan
            ->get()
            ->map(function ($item) {
                // 🔥 PENANDA JENIS DATA UNTUK LINK DETAIL & LABEL CARD
                $item->jenis = 'kehilangan';
                return $item;
            });

        // ================= PENEMUAN =================
        $qPenemuan = LaporanPenemuan::query();

        if ($search) {
            $qPenemuan->where('nama_barang', 'like', "%$search%");
        }

        if ($kategori && $kategori != 'all') {
            $qPenemuan->where('kategori', $kategori);
        }

        if ($provinsi) {
            $qPenemuan->where('provinsi', $provinsi);
        }

        if ($kabupaten) {
            $qPenemuan->where('kabupaten', $kabupaten);
        }

        if ($kecamatan) {
            $qPenemuan->where('kecamatan', $kecamatan);
        }

        $penemuan = $qPenemuan
            ->get()
            ->map(function ($item) {
                // 🔥 PENANDA JENIS DATA UNTUK LINK DETAIL & LABEL CARD
                $item->jenis = 'penemuan';
                return $item;
            });

        // ✅ TAMBAHAN: ambil data kabupaten beserta kecamatannya dari database untuk filter wilayah
        $kabupatens = Kabupaten::with('kecamatans')
            ->orderBy('nama')
            ->get();

        // =====================================================
        // 🔥 BAGIAN PRIORITAS / RANKING LAPORAN
        // =====================================================
        // Urutan yang dipakai:
        // 1. Laporan yang BELUM ditemukan tampil paling atas.
        // 2. Kategori ORANG lebih penting daripada BARANG.
        // 3. Data TERBARU tampil lebih atas dalam kelompok yang sama.
        // 4. Laporan yang SUDAH ditemukan turun ke bawah.
        // =====================================================
        $laporan = $kehilangan
        ->merge($penemuan)
        ->sortBy(function ($item) {
    
            // 🔥 STATUS
            // belum ditemukan lebih penting
            $statusPriority =
                strtolower($item->status ?? 'belum') == 'ditemukan'
                ? 5 : 1;
    
            // 🔥 KATEGORI
            // orang lebih penting dari barang
            $kategoriPriority =
                strtolower($item->kategori ?? 'barang') == 'orang'
                ? 1 : 2;
    
            // 🔥 JENIS
            // kehilangan lebih penting dari penemuan
            $jenisPriority =
                strtolower($item->jenis ?? 'penemuan') == 'kehilangan'
                ? 1 : 2;
    
            // 🔥 TERBARU
            $waktu =
                strtotime($item->created_at);
    
            // 🔥 GABUNG SEMUA PRIORITAS
            return
                ($statusPriority * 1000000) +
                ($kategoriPriority * 100000) +
                ($jenisPriority * 10000) -
                $waktu;
    
        })
        ->values();

        return view('user.dashboard', compact('laporan', 'kabupatens')); // ✅ TAMBAHAN: kirim kabupatens ke view
    }

    // 🔥 DETAIL
    public function detailAcc($type, $id)
    {
        if ($type == 'kehilangan') {
            $data = LaporanKehilangan::findOrFail($id);
        } else {
            $data = LaporanPenemuan::findOrFail($id);
        }

        $data->type = $type;

        return view('user.detail-acc', compact('data'));
    }
}