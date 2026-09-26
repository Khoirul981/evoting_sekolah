<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Voting Sekolah') - Sistem Pemilihan Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased">
    <!-- Header Standar -->
    <header class="border-b border-slate-800 bg-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                    EV
                </div>
                <div>
                    <h1 class="font-bold text-base text-white leading-tight">E-Voting Sekolah</h1>
                    <p class="text-xs text-slate-400">Sistem Pemilihan Suara Digital</p>
                </div>
            </div>
            <div class="text-xs text-slate-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Server Terhubung</span>
            </div>
        </div>
    </header>

    <!-- Konten Utama Form Login -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-6">
        <div class="w-full max-w-md">
            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-lg bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-xs flex items-center gap-2.5">
                    <svg class="w-4 h-4 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Flash Message Error -->
            @if(session('error'))
                <div class="mb-4 p-3.5 rounded-lg bg-rose-950/60 border border-rose-800 text-rose-300 text-xs flex items-center gap-2.5">
                    <svg class="w-4 h-4 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer Standar -->
    <footer class="border-t border-slate-800 bg-slate-900 py-4 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} E-Voting Sekolah. Hak Cipta Dilindungi.</p>
    </footer>
</body>
</html>
