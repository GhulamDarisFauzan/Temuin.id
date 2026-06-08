<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
           class="bg-white border px-4 py-2 rounded-full text-sm shadow-sm hover:bg-gray-100 transition active:scale-95">
            ← Login Admin
        </a>
    </nav>

    <!-- CONTENT -->
    <div class="flex-1 flex items-center justify-center px-5 py-8">

        <div class="bg-white w-full max-w-md rounded-3xl shadow-lg p-6 md:p-8">

            <!-- TITLE -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-8 h-8 text-red-500"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3zm-7 4v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                    </svg>
                </div>

                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Reset Password
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Masukkan password baru untuk akun admin Temuin.id.
                </p>
            </div>

            <!-- ERROR -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Admin
                    </label>

                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email', $request->email) }}"
                           required
                           autofocus
                           autocomplete="username"
                           readonly
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100 text-gray-700 outline-none">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>

                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="Masukkan password baru"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="Ulangi password baru"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-semibold transition active:scale-95">
                    Reset Password
                </button>
            </form>

        </div>

    </div>

</div>

</body>
</html>