<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Posyandu')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">

    <!-- TOPBAR -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-blue-600"></div>
                <div class="leading-tight">
                    <div class="font-semibold">POSYANDU</div>
                    <div class="text-xs text-slate-500">Sistem Monitoring Gizi</div>
                </div>
            </div>

            <!-- Logout (sementara dummy) -->
            <a href="#" class="px-4 py-2 rounded-xl text-sm bg-slate-100 hover:bg-slate-200">
                Logout
            </a>
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

        </div>
    </nav>

    <!-- CONTENT -->
    <main class="max-w-6xl mx-auto px-6 py-6">
        @yield('content')
    </main>

</body>
</html>
