<!DOCTYPE html>
<html>
<head>
    <title>Detail Laporan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen pb-24">

<div class="max-w-6xl mx-auto px-4 py-4 md:p-6">

    <div class="flex justify-between items-center mb-5">
        <h1 class="font-extrabold text-lg md:text-xl">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <!-- <a href="/user/dashboard" -->
        <a href="/"
           class="text-xs md:text-sm bg-white border px-3 md:px-5 py-2 rounded-full shadow-sm">
           ← Back
        </a>
    </div>

    <div class="mb-5">
        <h2 class="text-xl md:text-3xl font-bold text-gray-800">
            Detail {{ ucfirst($data->type) }}
        </h2>
        <div class="w-16 md:w-20 h-1 bg-red-500 mt-2 rounded-full"></div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6">

        <div class="mb-4 md:hidden">
            <div class="bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center h-52">
                <img src="{{ $data->gambar ? asset($data->gambar) : 'https://via.placeholder.com/300' }}"
                     onerror="this.src='https://via.placeholder.com/300'"
                     onclick="openImage(this.src)"
                     class="max-w-full max-h-full object-contain cursor-pointer hover:scale-105 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="space-y-3 text-sm">

                <div>
                    <p class="text-gray-400 text-xs">Kategori</p>
                    <p class="font-semibold capitalize">{{ $data->type }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">Nama</p>
                    <p class="border-b pb-1">{{ $data->nama_barang }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">Deskripsi</p>
                    <p class="border-b pb-1">{{ $data->deskripsi }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">Kronologi</p>
                    <p class="border-b pb-1">{{ $data->kronologi }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">Lokasi</p>
                    <p class="border-b pb-1">{{ $data->kecamatan }}, {{ $data->kabupaten }}</p>
                    <p class="border-b pb-1">{{ $data->provinsi }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">No Telp</p>
                    <p>{{ $data->no_telp }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs mb-1">Status</p>

                    @if($data->status == 'ditemukan')
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600 font-semibold">
                            ✓ Ditemukan
                        </span>
                    @elseif(isset($konfirmasi) && $konfirmasi)
                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-600 font-semibold">
                            🕒 Menunggu Konfirmasi
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600 font-semibold">
                            ⏳ Belum
                        </span>
                    @endif
                </div>

                @if(Auth::id() == $data->user_id && isset($konfirmasi) && $konfirmasi)
                <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-3">

                    <p class="text-gray-400 text-xs mb-2">
                        Bukti Konfirmasi dari Pengguna Lain
                    </p>

                    @if($konfirmasi->bukti)
                        <div class="bg-white rounded-lg border overflow-hidden flex items-center justify-center h-40 mb-2">
                            <img src="{{ asset($konfirmasi->bukti) }}"
                                 onclick="openImage(this.src)"
                                 class="max-w-full max-h-full object-contain cursor-pointer hover:scale-105 transition">
                        </div>
                    @endif

                    <p class="text-gray-400 text-xs">Kronologi Konfirmasi</p>
                    <p class="border-b pb-1 text-sm">
                        {{ $konfirmasi->kronologi ?: 'Tidak ada kronologi tambahan.' }}
                    </p>

                    @if($data->status != 'ditemukan')
                    <a href="/user/status/{{ strtolower($data->type) }}/{{ $data->id }}"
                       class="block w-full text-center mt-4 py-3 rounded-xl font-bold bg-red-600 text-white border-2 border-red-700 shadow-lg hover:bg-red-700 transition"
                       onclick="return confirm('Yakin laporan ini sudah ditemukan?')">
                       ✓ TANDAI SUDAH DITEMUKAN
                    </a>
                    @endif

                </div>
                @endif

                @if($data->status != 'ditemukan')
                    @if(Auth::id() == $data->user_id && !(isset($konfirmasi) && $konfirmasi))
                        <a href="/user/status/{{ strtolower($data->type) }}/{{ $data->id }}"
                           class="hidden md:block bg-red-600 hover:bg-red-700 text-white text-center py-3 rounded-xl mt-3 font-bold shadow"
                           onclick="return confirm('Yakin laporan ini sudah ditemukan?')">
                           ✓ TANDAI SUDAH DITEMUKAN
                        </a>
                    @elseif(Auth::id() != $data->user_id)
                        <!-- <a href="/user/konfirmasi/{{ strtolower($data->type) }}/{{ $data->id }}" -->
                        <a href="{{ auth()->check() ? '/user/konfirmasi/'.strtolower($data->type).'/'.$data->id : route('login.user') }}"
                           class="hidden md:block bg-green-500 text-white text-center py-3 rounded-xl mt-3 font-semibold">
                           Saya Menemukan
                        </a>
                    @endif
                @endif

            </div>

            <div class="space-y-2">
                <div id="map"
                     class="w-full h-52 md:h-64 rounded-xl shadow"
                     data-lat="{{ $data->latitude }}"
                     data-lng="{{ $data->longitude }}">
                </div>

                <p class="text-xs text-gray-600">
                    {{ $data->alamat ?? ($data->kecamatan . ', ' . $data->kabupaten . ', ' . $data->provinsi) }}
                </p>

                <p class="text-xs text-gray-400">
                    Lat: {{ $data->latitude ?? '-' }} |
                    Lng: {{ $data->longitude ?? '-' }}
                </p>
            </div>

            <div class="hidden md:flex flex-col gap-4">

                <div class="bg-gray-100 rounded-xl overflow-hidden shadow h-64 flex items-center justify-center">
                    <img src="{{ $data->gambar ? asset($data->gambar) : 'https://via.placeholder.com/300' }}"
                         onerror="this.src='https://via.placeholder.com/300'"
                         onclick="openImage(this.src)"
                         class="max-w-full max-h-full object-contain cursor-pointer hover:scale-105 transition">
                </div>

                <!-- <a href="/chat/{{ $data->id }}" -->
                <a href="{{ auth()->check() ? '/chat/'.$data->id : route('login.user') }}"
                   class="bg-blue-600 text-white text-center py-3 rounded-xl font-semibold">
                   💬 Chat Pemilik
                </a>

            </div>

        </div>

    </div>

</div>

<div class="fixed bottom-0 left-0 w-full bg-white border-t p-3 md:hidden z-50">
    <div class="flex gap-2">

        @if($data->status != 'ditemukan')
            @if(Auth::id() == $data->user_id)
                <a href="/user/status/{{ strtolower($data->type) }}/{{ $data->id }}"
                   class="flex-1 text-center bg-red-600 text-white py-3 rounded-lg text-sm font-bold"
                   onclick="return confirm('Yakin laporan ini sudah ditemukan?')">
                   Ditemukan
                </a>
            @else
                <!-- <a href="/user/konfirmasi/{{ strtolower($data->type) }}/{{ $data->id }}" -->
                <a href="{{ auth()->check() ? '/user/konfirmasi/'.strtolower($data->type).'/'.$data->id : route('login.user') }}"
                   class="flex-1 text-center bg-green-500 text-white py-3 rounded-lg text-sm font-semibold">
                   Temukan
                </a>
            @endif
        @endif

        <!-- <a href="/chat/{{ $data->id }}" -->
        <a href="{{ auth()->check() ? '/chat/'.$data->id : route('login.user') }}"
           class="flex-1 text-center bg-blue-600 text-white py-3 rounded-lg text-sm font-semibold">
           Chat
        </a>

    </div>
</div>

<div id="imageModal"
     class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[9999] p-4">

    <button onclick="closeImage()"
        class="absolute top-5 right-5 text-white text-4xl font-bold">
        ×
    </button>

    <img id="modalImage"
         class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let mapEl = document.getElementById('map');

    let lat = parseFloat(mapEl.dataset.lat);
    let lng = parseFloat(mapEl.dataset.lng);

    if (!lat || isNaN(lat)) lat = -5.45;
    if (!lng || isNaN(lng)) lng = 105.26;

    var map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map);

    setTimeout(() => map.invalidateSize(), 300);
});

function openImage(src) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    modalImage.src = src;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.style.overflow = 'hidden';
}

function closeImage() {
    const modal = document.getElementById('imageModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');

    document.body.style.overflow = 'auto';
}

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target.id === 'imageModal') {
        closeImage();
    }
});
</script>

</body>
</html>