<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-5 md:px-10 py-5 md:py-6">
        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
            Temuin<span class="text-red-500">.id</span>
        </h1>
    </nav>



    <!-- Content -->
    <div class="flex flex-1 items-center justify-center px-5 md:px-10 py-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 items-center max-w-6xl w-full">

            <!-- Illustration -->
            <div class="flex justify-center order-1 md:order-none">

<div class="w-80 h-80 md:w-[420px] md:h-[420px]
            bg-white rounded-full overflow-hidden
            shadow-2xl flex items-center justify-center">

    <img src="{{ asset('images/Temuin.png') }}"
         alt="Temuin"
         class="w-64 md:w-80 object-contain">

</div>

</div>

            <!-- Login Content -->
            <div class="flex flex-col items-center md:items-start order-2">

                <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center md:text-left">
                    Login User
                </h2>

                <!-- Button Google -->
                <a href="/auth/google"
                   class="w-full sm:w-auto flex items-center justify-center gap-3 bg-white border px-5 md:px-6 py-3 rounded-xl shadow hover:bg-gray-100 transition active:scale-95">

                    <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                         class="w-5 h-5"
                         alt="google">

                    <span class="text-sm font-medium text-center">
                        Login / Register dengan Google
                    </span>
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>