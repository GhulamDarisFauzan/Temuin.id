<!DOCTYPE html>
<html>
<head>
    <title>Daftar Laporan</title>
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
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
        Daftar Barang / Orang
    </h2>
    <div class="w-28 h-1 bg-red-500 mt-2 rounded-full"></div>
</div>

<!-- 🔥 DESKTOP TABLE (TIDAK DIUBAH) -->
<div class="hidden md:block bg-white p-4 md:p-6 rounded-2xl shadow-lg overflow-x-auto">

    <table class="w-full text-left border-separate border-spacing-y-3 text-sm">

        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="p-3 rounded-l-lg">No</th>
                <th class="p-3">ID</th>
                <th class="p-3">Nama</th>
                <th class="p-3">Kategori</th>
                <th class="p-3">Tanggal</th>
                <th class="p-3">Status</th>
                <th class="p-3 rounded-r-lg">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($laporan as $item)
        <tr class="bg-gray-50 hover:bg-gray-100 transition shadow-sm rounded-lg">

            <td class="p-3">{{ $loop->iteration }}</td>
            <td class="p-3 text-gray-500">{{ $item->id }}</td>

            <td class="p-3 font-semibold text-gray-800">
                {{ $item->nama_barang }}
            </td>

            <td class="p-3">
                @php
                    $type = strtolower($item->type);
                @endphp

                @if($type === 'kehilangan')
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
                        Kehilangan
                    </span>
                @elseif($type === 'penemuan')
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold">
                        Penemuan
                    </span>
                @else
                    <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                        Tidak diketahui
                    </span>
                @endif
            </td>

            <td class="p-3 text-gray-500">
                {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
            </td>

            <td class="p-3">
                @php
                    $konfirmasi = \App\Models\Konfirmasi::where('laporan_id', $item->id)
                                    ->where('type', strtolower($item->type))
                                    ->latest()
                                    ->first();
                @endphp

                @if($item->status == 'ditemukan')
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">
                        Sudah Ditemukan
                    </span>
                @elseif($konfirmasi)
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs">
                        Menunggu Konfirmasi
                    </span>
                @else
                    <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs">
                        Belum
                    </span>
                @endif
            </td>

            <td class="p-3 flex flex-wrap gap-2">

                @if($konfirmasi)
                <a href="/admin/konfirmasi"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                   Konfirmasi
                </a>
                @endif

                <a href="/admin/detail-acc/{{ strtolower($item->type) }}/{{ $item->id }}"
                   class="bg-black hover:bg-gray-800 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                   Detail
                </a>

                @if($item->status != 'ditemukan' && !$konfirmasi)
                    <a href="/admin/status/{{ strtolower($item->type) }}/{{ $item->id }}"
                       class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                       Tandai
                    </a>
                @endif

                {{-- //hapus --}}
                <form action="{{ route('admin.hapus', [strtolower($item->type), $item->id]) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs shadow transition">
                        Hapus
                    </button>
                </form>

            </td>

        </tr>

        @empty
        <tr>
            <td colspan="7" class="text-center py-6 text-gray-500">
                Tidak ada data
            </td>
        </tr>
        @endforelse

        </tbody>

    </table>

</div>

<!-- 🔥 MOBILE CARD -->
<div class="md:hidden space-y-4">

@forelse($laporan as $item)

@php
    $type = strtolower($item->type);

    $konfirmasi = \App\Models\Konfirmasi::where('laporan_id', $item->id)
                    ->where('type', strtolower($item->type))
                    ->latest()
                    ->first();
@endphp

<div class="bg-white rounded-2xl shadow p-4">

    <div class="flex justify-between items-start mb-2">
        <h3 class="font-bold text-gray-800 text-sm">
            {{ $item->nama_barang }}
        </h3>
        <span class="text-xs text-gray-500">
            {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
        </span>
    </div>

    <p class="text-xs text-gray-500 mb-2">ID: {{ $item->id }}</p>

    <!-- kategori -->
    <div class="mb-2">
        @if($type === 'kehilangan')
            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">Kehilangan</span>
        @elseif($type === 'penemuan')
            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs">Penemuan</span>
        @else
            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded-full text-xs">Tidak diketahui</span>
        @endif
    </div>

    <!-- status -->
    <div class="mb-3">
        @if($item->status == 'ditemukan')
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Sudah Ditemukan</span>
        @elseif($konfirmasi)
            <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Menunggu Konfirmasi</span>
        @else
            <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs">Belum</span>
        @endif
    </div>

    <!-- aksi -->
    <div class="flex flex-wrap gap-2">

        @if($konfirmasi)
        <a href="/admin/konfirmasi"
           class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs">
           Konfirmasi
        </a>
        @endif

        <a href="/admin/detail-acc/{{ strtolower($item->type) }}/{{ $item->id }}"
           class="bg-black text-white px-3 py-1 rounded-lg text-xs">
           Detail
        </a>

        @if($item->status != 'ditemukan' && !$konfirmasi)
            <a href="/admin/status/{{ strtolower($item->type) }}/{{ $item->id }}"
               class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs">
               Tandai
            </a>
        @endif

        {{-- //hapus --}}
        <form action="{{ route('admin.hapus', [strtolower($item->type), $item->id]) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs">
                Hapus
            </button>
        </form>

    </div>

</div>

@empty
<p class="text-center text-gray-500">Tidak ada data</p>
@endforelse

</div>

</div>

</body>
</html>