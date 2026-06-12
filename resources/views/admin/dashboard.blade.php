<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="p-4 sm:p-6 max-w-7xl mx-auto">

    <!-- Navbar -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <div class="flex flex-wrap items-center justify-center md:justify-end gap-2">

            <a href="/admin/daftar" class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white shadow hover:shadow-md transition">
                Daftar Laporan
            </a>

            <a href="/admin/verifikasi" class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-red-500 text-white shadow hover:shadow-md transition">
                Verifikasi
            </a>

            <a href="/admin/about" class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white shadow hover:shadow-md transition">
                About
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-black text-white hover:bg-gray-800 transition">
                    Logout
                </button>
            </form>

        </div>
    </div>

    <!-- Title -->
    <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800 text-center md:text-left">
        Daftar Laporan Aktif
    </h2>

    <!-- 🔥 TAMBAHAN REVISI 30 HARI -->
    <div class="mb-5 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-xl text-sm">
        Dashboard ini hanya menampilkan laporan yang masih aktif dan belum melewati masa berlaku 30 hari.
    </div>
    <!-- 🔥 TAMBAHAN REVISI 30 HARI -->

    <!-- 🔥 TRAFFIC WEBSITE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">

        <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
            <p class="text-xs text-gray-500">Pengunjung Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">
                {{ $pengunjungHariIni }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
            <p class="text-xs text-gray-500">Pengunjung Bulan Ini</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">
                {{ $pengunjungBulanIni }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
            <p class="text-xs text-gray-500">Total Pengunjung</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">
                {{ $totalPengunjung }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
            <p class="text-xs text-gray-500">Laporan Hari Ini</p>
            <h3 class="text-2xl font-bold text-red-500 mt-1">
                {{ $laporanHariIni }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
            <p class="text-xs text-gray-500">Laporan Bulan Ini</p>
            <h3 class="text-2xl font-bold text-blue-500 mt-1">
                {{ $laporanBulanIni }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
            <p class="text-xs text-gray-500">Total Laporan</p>
            <h3 class="text-2xl font-bold text-green-500 mt-1">
                {{ $totalLaporan }}
            </h3>
        </div>

    </div>
    <!-- 🔥 TRAFFIC WEBSITE -->

    <!-- FORM -->
    <form method="GET" action="/admin/dashboard">

        <div class="flex flex-col md:flex-row gap-4 mb-6">

            <!-- SEARCH -->
            <div class="flex items-center border rounded-xl px-4 py-2 w-full md:w-2/3 bg-white shadow-sm focus-within:ring-2 focus-within:ring-red-400">

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari laporan..."
                    class="w-full outline-none text-sm sm:text-base">

                <button type="submit" class="ml-2 flex items-center justify-center">
                    <img src="{{ asset('images/search.png') }}"
                        alt="Search"
                        class="w-10 h-10 object-contain hover:scale-110 transition duration-200">
                </button>

            </div>

            <!-- FILTER -->
            <div class="space-y-2 w-full md:w-1/3">

                <select name="provinsi" id="provinsi"
                    class="w-full border rounded-lg px-3 py-2 bg-white shadow-sm focus:ring-2 focus:ring-red-400 text-sm">
                    <option value="Lampung">Lampung</option>
                </select>

                <select name="kabupaten" id="kabupaten"
                    class="w-full border rounded-lg px-3 py-2 bg-white shadow-sm focus:ring-2 focus:ring-red-400 text-sm">

                    <option value="">Kabupaten / Kota</option>

                    @foreach($kabupatens as $kabupaten)
                        <option value="{{ $kabupaten->nama }}"
                            {{ request('kabupaten') == $kabupaten->nama ? 'selected' : '' }}>
                            {{ $kabupaten->nama }}
                        </option>
                    @endforeach

                </select>

                <select name="kecamatan" id="kecamatan"
                    class="w-full border rounded-lg px-3 py-2 bg-white shadow-sm focus:ring-2 focus:ring-red-400 text-sm">
                    <option value="">Kecamatan</option>
                </select>

            </div>
        </div>

        <!-- KATEGORI -->
        <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-4">

            <button name="kategori" value="all"
                class="p-3 sm:p-4 rounded-xl text-center font-medium text-sm sm:text-base transition
                {{ request('kategori') == 'all' ? 'bg-gray-800 text-white shadow-lg' : 'bg-white shadow hover:shadow-md' }}">
                All
            </button>

            <button name="kategori" value="orang"
                class="p-3 sm:p-4 rounded-xl text-center font-medium text-sm sm:text-base transition
                {{ request('kategori') == 'orang' ? 'bg-gray-800 text-white shadow-lg' : 'bg-white shadow hover:shadow-md' }}">
                Orang
            </button>

            <button name="kategori" value="barang"
                class="p-3 sm:p-4 rounded-xl text-center font-medium text-sm sm:text-base transition
                {{ request('kategori') == 'barang' ? 'bg-gray-800 text-white shadow-lg' : 'bg-white shadow hover:shadow-md' }}">
                Barang
            </button>

        </div>

    </form>

    <!-- TOMBOL URUTKAN TERDEKAT -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-6">

        <button type="button"
            onclick="urutkanTerdekat()"
            class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full text-sm shadow transition">
            Urutkan Terdekat
        </button>

        @if(request('terdekat'))
            <a href="/admin/dashboard"
               class="w-full sm:w-auto text-center bg-white hover:bg-gray-100 text-gray-700 px-5 py-2 rounded-full text-sm shadow transition">
                Reset Urutan
            </a>
        @endif

    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">

        @foreach($laporan as $item)

        @php
            $konfirmasi = \App\Models\Konfirmasi::where('laporan_id', $item->id)
                            ->where('type', $item->jenis)
                            ->latest()
                            ->first();
        @endphp

        <div class="bg-white rounded-2xl shadow hover:shadow-xl transition duration-300 overflow-hidden">

            <div class="w-full h-52 bg-gray-100 overflow-hidden flex items-center justify-center">

                <img src="{{ $item->gambar ? asset($item->gambar) : 'https://via.placeholder.com/150' }}"
                    class="max-w-full max-h-full object-contain hover:scale-105 transition duration-300">

            </div>

            <div class="p-3">

                <h3 class="font-semibold text-gray-800 text-sm sm:text-base line-clamp-1">
                    {{ $item->nama_barang }}
                </h3>

                <p class="text-xs text-gray-500 mt-1">
                    {{ $item->kecamatan }}, {{ $item->kabupaten }}
                </p>

                <!-- 🔥 TAMBAHAN REVISI 30 HARI -->
                <div class="mt-2">
                    @if($item->expired_at)
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-[10px] font-semibold">
                            Aktif sampai {{ $item->expired_at->format('d M Y') }}
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-[10px] font-semibold">
                            Masa aktif belum tersedia
                        </span>
                    @endif
                </div>
                <!-- 🔥 TAMBAHAN REVISI 30 HARI -->

                @if(request('terdekat') && isset($item->jarak))
                    <p class="text-xs text-gray-500 mt-1">
                        Jarak: {{ number_format($item->jarak, 2) }} km dari lokasi Anda
                    </p>
                @endif

                <div class="mt-2">

                    @if($item->status == 'ditemukan')
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-[10px] font-semibold">
                            Sudah Ditemukan
                        </span>
                    @elseif($konfirmasi)
                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-[10px] font-semibold">
                            Menunggu Konfirmasi
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-[10px] font-semibold">
                            Belum Ditemukan
                        </span>
                    @endif

                </div>

                <div class="flex justify-between items-center mt-3">

                    @if($item->jenis == 'kehilangan')
                        <span class="text-red-500 text-xs font-medium">
                            Kehilangan
                        </span>
                    @else
                        <span class="text-blue-500 text-xs font-medium">
                            Penemuan
                        </span>
                    @endif

                    <a href="/admin/detail-acc/{{ $item->jenis }}/{{ $item->id }}"
                        class="
                        {{ $item->jenis == 'kehilangan'
                            ? 'bg-red-500 hover:bg-red-600'
                            : 'bg-blue-500 hover:bg-blue-600'
                        }}
                        text-white px-3 py-1 rounded-full text-xs transition">
                        Detail
                    </a>

                </div>

            </div>

        </div>
        @endforeach

        @if($laporan->isEmpty())
        <p class="col-span-4 text-center text-gray-500">
            Belum ada data laporan aktif
        </p>
        @endif

    </div>

</div>

<script>

    let dataWilayah = JSON.parse('@json($kabupatens)');

    let prov = document.getElementById('provinsi');
    let kab = document.getElementById('kabupaten');
    let kec = document.getElementById('kecamatan');

    prov.innerHTML = `<option value="Lampung">Lampung</option>`;
    prov.value = "Lampung";
    prov.setAttribute("disabled", true);

    function loadKecamatan(selectedKab, selectedKec = null)
    {
        kec.innerHTML = '<option value="">Kecamatan</option>';

        let kabupaten = dataWilayah.find(
            item => item.nama === selectedKab
        );

        if (kabupaten && kabupaten.kecamatans)
        {
            kabupaten.kecamatans.forEach(item => {

                kec.innerHTML += `
                    <option value="${item.nama}">
                        ${item.nama}
                    </option>
                `;

            });
        }

        if (selectedKec)
        {
            kec.value = selectedKec;
        }
    }

    kab.addEventListener('change', function() {
        loadKecamatan(this.value);
    });

    loadKecamatan(
        "{{ request('kabupaten') }}",
        "{{ request('kecamatan') }}"
    );

    document.querySelectorAll('select').forEach(el => {
        el.addEventListener('change', function () {
            this.form.submit();
        });
    });

    function urutkanTerdekat() {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung fitur lokasi.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const url = new URL(window.location.href);

                url.searchParams.set('terdekat', '1');
                url.searchParams.set('lat', position.coords.latitude);
                url.searchParams.set('lng', position.coords.longitude);

                window.location.href = url.toString();
            },
            function() {
                alert('Izin lokasi ditolak. Fitur laporan terdekat tidak dapat digunakan.');
            }
        );
    }

</script>

</body>
</html>