<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cahaya Buku - Inventory System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #fbfcfc;
            color: #1f2937;
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #e5e7eb;
            border-radius: 20px;
        }
        @keyframes bell-ring {
            0%, 100% { transform: rotate(0); }
            20% { transform: rotate(14deg); }
            40% { transform: rotate(-12deg); }
            60% { transform: rotate(8deg); }
            80% { transform: rotate(-6deg); }
        }
        .animate-bell-ring {
            animation: bell-ring 0.6s ease-in-out;
        }
        .group:hover .bell-icon {
            animation: bell-ring 0.6s ease-in-out;
        }
        .dropdown-menu-animate {
            animation: dropdownFade 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transform-origin: top right;
        }
        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-6px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
    </style>
</head>
<body class="antialiased h-screen overflow-hidden flex bg-[#fbfcfc] text-[#1e293b]">

    <!-- Sidebar -->
    <aside class="w-[260px] bg-white border-r border-[#eef0f2] flex flex-col h-full flex-shrink-0 relative z-20 select-none">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-[#f3f4f6]">
            <div class="flex items-center gap-3">
                <div class="w-[38px] h-[38px] bg-[#3a5d43] rounded-lg flex items-center justify-center shadow-sm text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-[#2d4636] text-[17px] tracking-tight leading-tight">Cahaya Buku</h1>
                    <p class="text-[11px] text-[#8c9ca2] font-medium tracking-wide">Inventory System</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3 space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('dashboard') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>{{ __('Dashboard') }}</span>
                </div>
                @if(request()->routeIs('dashboard'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Data Buku --}}
            <a href="{{ route('buku.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('buku.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('buku.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>{{ __('Data Buku') }}</span>
                </div>
                @if(request()->routeIs('buku.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Kategori Buku --}}
            <a href="{{ route('kategori.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('kategori.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('kategori.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>{{ __('Kategori Buku') }}</span>
                </div>
                @if(request()->routeIs('kategori.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Penulis --}}
            <a href="{{ route('penulis.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('penulis.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('penulis.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>{{ __('Penulis') }}</span>
                </div>
                @if(request()->routeIs('penulis.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Penerbit --}}
            <a href="{{ route('penerbit.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('penerbit.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('penerbit.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span>{{ __('Penerbit') }}</span>
                </div>
                @if(request()->routeIs('penerbit.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Rak Buku --}}
            <a href="{{ route('rak.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('rak.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('rak.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    <span>{{ __('Rak Buku') }}</span>
                </div>
                @if(request()->routeIs('rak.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Divider --}}
            <div class="pt-3 pb-1">
                <div class="h-px w-full bg-[#f1f3f5]"></div>
            </div>

            {{-- Buku Masuk --}}
            <a href="{{ route('buku-masuk.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('buku-masuk.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('buku-masuk.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Buku Masuk') }}</span>
                </div>
                @if(request()->routeIs('buku-masuk.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Buku Keluar --}}
            <a href="{{ route('buku-keluar.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('buku-keluar.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('buku-keluar.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Buku Keluar') }}</span>
                </div>
                @if(request()->routeIs('buku-keluar.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Stok Buku --}}
            <a href="{{ route('stok.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('stok.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('stok.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>{{ __('Stok Buku') }}</span>
                </div>
                @if(request()->routeIs('stok.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Divider --}}
            <div class="pt-3 pb-1">
                <div class="h-px w-full bg-[#f1f3f5]"></div>
            </div>

            {{-- Laporan --}}
            <a href="{{ route('laporan.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('laporan.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('laporan.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>{{ __('Laporan') }}</span>
                </div>
                @if(request()->routeIs('laporan.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Pengguna --}}
            <a href="{{ route('pengguna.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('pengguna.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('pengguna.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>{{ __('Pengguna') }}</span>
                </div>
                @if(request()->routeIs('pengguna.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>

            {{-- Pengaturan --}}
            <a href="{{ route('pengaturan.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[13.5px] font-medium transition-all {{ request()->routeIs('pengaturan.*') ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-[#64748b] hover:bg-[#f8faf9] hover:text-[#1e293b]' }}">
                <div class="flex items-center gap-3.5">
                    <svg class="w-[18px] h-[18px] {{ request()->routeIs('pengaturan.*') ? 'text-[#2d4636]' : 'text-[#94a3b8] group-hover:text-[#64748b]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ __('Pengaturan') }}</span>
                </div>
                @if(request()->routeIs('pengaturan.*'))
                    <span class="w-[3.5px] h-[18px] bg-[#2d4636] rounded-full"></span>
                @endif
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-[#f3f4f6]">
            <button type="button" onclick="openLogoutConfirmModal()" class="flex items-center gap-3.5 w-full text-left px-3.5 py-2 text-[#e02424] hover:bg-red-50/70 rounded-lg font-medium text-[13.5px] transition-colors cursor-pointer">
                <svg class="w-[18px] h-[18px] text-[#e02424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>{{ __('Logout') }}</span>
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-20 bg-white border-b border-[#eef0f2] flex items-center justify-between px-8 flex-shrink-0 relative z-30">
            <h2 class="text-[19px] font-bold text-[#1e293b] tracking-tight">{{ __('Sistem Pendataan Buku') }}</h2>
            
            <div class="flex items-center gap-6">
                <!-- Search with Live Autocomplete -->
                <div class="relative w-[360px]" id="global-search-container">
                    <form method="GET" action="@yield('search_action', route('buku.index'))" id="global-search-form" class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               id="global-search-input"
                               value="{{ request('search') }}" 
                               autocomplete="off"
                               class="block w-full pl-9 pr-9 py-2 border border-[#e2e8f0] rounded-xl bg-white text-[13px] text-[#1e293b] focus:outline-none focus:border-[#3a5d43] focus:ring-2 focus:ring-[#3a5d43]/15 transition-all placeholder-[#94a3b8]" 
                               placeholder="@yield('search_placeholder', __('Cari judul buku, penulis, atau ISBN...'))">
                        
                        <button type="button" id="global-search-clear" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </form>

                    <!-- Instant Search Dropdown Preview -->
                    <div id="global-search-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                        <div class="px-4 py-2.5 bg-gray-50/80 border-b border-gray-100 flex items-center justify-between text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                            <span>{{ __('Hasil Pencarian Cepat') }}</span>
                            <span id="search-spinner" class="hidden text-[#3a5d43] font-normal lowercase">{{ __('mencari...') }}</span>
                        </div>
                        <div id="search-results-list" class="max-h-[300px] overflow-y-auto divide-y divide-gray-50">
                            <!-- Injected by JavaScript -->
                        </div>
                        <div class="p-2.5 bg-gray-50/50 border-t border-gray-100 text-center">
                            <button type="button" onclick="document.getElementById('global-search-form').submit()" class="text-[12px] font-semibold text-[#3a5d43] hover:text-[#284230] flex items-center justify-center gap-1.5 w-full">
                                <span>{{ __('Lihat Semua Hasil di Data Buku') }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Notification Bell & Dropdown -->
                    <div class="relative" id="notification-container">
                        <button id="notification-button" type="button" aria-haspopup="true" aria-expanded="false" 
                            class="relative w-10 h-10 rounded-xl bg-white border border-[#e2e8f0] hover:border-[#3a5d43]/50 hover:bg-[#edf3ee]/40 text-[#64748b] hover:text-[#2d4636] flex items-center justify-center transition-all duration-200 shadow-xs focus:outline-none cursor-pointer group"
                            title="{{ __('Pusat Notifikasi') }}">
                            
                            <!-- Bell Icon with explicit dimensions and swing animation on hover -->
                            <svg class="bell-icon transition-transform duration-200" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>

                            <!-- Real-time dynamic unread badge with pulsing glow -->
                            <span id="notif-badge-wrapper" class="hidden absolute -top-1.5 -right-1.5 flex h-[20px] min-w-[20px] items-center justify-center pointer-events-none">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-70"></span>
                                <span id="notif-badge" class="relative inline-flex items-center justify-center h-[20px] min-w-[20px] px-1.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-[10px] font-black rounded-full ring-2 ring-white shadow-xs">0</span>
                            </span>
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="notification-dropdown" class="hidden absolute right-0 top-full mt-2.5 w-[420px] bg-white rounded-2xl shadow-2xl border border-slate-200/90 overflow-hidden z-50 dropdown-menu-animate">
                            <!-- Header with Dark Forest Green Gradient -->
                            <div class="px-5 py-3.5 bg-gradient-to-r from-[#203426] via-[#2d4636] to-[#3a5d43] text-white flex items-center justify-between border-b border-[#203426]/30">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-white backdrop-blur-xs shadow-xs">
                                        <svg style="width: 17px; height: 17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[14px] font-bold text-white tracking-tight">{{ __('Notifikasi Sistem') }}</span>
                                            <span id="notif-count-pill" class="text-[10px] font-bold bg-white/20 text-white px-2 py-0.5 rounded-full border border-white/25 backdrop-blur-xs">0 {{ __('Baru') }}</span>
                                        </div>
                                        <p class="text-[10.5px] text-emerald-100/75 font-normal mt-0.5">{{ __('Pemberitahuan stok buku & transaksi') }}</p>
                                    </div>
                                </div>
                                <button type="button" id="notif-mark-all" class="text-[11px] font-medium text-emerald-100 hover:text-white bg-white/10 hover:bg-white/20 border border-white/15 px-2.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                                    <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ __('Tandai Baca') }}</span>
                                </button>
                            </div>

                            <!-- Filter Tabs Bar -->
                            <div class="px-4 py-2 bg-slate-50/90 border-b border-slate-100 flex items-center gap-1.5 text-[11.5px] font-semibold text-slate-500 overflow-x-auto custom-scroll">
                                <button type="button" data-filter="all" class="notif-filter-btn active px-2.5 py-1 rounded-lg bg-white text-[#2d4636] border border-slate-200/90 shadow-2xs font-bold transition-all cursor-pointer flex-shrink-0">
                                    {{ __('Semua') }} (<span id="notif-tab-all-count">0</span>)
                                </button>
                                <button type="button" data-filter="aktivitas" class="notif-filter-btn px-2.5 py-1 rounded-lg hover:bg-white/80 hover:text-slate-700 text-slate-500 border border-transparent transition-all cursor-pointer flex-shrink-0">
                                    {{ __('Aktivitas') }} (<span id="notif-tab-akt-count">0</span>)
                                </button>
                                <button type="button" data-filter="stok" class="notif-filter-btn px-2.5 py-1 rounded-lg hover:bg-white/80 hover:text-slate-700 text-slate-500 border border-transparent transition-all cursor-pointer flex-shrink-0">
                                    {{ __('Stok Kritis') }} (<span id="notif-tab-stok-count">0</span>)
                                </button>
                                <button type="button" data-filter="transaksi" class="notif-filter-btn px-2.5 py-1 rounded-lg hover:bg-white/80 hover:text-slate-700 text-slate-500 border border-transparent transition-all cursor-pointer flex-shrink-0">
                                    {{ __('Transaksi') }} (<span id="notif-tab-trx-count">0</span>)
                                </button>
                            </div>
                            
                            <!-- Notification List Container -->
                            <div id="notif-list" class="max-h-[340px] overflow-y-auto divide-y divide-slate-100 custom-scroll bg-white">
                                <div class="p-8 text-center text-slate-400 text-[13px] flex flex-col items-center justify-center">
                                    <div class="w-6 h-6 border-2 border-slate-300 border-t-[#3a5d43] rounded-full animate-spin mb-2"></div>
                                    <span>Memuat notifikasi sistem...</span>
                                </div>
                            </div>
                            
                            <!-- Dropdown Footer Actions -->
                            <div class="px-4 py-2.5 bg-slate-50/90 border-t border-slate-100 flex items-center justify-between text-[11.5px] font-semibold text-slate-600">
                                <a href="{{ route('stok.index') }}" class="inline-flex items-center gap-1.5 text-[#3a5d43] hover:text-[#284230] group/foot transition-colors">
                                    <span>{{ __('Pantau Stok Buku') }}</span>
                                    <svg class="group-hover/foot:translate-x-0.5 transition-transform" style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                                <span class="text-slate-300">•</span>
                                <a href="{{ route('buku-masuk.index') }}" class="inline-flex items-center gap-1.5 text-[#3a5d43] hover:text-[#284230] group/foot transition-colors">
                                    <span>{{ __('Buku Masuk') }}</span>
                                    <svg class="group-hover/foot:translate-x-0.5 transition-transform" style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown (Executive User Pill) -->
                    <div class="relative" id="profile-container">
                        <button type="button" id="profile-button" aria-haspopup="true" aria-expanded="false" 
                            class="flex items-center gap-3 pl-2 pr-3.5 py-1.5 rounded-2xl border border-[#e2e8f0] bg-white hover:border-[#3a5d43]/50 hover:bg-[#edf3ee]/30 transition-all duration-200 shadow-xs hover:shadow-sm cursor-pointer select-none group focus:outline-none">
                            
                            <!-- Avatar with Online Status Indicator -->
                            <div class="relative w-[36px] h-[36px] flex-shrink-0">
                                <div class="w-full h-full rounded-xl overflow-hidden border border-slate-200/90 shadow-2xs group-hover:ring-2 group-hover:ring-[#3a5d43]/30 transition-all">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" 
                                         alt="{{ auth()->user()->nama ?? 'Admin' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white" title="Status: Aktif"></span>
                            </div>

                            <!-- User Identity Info -->
                            <div class="text-left hidden sm:block">
                                <p class="text-[13px] font-bold text-slate-800 group-hover:text-[#2d4636] transition-colors leading-tight truncate max-w-[130px]">
                                    {{ auth()->user()->nama ?? auth()->user()->name ?? 'Petugas Gudang' }}
                                </p>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <span class="inline-block text-[9.5px] font-extrabold uppercase tracking-wider text-[#3a5d43] bg-[#edf3ee] px-1.5 py-0.5 rounded border border-[#cbe6d0] leading-none">
                                        {{ auth()->user()->role ?? 'ADMIN' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Animated Rotating Chevron Icon -->
                            <svg id="profile-chevron" class="text-slate-400 group-hover:text-[#2d4636] transition-transform duration-200 ml-0.5" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div id="profile-dropdown" class="hidden absolute right-0 top-full mt-2.5 w-72 bg-white rounded-2xl shadow-2xl border border-slate-200/90 overflow-hidden z-50 dropdown-menu-animate">
                            <!-- Hero User Header Card -->
                            <div class="p-4 bg-gradient-to-br from-[#1e3225] via-[#2d4636] to-[#3a5d43] text-white relative overflow-hidden">
                                <!-- Background subtle glow decorations -->
                                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
                                <div class="absolute right-10 -top-6 w-16 h-16 rounded-full bg-emerald-400/10 pointer-events-none"></div>

                                <div class="relative z-10">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-12 h-12 rounded-xl overflow-hidden ring-2 ring-white/40 shadow-sm flex-shrink-0">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" 
                                                 alt="{{ auth()->user()->nama ?? 'Admin' }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[14px] font-bold text-white truncate leading-tight">
                                                {{ auth()->user()->nama ?? auth()->user()->name ?? 'Petugas Gudang' }}
                                            </p>
                                            <p class="text-[11.5px] text-emerald-200/90 font-medium truncate mt-0.5">
                                                {{ auth()->user()->email ?? 'admin@cahayabook.com' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status Bar Pill -->
                                    <div class="mt-3 pt-2.5 border-t border-white/15 flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-semibold text-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            {{ __('Sesi Aktif') }}
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider bg-white/20 text-white px-2 py-0.5 rounded-md border border-white/20 backdrop-blur-xs">
                                            {{ strtoupper(auth()->user()->role ?? 'ADMINISTRATOR') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="p-2 space-y-0.5 text-[13px] text-slate-700">
                                <a href="{{ route('pengguna.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#edf3ee]/70 hover:text-[#2d4636] transition-all group/item">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#3a5d43] flex items-center justify-center border border-emerald-100 group-hover/item:scale-105 transition-transform flex-shrink-0">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-800 group-hover/item:text-[#2d4636] text-[12.5px] leading-tight">{{ __('Profil & Akun') }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Kelola akun & hak akses') }}</p>
                                    </div>
                                    <svg class="text-slate-300 group-hover/item:text-[#3a5d43] group-hover/item:translate-x-0.5 transition-all" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>

                                <a href="{{ route('pengaturan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#edf3ee]/70 hover:text-[#2d4636] transition-all group/item">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100 group-hover/item:scale-105 transition-transform flex-shrink-0">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-800 group-hover/item:text-[#2d4636] text-[12.5px] leading-tight">{{ __('Pengaturan Sistem') }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Konfigurasi & preferensi') }}</p>
                                    </div>
                                    <svg class="text-slate-300 group-hover/item:text-[#3a5d43] group-hover/item:translate-x-0.5 transition-all" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>

                                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#edf3ee]/70 hover:text-[#2d4636] transition-all group/item">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-100 group-hover/item:scale-105 transition-transform flex-shrink-0">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-800 group-hover/item:text-[#2d4636] text-[12.5px] leading-tight">{{ __('Laporan & Mutasi') }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">{{ __('Riwayat stok & transaksi') }}</p>
                                    </div>
                                    <svg class="text-slate-300 group-hover/item:text-[#3a5d43] group-hover/item:translate-x-0.5 transition-all" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Logout Footer Button -->
                            <div class="border-t border-slate-100 p-2 bg-slate-50/60">
                                <button type="button" onclick="openLogoutConfirmModal()" class="w-full flex items-center justify-between px-3.5 py-2.5 text-[12.5px] font-semibold text-rose-600 hover:bg-rose-50/80 rounded-xl transition-all cursor-pointer group/logout">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="text-rose-500 group-hover/logout:scale-110 transition-transform" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>{{ __('Keluar (Logout)') }}</span>
                                    </div>
                                    <span class="text-[10px] text-rose-400 uppercase tracking-wider font-mono">{{ __('Keluar') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-[#fbfcfc]">
            @yield('content')
        </main>

    </div>

    <!-- GLOBAL DELETE CONFIRMATION MODAL -->
    <div id="global-delete-modal" class="hidden fixed inset-0 z-[9999] overflow-y-auto bg-black/50 backdrop-blur-sm transition-all duration-200">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 border border-red-100 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[17px] font-bold text-[#111827]">Konfirmasi Hapus Data</h3>
                        <p class="text-[13px] text-[#6b7280] mt-1.5 leading-relaxed">
                            Apakah Anda yakin ingin menghapus <span id="global-delete-item-name" class="font-bold text-[#111827]">data ini</span>?
                        </p>
                        <p class="text-[12px] text-red-500 font-medium mt-1">
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>

                <form id="global-delete-form" method="POST" class="mt-6 flex items-center justify-end gap-2.5">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-[13px] font-semibold text-white bg-[#dc2626] hover:bg-[#b91c1c] rounded-xl transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Ya, Hapus</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- GLOBAL LOGOUT CONFIRMATION MODAL (TEMA PERSIS CAHAYA BUKU) -->
    <div id="modal-confirm-logout" class="hidden fixed inset-0 z-[9999] overflow-y-auto bg-black/50 backdrop-blur-sm transition-all duration-200" onclick="if(event.target === this) closeLogoutConfirmModal()">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150 p-6" onclick="event.stopPropagation()">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 border border-red-100 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[17px] font-bold text-[#111827] leading-snug">{{ __('Konfirmasi Keluar Sistem?') }}</h3>
                        <p class="text-[13px] text-[#6b7280] mt-1.5 leading-relaxed">
                            {{ __('Apakah Anda yakin ingin keluar dari sistem Cahaya Buku? Sesi aktif Anda saat ini akan diakhiri.') }}
                        </p>
                        <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-[12px] text-amber-800 flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('Pastikan seluruh input data dan transaksi Anda telah tersimpan sebelum mengakhiri sesi.') }}</span>
                        </div>
                    </div>
                </div>

                <form id="logout-confirm-form" action="{{ route('logout') }}" method="POST" class="mt-6 flex items-center justify-end gap-2.5">
                    @csrf
                    <button type="button" onclick="closeLogoutConfirmModal()" class="px-4 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors cursor-pointer">
                        {{ __('Batal') }}
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-[13px] font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>{{ __('Ya, Keluar') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- MODAL DELETE GLOBAL ---
        function showDeleteModal(actionUrl, itemName) {
            var modal = document.getElementById('global-delete-modal');
            var form = document.getElementById('global-delete-form');
            var nameEl = document.getElementById('global-delete-item-name');
            
            form.action = actionUrl;
            nameEl.textContent = itemName ? "'" + itemName + "'" : 'data ini';
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            var modal = document.getElementById('global-delete-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // --- MODAL LOGOUT GLOBAL ---
        function openLogoutConfirmModal() {
            var profileDropdown = document.getElementById('profile-dropdown');
            var profileChevron = document.getElementById('profile-chevron');
            if (profileDropdown) profileDropdown.classList.add('hidden');
            if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';

            var modal = document.getElementById('modal-confirm-logout');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeLogoutConfirmModal() {
            var modal = document.getElementById('modal-confirm-logout');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutConfirmModal();
            }
        });

        // --- GLOBAL PENCARIAN (LIVE AUTOCOMPLETE PREVIEW) ---
        (function() {
            var searchInput = document.getElementById('global-search-input');
            var searchClear = document.getElementById('global-search-clear');
            var searchDropdown = document.getElementById('global-search-dropdown');
            var searchList = document.getElementById('search-results-list');
            var searchSpinner = document.getElementById('search-spinner');
            var searchTimer = null;

            if (!searchInput) return;

            function updateClearButton() {
                if (searchInput.value.trim().length > 0) {
                    searchClear.classList.remove('hidden');
                } else {
                    searchClear.classList.add('hidden');
                }
            }
            updateClearButton();

            if (searchClear) {
                searchClear.addEventListener('click', function() {
                    searchInput.value = '';
                    updateClearButton();
                    searchDropdown.classList.add('hidden');
                    searchInput.focus();
                });
            }

            searchInput.addEventListener('input', function() {
                updateClearButton();
                var query = searchInput.value.trim();
                clearTimeout(searchTimer);

                if (query.length < 2) {
                    searchDropdown.classList.add('hidden');
                    return;
                }

                searchSpinner.classList.remove('hidden');
                searchDropdown.classList.remove('hidden');

                searchTimer = setTimeout(function() {
                    fetch('{{ route("buku.quick-search") }}?q=' + encodeURIComponent(query))
                        .then(function(res) { return res.json(); })
                        .then(function(items) {
                            searchSpinner.classList.add('hidden');
                            if (!items || items.length === 0) {
                                searchList.innerHTML = '<div class="p-4 text-center text-[12.5px] text-gray-500">Tidak ada buku yang cocok dengan "<b>' + escapeHtml(query) + '</b>"</div>';
                                return;
                            }

                            var html = '';
                            items.forEach(function(item) {
                                var stokBadge = item.stok > 0 
                                    ? '<span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Stok: ' + item.stok + '</span>'
                                    : '<span class="text-[11px] font-semibold text-red-700 bg-red-50 px-2 py-0.5 rounded-md border border-red-200">Stok Habis</span>';

                                html += '<a href="' + item.url + '" class="flex items-center justify-between p-3 hover:bg-gray-50/90 transition-colors group">' +
                                    '<div class="min-w-0 pr-3">' +
                                        '<p class="text-[13px] font-bold text-gray-900 group-hover:text-[#2d4636] truncate">' + escapeHtml(item.judul) + '</p>' +
                                        '<p class="text-[11.5px] text-gray-500 truncate mt-0.5">Penulis: ' + escapeHtml(item.penulis) + ' &bull; <span class="font-mono text-gray-400">ISBN: ' + escapeHtml(item.isbn) + '</span></p>' +
                                    '</div>' +
                                    '<div class="flex-shrink-0 text-right">' +
                                        stokBadge +
                                    '</div>' +
                                '</a>';
                            });
                            searchList.innerHTML = html;
                        })
                        .catch(function(err) {
                            searchSpinner.classList.add('hidden');
                            console.error('Search error:', err);
                        });
                }, 250);
            });

            function escapeHtml(str) {
                var div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }
        })();

        // --- REALTIME NOTIFIKASI SISTEM ---
        (function() {
            var notifBtn = document.getElementById('notification-button');
            var notifDropdown = document.getElementById('notification-dropdown');
            var notifBadgeWrapper = document.getElementById('notif-badge-wrapper');
            var notifBadge = document.getElementById('notif-badge');
            var notifCountPill = document.getElementById('notif-count-pill');
            var notifList = document.getElementById('notif-list');
            var notifMarkAll = document.getElementById('notif-mark-all');
            
            var tabAllCount = document.getElementById('notif-tab-all-count');
            var tabAktCount = document.getElementById('notif-tab-akt-count');
            var tabStokCount = document.getElementById('notif-tab-stok-count');
            var tabTrxCount = document.getElementById('notif-tab-trx-count');
            var filterBtns = document.querySelectorAll('.notif-filter-btn');

            var allNotifications = [];
            var currentNotifIds = [];
            var activeFilter = 'all';
            var previousUnread = 0;
            var pollTimer = null;

            if (!notifBtn) return;

            // Toggle Dropdown
            notifBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var profileDropdown = document.getElementById('profile-dropdown');
                var profileChevron = document.getElementById('profile-chevron');
                if (profileDropdown) profileDropdown.classList.add('hidden');
                if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';
                
                var searchDropdown = document.getElementById('global-search-dropdown');
                if (searchDropdown) searchDropdown.classList.add('hidden');

                var isHidden = notifDropdown.classList.toggle('hidden');
                notifBtn.setAttribute('aria-expanded', !isHidden);

                // Fetch terbaru saat dropdown dibuka
                if (!isHidden) {
                    fetchNotifications();
                }
            });

            // Tab Filter Buttons
            filterBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(function(b) {
                        b.classList.remove('bg-white', 'text-[#2d4636]', 'border-slate-200/90', 'shadow-2xs', 'font-bold');
                        b.classList.add('hover:bg-white/80', 'hover:text-slate-700', 'text-slate-500', 'border-transparent');
                    });
                    this.classList.remove('hover:bg-white/80', 'hover:text-slate-700', 'text-slate-500', 'border-transparent');
                    this.classList.add('bg-white', 'text-[#2d4636]', 'border-slate-200/90', 'shadow-2xs', 'font-bold');

                    activeFilter = this.getAttribute('data-filter');
                    renderNotifications();
                });
            });

            function renderNotifications() {
                var filtered = allNotifications;
                if (activeFilter === 'aktivitas') {
                    filtered = allNotifications.filter(function(n) {
                        return n.category === 'Aktivitas';
                    });
                } else if (activeFilter === 'stok') {
                    filtered = allNotifications.filter(function(n) {
                        return n.category === 'Stok Inventaris' || (n.category !== 'Aktivitas' && (n.type === 'danger' || n.type === 'warning'));
                    });
                } else if (activeFilter === 'transaksi') {
                    filtered = allNotifications.filter(function(n) {
                        var titleLow = (n.title || '').toLowerCase();
                        return n.category === 'Buku Masuk' || n.category === 'Transaksi Sukses' ||
                               titleLow.indexOf('buku masuk') !== -1 || titleLow.indexOf('buku keluar') !== -1 || titleLow.indexOf('transaksi') !== -1;
                    });
                }

                if (filtered.length === 0) {
                    var emptyMsg = 'Tidak ada notifikasi';
                    if (activeFilter === 'aktivitas') emptyMsg = 'Belum ada catatan aktivitas baru';
                    else if (activeFilter === 'stok') emptyMsg = 'Semua stok buku aman & mencukupi';
                    else if (activeFilter === 'transaksi') emptyMsg = 'Tidak ada transaksi yang perlu ditindaklanjuti';
                    else emptyMsg = 'Semua aktivitas dan stok dalam status normal';

                    notifList.innerHTML = '<div class="p-8 text-center text-slate-400">' +
                        '<div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#3a5d43] mx-auto mb-3 flex items-center justify-center border border-emerald-100">' +
                            '<svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' +
                        '</div>' +
                        '<p class="text-[13px] font-bold text-slate-700">Semua Terkendali</p>' +
                        '<p class="text-[11.5px] text-slate-400 mt-0.5">' + emptyMsg + '</p>' +
                    '</div>';
                    return;
                }

                var html = '';
                filtered.forEach(function(item) {
                    var iconBg = 'bg-slate-50 text-slate-600 border-slate-200';
                    var iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    var badgeStyle = 'bg-slate-100 text-slate-600 border-slate-200';

                    if (item.type === 'danger') {
                        iconBg = 'bg-rose-50 text-rose-600 border-rose-200/80';
                        badgeStyle = 'bg-rose-50 text-rose-700 border-rose-200';
                        iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                    } else if (item.type === 'warning') {
                        iconBg = 'bg-amber-50 text-amber-600 border-amber-200/80';
                        badgeStyle = 'bg-amber-50 text-amber-700 border-amber-200';
                        iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
                    } else if (item.type === 'success') {
                        iconBg = 'bg-emerald-50 text-emerald-600 border-emerald-200/80';
                        badgeStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"></path></svg>';
                    } else if (item.type === 'info') {
                        iconBg = 'bg-sky-50 text-sky-600 border-sky-200/80';
                        badgeStyle = 'bg-sky-50 text-sky-700 border-sky-200';
                        iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    }

                    var unreadDot = !item.is_read ? '<span class="w-2 h-2 rounded-full bg-rose-500 shadow-2xs flex-shrink-0 animate-pulse" title="Belum dibaca"></span>' : '';
                    var readOpacity = item.is_read ? 'opacity-70 bg-slate-50/40' : 'bg-white';

                    html += '<a href="' + item.url + '" class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition-all border-b border-slate-100 last:border-b-0 ' + readOpacity + ' group">' +
                        '<div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 border shadow-2xs ' + iconBg + ' group-hover:scale-105 transition-transform">' +
                            iconSvg +
                        '</div>' +
                        '<div class="min-w-0 flex-1">' +
                            '<div class="flex items-center justify-between gap-1.5">' +
                                '<div class="flex items-center gap-1.5 min-w-0">' +
                                    '<span class="text-[12.5px] font-bold text-slate-900 group-hover:text-[#2d4636] transition-colors truncate">' + item.title + '</span>' +
                                    unreadDot +
                                '</div>' +
                                '<span class="text-[9.5px] font-bold px-1.5 py-0.5 rounded border ' + badgeStyle + ' flex-shrink-0">' + item.time + '</span>' +
                            '</div>' +
                            '<p class="text-[11.5px] text-slate-600 leading-snug mt-1 line-clamp-2">' + item.message + '</p>' +
                        '</div>' +
                    '</a>';
                });
                notifList.innerHTML = html;
            }

            function fetchNotifications() {
                fetch('{{ route("notifications.index") }}')
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        allNotifications = data.notifications || [];
                        currentNotifIds = [];
                        allNotifications.forEach(function(n) { currentNotifIds.push(n.id); });

                        var unread = data.unread_count || 0;

                        // Ring bell animation if new unread arrived
                        if (unread > previousUnread && previousUnread !== 0) {
                            notifBtn.classList.add('animate-bell-ring');
                            setTimeout(function() { notifBtn.classList.remove('animate-bell-ring'); }, 700);
                        }
                        previousUnread = unread;

                        // Badge & Pill display
                        if (unread > 0) {
                            notifBadge.textContent = unread > 99 ? '99+' : unread;
                            if (notifBadgeWrapper) notifBadgeWrapper.classList.remove('hidden');
                            notifCountPill.textContent = unread + ' Baru';
                            notifCountPill.className = 'text-[10px] font-bold bg-rose-500 text-white px-2 py-0.5 rounded-full shadow-2xs';
                        } else {
                            if (notifBadgeWrapper) notifBadgeWrapper.classList.add('hidden');
                            notifCountPill.textContent = 'Semua Terbaca';
                            notifCountPill.className = 'text-[10px] font-medium bg-white/20 text-white px-2 py-0.5 rounded-full backdrop-blur-xs';
                        }

                        // Tab Counts
                        if (tabAllCount) tabAllCount.textContent = allNotifications.length;
                        if (tabAktCount) {
                            var aktCount = allNotifications.filter(function(n) {
                                return n.category === 'Aktivitas';
                            }).length;
                            tabAktCount.textContent = aktCount;
                        }
                        if (tabStokCount) {
                            var stokCount = allNotifications.filter(function(n) {
                                return n.category === 'Stok Inventaris' || (n.category !== 'Aktivitas' && (n.type === 'danger' || n.type === 'warning'));
                            }).length;
                            tabStokCount.textContent = stokCount;
                        }
                        if (tabTrxCount) {
                            var trxCount = allNotifications.filter(function(n) {
                                var titleLow = (n.title || '').toLowerCase();
                                return n.category === 'Buku Masuk' || n.category === 'Transaksi Sukses' ||
                                       titleLow.indexOf('buku masuk') !== -1 || titleLow.indexOf('buku keluar') !== -1 || titleLow.indexOf('transaksi') !== -1;
                            }).length;
                            tabTrxCount.textContent = trxCount;
                        }

                        renderNotifications();
                    })
                    .catch(function(err) {
                        console.error('Failed to fetch notifications:', err);
                    });
            }

            // Real-time Dynamic Polling (3 detik saat tab aktif, 12 detik saat di background)
            function startRealtimePolling() {
                if (pollTimer) clearInterval(pollTimer);
                var delay = document.hidden ? 12000 : 3000;
                pollTimer = setInterval(fetchNotifications, delay);
            }

            // Fetch pertama kali & jalankan polling
            fetchNotifications();
            startRealtimePolling();

            // Respon instan saat tab dibuka kembali / window fokus
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    fetchNotifications();
                }
                startRealtimePolling();
            });

            window.addEventListener('focus', function() {
                fetchNotifications();
                startRealtimePolling();
            });

            // Global trigger agar aksi perubahan data di halaman langsung mengupdate notifikasi seketika
            window.refreshNotifications = function() {
                setTimeout(fetchNotifications, 300);
                setTimeout(fetchNotifications, 1200);
            };

            // Trigger notifikasi saat form disubmit di halaman
            document.addEventListener('submit', function() {
                setTimeout(fetchNotifications, 700);
                setTimeout(fetchNotifications, 1800);
            });

            if (notifMarkAll) {
                notifMarkAll.addEventListener('click', function(e) {
                    e.preventDefault();
                    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    var token = csrfMeta ? csrfMeta.getAttribute('content') : '';

                    fetch('{{ route("notifications.markRead") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({ ids: currentNotifIds })
                    })
                    .then(function(res) { return res.json(); })
                    .then(function() {
                        if (notifBadgeWrapper) notifBadgeWrapper.classList.add('hidden');
                        notifCountPill.textContent = 'Semua Terbaca';
                        notifCountPill.className = 'text-[10px] font-medium bg-white/20 text-white px-2 py-0.5 rounded-full backdrop-blur-xs';
                        fetchNotifications();
                    });
                });
            }
        })();

        // --- DROPDOWN PROFIL PENGGUNA ---
        (function() {
            var profileBtn = document.getElementById('profile-button');
            var profileDropdown = document.getElementById('profile-dropdown');
            var profileChevron = document.getElementById('profile-chevron');

            if (!profileBtn) return;

            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                var notifDropdown = document.getElementById('notification-dropdown');
                if (notifDropdown) notifDropdown.classList.add('hidden');

                var searchDropdown = document.getElementById('global-search-dropdown');
                if (searchDropdown) searchDropdown.classList.add('hidden');

                var isHidden = profileDropdown.classList.toggle('hidden');
                profileBtn.setAttribute('aria-expanded', !isHidden);

                if (profileChevron) {
                    profileChevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
        })();

        // --- GLOBAL CLICK OUTSIDE & ESCAPE HANDLER ---
        document.addEventListener('click', function(e) {
            var notifContainer = document.getElementById('notification-container');
            var notifDropdown = document.getElementById('notification-dropdown');
            if (notifContainer && notifDropdown && !notifContainer.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }

            var profileContainer = document.getElementById('profile-container');
            var profileDropdown = document.getElementById('profile-dropdown');
            var profileChevron = document.getElementById('profile-chevron');
            if (profileContainer && profileDropdown && !profileContainer.contains(e.target)) {
                profileDropdown.classList.add('hidden');
                if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';
            }

            var searchContainer = document.getElementById('global-search-container');
            var searchDropdown = document.getElementById('global-search-dropdown');
            if (searchContainer && searchDropdown && !searchContainer.contains(e.target)) {
                searchDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();

                var notifDropdown = document.getElementById('notification-dropdown');
                if (notifDropdown) notifDropdown.classList.add('hidden');

                var profileDropdown = document.getElementById('profile-dropdown');
                var profileChevron = document.getElementById('profile-chevron');
                if (profileDropdown) profileDropdown.classList.add('hidden');
                if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';

                var searchDropdown = document.getElementById('global-search-dropdown');
                if (searchDropdown) searchDropdown.classList.add('hidden');
            }
        });

        // --- 30 MINUTES IDLE AUTO-LOGOUT (TIMEOUT SESI 30 MENIT) ---
        (function() {
            var INACTIVITY_TIMEOUT_MS = 30 * 60 * 1000; // 30 menit
            var lastActivityKey = 'cahayabook_last_active_time';
            var idleTimer;

            function resetActivity() {
                localStorage.setItem(lastActivityKey, Date.now().toString());
                clearTimeout(idleTimer);
                idleTimer = setTimeout(triggerAutoLogout, INACTIVITY_TIMEOUT_MS);
            }

            function checkInactivity() {
                var lastActiveStr = localStorage.getItem(lastActivityKey);
                var lastActive = lastActiveStr ? parseInt(lastActiveStr, 10) : Date.now();
                if (Date.now() - lastActive >= INACTIVITY_TIMEOUT_MS) {
                    triggerAutoLogout();
                }
            }

            function triggerAutoLogout() {
                localStorage.removeItem(lastActivityKey);
                var logoutForm = document.querySelector('form[action*="logout"]');
                if (logoutForm) {
                    var timeoutInput = logoutForm.querySelector('input[name="timeout"]');
                    if (!timeoutInput) {
                        timeoutInput = document.createElement('input');
                        timeoutInput.type = 'hidden';
                        timeoutInput.name = 'timeout';
                        timeoutInput.value = '1';
                        logoutForm.appendChild(timeoutInput);
                    }
                    logoutForm.submit();
                } else {
                    window.location.href = "{{ route('login') }}?timeout=1";
                }
            }

            var activityEvents = ['mousemove', 'keydown', 'mousedown', 'scroll', 'touchstart'];
            activityEvents.forEach(function(evt) {
                window.addEventListener(evt, resetActivity, { passive: true });
            });

            // Inisialisasi awal
            resetActivity();

            // Interval pengecekan berkala (setiap 10 detik)
            setInterval(checkInactivity, 10000);
        })();
    </script>

</body>
</html>
