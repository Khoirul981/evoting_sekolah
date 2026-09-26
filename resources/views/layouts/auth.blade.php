<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Voting Sekolah') - Sistem Pemilihan Digital</title>
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body_class', 'bg-slate-950 text-slate-100') min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 antialiased" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">

    <!-- Container Utama Centered & Fixed Max Width -->
    <div class="w-full max-w-[440px] mx-auto flex flex-col items-center">

        <!-- Header Identitas Aplikasi (Pasti di Atas) -->
        <div class="flex flex-col items-center text-center mb-5">
            @yield('header_badge')
            <h1 class="text-2xl font-bold tracking-tight mt-2.5 @yield('title_color', 'text-white')">E-Voting Sekolah</h1>
            <p class="text-xs mt-0.5 @yield('subtitle_color', 'text-slate-400')">@yield('header_subtitle', 'Sistem Pemilihan Suara Digital')</p>
        </div>

        <!-- Flash Message Sukses -->
        @if(session('success'))
            <div class="w-full mb-4 p-3.5 rounded-xl bg-emerald-950/80 border border-emerald-700 text-emerald-300 text-xs flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Flash Message Error -->
        @if(session('error'))
            <div class="w-full mb-4 p-3.5 rounded-xl bg-rose-950/80 border border-rose-700 text-rose-300 text-xs flex items-center gap-2.5 shadow-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Konten Form Login -->
        <div class="w-full">
            @yield('content')
        </div>

        <!-- Footer Bawah Konsisten di Luar Card -->
        <div class="flex flex-col items-center gap-1.5 text-center text-xs mt-5 @yield('footer_color', 'text-slate-500')">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                <span class="text-[11px] font-medium @yield('status_color', 'text-slate-400')">Server Sistem Terhubung</span>
            </div>
            <p class="text-[11px] opacity-75">&copy; {{ date('Y') }} E-Voting Sekolah. Hak Cipta Dilindungi.</p>
        </div>

    </div>

    @stack('scripts')
</body>
</html>



