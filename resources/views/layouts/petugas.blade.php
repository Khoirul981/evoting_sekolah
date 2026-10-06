<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Petugas') - {{ config('app.name', 'VOTINGYUK') }}</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#050d1a] text-slate-100 min-h-screen flex antialiased" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">

    <!-- =========================================================================
         BACKDROP UNTUK SIDEBAR MOBILE / TABLET
         ========================================================================= -->
    <div id="mobileSidebarBackdrop" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- =========================================================================
         1. SIDEBAR PETUGAS (TEMA CYAN / ELECTRIC BLUE ELEGAN)
         ========================================================================= -->
    <aside id="sidebarMenu" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#071226] border-r border-cyan-950/70 flex flex-col justify-between h-full lg:h-screen lg:sticky lg:top-0 lg:shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
            <!-- Header Logo & Role Badge -->
            <div class="p-5 border-b border-cyan-950/60 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#042438] border border-cyan-400/60 flex items-center justify-center text-cyan-400 shadow-lg shadow-cyan-950/60 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-tight text-white leading-tight">
                            {{ config('app.name', 'VOTINGYUK') }}
                        </h1>
                        <div class="inline-flex items-center gap-1.5 mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500/15 text-cyan-300 border border-cyan-400/40 tracking-wide">
                            <span>PANEL PETUGAS</span>
                        </div>
                    </div>
                </div>
                <!-- Tombol Tutup Sidebar di Layar Mobile/Tab -->
                <button type="button" id="closeSidebarBtn" class="lg:hidden p-1.5 text-slate-400 hover:text-white hover:bg-slate-800/60 rounded-lg transition-colors cursor-pointer" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Menu Navigasi Petugas -->
            <nav class="p-4 space-y-1.5 flex-1">
                <div class="px-3 pb-2 text-[10px] font-semibold text-cyan-400/50 uppercase tracking-wider">
                    Menu Navigasi
                </div>

                <!-- 1. Kelola Event & Pemilihan -->
                <a
                    href="{{ route('petugas.events.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ (request()->routeIs('petugas.events.*') || request()->routeIs('petugas.candidates.*')) ? 'bg-cyan-600 text-white shadow-md shadow-cyan-950/60 border border-cyan-400/40' : 'text-slate-300 hover:text-white hover:bg-[#0c1a36]' }}"
                >
                    <svg class="w-4 h-4 {{ (request()->routeIs('petugas.events.*') || request()->routeIs('petugas.candidates.*')) ? 'text-white' : 'text-cyan-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Kelola Event & Voting</span>
                </a>
            </nav>
        </div>

        <!-- Profil Petugas & Tombol Logout di Bawah Sidebar -->
        <div class="p-4 border-t border-cyan-950/60 shrink-0">
            <div class="p-3 bg-[#0a1832] border border-cyan-900/40 rounded-xl flex flex-col gap-2.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-cyan-600/20 text-cyan-400 font-bold text-xs flex items-center justify-center shrink-0 border border-cyan-400/40">
                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-white truncate leading-tight">
                            {{ Auth::user()->name ?? 'Petugas' }}
                        </p>
                        <p class="text-[10px] text-cyan-200/60 truncate mt-0.5">
                            {{ Auth::user()->email ?? 'petugas@sekolah.sch.id' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-slate-800/90 hover:bg-rose-900/80 text-slate-300 hover:text-white text-[11px] font-semibold border border-slate-700/60 hover:border-rose-700 transition-all cursor-pointer"
                    >
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- =========================================================================
         2. KONTEN UTAMA DENGAN HEADER BIRU CYAN NAVY
         ========================================================================= -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Header Panel -->
        <header class="bg-[#071226] border-b border-cyan-950/70 px-4 sm:px-6 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button untuk Mobile / Tablet -->
                <button type="button" id="openSidebarBtn" class="lg:hidden p-1.5 rounded-lg bg-[#050d1a] border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800/60 focus:outline-none transition-colors cursor-pointer" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-cyan-400/70 hidden sm:inline">Panel Petugas</span>
                    <span class="text-slate-600 hidden sm:inline">/</span>
                    <span class="text-xs font-bold text-white">@yield('page_title', 'Kelola Event')</span>
                </div>
            </div>

            <!-- Pojok Kanan: Tanggal & Jam WIB (UTC+7) -->
            <div class="flex items-center gap-2 px-2.5 sm:px-3 py-1.5 rounded-lg bg-[#050d1a] border border-cyan-950/80 text-[11px] sm:text-xs text-slate-300 font-medium">
                <span id="liveClock">{{ now()->translatedFormat('l, d F Y | H:i') }} WIB </span>
            </div>
        </header>

        <!-- Area Konten Halaman -->
        <main class="flex-1 p-4 sm:p-6 space-y-6 max-w-7xl w-full mx-auto">
            <!-- Flash Message Error -->
            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-950/80 border border-rose-700 text-rose-300 text-xs flex items-center gap-2.5 shadow-sm">
                    <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Validasi Error Form Global -->
            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/80 border border-rose-700 text-rose-300 text-xs shadow-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <span>Terjadi kesalahan pada input:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-200 text-[11px] ml-1">
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        // Toggle Sidebar Drawer pada Mobile / Tablet
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebarMenu');
            const openBtn = document.getElementById('openSidebarBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const backdrop = document.getElementById('mobileSidebarBackdrop');

            function openSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
                }
            }

            function closeSidebar() {
                if (sidebar && backdrop) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
                }
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Real-time Clock WIB (UTC+7)
            function updateClock() {
                const clockEl = document.getElementById('liveClock');
                if (!clockEl) return;
                const now = new Date();
                const options = {
                    timeZone: 'Asia/Jakarta',
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                const formatter = new Intl.DateTimeFormat('id-ID', options);
                clockEl.textContent = formatter.format(now).replace(/\./g, ':') + ' WIB';
            }
            setInterval(updateClock, 1000);
        });
    </script>
</body>
</html>
