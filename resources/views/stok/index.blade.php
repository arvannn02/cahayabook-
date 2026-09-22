@extends('layouts.app')

@section('title', 'Stok Buku - Cahaya Buku')
@section('search_action', route('stok.index'))
@section('search_placeholder', 'Cari ID atau judul buku...')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">

    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-[28px] font-bold text-gray-900 leading-tight">Manajemen Stok Buku</h1>
        <p class="text-[14px] text-gray-500 mt-1">Pantau ketersediaan stok buku, lokasi rak, dan status inventaris secara real-time.</p>
    </div>

    {{-- Stats Cards (4 Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Card 1: Total Judul Buku --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Total Judul Buku</span>
                <div class="w-9 h-9 rounded-xl bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ number_format($totalJudulBuku) }}</h3>
                <span class="text-[13px] font-normal text-[#6b7280] block mt-1.5">Judul Terdaftar</span>
            </div>
        </div>

        {{-- Card 2: Total Eksemplar --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Total Eksemplar</span>
                <div class="w-9 h-9 rounded-xl bg-[#edf3ee] flex items-center justify-center text-[#2d4636]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ number_format($totalEksemplar) }}</h3>
                <span class="text-[13px] font-normal text-[#6b7280] block mt-1.5">Unit Buku</span>
            </div>
        </div>

        {{-- Card 3: Stok Menipis --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Stok Menipis</span>
                <div class="w-9 h-9 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#ef4444]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ number_format($stokMenipis) }}</h3>
                <span class="text-[13px] font-normal text-[#ef4444] font-medium block mt-1.5">Perlu restock</span>
            </div>
        </div>

        {{-- Card 4: Kategori Terbanyak --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Kategori Terbanyak</span>
                <div class="w-9 h-9 rounded-xl bg-[#f3f4f6] flex items-center justify-center text-[#6b7280]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[20px] font-bold text-[#111827] leading-snug tracking-tight truncate" title="{{ $kategoriTerbanyak }}">{{ $kategoriTerbanyak }}</h3>
                <span class="text-[13px] font-normal text-[#6b7280] block mt-1.5">Stok terbanyak</span>
            </div>
        </div>

    </div>

    @php
        $buildFilterUrl = function($key, $val) {
            $params = request()->except(['page']);
            if ($val === '' || $val === null) {
                unset($params[$key]);
            } else {
                $params[$key] = $val;
            }
            return route('stok.index', $params);
        };
    @endphp

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        {{-- Search Input --}}
        <form method="GET" action="{{ route('stok.index') }}" class="relative w-full sm:w-[340px]">
            @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
            @if(request('status'))   <input type="hidden" name="status"   value="{{ request('status') }}">   @endif
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#9ca3af]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID atau Judul..." 
                   class="block w-full pl-9 pr-8 py-2 text-[13px] bg-white border border-[#e5e7eb] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] transition-colors placeholder-[#9ca3af]">
            @if(request('search'))
                <a href="{{ route('stok.index', request()->except(['search', 'page'])) }}" 
                   class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer" 
                   title="Hapus pencarian">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2.5">
            {{-- Kategori Dropdown --}}
            <div class="relative" id="kategori-dropdown-container">
                <button type="button" 
                        id="kategori-button"
                        onclick="toggleActionDropdown(event, 'kategori-dropdown')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 border rounded-lg text-[13px] font-medium transition-colors cursor-pointer {{ request('kategori') ? 'border-[#2d4636] bg-[#edf3ee] text-[#2d4636]' : 'border-[#e5e7eb] text-[#374151] bg-white hover:bg-[#f9fafb]' }}">
                    <svg class="w-4 h-4 {{ request('kategori') ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>{{ $activeKategoriLabel }}</span>
                    <svg class="w-3.5 h-3.5 {{ request('kategori') ? 'text-[#2d4636]' : 'text-[#9ca3af]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Kategori Dropdown Panel -->
                <div id="kategori-dropdown" 
                     class="action-dropdown hidden absolute right-0 sm:right-auto sm:left-0 top-full mt-2 w-60 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 animate-in fade-in zoom-in-95"
                     onclick="event.stopPropagation()">
                    <div class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Pilih Kategori
                    </div>
                    <div class="py-1">
                        <a href="{{ $buildFilterUrl('kategori', '') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ !request('kategori') ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Semua Kategori</span>
                            @if(!request('kategori'))
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                    </div>
                    <div class="border-t border-slate-100 my-1"></div>
                    <div class="py-1 max-h-60 overflow-y-auto custom-scroll">
                        @foreach($kategoris as $kat)
                            <a href="{{ $buildFilterUrl('kategori', $kat->id) }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('kategori') == $kat->id ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                                <span class="truncate">{{ $kat->nama_kategori }}</span>
                                @if(request('kategori') == $kat->id)
                                    <svg class="w-4 h-4 text-[#2d4636] flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Status Dropdown --}}
            <div class="relative" id="status-dropdown-container">
                <button type="button" 
                        id="status-button"
                        onclick="toggleActionDropdown(event, 'status-dropdown')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 border rounded-lg text-[13px] font-medium transition-colors cursor-pointer {{ request('status') ? 'border-[#2d4636] bg-[#edf3ee] text-[#2d4636]' : 'border-[#e5e7eb] text-[#374151] bg-white hover:bg-[#f9fafb]' }}">
                    <svg class="w-4 h-4 {{ request('status') ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $activeStatusLabel }}</span>
                    <svg class="w-3.5 h-3.5 {{ request('status') ? 'text-[#2d4636]' : 'text-[#9ca3af]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Status Dropdown Panel -->
                <div id="status-dropdown" 
                     class="action-dropdown hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 animate-in fade-in zoom-in-95"
                     onclick="event.stopPropagation()">
                    <div class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Pilih Status Stok
                    </div>
                    <div class="py-1">
                        <a href="{{ $buildFilterUrl('status', '') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ !request('status') ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Semua Status</span>
                            @if(!request('status'))
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                    </div>
                    <div class="border-t border-slate-100 my-1"></div>
                    <div class="py-1">
                        <a href="{{ $buildFilterUrl('status', 'tersedia') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('status') === 'tersedia' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Tersedia</span>
                            </span>
                            @if(request('status') === 'tersedia')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        <a href="{{ $buildFilterUrl('status', 'stok_rendah') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('status') === 'stok_rendah' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Stok Rendah</span>
                            </span>
                            @if(request('status') === 'stok_rendah')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        <a href="{{ $buildFilterUrl('status', 'habis') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('status') === 'habis' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span>Habis</span>
                            </span>
                            @if(request('status') === 'habis')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Filter Tags --}}
    @if(request('search') || request('kategori') || request('status'))
    <div class="flex flex-wrap items-center gap-2 mb-5">
        <span class="text-[12px] text-slate-500 font-medium">Filter Aktif:</span>
        
        @if(request('search'))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Pencarian: "{{ request('search') }}"
                <a href="{{ route('stok.index', request()->except(['search', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        @if(request('kategori'))
            @php
                $activeKat = $kategoris->firstWhere('id', request('kategori')) ?? $kategoris->firstWhere('nama_kategori', request('kategori'));
            @endphp
            @if($activeKat)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Kategori: {{ $activeKat->nama_kategori }}
                <a href="{{ route('stok.index', request()->except(['kategori', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
            @endif
        @endif

        @if(request('status'))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Status: {{ $activeStatusLabel }}
                <a href="{{ route('stok.index', request()->except(['status', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        <a href="{{ route('stok.index') }}" class="text-[12px] text-red-600 hover:underline font-semibold ml-1">
            Reset Semua
        </a>
    </div>
    @endif

    {{-- Section Title --}}
    <h2 class="text-[18px] font-bold text-[#111827] mb-4">Daftar Stok Buku</h2>

    {{-- Table Container --}}
    <div class="bg-white rounded-2xl border border-[#eef0f2] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden mb-8">
        <div class="w-full overflow-x-auto overflow-y-visible [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <table class="w-full text-left border-collapse" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 32%;">
                    <col style="width: 14%;">
                    <col style="width: 12%;">
                    <col style="width: 10%;">
                    <col style="width: 12%;">
                    <col style="width: 14%;">
                    <col style="width: 6%;">
                </colgroup>
                <thead>
                    <tr class="bg-[#fafbfc] border-b border-slate-200/80">
                        <th class="text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 pl-6 pr-3 whitespace-nowrap">JUDUL & ISBN</th>
                        <th class="text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 px-3 whitespace-nowrap">KATEGORI</th>
                        <th class="text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 px-2 whitespace-nowrap">LOKASI RAK</th>
                        <th class="text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 px-1 whitespace-nowrap">STOK MIN.</th>
                        <th class="text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 px-1 whitespace-nowrap">SAAT INI</th>
                        <th class="text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 px-1 whitespace-nowrap">STATUS</th>
                        <th class="text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider py-4 pl-1 pr-6 whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f3f4f6]">
                    @forelse($stok as $s)
                    @php
                        $bukuObj = $s->buku;
                        $kategoriName = $bukuObj?->kategori?->nama_kategori ?? 'Umum';
                        $rakKode = $bukuObj?->rak?->kode_rak ?? '-';

                        $isbn = $bukuObj?->isbn ?? '-';
                        $judul = $bukuObj?->judul ?? 'Judul Buku';
                        $kategori = $kategoriName;
                        $rak = $rakKode;
                        $minStok = $s->min_stok ?? 10;
                        $saatIni = $s->stok_sisa ?? 0;
                        $statusStr = $saatIni <= 0 ? 'Habis' : ($saatIni <= $minStok ? 'Stok Rendah' : 'Tersedia');

                        $penulis = $bukuObj?->penulis?->nama_penulis;
                        $penerbit = $bukuObj?->penerbit?->nama_penerbit;
                        $subtext = $penulis ? ($penerbit ? $penulis . ' • ' . $penerbit : $penulis) : ($penerbit ?? null);

                        $isPsikologi = str_contains(strtolower($kategori), 'psikologi');
                        $badgeBg = $isPsikologi ? 'bg-[#d2ded5]' : 'bg-[#c2e7cc]';
                        $badgeText = $isPsikologi ? 'text-[#556b5a]' : 'text-[#2b6b3e]';
                    @endphp
                    <tr class="hover:bg-[#fcfdfc] transition-colors group">
                        {{-- 1. Judul & ISBN with Cover Thumbnail --}}
                        <td class="pl-6 pr-3 py-4.5 align-middle overflow-hidden">
                            <div class="flex items-center gap-3.5 min-w-0">
                                {{-- Book Cover Thumbnail --}}
                                <div class="w-[42px] h-[58px] rounded-[4px] shadow-sm border border-gray-200/80 overflow-hidden flex-shrink-0 relative select-none">
                                    @if($judul == 'The Silent Forest')
                                        <div class="w-full h-full bg-[#182626] flex flex-col items-center justify-center relative p-1">
                                            <div class="w-4 h-4 rounded-full border-[1.5px] border-[#4dd0e1] flex items-center justify-center shadow-[0_0_8px_rgba(77,208,225,0.6)]">
                                                <div class="w-1.5 h-1.5 rounded-full bg-[#4dd0e1]"></div>
                                            </div>
                                            <span class="text-[3.5px] text-teal-100/70 uppercase tracking-widest mt-2 font-mono">SILENT FOREST</span>
                                        </div>
                                    @elseif($judul == 'Membangun Fokus')
                                        <div class="w-full h-full bg-[#f4f7f4] border border-[#dce5dc] flex flex-col items-center justify-center p-1 relative">
                                            <div class="w-[30px] h-[44px] border border-[#a3b8a6] flex flex-col items-center justify-center text-center p-0.5">
                                                <span class="text-[4px] font-serif text-[#2f4233] uppercase leading-tight font-bold">Membangun<br>Fokus</span>
                                            </div>
                                        </div>
                                    @elseif($judul == 'Jejak Nusantara')
                                        <div class="w-full h-full bg-[#dfceb3] flex flex-col justify-between p-1.5 relative border border-[#cdb797]">
                                            <div class="w-full h-[3px] bg-[#8c5932] rounded-full mt-1"></div>
                                            <div class="text-center">
                                                <div class="w-2 h-2 rounded-full bg-[#8c5932] mx-auto mb-1"></div>
                                                <span class="text-[3.5px] font-bold text-[#5c371c] uppercase tracking-wider block">NUSANTARA</span>
                                            </div>
                                            <div class="w-full h-[2px] bg-[#8c5932]/40 rounded-full"></div>
                                        </div>
                                    @elseif($judul == 'Kuliner Tradisional')
                                        <div class="w-full h-full bg-[#1a1715] flex flex-col justify-between overflow-hidden relative">
                                            <div class="w-full h-[55%] bg-gradient-to-b from-[#3d251a] to-[#1a1715] p-1 flex items-center justify-center">
                                                <div class="w-4 h-4 rounded-full bg-gradient-to-tr from-[#d97706] to-[#fbbf24] shadow-[0_0_6px_rgba(245,158,11,0.5)]"></div>
                                            </div>
                                            <div class="p-1 bg-[#b91c1c] text-center">
                                                <span class="text-[3.5px] font-bold text-white uppercase tracking-wider block">KULINER</span>
                                            </div>
                                        </div>
                                    @elseif($bukuObj?->sampul && file_exists(public_path('storage/' . $bukuObj->sampul)))
                                        <img src="{{ asset('storage/' . $bukuObj->sampul) }}" alt="{{ $judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#2d4636] flex flex-col items-center justify-center text-white p-1 text-center">
                                            <span class="text-[4px] font-bold uppercase leading-tight">{{ \Illuminate\Support\Str::limit($judul, 15) }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Title & Info --}}
                                <div class="min-w-0 flex-1 overflow-hidden">
                                    <p class="text-[14px] font-bold text-[#111827] leading-snug truncate" title="{{ $judul }}">{{ $judul }}</p>
                                    <p class="text-[11.5px] text-[#6b7280] font-mono mt-0.5 truncate">ISBN: {{ $isbn }}</p>
                                    @if($subtext)
                                        <p class="text-[11px] text-slate-400 mt-0.5 truncate" title="{{ $subtext }}">{{ $subtext }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- 2. Kategori --}}
                        <td class="px-3 py-4.5 align-middle overflow-hidden">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9.5px] font-bold tracking-wider uppercase {{ $badgeBg }} {{ $badgeText }} whitespace-nowrap" title="{{ $kategori }}">
                                {{ $kategori }}
                            </span>
                        </td>

                        {{-- 3. Lokasi Rak --}}
                        <td class="px-2 py-4.5 align-middle text-center whitespace-nowrap overflow-hidden">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-mono font-semibold bg-amber-50 text-amber-800 border border-amber-200/70 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>{{ $rak }}</span>
                            </span>
                        </td>

                        {{-- 4. Stok Min --}}
                        <td class="px-1 py-4.5 align-middle text-center whitespace-nowrap overflow-hidden">
                            <span class="text-[13px] font-semibold text-slate-700 whitespace-nowrap">
                                {{ number_format($minStok) }} <span class="text-[11px] font-normal text-slate-400">Unit</span>
                            </span>
                        </td>

                        {{-- 5. Saat Ini --}}
                        <td class="px-1 py-4.5 align-middle text-center whitespace-nowrap overflow-hidden">
                            <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $saatIni <= 0 ? 'bg-red-500' : ($saatIni <= $minStok ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                                <span class="text-[13.5px] font-bold {{ $saatIni <= 0 ? 'text-red-600' : ($saatIni <= $minStok ? 'text-amber-600' : 'text-slate-800') }}">
                                    {{ number_format($saatIni) }}
                                </span>
                                <span class="text-[11.5px] text-slate-400 font-medium">Unit</span>
                            </div>
                        </td>

                        {{-- 6. Status --}}
                        <td class="px-1 py-4.5 align-middle text-center whitespace-nowrap overflow-hidden">
                            @if($statusStr === 'Habis')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-red-100 text-red-700 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                                    <span>Habis</span>
                                </span>
                            @elseif($statusStr === 'Stok Rendah')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-amber-100 text-amber-800 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                    <span>Stok Rendah</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-emerald-100 text-emerald-800 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 flex-shrink-0"></span>
                                    <span>Tersedia</span>
                                </span>
                            @endif
                        </td>

                        {{-- 7. Aksi --}}
                        <td class="pl-1 pr-6 py-4.5 align-middle text-center whitespace-nowrap">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                        onclick="toggleActionDropdown(event, 'dropdown-{{ $s->id }}')"
                                        title="Menu Aksi"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-[#6b7280] hover:text-[#111827] hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer mx-auto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div id="dropdown-{{ $s->id }}"
                                     class="action-dropdown hidden absolute right-0 @if($loop->remaining < 2) bottom-full mb-1.5 @else top-full mt-1.5 @endif w-40 bg-white rounded-2xl shadow-xl border border-[#eef0f2] overflow-hidden z-30 divide-y divide-[#f3f4f6]">
                                    
                                    {{-- Detail Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openDetailModal({{ json_encode($s) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-blue-600 hover:bg-blue-50/50 flex items-center gap-3 transition-colors">
                                            <svg class="w-[18px] h-[18px] text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span class="text-blue-600">Detail</span>
                                        </button>
                                    </div>

                                    {{-- Edit Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openEditModal({{ json_encode($s) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-[#d97706] hover:bg-amber-50/50 flex items-center gap-3 transition-colors">
                                            <svg class="w-[18px] h-[18px] text-[#d97706] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span class="text-[#d97706]">Edit</span>
                                        </button>
                                    </div>

                                    {{-- Delete Option --}}
                                    <div>
                                        <form method="POST" action="{{ route('stok.destroy', $s->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-[#dc2626] hover:bg-red-50/50 flex items-center gap-3 transition-colors">
                                                <svg class="w-[18px] h-[18px] text-[#dc2626] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span class="text-[#dc2626]">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-16 text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <p class="text-[13.5px] font-medium text-gray-400">Belum ada data stok buku yang sesuai dengan filter atau pencarian.</p>
                                @if(request('search') || request('kategori') || request('status'))
                                    <a href="{{ route('stok.index') }}" class="text-[12.5px] font-semibold text-[#2d4636] hover:underline mt-1">Reset Pencarian & Filter</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        @if($stok->total() > 0)
        <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-[#eef0f2] bg-white gap-3 select-none">
            {{-- Info --}}
            <p class="text-[12.5px] text-gray-500">
                Menampilkan
                <span class="font-semibold text-gray-700">{{ $stok->firstItem() }}</span>–<span class="font-semibold text-gray-700">{{ $stok->lastItem() }}</span>
                dari
                <span class="font-semibold text-gray-700">{{ $stok->total() }}</span>
                entri
            </p>

            {{-- Pagination Buttons --}}
            @if($stok->hasPages())
            <div class="flex items-center gap-1.5">
                {{-- Prev --}}
                @if($stok->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $stok->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-[#edf3ee] hover:border-[#2d4636] hover:text-[#2d4636] transition-colors bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach($stok->getUrlRange(1, $stok->lastPage()) as $page => $url)
                    @if($page == $stok->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2d4636] text-white text-[13px] font-semibold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 text-[13px] hover:bg-[#edf3ee] hover:border-[#2d4636] hover:text-[#2d4636] transition-colors bg-white">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($stok->hasMorePages())
                    <a href="{{ $stok->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-[#edf3ee] hover:border-[#2d4636] hover:text-[#2d4636] transition-colors bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- MODAL: EDIT STOK --}}
<div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Stok Buku</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui kuantitas stok masuk, keluar, dan batas minimum.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form id="edit-form" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div class="p-3 bg-[#fafbfa] rounded-xl border border-[#eef0f2] mb-2">
                    <span class="text-[11.5px] text-gray-500 font-medium block">Buku</span>
                    <h4 id="edit-judul-buku" class="text-[14px] font-bold text-gray-900 mt-0.5">-</h4>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Stok Masuk <span class="text-red-500">*</span></label>
                        <input type="number" id="edit-stok-masuk" name="stok_masuk" min="0" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Stok Keluar <span class="text-red-500">*</span></label>
                        <input type="number" id="edit-stok-keluar" name="stok_keluar" min="0" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Batas Minimum Stok <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-min-stok" name="min_stok" min="0" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: DETAIL STOK --}}
<div id="detail-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Informasi Stok</h3>
                        <p class="text-[12px] text-[#6b7280]">Rincian status dan pergerakan stok inventaris buku.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-[#fafbfa] border border-[#eef0f2] flex items-center justify-between">
                    <div>
                        <span id="detail-kategori" class="text-[11.5px] font-semibold text-[#2d4636] uppercase tracking-wider block">Kategori</span>
                        <h2 id="detail-judul-buku" class="text-[17px] font-bold text-[#111827] mt-0.5 leading-snug">Judul Buku</h2>
                    </div>
                    <span id="detail-status-badge" class="px-3 py-1 rounded-full text-[12px] font-semibold bg-[#e8f3ea] text-[#4a6b54]">Tersedia</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Lokasi Rak</span>
                        <span id="detail-rak" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Batas Minimum Stok</span>
                        <span id="detail-min-stok" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">0 Eks</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="p-3 bg-white rounded-xl border border-gray-100 text-center">
                        <span class="text-[11px] text-gray-500 font-medium block">Total Masuk</span>
                        <span id="detail-stok-masuk" class="text-[16px] font-bold text-gray-900 mt-1 block">0</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100 text-center">
                        <span class="text-[11px] text-gray-500 font-medium block">Total Keluar</span>
                        <span id="detail-stok-keluar" class="text-[16px] font-bold text-gray-900 mt-1 block">0</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100 text-center">
                        <span class="text-[11px] text-gray-500 font-medium block">Stok Saat Ini</span>
                        <span id="detail-stok-sisa" class="text-[16px] font-bold text-[#2d4636] mt-1 block">0</span>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-t border-[#eef0f2] flex items-center justify-end">
                <button type="button" onclick="closeDetailModal()" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function openDetailModal(stok) {
        var judul = stok.buku ? stok.buku.judul : 'Buku Inventaris';
        var kategori = (stok.buku && stok.buku.kategori) ? stok.buku.kategori.nama_kategori : 'Kategori Umum';
        var rak = (stok.buku && stok.buku.rak) ? stok.buku.rak.kode_rak : '-';

        document.getElementById('detail-judul-buku').textContent = judul;
        document.getElementById('detail-kategori').textContent = kategori;
        document.getElementById('detail-rak').textContent = rak;
        document.getElementById('detail-stok-masuk').textContent = stok.stok_masuk || 0;
        document.getElementById('detail-stok-keluar').textContent = stok.stok_keluar || 0;
        document.getElementById('detail-stok-sisa').textContent = stok.stok_sisa || 0;
        document.getElementById('detail-min-stok').textContent = (stok.min_stok || 0) + ' Unit';

        var badge = document.getElementById('detail-status-badge');
        var sisa = stok.stok_sisa || 0;
        var min = stok.min_stok || 0;
        if (sisa <= 0) {
            badge.textContent = 'Habis';
            badge.className = 'px-3 py-1 rounded-full text-[12px] font-semibold bg-red-100 text-red-600';
        } else if (sisa <= min) {
            badge.textContent = 'Stok Rendah';
            badge.className = 'px-3 py-1 rounded-full text-[12px] font-semibold bg-amber-100 text-amber-700';
        } else {
            badge.textContent = 'Tersedia';
            badge.className = 'px-3 py-1 rounded-full text-[12px] font-semibold bg-[#e8f3ea] text-[#4a6b54]';
        }

        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(stok) {
        document.getElementById('edit-form').action = '/stok/' + stok.id;
        document.getElementById('edit-judul-buku').textContent = stok.buku ? stok.buku.judul : 'Buku Inventaris';
        document.getElementById('edit-stok-masuk').value = stok.stok_masuk || 0;
        document.getElementById('edit-stok-keluar').value = stok.stok_keluar || 0;
        document.getElementById('edit-min-stok').value = stok.min_stok || 0;
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    // ---- Kebab Dropdown Logic ----
    function closeAllDropdowns() {
        document.querySelectorAll('.action-dropdown').forEach(function(el) {
            el.classList.add('hidden');
        });
    }

    function toggleActionDropdown(event, dropdownId) {
        event.stopPropagation();
        var target = document.getElementById(dropdownId);
        var isHidden = target.classList.contains('hidden');
        closeAllDropdowns();
        if (isHidden) {
            target.classList.remove('hidden');
        }
    }

    // Close all dropdowns when clicking outside
    document.addEventListener('click', function() {
        closeAllDropdowns();
    });

    // Close dropdowns on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeEditModal();
            closeAllDropdowns();
        }
    });
</script>

@endsection
