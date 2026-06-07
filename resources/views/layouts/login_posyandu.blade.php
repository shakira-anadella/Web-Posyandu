<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kader - Posyandu Durian 14A</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800 uppercase tracking-wide">Posyandu Durian 14A</h2>
            <p class="text-sm text-slate-500 mt-1">Sistem Prediksi Status Gizi Balita (XGBoost)</p>
        </div>

        @if(session('error_login'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm text-center">
                {{ session('error_login') }}
            </div>
        @endif
        
        @if(session('success_logout'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm text-center">
                {{ session('success_logout') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Username Kader</label>
                <input type="text" name="username" required placeholder="Masukkan username" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none text-sm">
            </div>

            <button type="submit" 
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-lg transition duration-200 text-sm mt-2">
                Masuk ke Aplikasi 
            </button>
        </form>
    </div>

</body>
</html>