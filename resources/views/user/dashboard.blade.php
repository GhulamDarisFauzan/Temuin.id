<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="p-4 sm:p-6 max-w-7xl mx-auto">

<!-- Navbar -->
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

    <!-- LOGO -->
    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
        Temuin<span class="text-red-500">.id</span>
    </h1>

<!-- MENU -->
<div class="flex flex-wrap items-center justify-center md:justify-end gap-2">

    <!-- <a href="{{ auth()->check() ? '/user/about' : route('login.user') }}"
       class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white shadow hover:shadow-md transition">
       About
    </a> -->

    <a href="{{ url('/user/about') }}"
   class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white shadow hover:shadow-md transition">
   About
</a>

    <a href="{{ auth()->check() ? '/user/kehilangan' : route('login.user') }}"
       class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-red-500 text-white shadow hover:shadow-md transition">
       Kehilangan
    </a>

    <a href="{{ auth()->check() ? '/user/penemuan' : route('login.user') }}"
       class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-blue-500 text-white shadow hover:bg-blue-600 hover:shadow-md transition">
       Penemuan
    </a>

    <!-- 🔥 TAMBAHAN REVISI 30 HARI -->
    <!-- Menu ini dipakai untuk melihat semua laporan user, termasuk yang sudah kadaluarsa dan bisa dikirim ulang -->
    @auth
        <a href="/user/laporan-saya"
           class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-yellow-400 text-gray-900 shadow hover:bg-yellow-500 hover:shadow-md transition">
           Laporan Saya
        </a>
    @endauth
    <!-- 🔥 TAMBAHAN REVISI 30 HARI -->

    @auth
        <a href="/user/profile"
           class="flex items-center gap-2 ml-2 bg-white px-3 py-1.5 rounded-full shadow hover:shadow-md transition">

            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-xs font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            <span class="text-xs sm:text-sm font-semibold text-gray-700">
                {{ Auth::user()->name }}
            </span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="ml-1">
            @csrf
            <button class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-black text-white hover:bg-gray-800 transition">
                Logout
            </button>
        </form>
    @endauth

    @guest
        <a href="{{ route('login.user') }}"
           class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-green-500 text-white shadow hover:bg-green-600 transition">
           Masuk
        </a>
    @endguest

</div>

</div>

    <!-- Title -->
    <h2 class="text-2xl sm:text-3xl font-bold mb-3 text-gray-800 text-center md:text-left">
        Daftar Laporan Aktif
    </h2>

    <!-- 🔥 TAMBAHAN REVISI 30 HARI -->
    <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-xl text-sm">
        Dashboard ini hanya menampilkan laporan yang masih aktif dan belum melewati masa berlaku 30 hari.
        Laporan yang kadaluarsa dapat dilihat kembali di menu <b>Laporan Saya</b>.
    </div>
    <!-- 🔥 TAMBAHAN REVISI 30 HARI -->

    <!-- FORM -->
    <form method="GET" action="/user/dashboard">

        <div class="flex flex-col md:flex-row gap-4 mb-6">

            <!-- SEARCH -->
            <div class="flex items-center border rounded-xl px-4 py-2 w-full md:w-2/3 bg-white shadow-sm focus-within:ring-2 focus-within:ring-red-400">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari laporan..."
                    class="w-full outline-none text-sm sm:text-base">
                    <button type="submit" class="ml-2">
                        <img src="{{ asset('images/search.png') }}"
                            alt="Search"
                            class="w-10 h-10 opacity-70 hover:opacity-100 transition">
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
            <a href="/user/dashboard"
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

        <!-- STATUS -->
        <div class="flex justify-between items-center mb-2">

            @if($item->jenis == 'kehilangan')
                <span class="text-red-500 text-xs font-semibold">
                    Kehilangan
                </span>
            @else
                <span class="text-blue-500 text-xs font-semibold">
                    Penemuan
                </span>
            @endif

            @if($item->status == 'ditemukan')
                <span class="bg-green-100 text-green-600 text-[10px] px-2 py-1 rounded-full">
                    Ditemukan
                </span>
            @elseif($konfirmasi)
                <span class="bg-blue-100 text-blue-600 text-[10px] px-2 py-1 rounded-full">
                    Menunggu Konfirmasi
                </span>
            @else
                <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full">
                    Belum
                </span>
            @endif

        </div>

        <!-- 🔥 TAMBAHAN REVISI 30 HARI -->
        <!-- Menampilkan batas masa aktif laporan di dashboard -->
        @if($item->expired_at)
            <div class="mb-2">
                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-1 rounded-full font-semibold">
                    Aktif sampai {{ $item->expired_at->format('d M Y') }}
                </span>
            </div>
        @else
            <div class="mb-2">
                <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded-full font-semibold">
                    Masa aktif belum tersedia
                </span>
            </div>
        @endif
        <!-- 🔥 TAMBAHAN REVISI 30 HARI -->

        <!-- NAMA -->
        <h3 class="font-semibold text-gray-800 text-sm sm:text-base line-clamp-1">
            {{ $item->nama_barang }}
        </h3>

        <!-- LOKASI -->
        <p class="text-xs text-gray-500 mt-1">
            {{ $item->kecamatan }}, {{ $item->kabupaten }}
        </p>

        <!-- JARAK -->
        @if(request('terdekat') && isset($item->jarak))
            <p class="text-xs text-gray-500 mt-1">
                Jarak: {{ number_format($item->jarak, 2) }} km dari lokasi Anda
            </p>
        @endif

        <!-- PRIORITAS -->
        @if($item->kategori == 'orang')
            <div class="mt-2">
                <span class="bg-red-100 text-red-600 text-[10px] px-2 py-1 rounded-full">
                    Prioritas Tinggi
                </span>
            </div>
        @endif

        <!-- BUTTON -->
        <div class="flex justify-end items-center mt-3">

            <!-- ✅ DIPERBAIKI: Jika belum login, tombol Detail masuk ke login user, bukan login admin -->
            <!-- <a href="{{ auth()->check() ? '/user/detail-acc/'.$item->jenis.'/'.$item->id : route('login.user') }}" -->
            <!-- <a href="/user/detail-acc/{{ $item->jenis }}/{{ $item->id }}" -->
            <!-- ✅ MENGGUNAKAN NAMED ROUTE -->
<a href="{{ route('user.detail', ['type' => $item->jenis, 'id' => $item->id]) }}"
            
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

<!-- KOSONG -->
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

// 🔥 FITUR URUTKAN TERDEKAT
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