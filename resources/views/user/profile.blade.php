<!DOCTYPE html>
<html>
<head>
    <title>Profile User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<div class="max-w-6xl mx-auto p-4 md:p-8">

<!-- HEADER -->
<div class="bg-white px-4 py-3 rounded-xl shadow-sm flex justify-between items-center mb-6">

    <h1 class="font-extrabold text-lg md:text-xl tracking-wide">
        Temuin<span class="text-red-500">.id</span>
    </h1>

    <a href="/user/dashboard"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm shadow-sm transition active:scale-95">
       ← Back
    </a>

</div>

<!-- PROFILE CARD -->
<div class="bg-white p-5 md:p-6 rounded-2xl shadow-lg mb-6 flex items-center gap-4">

    <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-300 rounded-full flex items-center justify-center text-lg font-bold text-gray-700">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>

    <div>
        <h2 class="text-lg md:text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
        <p class="text-gray-500 text-sm md:text-base">{{ $user->email }}</p>
    </div>

</div>

<!-- STATISTIK -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <div class="bg-white p-4 rounded-2xl shadow text-center hover:shadow-lg transition">
        <p class="text-gray-500 text-sm">Total Kehilangan</p>
        <h3 class="text-xl md:text-2xl font-bold text-red-500">{{ $kehilangan->count() }}</h3>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow text-center hover:shadow-lg transition">
        <p class="text-gray-500 text-sm">Total Penemuan</p>
        <h3 class="text-xl md:text-2xl font-bold text-blue-500">{{ $penemuan->count() }}</h3>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow text-center hover:shadow-lg transition">
        <p class="text-gray-500 text-sm">Menunggu Verifikasi</p>
        <h3 class="text-xl md:text-2xl font-bold text-yellow-500">{{ $pending->count() }}</h3>
    </div>

</div>

<!-- LIST DATA -->
<div class="bg-white p-4 md:p-6 rounded-2xl shadow-lg overflow-x-auto">

    <h3 class="font-bold text-lg mb-4 text-gray-800">Riwayat Laporan</h3>

    <table class="w-full text-left border-separate border-spacing-y-3 text-sm">

        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="p-3 rounded-l-lg">Nama</th>
                <th class="p-3">Jenis</th>
                <th class="p-3">Status</th>
                <th class="p-3 rounded-r-lg">Aksi</th>
            </tr>
        </thead>

        <tbody>

        <!-- KEHILANGAN -->
        @foreach($kehilangan as $item)

        @php
            $adaKonfirmasiKehilangan = \App\Models\Konfirmasi::where('laporan_id', $item->id)
                ->where('type', 'kehilangan')
                ->exists();
        @endphp

        <tr class="bg-gray-50 hover:bg-gray-100 transition shadow-sm rounded-lg">
            <td class="p-3 font-medium text-gray-800">{{ $item->nama_barang }}</td>

            <td class="p-3">
                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
                    Kehilangan
                </span>
            </td>

            <td class="p-3">
                @if($item->status == 'ditemukan')
                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
                        Ditemukan
                    </span>
                @elseif($adaKonfirmasiKehilangan)
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">
                        Menunggu Konfirmasi
                    </span>
                @else
                    <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs">
                        Belum
                    </span>
                @endif
            </td>

            <td class="p-3">
                <a href="/user/detail-acc/kehilangan/{{ $item->id }}"
                   class="bg-gray-800 text-white px-3 py-1 rounded-full text-xs hover:bg-black transition">
                    Detail
                </a>
            </td>
        </tr>
        @endforeach

        <!-- PENEMUAN -->
        @foreach($penemuan as $item)

        @php
            $adaKonfirmasiPenemuan = \App\Models\Konfirmasi::where('laporan_id', $item->id)
                ->where('type', 'penemuan')
                ->exists();
        @endphp

        <tr class="bg-gray-50 hover:bg-gray-100 transition shadow-sm rounded-lg">
            <td class="p-3 font-medium text-gray-800">{{ $item->nama_barang }}</td>

            <td class="p-3">
                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold">
                    Penemuan
                </span>
            </td>

            <td class="p-3">
                @if($item->status == 'ditemukan')
                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
                        Ditemukan
                    </span>
                @elseif($adaKonfirmasiPenemuan)
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">
                        Menunggu Konfirmasi
                    </span>
                @else
                    <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs">
                        Belum
                    </span>
                @endif
            </td>

            <td class="p-3">
                <a href="/user/detail-acc/penemuan/{{ $item->id }}"
                   class="bg-gray-800 text-white px-3 py-1 rounded-full text-xs hover:bg-black transition">
                    Detail
                </a>
            </td>
        </tr>
        @endforeach

        <!-- PENDING -->
        @foreach($pending as $item)
        <tr class="bg-gray-50 hover:bg-gray-100 transition shadow-sm rounded-lg">
            <td class="p-3 font-medium text-gray-800">{{ $item->nama_barang }}</td>

            <td class="p-3">
                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ $item->type }}
                </span>
            </td>

            <td class="p-3">
                <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs">
                    Menunggu Verifikasi
                </span>
            </td>

            <td class="p-3">
                <span class="bg-gray-200 text-gray-500 px-3 py-1 rounded-full text-xs">
                    Menunggu ACC
                </span>
            </td>
        </tr>
        @endforeach

        </tbody>

    </table>

</div>

</div>

</body>
</html>