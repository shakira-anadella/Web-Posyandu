<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Posyandu')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    
    <link rel="icon" type="image/png" href="{{ asset('Logo.png') }}">
</head>
<body class="bg-slate-50 text-slate-900">

    <!-- TOPBAR -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <img src="/Logo.png" alt="Logo Posyandu" class="h-9 w-9 object-contain" />
                
                <div class="leading-tight">
                    <div class="font-semibold">POSYANDU</div>
                    <div class="text-xs text-slate-500">Sistem Monitoring Status Gizi Balita</div>
                </div>
            </div>

            <!-- Tombol Logout Aktif Khusus Kader -->
            <form action="{{ url('/logout') }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl text-sm bg-red-50 font-semibold text-red-600 hover:bg-red-100 transition duration-200">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- MENU -->
    <nav class="bg-white">
        <div class="max-w-6xl mx-auto px-6 flex gap-6">

            <!-- Beranda -->
            <a href="{{ url('/dashboard') }}"
            class="py-3 text-sm font-medium border-b-2 
            {{ request()->is('dashboard') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                Beranda
            </a>

            <!-- Data Balita -->
            <a href="{{ url('/balita/create') }}"
            class="py-3 text-sm font-medium border-b-2 
            {{ request()->is('balita*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                Data Balita
            </a>

            <a href="{{ url('/laporan/bulanan') }}" target="_blank"
            class="py-3 text-sm font-medium border-b-2 
            {{ request()->is('laporan/bulanan*') ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
                Laporan Bulanan
            </a>

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="max-w-6xl mx-auto px-6 py-6">
        @yield('content')
    </main>

</body>
</html>
