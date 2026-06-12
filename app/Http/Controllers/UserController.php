<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;
use App\Models\Kabupaten;
use App\Models\VisitorLog; // 🔥 TRAFFIC WEBSITE


class UserController extends Controller
{
    // 🔥 DASHBOARD
    public function dashboard(Request $request)
    {
        // =====================================================
        // 🔥 TRAFFIC WEBSITE
        // =====================================================
        // 1 IP address hanya dihitung 1 kali dalam 1 hari
        VisitorLog::firstOrCreate([
            'ip_address' => $request->ip(),
            'visit_date' => now()->toDateString(),
        ]);
        // =====================================================
        // 🔥 TRAFFIC WEBSITE
        // =====================================================

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
                $item->jenis = 'penemuan';
                return $item;
            });

        // ✅ AMBIL DATA KABUPATEN UNTUK FILTER WILAYAH
        $kabupatens = Kabupaten::with('kecamatans')
            ->orderBy('nama')
            ->get();

        // =====================================================
        // 🔥 GABUNG LAPORAN KEHILANGAN DAN PENEMUAN
        // =====================================================
        $laporan = $kehilangan->merge($penemuan);

        // =====================================================
        // 🔥 FITUR URUTKAN TERDEKAT
        // =====================================================
        // Jika user klik tombol Urutkan Terdekat,
        // sistem mengambil lat/lng dari browser,
        // lalu menghitung jarak user ke setiap laporan.
        // =====================================================
        $laporan = $this->hitungJarakLaporan($laporan, $request);

        // =====================================================
        // 🔥 BAGIAN PRIORITAS / RANKING LAPORAN
        // =====================================================
        // Jika sedang mode terdekat, laporan diurutkan berdasarkan jarak.
        // Jika tidak, laporan diurutkan berdasarkan prioritas biasa.
        // =====================================================
        if ($request->terdekat && $request->lat && $request->lng) {
            $laporan = $laporan
                ->sortBy(function ($item) {
                    return $item->jarak ?? 999999;
                })
                ->values();
        } else {
            $laporan = $laporan
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
                    $waktu = strtotime($item->created_at);

                    // 🔥 GABUNG SEMUA PRIORITAS
                    return
                        ($statusPriority * 1000000) +
                        ($kategoriPriority * 100000) +
                        ($jenisPriority * 10000) -
                        $waktu;
                })
                ->values();
        }

        return view('user.dashboard', compact('laporan', 'kabupatens'));
    }

    // =====================================================
    // 🔥 FUNCTION HITUNG JARAK LAPORAN
    // =====================================================
    private function hitungJarakLaporan($laporan, Request $request)
    {
        return $laporan->map(function ($item) use ($request) {
            $item->jarak = null;

            if (
                $request->terdekat &&
                $request->lat &&
                $request->lng &&
                $item->latitude &&
                $item->longitude
            ) {
                $lat1 = deg2rad($request->lat);
                $lng1 = deg2rad($request->lng);
                $lat2 = deg2rad($item->latitude);
                $lng2 = deg2rad($item->longitude);

                // Radius bumi dalam kilometer
                $earthRadius = 6371;

                $dLat = $lat2 - $lat1;
                $dLng = $lng2 - $lng1;

                $a = sin($dLat / 2) * sin($dLat / 2) +
                    cos($lat1) * cos($lat2) *
                    sin($dLng / 2) * sin($dLng / 2);

                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                $item->jarak = $earthRadius * $c;
            }

            return $item;
        });
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

        $konfirmasi = \App\Models\Konfirmasi::where('laporan_id', $id)
            ->where('type', $type)
            ->latest()
            ->first();

        return view('user.detail-acc', compact('data', 'konfirmasi'));
    }

    // 🔥 STATUS USER
    public function ubahStatus($type, $id)
    {
        if ($type == 'kehilangan') {
            $laporan = \App\Models\LaporanKehilangan::findOrFail($id);
        } else {
            $laporan = \App\Models\LaporanPenemuan::findOrFail($id);
        }

        if ($laporan->user_id != auth()->id()) {
            abort(403);
        }

        $laporan->status = 'ditemukan';
        $laporan->save();

        return back()->with('success', 'Laporan berhasil ditandai sudah ditemukan');
    }


    //30 hari
    public function laporanSaya()
{
    $kehilangan = LaporanKehilangan::where('user_id', auth()->id())
        ->latest()
        ->get();

    $penemuan = LaporanPenemuan::where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('user.laporan-saya', compact('kehilangan', 'penemuan'));
}

public function kirimUlang($type, $id)
{
    if ($type === 'kehilangan') {
        $laporan = LaporanKehilangan::where('user_id', auth()->id())
            ->findOrFail($id);
    } else {
        $laporan = LaporanPenemuan::where('user_id', auth()->id())
            ->findOrFail($id);
    }

    if ($laporan->expired_at && $laporan->expired_at->isFuture()) {
        return back()->with('error', 'Laporan masih aktif dan belum bisa dikirim ulang.');
    }

    $laporan->update([
        'status_laporan' => 'aktif',
        'expired_at' => now()->addDays(30),
    ]);

    return back()->with('success', 'Laporan berhasil dikirim ulang selama 30 hari.');
}
}