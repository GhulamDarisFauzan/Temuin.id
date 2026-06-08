<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi User</title>
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

    <a href="/admin/dashboard"
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm shadow-sm transition">
       ← Back
    </a>
</div>

<!-- TITLE -->
<div class="mb-6">
    <h2 class="text-xl md:text-3xl font-bold text-gray-800">
        Data Konfirmasi User
    </h2>
    <div class="w-20 h-1 bg-red-500 mt-2 rounded-full"></div>
</div>

<!-- LIST -->
<div class="space-y-5">

@forelse($data as $item)
<div class="bg-white p-4 md:p-5 rounded-2xl shadow hover:shadow-lg transition">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-center">

        <!-- TEXT -->
        <div class="text-sm space-y-2">

            <p>
                <span class="text-gray-400 text-xs">ID Laporan</span><br>
                <span class="font-semibold">{{ $item->laporan_id }}</span>
            </p>

            <p>
                <span class="text-gray-400 text-xs">Kategori</span><br>
                <span class="font-semibold capitalize">{{ $item->type }}</span>
            </p>

            <div>
                <p class="text-gray-400 text-xs">Kronologi</p>
                <p class="text-gray-600">{{ $item->kronologi }}</p>
            </div>

            <p>
                <span class="text-gray-400 text-xs">Status</span><br>

                @if($item->status == 'pending')
                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600 font-semibold">
                        ⏳ Pending
                    </span>
                @elseif($item->status == 'diterima')
                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600 font-semibold">
                        ✓ Diterima
                    </span>
                @else
                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-600 font-semibold">
                        ✕ Ditolak
                    </span>
                @endif
            </p>

        </div>

        <!-- GAMBAR -->
        <div class="flex justify-center">
            @if($item->bukti)
                <img src="{{ asset($item->bukti) }}"
                     class="w-full max-w-[220px] h-48 object-cover rounded-xl shadow">
            @else
                <div class="w-full max-w-[220px] h-48 bg-gray-200 flex items-center justify-center rounded-xl text-gray-500 text-sm">
                    Tidak ada bukti
                </div>
            @endif
        </div>

        <!-- AKSI -->
        <div class="flex flex-col gap-3 justify-center">

            @if($item->status == 'pending')

                <a href="/admin/konfirmasi/acc/{{ $item->id }}"
                   class="bg-green-500 hover:bg-green-600 text-white py-2 rounded-xl text-center text-sm font-semibold transition">
                   ✔ Terima
                </a>

                <a href="/admin/konfirmasi/tolak/{{ $item->id }}"
                   class="bg-red-500 hover:bg-red-600 text-white py-2 rounded-xl text-center text-sm font-semibold transition">
                   ✕ Tolak
                </a>

            @else
                <div class="text-center text-gray-400 text-sm">
                    Sudah diproses
                </div>
            @endif

        </div>

    </div>

</div>

@empty
<div class="text-center text-gray-500 py-10">
    Tidak ada konfirmasi
</div>
@endforelse

</div>

</div>

</body>
</html>