<!DOCTYPE html>
<html>
<head>
    <title>Detail Laporan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200">

<div class="p-4 md:p-8 max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="bg-white px-4 py-3 rounded-xl shadow-sm flex justify-between items-center mb-6">
        <h1 class="font-bold text-lg tracking-wide">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <a href="/admin/verifikasi"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm shadow-sm transition">
           ← Back
        </a>
    </div>

    <!-- TITLE + BUTTON -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                Detail {{ ucfirst($data->type) }} Barang/Orang
            </h2>
            <div class="w-32 md:w-48 h-1 bg-red-500 mt-2 rounded-full"></div>
        </div>

        <!-- BUTTON -->
        <div class="flex gap-2">
            <a href="/admin/terima/{{ $data->id }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 md:px-5 py-2 rounded-full text-sm shadow transition">
               Terima
            </a>

            <a href="/admin/tolak/{{ $data->id }}"
               class="bg-red-500 hover:bg-red-600 text-white px-4 md:px-5 py-2 rounded-full text-sm shadow transition">
               Tolak
            </a>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-lg">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- KIRI -->
            <div class="space-y-3 text-sm text-gray-700">

                <p><b>Jenis Laporan:</b><br>{{ ucfirst($data->type) }}</p>

                <p><b>Kategori:</b><br>
                    {{ $data->kategori ? ucfirst($data->kategori) : '-' }}
                </p>

                <p class="font-semibold mt-2">Detail:</p>

                <p class="border-b pb-1">{{ $data->nama_barang }}</p>
                <p class="border-b pb-1">{{ $data->deskripsi }}</p>
                <p class="border-b pb-1">{{ $data->kronologi }}</p>

                <p class="border-b pb-1">{{ $data->provinsi }}</p>
                <p class="border-b pb-1">{{ $data->kabupaten }}</p>
                <p class="border-b pb-1">{{ $data->kecamatan }}</p>

                <p>
                    <b>Alamat Lengkap:</b><br>
                    <span class="text-gray-600">
                        {{ $data->alamat ?? ($data->kecamatan . ', ' . $data->kabupaten . ', ' . $data->provinsi) }}
                    </span>
                </p>

                <p><b>No Telp:</b><br>{{ $data->no_telp }}</p>

            </div>

            <!-- MAP -->
            <div class="flex flex-col">
                <div id="map" class="w-full h-64 rounded-lg shadow-sm"></div>

                <p class="text-xs mt-2 text-gray-600 bg-gray-50 p-2 rounded">
                    {{ $data->alamat ?? ($data->kecamatan . ', ' . $data->kabupaten . ', ' . $data->provinsi) }}
                </p>
            </div>

            <!-- IMAGE -->
            <div class="flex items-center justify-center">
                <div class="w-full bg-gray-100 rounded-2xl shadow overflow-hidden p-3">

                    <div class="w-full h-[320px] md:h-[400px] flex items-center justify-center bg-white rounded-xl overflow-hidden">

                        <img src="{{ $data->gambar ? asset($data->gambar) : 'https://via.placeholder.com/300' }}"
                             onerror="this.src='https://via.placeholder.com/300'"
                             onclick="openImage(this.src)"
                             class="max-w-full max-h-full object-contain hover:scale-105 transition duration-300 cursor-pointer">

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- IMAGE MODAL -->
<div id="imageModal"
     class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[9999] p-4">

    <button onclick="closeImage()"
        class="absolute top-5 right-5 text-white text-4xl font-bold">
        ×
    </button>

    <img id="modalImage"
         class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
</div>

<!-- MAP -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let lat = Number("{{ $data->latitude ?? -5.45 }}");
    let lng = Number("{{ $data->longitude ?? 105.26 }}");

    if (!lat) lat = -5.45;
    if (!lng) lng = 105.26;

    var map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map);

    setTimeout(() => {
        map.invalidateSize();
    }, 300);

});

// IMAGE MODAL
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