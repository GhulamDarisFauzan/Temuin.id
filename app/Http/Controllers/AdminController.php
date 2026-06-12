<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\LaporanPending;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;
use App\Models\Konfirmasi;
use App\Models\Kabupaten;
use App\Models\VisitorLog; // 🔥 TRAFFIC WEBSITE

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

        // 🔥 TAMBAHAN REVISI 30 HARI
        // Dashboard admin hanya menampilkan laporan yang masih aktif dan belum kadaluarsa
        $qKehilangan = LaporanKehilangan::query()
            ->where('status_laporan', 'aktif')
            ->where('expired_at', '>=', now());

        // 🔥 TAMBAHAN REVISI 30 HARI
        // Dashboard admin hanya menampilkan laporan yang masih aktif dan belum kadaluarsa
        $qPenemuan = LaporanPenemuan::query()
            ->where('status_laporan', 'aktif')
            ->where('expired_at', '>=', now());

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
        $laporan = $this->hitungJarakLaporan($laporan, $request);

        // =====================================================
        // 🔥 BAGIAN PRIORITAS / RANKING LAPORAN ADMIN
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
        }

        // =====================================================
        // 🔥 TRAFFIC WEBSITE
        // =====================================================
        $pengunjungHariIni = VisitorLog::whereDate('visit_date', today())->count();

        $pengunjungBulanIni = VisitorLog::whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->count();

        $totalPengunjung = VisitorLog::count();

        $laporanHariIni = LaporanKehilangan::whereDate('created_at', today())->count()
            + LaporanPenemuan::whereDate('created_at', today())->count();

        $laporanBulanIni = LaporanKehilangan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count()
            + LaporanPenemuan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalLaporan = LaporanKehilangan::count() + LaporanPenemuan::count();

        return view('admin.dashboard', compact(
            'laporan',
            'kabupatens',

            // 🔥 TRAFFIC WEBSITE
            'pengunjungHariIni',
            'pengunjungBulanIni',
            'totalPengunjung',
            'laporanHariIni',
            'laporanBulanIni',
            'totalLaporan'
        ));
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
                'status'      => 'belum',

                // 🔥 TAMBAHAN REVISI 30 HARI
                // Masa aktif laporan dimulai setelah admin ACC
                'status_laporan' => 'aktif',
                'expired_at'     => now()->addDays(30),
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
                'status'      => 'belum',

                // 🔥 TAMBAHAN REVISI 30 HARI
                // Masa aktif laporan dimulai setelah admin ACC
                'status_laporan' => 'aktif',
                'expired_at'     => now()->addDays(30),
            ]);
        }

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

        // 🔥 TAMBAHAN REVISI 30 HARI
        // Kalau laporan sudah ditemukan, tidak dianggap laporan aktif lagi
        $data->status_laporan = 'selesai';

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

        if ($laporan) {
            $laporan->status = 'ditemukan';

            // 🔥 TAMBAHAN REVISI 30 HARI
            // Kalau laporan sudah ditemukan, status aktifnya selesai
            $laporan->status_laporan = 'selesai';

            $laporan->save();
        }

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

    // 🗑️ HAPUS
    public function hapus($type, $id)
    {
        Konfirmasi::where('laporan_id', $id)
            ->where('type', $type)
            ->delete();

        Message::where('conversation_id', $id)->delete();

        if ($type == 'kehilangan') {
            LaporanKehilangan::findOrFail($id)->delete();
        } elseif ($type == 'penemuan') {
            LaporanPenemuan::findOrFail($id)->delete();
        } else {
            abort(404);
        }

        return redirect('/admin/daftar')
            ->with('success', 'Laporan, konfirmasi, dan chat berhasil dihapus.');
    }

    // 🔄 UBAH STATUS
    public function ubahStatus($type, $id)
    {
        if ($type === 'kehilangan') {
            $laporan = LaporanKehilangan::findOrFail($id);
        } else {
            $laporan = LaporanPenemuan::findOrFail($id);
        }

        $laporan->status = 'ditemukan';

        // 🔥 TAMBAHAN REVISI 30 HARI
        // Kalau laporan ditandai ditemukan, laporan dianggap selesai
        $laporan->status_laporan = 'selesai';

        $laporan->save();

        return back()->with('success', 'Status laporan berhasil ditandai ditemukan.');
    }
}