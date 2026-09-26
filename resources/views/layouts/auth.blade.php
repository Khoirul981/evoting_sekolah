<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Voting Sekolah') - Sistem Pemilihan Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between antialiased">
    <!-- Navbar Sederhana -->
    <header class="w-full border-b border-slate-800 bg-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
                    EV
                </div>
                <div>
                    <h1 class="font-bold text-base text-white leading-tight">E-VOTING SEKOLAH</h1>
                    <p class="text-xs text-slate-400">Sistem Pemilihan Ketua & Aspirasi Siswa</p>
                </div>
            </div>
            <div class="text-xs text-slate-400 font-medium">
                Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Flash Notification -->
            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-lg bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-sm flex items-start gap-2.5">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3.5 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-300 text-sm flex items-start gap-2.5">
                    <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Navigation Tab Pemilih vs Pengelola di Bagian Atas Form -->
            <div class="grid grid-cols-2 p-1 bg-slate-900 border border-slate-800 rounded-xl mb-4 text-xs font-semibold">
                <a
                    href="{{ route('voter.login') }}"
                    class="py-2.5 text-center rounded-lg transition-colors {{ request()->routeIs('voter.login') || request()->routeIs('home') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200' }}"
                >
                    Pemilih (Siswa & Guru)
                </a>
                <a
                    href="{{ route('admin.login') }}"
                    class="py-2.5 text-center rounded-lg transition-colors {{ request()->routeIs('admin.login') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-200' }}"
                >
                    Pengelola (Admin & Petugas)
                </a>
            </div>

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-4 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} E-Voting Sekolah. Mengedepankan asas Langsung, Umum, Bebas, dan Rahasia.</p>
    </footer>
</body>
</html>
