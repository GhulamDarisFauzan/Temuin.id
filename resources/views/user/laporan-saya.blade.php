<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Saya</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

<div class="p-4 sm:p-6 max-w-7xl mx-auto">

    <!-- 🔥 DIUBAH: navbar dibuat responsif mobile -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-3">
        <h1 class="text-xl sm:text-2xl font-bold">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <a href="/user/dashboard"
           class="w-full sm:w-auto text-center bg-red-500 text-white px-4 py-2 rounded-full text-sm shadow hover:bg-red-600 transition">
            Back
        </a>
    </div>

    @if(session('success'))
        <!-- 🔥 DIUBAH: alert dibuat responsif -->
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <!-- 🔥 DIUBAH: alert dibuat responsif -->
        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    <!-- 🔥 DIUBAH: title dibuat responsif -->
    <div class="mb-6 text-center sm:text-left">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Laporan Saya
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Semua laporan milik Anda, termasuk laporan aktif dan kadaluarsa.
        </p>
    </div>

    <!-- ================= LAPORAN KEHILANGAN ================= -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
        <h3 class="font-bold text-lg text-gray-800">
            Laporan Kehilangan
        </h3>

        <!-- 🔥 TAMBAHAN: info kecil -->
        <span class="text-xs text-gray-500">
            Tombol Kirim Ulang muncul jika laporan sudah kadaluarsa.
        </span>
    </div>

    <!-- 🔥 DIUBAH: grid lebih rapi di mobile -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        @forelse($kehilangan as $laporan)
            <!-- 🔥 DIUBAH: card diberi hover dan layout lebih rapi -->
            <div class="bg-white rounded-2xl shadow p-4 hover:shadow-lg transition">

                <div class="flex flex-col gap-2">

                    <div class="flex justify-between items-start gap-3">
                        <h4 class="font-bold text-base sm:text-lg text-gray-800 line-clamp-1">
                            {{ $laporan->nama_barang }}
                        </h4>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <span class="shrink-0 bg-red-100 text-red-600 text-[10px] px-2 py-1 rounded-full font-semibold">
                                Kadaluarsa
                            </span>
                        @else
                            <span class="shrink-0 bg-green-100 text-green-600 text-[10px] px-2 py-1 rounded-full font-semibold">
                                Aktif
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600">
                        {{ $laporan->kabupaten }} - {{ $laporan->kecamatan }}
                    </p>

                    <p class="text-sm text-gray-600">
                        Berlaku sampai:
                        <span class="font-semibold text-gray-800">
                            {{ $laporan->expired_at ? $laporan->expired_at->format('d M Y') : '-' }}
                        </span>
                    </p>

                    @if($laporan->expired_at && $laporan->expired_at->isPast())
                        <p class="text-xs text-red-500">
                            Laporan ini sudah tidak tampil di dashboard utama.
                        </p>
                    @else
                        <p class="text-xs text-green-600">
                            Laporan ini masih tampil di dashboard utama.
                        </p>
                    @endif

                </div>

                <!-- 🔥 DIUBAH: tombol responsif mobile -->
                <div class="flex flex-col sm:flex-row gap-2 mt-4">
                    <a href="/user/detail-acc/kehilangan/{{ $laporan->id }}"
                       class="w-full sm:w-auto text-center bg-gray-800 hover:bg-gray-900 text-white px-3 py-2 rounded-lg text-sm transition">
                        Detail
                    </a>

                    @if($laporan->expired_at && $laporan->expired_at->isPast())
                        <form action="/user/kirim-ulang/kehilangan/{{ $laporan->id }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition">
                                Kirim Ulang
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        @empty
            <!-- 🔥 TAMBAHAN: kondisi kosong -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white rounded-2xl shadow p-6 text-center text-gray-500">
                Belum ada laporan kehilangan.
            </div>
        @endforelse
    </div>

    <!-- ================= LAPORAN PENEMUAN ================= -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
        <h3 class="font-bold text-lg text-gray-800">
            Laporan Penemuan
        </h3>

        <!-- 🔥 TAMBAHAN: info kecil -->
        <span class="text-xs text-gray-500">
            Tombol Kirim Ulang muncul jika laporan sudah kadaluarsa.
        </span>
    </div>

    <!-- 🔥 DIUBAH: grid lebih rapi di mobile -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @forelse($penemuan as $laporan)
            <!-- 🔥 DIUBAH: card diberi hover dan layout lebih rapi -->
            <div class="bg-white rounded-2xl shadow p-4 hover:shadow-lg transition">

                <div class="flex flex-col gap-2">

                    <div class="flex justify-between items-start gap-3">
                        <h4 class="font-bold text-base sm:text-lg text-gray-800 line-clamp-1">
                            {{ $laporan->nama_barang }}
                        </h4>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <span class="shrink-0 bg-red-100 text-red-600 text-[10px] px-2 py-1 rounded-full font-semibold">
                                Kadaluarsa
                            </span>
                        @else
                            <span class="shrink-0 bg-green-100 text-green-600 text-[10px] px-2 py-1 rounded-full font-semibold">
                                Aktif
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600">
                        {{ $laporan->kabupaten }} - {{ $laporan->kecamatan }}
                    </p>

                    <p class="text-sm text-gray-600">
                        Berlaku sampai:
                        <span class="font-semibold text-gray-800">
                            {{ $laporan->expired_at ? $laporan->expired_at->format('d M Y') : '-' }}
                        </span>
                    </p>

                    @if($laporan->expired_at && $laporan->expired_at->isPast())
                        <p class="text-xs text-red-500">
                            Laporan ini sudah tidak tampil di dashboard utama.
                        </p>
                    @else
                        <p class="text-xs text-green-600">
                            Laporan ini masih tampil di dashboard utama.
                        </p>
                    @endif

                </div>

                <!-- 🔥 DIUBAH: tombol responsif mobile -->
                <div class="flex flex-col sm:flex-row gap-2 mt-4">
                    <a href="/user/detail-acc/penemuan/{{ $laporan->id }}"
                       class="w-full sm:w-auto text-center bg-gray-800 hover:bg-gray-900 text-white px-3 py-2 rounded-lg text-sm transition">
                        Detail
                    </a>

                    @if($laporan->expired_at && $laporan->expired_at->isPast())
                        <form action="/user/kirim-ulang/penemuan/{{ $laporan->id }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition">
                                Kirim Ulang
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        @empty
            <!-- 🔥 TAMBAHAN: kondisi kosong -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white rounded-2xl shadow p-6 text-center text-gray-500">
                Belum ada laporan penemuan.
            </div>
        @endforelse
    </div>

</div>

</body>
</html>