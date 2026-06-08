<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temuin.id - Temukan yang Hilang</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- NAVBAR -->
    <nav class="w-full px-6 md:px-16 py-5 flex justify-between items-center bg-white shadow-sm">
        <h1 class="text-xl md:text-2xl font-extrabold tracking-wide">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <a href="{{ route('login.user') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-full text-sm font-semibold transition duration-300">
            Masuk
        </a>
    </nav>

    <!-- HERO -->
    <main class="min-h-[85vh] flex items-center justify-center px-6 py-8">

        <section class="max-w-7xl w-full bg-white rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">

            <!-- KIRI -->
            <div class="p-8 md:p-14 flex flex-col justify-center">

                <span class="inline-block bg-red-100 text-red-600 text-sm font-semibold px-4 py-2 rounded-full mb-5 w-fit">
                    Platform Pelaporan Kehilangan & Penemuan
                </span>

                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                    Kehilangan Barang atau Orang?
                </h2>

                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    Laporkan sekarang melalui
                    <span class="font-bold text-red-500">Temuin.id</span>
                    dan bantu sebarkan informasi kepada masyarakat untuk
                    meningkatkan peluang ditemukan kembali.
                </p>

                <p class="text-gray-600 leading-relaxed mb-8">
                    Temuin.id menyediakan fitur pelaporan kehilangan,
                    pelaporan penemuan, pencarian laporan, komunikasi antara
                    pelapor dan penemu, serta dukungan lokasi interaktif untuk
                    membantu proses pencarian menjadi lebih efektif.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('login.user') }}"
                       class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-full font-semibold shadow-md transition duration-300">
                        Mulai Sekarang
                    </a>
                </div>

            </div>

            <!-- KANAN -->
            <div class="bg-gradient-to-br from-red-500 via-red-600 to-red-700 flex items-center justify-center p-10">

                <div class="text-center text-white">

                <div class="w-36 h-36 mx-auto mb-6 bg-white rounded-full flex items-center justify-center shadow-xl">

                <img src="{{ asset('images/search.png') }}"
                    alt="Search"
                    class="w-20 h-20 object-contain">

                </div>

                    <h3 class="text-3xl font-bold mb-4">
                        Temukan yang Hilang
                    </h3>

                    <p class="text-red-100 text-lg leading-relaxed max-w-md mx-auto">
                        Hubungkan pelapor dan penemu melalui sistem informasi
                        berbasis web yang mudah digunakan, aman, dan terorganisir.
                    </p>

                    <div class="mt-8 bg-white/15 backdrop-blur-sm rounded-2xl p-5 border border-white/20">
                        <p class="font-semibold text-lg">
                            “Temukan yang Hilang,<br>
                            Hubungkan yang Menemukan.”
                        </p>
                    </div>

                </div>

            </div>

        </section>

    </main>

</body>
</html>