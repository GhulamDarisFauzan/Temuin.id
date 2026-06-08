<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200">

<div class="p-4 md:p-8 max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <h1 class="font-extrabold text-lg md:text-xl tracking-wide">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <button onclick="window.location.href='/admin/dashboard'"
            class="bg-white border px-4 py-2 rounded-full text-sm shadow-sm hover:bg-gray-100 transition active:scale-95">
            ← Back
        </button>

    </div>

    <!-- TITLE -->
    <div class="mb-6">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Verifikasi</h2>
        <div class="w-24 h-1 bg-red-500 mt-2 rounded-full"></div>
    </div>

    <!-- 🔥 DESKTOP TABLE (TETAP SAMA) -->
    <div class="hidden md:block bg-white p-4 md:p-6 rounded-2xl shadow-lg overflow-x-auto">

        <table class="w-full text-left border-separate border-spacing-y-3 text-sm">

            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="p-3 rounded-l-lg">No</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Ciri-ciri / Detail</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Aksi</th>
                    <th class="p-3 rounded-r-lg">Detail</th>
                </tr>
            </thead>

            <tbody>

                @forelse($laporan as $item)
                <tr class="bg-gray-50 hover:bg-gray-100 transition shadow-sm rounded-lg">

                    <td class="p-3">{{ $loop->iteration }}</td>

                    <td class="p-3 font-semibold text-gray-800">
                        {{ $item->nama_barang }}
                    </td>

                    <td class="p-3 text-gray-600">
                        {{ $item->deskripsi }}
                    </td>

                    <td class="p-3 text-gray-500">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
                    </td>

                    <td class="p-3 space-x-2 whitespace-nowrap">
                        <a href="/admin/terima/{{ $item->id }}"
                           class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                           Terima
                        </a>

                        <a href="/admin/tolak/{{ $item->id }}"
                           onclick="return confirm('Yakin mau menolak?')"
                           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                           Tolak
                        </a>
                    </td>

                    <td class="p-3 whitespace-nowrap">
                        <a href="/admin/detail/{{ $item->id }}"
                            class="bg-black hover:bg-gray-800 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                            Lihat
                        </a>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        Tidak ada data untuk diverifikasi
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <!-- 🔥 MOBILE CARD -->
    <div class="md:hidden space-y-4">

        @forelse($laporan as $item)
        <div class="bg-white rounded-2xl shadow p-4">

            <div class="flex justify-between items-start mb-2">
                <h3 class="font-bold text-gray-800 text-sm">
                    {{ $item->nama_barang }}
                </h3>
                <span class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
                </span>
            </div>

            <p class="text-xs text-gray-600 mb-3">
                {{ $item->deskripsi }}
            </p>

            <div class="flex justify-between items-center">

                <div class="flex gap-2">
                    <a href="/admin/terima/{{ $item->id }}"
                        class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs">
                        Terima
                    </a>

                    <a href="/admin/tolak/{{ $item->id }}"
                        onclick="return confirm('Yakin mau menolak?')"
                        class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs">
                        Tolak
                    </a>
                </div>

                <a href="/admin/detail/{{ $item->id }}"
                    class="bg-black text-white px-3 py-1 rounded-lg text-xs">
                    Detail
                </a>

            </div>

        </div>

        @empty
        <p class="text-center text-gray-500">
            Tidak ada data untuk diverifikasi
        </p>
        @endforelse

    </div>

</div>

</body>
</html>