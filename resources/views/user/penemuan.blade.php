<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Penemuan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css"/>
</head>

<body class="bg-gray-100">

<div class="min-h-screen px-4 py-5 md:p-8">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="bg-white px-4 py-3 rounded-xl shadow-sm flex justify-between items-center mb-6">
            <h1 class="font-bold text-lg md:text-xl">
                Temuin<span class="text-red-500">.id</span>
            </h1>

            <a href="/user/dashboard"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full text-xs md:text-sm transition active:scale-95">
                Back
            </a>
        </div>

        <h2 class="text-xl md:text-2xl font-bold text-red-500 mb-6 border-b-2 border-red-500 inline-block">
            Form Penemuan
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 p-3 mb-4 rounded text-sm">
                @foreach ($errors->all() as $error)
                    <p class="text-red-500">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="/penemuan/store" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- FORM INPUT -->
                <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 space-y-4">

                    <select name="kategori" class="w-full border-b outline-none p-3 bg-transparent text-sm">
                        <option value="">Pilih Kategori</option>
                        <option value="barang">Barang</option>
                        <option value="orang">Orang</option>
                    </select>

                    <p class="text-sm text-gray-500">Masukkan data-data berikut:</p>

                    <input type="text" name="nama_barang" placeholder="Nama Barang/Orang"
                        class="w-full border-b outline-none p-3 bg-transparent text-sm">

                    <input type="text" name="deskripsi" placeholder="Ciri-ciri / detail"
                        class="w-full border-b outline-none p-3 bg-transparent text-sm">

                    <textarea name="kronologi" placeholder="Kronologi Penemuan"
                        class="w-full border-b outline-none p-3 bg-transparent text-sm min-h-[90px] resize-none"></textarea>

                    <select id="kabupaten" name="kabupaten" class="w-full border-b outline-none p-3 bg-transparent text-sm">
                        <option value="">Pilih Kabupaten / Kota</option>
                        @foreach ($kabupatens ?? [] as $kabupaten)
                            <option value="{{ $kabupaten->nama }}" data-id="{{ $kabupaten->id }}">
                                {{ $kabupaten->nama }}
                            </option>
                        @endforeach
                    </select>

                    <select id="kecamatan" name="kecamatan" class="w-full border-b outline-none p-3 bg-transparent text-sm">
                        <option value="">Pilih Kecamatan</option>
                    </select>

                    <input type="text" name="no_telp" placeholder="No Telepon"
                        class="w-full border-b outline-none p-3 bg-transparent text-sm">

                </div>

                <!-- MAP -->
                <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col items-center justify-center">

                    <div id="map" class="w-full h-72 md:h-80 lg:h-64 rounded-xl shadow"></div>

                    <!-- TAMBAHAN: TOMBOL GUNAKAN LOKASI SAYA -->
                    <button type="button" id="btnLokasiSaya"
                        class="mt-3 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full text-xs md:text-sm transition active:scale-95">
                        Gunakan Lokasi Saya Sekarang
                    </button>

                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                    <input type="hidden" name="alamat" id="alamat_input">

                    <p class="text-xs md:text-sm text-red-500 mt-3 text-center">
                        * Klik peta, cari lokasi, atau gunakan lokasi saya untuk menentukan titik
                    </p>

                    <p id="alamat" class="text-xs md:text-sm mt-2 text-gray-700 text-center leading-relaxed">
                        Lokasi belum dipilih
                    </p>

                    <div class="mt-4 flex flex-wrap justify-center gap-3 text-sm">
                        <label class="flex items-center gap-1">
                            <input type="radio" name="lokasi" value="1" checked>
                            1 Titik
                        </label>

                        <label class="flex items-center gap-1">
                            <input type="radio" name="lokasi" value="multi">
                            Lebih dari 1 titik
                        </label>
                    </div>

                </div>

                <!-- UPLOAD -->
                <div class="bg-white rounded-2xl shadow-sm p-4 md:p-5 flex flex-col items-center justify-center">

                    <label for="upload" class="w-full cursor-pointer group">

                        <div class="relative w-full aspect-[4/3] bg-white border-2 border-dashed border-gray-300 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">

                            <img id="preview"
                                class="w-full h-full object-contain hidden bg-gray-100">

                            <div id="placeholder"
                                class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">

                                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3 text-3xl">
                                    📷
                                </div>

                                <p class="text-sm font-semibold text-gray-700">
                                    Upload Foto
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    PNG, JPG, JPEG
                                </p>

                                <p class="text-[11px] text-gray-400 mt-2">
                                    Klik untuk memilih gambar
                                </p>

                            </div>

                            <div class="absolute bottom-3 right-3 bg-black/70 text-white text-xs px-3 py-1 rounded-full opacity-0 group-hover:opacity-100 transition">
                                Ganti Foto
                            </div>

                        </div>

                    </label>

                    <p id="fileName" class="text-xs text-gray-500 mt-2 hidden text-center"></p>

                    <input type="file"
                        name="gambar"
                        class="hidden"
                        id="upload"
                        accept="image/*"
                        onchange="previewImage(event)">

                </div>

            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="w-full md:w-auto bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-full text-sm font-semibold transition active:scale-95">
                    Kirim
                </button>
            </div>

        </form>

    </div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ================= MAP =================
    var map = L.map('map').setView([-5.45, 105.26], 13);

    // ================= PERUBAHAN: PETA LEBIH BAGUS DENGAN CARTO VOYAGER =================
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        maxZoom: 20
    }).addTo(map);

    let markers = [];
    let polyline = null;
    let alamatList = [];
    let wilayahMarker = null;

    function bersihkanMarker() {
        markers.forEach(m => map.removeLayer(m));
        markers = [];
        alamatList = [];

        if (polyline) {
            map.removeLayer(polyline);
            polyline = null;
        }

        if (wilayahMarker) {
            map.removeLayer(wilayahMarker);
            wilayahMarker = null;
        }
    }

    function setTitikLokasi(lat, lng, alamat, zoomLevel = 17) {
        bersihkanMarker();

        map.setView([lat, lng], zoomLevel);

        let marker = L.marker([lat, lng]).addTo(map);
        markers.push(marker);

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        document.getElementById('alamat').innerText = alamat;
        document.getElementById('alamat_input').value = alamat;

        alamatList.push(alamat);
    }

    // ================= TAMBAHAN: FUNGSI ARAHKAN PETA KE KABUPATEN / KECAMATAN =================
    function arahkanPeta(namaLokasi, zoomLevel = 13) {
        if (!namaLokasi) return;

        const query = encodeURIComponent(namaLokasi + ', Lampung, Indonesia');

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1&countrycodes=id&addressdetails=1`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);

                    map.setView([lat, lng], zoomLevel);

                    if (wilayahMarker) {
                        map.removeLayer(wilayahMarker);
                    }

                    wilayahMarker = L.marker([lat, lng]).addTo(map)
                        .bindPopup("Area: " + namaLokasi)
                        .openPopup();

                    setTimeout(() => {
                        map.invalidateSize();
                    }, 300);
                }
            })
            .catch(() => {
                console.log("Gagal mengarahkan peta ke wilayah");
            });
    }

    // ================= SEARCH MAP =================
    if (window.GeoSearch) {
        const provider = new GeoSearch.OpenStreetMapProvider({
            params: {
                countrycodes: 'id',
                bounded: 1,
                viewbox: '103.5,-3.7,106.2,-6.2',
                addressdetails: 1,
                limit: 8
            }
        });

        const search = new GeoSearch.GeoSearchControl({
            provider: provider,
            style: 'bar',
            searchLabel: 'Cari lokasi di Lampung...',
            autoComplete: true,
            autoCompleteDelay: 250,
            showMarker: false,
            showPopup: false,
            retainZoomLevel: false,
            animateZoom: true,
            keepResult: true
        });

        map.addControl(search);

        map.on('geosearch/showlocation', function(result) {
            let lat = result.location.y;
            let lng = result.location.x;
            let alamat = result.location.label;

            setTitikLokasi(lat, lng, alamat, 17);
        });
    }

    // ================= TAMBAHAN: GUNAKAN LOKASI SAYA =================
    const btnLokasiSaya = document.getElementById('btnLokasiSaya');

    btnLokasiSaya.addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung fitur lokasi.');
            return;
        }

        btnLokasiSaya.innerText = 'Mengambil lokasi...';
        btnLokasiSaya.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&zoom=18`)
                    .then(res => res.json())
                    .then(data => {
                        let alamat = data.display_name || "Alamat tidak ditemukan";

                        setTitikLokasi(lat, lng, alamat, 18);

                        btnLokasiSaya.innerText = 'Gunakan Lokasi Saya';
                        btnLokasiSaya.disabled = false;
                    })
                    .catch(() => {
                        setTitikLokasi(lat, lng, 'Lokasi berhasil diambil, tetapi alamat tidak ditemukan', 18);

                        btnLokasiSaya.innerText = 'Gunakan Lokasi Saya';
                        btnLokasiSaya.disabled = false;
                    });
            },
            function(error) {
                alert('Lokasi tidak diizinkan atau gagal diambil.');
                btnLokasiSaya.innerText = 'Gunakan Lokasi Saya';
                btnLokasiSaya.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });

    setTimeout(() => map.invalidateSize(), 300);

    // ================= KLIK MAP =================
    map.on('click', function(e) {

        let mode = document.querySelector('input[name="lokasi"]:checked').value;

        if (mode === '1') {
            bersihkanMarker();
        } else {
            if (wilayahMarker) {
                map.removeLayer(wilayahMarker);
                wilayahMarker = null;
            }
        }

        let marker = L.marker(e.latlng).addTo(map);
        markers.push(marker);

        let last = e.latlng;

        document.getElementById('lat').value = last.lat;
        document.getElementById('lng').value = last.lng;

        if (markers.length > 1) {

            let latlngs = markers.map(m => m.getLatLng());

            if (polyline) {
                map.removeLayer(polyline);
            }

            polyline = L.polyline(latlngs).addTo(map);
        }

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${last.lat}&lon=${last.lng}&addressdetails=1&zoom=18`)
        .then(res => res.json())
        .then(data => {

            let alamat = data.display_name || "Alamat tidak ditemukan";

            alamatList.push(alamat);

            let teks = "";

            if (alamatList.length === 1) {
                teks = alamatList[0];
            } else {
                teks = "Dari " + alamatList[0] + " → " + alamatList[alamatList.length - 1];
            }

            document.getElementById('alamat').innerText = teks;
            document.getElementById('alamat_input').value = teks;
        });

    });

    // ================= DROPDOWN DARI DATABASE =================
    const kab = document.getElementById('kabupaten');
    const kec = document.getElementById('kecamatan');

    kab.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const kabupatenId = selectedOption.getAttribute('data-id');
        const namaKabupaten = this.value;

        // ================= TAMBAHAN: SAAT PILIH KABUPATEN, PETA LANGSUNG MENGARAH =================
        arahkanPeta(namaKabupaten, 11);

        kec.innerHTML = '<option value="">Memuat kecamatan...</option>';

        if (!kabupatenId) {
            kec.innerHTML = '<option value="">Pilih Kecamatan</option>';
            return;
        }

        fetch('/get-kecamatan/' + kabupatenId)
            .then(response => response.json())
            .then(data => {
                kec.innerHTML = '<option value="">Pilih Kecamatan</option>';

                data.forEach(item => {
                    kec.innerHTML += `
                        <option value="${item.nama}">
                            ${item.nama}
                        </option>
                    `;
                });
            })
            .catch(() => {
                kec.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
            });
    });

    // ================= TAMBAHAN: SAAT PILIH KECAMATAN, PETA LANGSUNG MENGARAH =================
    kec.addEventListener('change', function () {
        const namaKecamatan = this.value;
        const namaKabupaten = kab.value;

        if (namaKecamatan && namaKabupaten) {
            arahkanPeta(namaKecamatan + ', ' + namaKabupaten, 14);
        }
    });

});

// ================= PREVIEW GAMBAR =================
function previewImage(event) {

    const input = event.target;
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    const fileName = document.getElementById('fileName');

    if (input.files && input.files[0]) {

        const file = input.files[0];

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.src = e.target.result;

            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');

            fileName.innerText =
                file.name + " • " +
                Math.round(file.size / 1024) + " KB";

            fileName.classList.remove('hidden');

        }

        reader.readAsDataURL(file);
    }
}
</script>

</body>
</html>