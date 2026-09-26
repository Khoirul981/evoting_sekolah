<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - E-Voting Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between fixed inset-y-0 left-0 z-30 transition-transform duration-200 lg:translate-x-0" id="sidebar">
        <div>
            <!-- Brand -->
            <div class="h-16 border-b border-slate-800 flex items-center px-6 gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    EV
                </div>
                <div>
                    <h1 class="font-bold text-sm text-white leading-tight">E-VOTING SEKOLAH</h1>
                    <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded {{ auth()->user()->isSuperAdmin() ? 'bg-indigo-950 text-indigo-400 border border-indigo-800' : 'bg-sky-950 text-sky-400 border border-sky-800' }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs font-medium">
                <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Menu Utama
                </div>

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a
                    href="{{ route('admin.events.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.events.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Kelola Event Pemilihan</span>
                </a>

                <a
                    href="{{ route('admin.voters.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.voters.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Master Pemilih (DPT)</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                    <div class="pt-4 px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Master Pengguna
                    </div>

                    <a
                        href="{{ route('admin.super-admins.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.super-admins.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Manajemen Super Admin</span>
                    </a>

                    <a
                        href="{{ route('admin.petugas.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.petugas.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Manajemen Petugas</span>
                    </a>

                    <div class="pt-4 px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        Sistem & Keamanan
                    </div>

                    <a
                        href="{{ route('admin.audit-logs.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Audit Log Aktivitas</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Current User Profile & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center justify-between">
                <div class="overflow-hidden pr-2">
                    <div class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        title="Logout"
                        class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <!-- Top Navbar -->
        <header class="h-16 border-b border-slate-800 bg-slate-900 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    id="sidebarToggle"
                    class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800"
                    onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-400 hidden sm:block">@yield('page_description', 'Panel Manajemen E-Voting Sekolah')</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 font-medium hidden sm:inline">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <a
                    href="{{ route('voter.login') }}"
                    target="_blank"
                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 transition-colors border border-slate-700 flex items-center gap-1.5"
                >
                    <span>Bilik Suara</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-300 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-rose-950/80 border border-rose-800 text-rose-300 text-sm">
                    <div class="font-semibold mb-1">Terdapat beberapa kesalahan pengisian form:</div>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
