<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Super Admin') - {{ config('app.name', 'VOTINGYUK') }}</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#060a12] text-slate-100 min-h-screen flex antialiased" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">

    <!-- =========================================================================
         BACKDROP UNTUK SIDEBAR MOBILE / TABLET
         ========================================================================= -->
    <div id="mobileSidebarBackdrop" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <!-- =========================================================================
         1. SIDEBAR (TEMA MERAH MARUN - KONSISTEN DENGAN LOGIN PENGELOLA)
         ========================================================================= -->
    <aside id="sidebarMenu" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#150308] border-r border-rose-950/70 flex flex-col justify-between h-full lg:h-screen lg:sticky lg:top-0 lg:shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
            <!-- Header Logo & Role Badge -->
            <div class="p-5 border-b border-rose-950/60 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#800020] border border-rose-500/30 flex items-center justify-center text-white shadow-lg shadow-rose-950/80 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-tight text-white leading-tight">
                            {{ config('app.name', 'VOTINGYUK') }}
                        </h1>
                        <div class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#800020]/80 text-rose-200 border border-rose-600/30 tracking-wide">
                            <span>SUPER ADMIN</span>
                        </div>
                    </div>
                </div>
                <!-- Tombol Tutup Sidebar di Layar Mobile/Tab -->
                <button type="button" id="closeSidebarBtn" class="lg:hidden p-1.5 text-rose-300 hover:text-white hover:bg-rose-950/60 rounded-lg transition-colors cursor-pointer" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Menu Navigasi Super Admin -->
            <nav class="p-4 space-y-1.5 flex-1">
                <div class="px-3 pb-2 text-[10px] font-semibold text-rose-400/50 uppercase tracking-wider">
                    Menu Utama
                </div>

                <!-- 1. Beranda / Dashboard -->
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#800020] text-white shadow-md shadow-rose-950/50 border border-rose-600/30' : 'text-rose-200/70 hover:text-white hover:bg-[#23060f]' }}"
                >
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Beranda</span>
                </a>

                <!-- 2. Kelola Akun -->
                <a
                    href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#800020] text-white shadow-md shadow-rose-950/50 border border-rose-600/30' : 'text-rose-200/70 hover:text-white hover:bg-[#23060f]' }}"
                >
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Kelola Akun</span>
                </a>

                <!-- 3. Audit Log -->
                <a
                    href="{{ Route::has('admin.audit-logs.index') ? route('admin.audit-logs.index') : '#' }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.audit-logs.*') ? 'bg-[#800020] text-white shadow-md shadow-rose-950/50 border border-rose-600/30' : 'text-rose-200/70 hover:text-white hover:bg-[#23060f]' }}"
                >
                    <svg class="w-4 h-4 {{ request()->routeIs('admin.audit-logs.*') ? 'text-white' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Audit Log</span>
                </a>
            </nav>
        </div>

        <!-- Profil Super Admin & Tombol Logout di Bawah Sidebar -->
        <div class="p-4 border-t border-rose-950/60 shrink-0">
            <div class="p-3 bg-[#20050d] border border-rose-900/40 rounded-xl flex flex-col gap-2.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#800020] text-white font-bold text-xs flex items-center justify-center shrink-0 border border-rose-500/30">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-white truncate leading-tight">
                            {{ Auth::user()->name ?? 'Super Admin' }}
                        </p>
                        <p class="text-[10px] text-rose-300/70 truncate mt-0.5">
                            {{ Auth::user()->email ?? 'admin@sekolah.sch.id' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-rose-950/80 hover:bg-[#881337] text-rose-200 hover:text-white text-[11px] font-semibold border border-rose-900/60 transition-all cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- =========================================================================
         2. KONTEN UTAMA DENGAN HEADER BIRU GELAP
         ========================================================================= -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Header Panel (Warna Biru Gelap sesuai referensi) -->
        <header class="bg-[#0c1424] border-b border-slate-800/80 px-4 sm:px-6 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button untuk Mobile / Tablet -->
                <button type="button" id="openSidebarBtn" class="lg:hidden p-1.5 rounded-lg bg-[#070e1c] border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800/60 focus:outline-none transition-colors cursor-pointer" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-400 hidden sm:inline">Panel Super Admin</span>
                    <span class="text-slate-600 hidden sm:inline">/</span>
                    <span class="text-xs font-bold text-white">@yield('page_title', 'Beranda')</span>
                </div>
            </div>

            <!-- Pojok Kanan: Tanggal & Jam WIB (UTC+7) -->
            <div class="flex items-center gap-2 px-2.5 sm:px-3 py-1.5 rounded-lg bg-[#070e1c] border border-slate-800 text-[11px] sm:text-xs text-slate-300 font-medium">
                <span id="liveClock">{{ now()->translatedFormat('l, d F Y | H:i') }} WIB (UTC+7)</span>
            </div>
        </header>

        <!-- Area Konten Halaman -->
        <main class="flex-1 p-4 sm:p-6 space-y-6 max-w-7xl w-full mx-auto">
            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-700 text-emerald-300 text-xs flex items-center gap-2.5 shadow-sm">
                    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Flash Message Error -->
            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-950/80 border border-rose-700 text-rose-300 text-xs flex items-center gap-2.5 shadow-sm">
                    <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
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
        });

        // Update jam secara real-time setiap menit
        function updateLiveTime() {
            const clockEl = document.getElementById('liveClock');
            if (!clockEl) return;
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZone: 'Asia/Jakarta',
                hour12: false
            };
            const formatted = new Intl.DateTimeFormat('id-ID', options).format(now);
            clockEl.textContent = formatted.replace(',', '') + ' WIB (UTC+7)';
        }
        setInterval(updateLiveTime, 30000);
    </script>
</body>
</html>
