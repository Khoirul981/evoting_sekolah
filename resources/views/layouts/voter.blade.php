<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bilik Suara Digital') - {{ config('app.name', 'VOTINGYUK') }}</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen flex flex-col antialiased" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">

    <!-- =========================================================================
         HEADER NAVBAR PEMILIH (TEMA PUTIH BERSIH)
         ========================================================================= -->
    <header class="bg-white border-b border-slate-200/90 sticky top-0 z-40 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <!-- Brand Logo & Identity -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <a href="{{ route('voter.dashboard') }}" class="text-base font-bold tracking-tight text-slate-900 hover:text-slate-700 transition-colors flex items-center gap-2">
                        <span>{{ config('app.name', 'VOTINGYUK') }}</span>
                    </a>
                </div>
            </div>

            <!-- Logout Widget -->
            @auth('voter')
                <div class="flex items-center gap-3">
                    <!-- Tombol Keluar -->
                    <form method="POST" action="{{ route('voter.logout') }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            title="Keluar dari Bilik Suara"
                            class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <!-- =========================================================================
         KONTEN UTAMA
         ========================================================================= -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-8 flex flex-col justify-center items-center">
        <!-- Flash Alert Error -->
        @if(session('error'))
            <div class="w-full max-w-lg mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Pemberitahuan</p>
                    <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- =========================================================================
         FOOTER DENGAN ASAS LUBER JURDIL
         ========================================================================= -->
    <footer class="bg-white border-t border-slate-200/90 py-4 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 text-center sm:text-left">
            <div class="flex items-center justify-center gap-2">
                <p><strong class="text-slate-700">Asas Pemilihan:</strong> Langsung, Umum, Bebas, Rahasia, Jujur, dan Adil (LUBER JURDIL).</p>
            </div>
            <p class="text-[11px] text-slate-400">&copy; {{ date('Y') }} {{ config('app.name', 'VOTINGYUK') }}. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
