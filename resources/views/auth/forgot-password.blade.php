<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password Admin</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200">

<div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <nav class="flex justify-between items-center px-5 md:px-10 py-5 md:py-6">
        <h1 class="text-lg md:text-xl font-bold">
            Temuin<span class="text-red-500">.id</span>
        </h1>

        <a href="/login-admin"
           class="bg-white border px-4 py-2 rounded-full text-sm shadow-sm hover:bg-gray-100 transition">
            ← Kembali
        </a>
    </nav>

    <!-- CONTENT -->
    <div class="flex-1 flex items-center justify-center px-5">

        <div class="bg-white w-full max-w-md rounded-3xl shadow-lg p-8">

            <div class="text-center mb-6">

                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    🔐
                </div>

                <h2 class="text-2xl font-bold text-gray-800">
                    Lupa Password
                </h2>

                <p class="text-gray-500 text-sm mt-2">
                    Masukkan email admin untuk menerima link reset password.
                </p>

            </div>

            <!-- STATUS -->
            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- ERROR -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Admin
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="Masukkan email admin"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-semibold transition">
                    Kirim Link Reset Password
                </button>
            </form>

        </div>

    </div>

</div>

</body>
</html>