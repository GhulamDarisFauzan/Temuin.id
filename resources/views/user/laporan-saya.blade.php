<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Saya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">

<div class="p-4 sm:p-6 max-w-7xl mx-auto">

    <!-- NAVBAR -->
    <div class="bg-white rounded-2xl shadow-sm px-4 py-3 mb-6 flex flex-col sm:flex-row justify-between items-center gap-3">
        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <a href="/user/dashboard"
           class="w-full sm:w-auto text-center bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-sm transition active:scale-95">
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    <!-- TITLE -->
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800">
            Laporan Saya
        </h2>

        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Semua laporan milik Anda, termasuk laporan aktif dan kadaluarsa. Laporan yang sudah kadaluarsa dapat dikirim ulang agar tampil kembali di dashboard utama.
        </p>
    </div>

    <!-- RINGKASAN -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

        <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Total Laporan Kehilangan</p>
            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">
                {{ $kehilangan->count() }}
            </h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Laporan Penemuan</p>
            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">
                {{ $penemuan->count() }}
            </h3>
        </div>

    </div>

    <!-- ================= LAPORAN KEHILANGAN ================= -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
        <div>
            <h3 class="font-extrabold text-xl text-gray-800">
                Laporan Kehilangan
            </h3>
            <p class="text-sm text-gray-500">
                Daftar laporan kehilangan yang pernah Anda buat.
            </p>
        </div>

        <span class="text-xs bg-red-50 text-red-600 px-3 py-1 rounded-full w-fit">
            Tombol Kirim Ulang muncul jika laporan kadaluarsa.
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-10">

        @forelse($kehilangan as $laporan)

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">

                <div class="p-5 flex flex-col h-full">

                    <div class="flex justify-between items-start gap-3 mb-4">
                        <span class="bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-full">
                            Kehilangan
                        </span>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <span class="shrink-0 bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full font-bold">
                                Kadaluarsa
                            </span>
                        @else
                            <span class="shrink-0 bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-bold">
                                Aktif
                            </span>
                        @endif
                    </div>

                    <h4 class="font-extrabold text-lg text-gray-800 line-clamp-1 mb-3">
                        {{ $laporan->nama_barang }}
                    </h4>

                    <div class="space-y-2 text-sm text-gray-600 flex-1">

                        <p>
                            <span class="font-semibold text-gray-800">Lokasi:</span>
                            {{ $laporan->kabupaten }} - {{ $laporan->kecamatan }}
                        </p>

                        <p>
                            <span class="font-semibold text-gray-800">Berlaku sampai:</span>
                            {{ $laporan->expired_at ? $laporan->expired_at->format('d M Y') : '-' }}
                        </p>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <div class="bg-red-50 text-red-600 text-xs p-3 rounded-xl leading-relaxed">
                                Laporan ini sudah tidak tampil di dashboard utama.
                            </div>
                        @else
                            <div class="bg-green-50 text-green-600 text-xs p-3 rounded-xl leading-relaxed">
                                Laporan ini masih tampil di dashboard utama.
                            </div>
                        @endif

                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 mt-5">
                        <a href="/user/detail-acc/kehilangan/{{ $laporan->id }}"
                           class="w-full text-center bg-gray-800 hover:bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition active:scale-95">
                            Detail
                        </a>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <form action="/user/kirim-ulang/kehilangan/{{ $laporan->id }}" method="POST" class="w-full">
                                @csrf
                                <button class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition active:scale-95">
                                    Kirim Ulang
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

            </div>

        @empty

            <div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl shadow-sm p-8 text-center border border-gray-100">
                <p class="text-gray-500 text-sm">
                    Belum ada laporan kehilangan.
                </p>
            </div>

        @endforelse

    </div>

    <!-- ================= LAPORAN PENEMUAN ================= -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
        <div>
            <h3 class="font-extrabold text-xl text-gray-800">
                Laporan Penemuan
            </h3>
            <p class="text-sm text-gray-500">
                Daftar laporan penemuan yang pernah Anda buat.
            </p>
        </div>

        <span class="text-xs bg-green-50 text-green-600 px-3 py-1 rounded-full w-fit">
            Tombol Kirim Ulang muncul jika laporan kadaluarsa.
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($penemuan as $laporan)

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">

                <div class="p-5 flex flex-col h-full">

                    <div class="flex justify-between items-start gap-3 mb-4">
                        <span class="bg-green-50 text-green-600 text-xs font-bold px-3 py-1 rounded-full">
                            Penemuan
                        </span>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <span class="shrink-0 bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full font-bold">
                                Kadaluarsa
                            </span>
                        @else
                            <span class="shrink-0 bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-bold">
                                Aktif
                            </span>
                        @endif
                    </div>

                    <h4 class="font-extrabold text-lg text-gray-800 line-clamp-1 mb-3">
                        {{ $laporan->nama_barang }}
                    </h4>

                    <div class="space-y-2 text-sm text-gray-600 flex-1">

                        <p>
                            <span class="font-semibold text-gray-800">Lokasi:</span>
                            {{ $laporan->kabupaten }} - {{ $laporan->kecamatan }}
                        </p>

                        <p>
                            <span class="font-semibold text-gray-800">Berlaku sampai:</span>
                            {{ $laporan->expired_at ? $laporan->expired_at->format('d M Y') : '-' }}
                        </p>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <div class="bg-red-50 text-red-600 text-xs p-3 rounded-xl leading-relaxed">
                                Laporan ini sudah tidak tampil di dashboard utama.
                            </div>
                        @else
                            <div class="bg-green-50 text-green-600 text-xs p-3 rounded-xl leading-relaxed">
                                Laporan ini masih tampil di dashboard utama.
                            </div>
                        @endif

                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 mt-5">
                        <a href="/user/detail-acc/penemuan/{{ $laporan->id }}"
                           class="w-full text-center bg-gray-800 hover:bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition active:scale-95">
                            Detail
                        </a>

                        @if($laporan->expired_at && $laporan->expired_at->isPast())
                            <form action="/user/kirim-ulang/penemuan/{{ $laporan->id }}" method="POST" class="w-full">
                                @csrf
                                <button class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition active:scale-95">
                                    Kirim Ulang
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

            </div>

        @empty

            <div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl shadow-sm p-8 text-center border border-gray-100">
                <p class="text-gray-500 text-sm">
                    Belum ada laporan penemuan.
                </p>
            </div>

        @endforelse

    </div>

</div>

</body>
</html>