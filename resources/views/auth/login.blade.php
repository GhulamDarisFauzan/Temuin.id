<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex flex-col">

    <nav class="flex justify-between items-center px-5 md:px-10 py-5 md:py-6">
        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
            Temuin<span class="text-red-500">.id</span>
        </h1>
    </nav>

    <div class="flex flex-1 items-center justify-center px-5 md:px-10 py-8">

        <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-6 md:p-8">

            <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800">
                Login Admin
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Masuk sebagai admin untuk mengelola laporan.
            </p>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-500 text-sm px-4 py-3 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="email" name="email" required placeholder="Email"
                    class="w-full border-b mb-4 py-3 outline-none bg-transparent text-sm focus:border-red-500">

                    <div class="relative mb-6">
    <input type="password"
           id="password"
           name="password"
           required
           placeholder="Password"
           class="w-full border-b py-3 pr-10 outline-none bg-transparent text-sm focus:border-red-500">

    <button type="button"
            onclick="togglePassword()"
            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">
        👁️
    </button>
</div>
                
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('password.request') }}"
                        class="text-sm text-red-500 hover:text-red-600">
                            Lupa Password?
                        </a>
                    </div>

                <button type="submit"
                    class="w-full md:w-auto bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl text-sm font-semibold transition active:scale-95">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');

    if (password.type === 'password') {
        password.type = 'text';
    } else {
        password.type = 'password';
    }
}
</script>

</body>
</html>