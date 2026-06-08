<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About - Temuin.id</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200">

<div class="max-w-7xl mx-auto min-h-screen px-4 py-5 md:p-8">

    <!-- HEADER -->
    <div class="bg-white px-4 py-3 rounded-xl shadow-sm flex justify-between items-center mb-6 md:mb-10">

        <h1 class="text-base md:text-xl font-extrabold tracking-wide">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <button onclick="window.location.href='/admin/dashboard'"
    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 md:px-4 py-2 rounded-full text-xs md:text-sm shadow-sm transition active:scale-95">
    ← Back
</button>

    </div>

    <!-- CONTENT -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 items-center">

        <!-- LEFT -->
        <div>

            <h2 class="text-xl sm:text-2xl md:text-5xl font-bold mb-3 md:mb-4 leading-tight">
                Temuin<span class="text-red-500">.id</span>
            </h2>

            <div class="w-14 sm:w-16 md:w-24 h-1 bg-red-500 mb-4 md:mb-6 rounded-full"></div>

            <p class="text-gray-700 mb-3 md:mb-4 leading-relaxed text-xs sm:text-sm md:text-base">
                Temuin.id merupakan sistem informasi berbasis website yang dirancang untuk memfasilitasi pelaporan dan pencarian barang maupun orang hilang secara terstruktur dan terverifikasi. Sistem ini memungkinkan pengguna untuk membuat laporan kehilangan atau penemuan dengan melengkapi informasi deskriptif, foto, serta lokasi kejadian dalam bentuk titik koordinat peta digital.
            </p>

            <p class="text-gray-700 leading-relaxed text-xs sm:text-sm md:text-base">
                Selain itu, Temuin.id menyediakan mekanisme verifikasi oleh admin untuk menjaga keakuratan data serta fitur komunikasi antar pengguna guna mendukung proses konfirmasi dan koordinasi. Dengan adanya sistem ini, diharapkan proses pencarian dapat dilakukan secara lebih terarah, efisien, dan dapat dipertanggungjawabkan dibandingkan metode penyebaran informasi secara informal.
            </p>

        </div>

        <!-- RIGHT IMAGE -->
        <div class="flex justify-center">

            <div class="w-full max-w-sm sm:max-w-md overflow-hidden rounded-2xl shadow-lg border border-gray-200">

                <img 
                    src="{{ asset('images/Temuin.png') }}"
                    alt="About Temuin"
                    class="w-full h-48 sm:h-64 md:h-[400px] object-cover hover:scale-105 transition duration-300"
                >

            </div>

        </div>

    </div>

</div>

</body>
</html>