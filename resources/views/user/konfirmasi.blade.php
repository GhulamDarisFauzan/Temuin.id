<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Penemuan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

@if(session('success'))

<div id="notif"
     class="fixed top-5 right-5 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-xl">

    {{ session('success') }}

</div>

<script>

setTimeout(() => {
    window.location.href = "/user/detail-acc/{{ $data->type }}/{{ $data->id }}";
}, 1800);

</script>

@endif



<!-- CONTAINER -->
<div class="max-w-2xl mx-auto px-4 py-5 md:py-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-5">
        <h1 class="font-extrabold text-base md:text-xl">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <a href="#" onclick="history.back()"
           class="text-xs md:text-sm bg-white border px-3 md:px-5 py-2 rounded-full shadow-sm active:scale-95 transition">
           ← Back
        </a>
    </div>

    <!-- TITLE -->
    <div class="mb-4">
        <h2 class="text-lg md:text-2xl font-bold text-gray-800 leading-snug">
            Konfirmasi Penemuan {{ ucfirst($data->type) }}
        </h2>
        <div class="w-14 md:w-16 h-1 bg-red-500 mt-2 rounded-full"></div>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 space-y-5">

        <!-- INFO -->
        <div class="bg-blue-50 border border-blue-100 text-blue-700 text-xs md:text-sm p-3 rounded-lg">
            Isi dengan jelas agar pemilik dapat memverifikasi penemuanmu.
        </div>

        <form action="/user/konfirmasi" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- hidden -->
        <input type="hidden" name="laporan_id" value="{{ $data->id }}">
        <input type="hidden" name="type" value="{{ $data->type }}">
        <input type="hidden"name="redirect_url"value="/user/detail-acc/{{ $data->type }}/{{ $data->id }}">

        <!-- KRONOLOGI -->
        <div>
            <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-1">
                Kronologi
            </label>

            <textarea name="kronologi"
                class="w-full border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-lg p-3 text-sm h-28 md:h-32 transition"
                placeholder="Ceritakan bagaimana kamu menemukan..."></textarea>
        </div>

        <!-- UPLOAD -->
        <div>
            <label class="block text-xs md:text-sm font-semibold text-gray-700 mb-1">
                Upload Bukti
            </label>

            <input type="file" name="bukti"
                class="w-full text-xs md:text-sm border border-gray-300 rounded-lg p-2 bg-gray-50 cursor-pointer">
        </div>

        <!-- BUTTON -->
        <div class="pt-2">
            <button type="submit"
                class="w-full bg-black hover:bg-gray-800 active:scale-95 text-white py-3 md:py-3.5 rounded-xl font-semibold text-sm md:text-base shadow transition">
                Kirim Konfirmasi
            </button>
        </div>

        </form>

    </div>

</div>

</body>
</html>