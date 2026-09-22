@extends('layouts.app')

@section('title', 'Manajemen Penulis - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">
    
    {{-- Header Section --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-[28px] font-bold text-[#111827] tracking-tight leading-tight">Manajemen Penulis</h1>
            <p class="text-[14px] text-[#6b7280] mt-1">Kelola data penulis dan kontribusi karya mereka dalam katalog.</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2.5 rounded-lg text-[13.5px] font-medium transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Penulis Baru</span>
        </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-5 flex items-center justify-between bg-[#edf7ee] border border-[#cbe6d0] text-[#22543d] px-4 py-3 rounded-xl text-[13.5px]">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-[#2d7a4d]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#2d7a4d] hover:text-[#1c452c]">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 flex items-center justify-between bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13.5px]">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13.5px]">
            <p class="font-semibold mb-1">Periksa kembali data Anda:</p>
            <ul class="list-disc list-inside text-[12.5px] space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats Cards (4 Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        
        {{-- Card 1: Total Penulis --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Total Penulis</span>
                <div class="w-8 h-8 rounded-full bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $totalPenulis }}</h3>
                <p class="text-[12px] font-medium text-[#16a34a] mt-2.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Aktif terdata</span>
                </p>
            </div>
        </div>

        {{-- Card 2: Penulis Paling Produktif --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Penulis Paling Produktif</span>
                <div class="w-8 h-8 rounded-full bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-4 h-4 text-[#16a34a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[20px] font-bold text-[#111827] leading-tight tracking-tight truncate" title="{{ $penulisProduktif?->nama_penulis ?? '-' }}">
                    {{ $penulisProduktif?->nama_penulis ?? '-' }}
                </h3>
                <p class="text-[12px] font-normal text-[#6b7280] mt-1.5">{{ $penulisProduktif?->buku_count ?? 0 }} Judul Buku</p>
            </div>
        </div>

        {{-- Card 3: Penulis Baru (Bulan Ini) --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Penulis Baru (Bulan Ini)</span>
                <div class="w-8 h-8 rounded-full bg-[#f3f4f6] flex items-center justify-center text-[#6b7280]">
                    <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $penulisBaru }}</h3>
                <div class="h-4 mt-2.5"></div>
            </div>
        </div>

        {{-- Card 4: Rata-rata Buku / Penulis --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Rata-rata Buku / Penulis</span>
                <div class="w-8 h-8 rounded-lg bg-[#f3f4f6] flex items-center justify-center text-[#6b7280]">
                    <svg class="w-4 h-4 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex items-baseline">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $avgBuku }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280] ml-1.5">Judul</span>
                </div>
                <div class="h-4 mt-2.5"></div>
            </div>
        </div>

    </div>

    {{-- Main Table Container: Daftar Penulis --}}
    <div class="bg-white rounded-2xl border border-[#eef0f2] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Card Header & Actions --}}
        <div class="px-6 py-4 border-b border-[#f3f4f6] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="text-[17px] font-bold text-[#111827]">Daftar Penulis</h2>
                @if(request('status') || request('sort') || request('negara'))
                    <a href="{{ route('penulis.index', request()->only('search')) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#edf7ee] hover:bg-emerald-100 text-[#2d7a4d] border border-[#cbe6d0] transition-colors" title="Klik untuk reset filter">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2d7a4d]"></span>
                        <span>Filter Aktif</span>
                        <svg class="w-3 h-3 text-[#2d7a4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
            
            <div class="flex items-center gap-3">
                {{-- Search Box --}}
                <form method="GET" action="{{ route('penulis.index') }}" class="relative">
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                    @if(request('sort'))   <input type="hidden" name="sort"   value="{{ request('sort') }}"> @endif
                    @if(request('negara')) <input type="hidden" name="negara" value="{{ request('negara') }}"> @endif

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penulis..." 
                           class="w-56 pl-8 pr-3 py-1.5 text-[13px] bg-[#f9fafb] border border-[#e5e7eb] rounded-lg focus:outline-none focus:border-[#2d4636] focus:bg-white transition-colors">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#9ca3af]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>

                {{-- Filter Button & Popover --}}
                <div class="relative">
                    <button type="button" 
                            onclick="toggleActionDropdown(event, 'penulis-filter-dropdown')"
                            class="p-2 border {{ (request('status') || request('sort') || request('negara')) ? 'border-[#3a5d43] bg-[#edf3ee] text-[#2d4636] font-semibold' : 'border-[#e5e7eb] text-[#6b7280] hover:bg-[#f9fafb]' }} rounded-lg transition-all cursor-pointer shadow-2xs relative group focus:outline-none" 
                            title="Filter Penulis">
                        <svg class="w-4 h-4 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        @if(request('status') || request('sort') || request('negara'))
                            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#2d7a4d] rounded-full ring-2 ring-white"></span>
                        @endif
                    </button>

                    <!-- Filter Dropdown Popover Panel -->
                    <div id="penulis-filter-dropdown" class="action-dropdown hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-5 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-[#edf3ee] text-[#2d4636] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                </div>
                                <h3 class="text-[13.5px] font-bold text-slate-800">Filter & Urutkan Penulis</h3>
                            </div>
                            <button type="button" onclick="closeAllDropdowns()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form method="GET" action="{{ route('penulis.index') }}" class="space-y-3.5">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            {{-- Filter Status Karya --}}
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Karya Buku</label>
                                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#3a5d43] focus:ring-1 focus:ring-[#3a5d43]">
                                    <option value="">Semua Penulis</option>
                                    <option value="has_book" {{ request('status') === 'has_book' ? 'selected' : '' }}>Memiliki Buku Terbit (> 0)</option>
                                    <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Belum Ada Buku (0)</option>
                                </select>
                            </div>

                            {{-- Filter Urutan / Sort --}}
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Urutan Data</label>
                                <select name="sort" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#3a5d43] focus:ring-1 focus:ring-[#3a5d43]">
                                    <option value="">Default (Terbaru)</option>
                                    <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama Penulis (A — Z)</option>
                                    <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama Penulis (Z — A)</option>
                                    <option value="buku_desc" {{ request('sort') === 'buku_desc' ? 'selected' : '' }}>Koleksi Buku (Terbanyak)</option>
                                    <option value="buku_asc" {{ request('sort') === 'buku_asc' ? 'selected' : '' }}>Koleksi Buku (Tersedikit)</option>
                                </select>
                            </div>

                            {{-- Filter Kewarganegaraan --}}
                            @if(isset($daftarNegara) && $daftarNegara->count() > 0)
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Kewarganegaraan</label>
                                <select name="negara" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#3a5d43] focus:ring-1 focus:ring-[#3a5d43]">
                                    <option value="">Semua Kewarganegaraan</option>
                                    @foreach($daftarNegara as $negaraItem)
                                        <option value="{{ $negaraItem }}" {{ request('negara') === $negaraItem ? 'selected' : '' }}>{{ $negaraItem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Buttons --}}
                            <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100 mt-2">
                                <a href="{{ route('penulis.index', request()->only('search')) }}" class="px-3 py-2 text-[12px] font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                                    Reset
                                </a>
                                <button type="submit" class="px-4 py-2 bg-[#2d4636] hover:bg-[#22362a] text-white text-[12.5px] font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto min-h-[340px] overflow-visible">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#fcfdfc] border-b border-[#f3f4f6]">
                        <th class="text-[11.5px] font-semibold text-[#6b7280] uppercase tracking-wider py-3.5 px-6">NAMA PENULIS</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-4 w-[28%]">Biografi Singkat (Genre)</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-4 w-[14%]">Jumlah Buku</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-4 w-[18%]">Kewarganegaraan</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-6 w-[12%] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f3f4f6]">
                    @forelse($penulis as $item)
                    @php
                        $count = $item->buku_count ?? 0;
                        $displayCount = $count;

                        // Avatar styling
                        if ($item->nama_penulis === 'Elena G. Wright') {
                            $avatarBg = 'bg-[#2d4636] text-white';
                            $avatarText = 'EW';
                            $hasPhoto = false;
                        } elseif ($item->nama_penulis === 'Raka Wijaya') {
                            $avatarBg = 'bg-[#2d4636] text-white';
                            $avatarText = 'RW';
                            $hasPhoto = true;
                        } elseif ($item->nama_penulis === 'Siti Markhamah') {
                            $avatarBg = 'bg-[#a7f3d0] text-[#065f46]';
                            $avatarText = 'SM';
                            $hasPhoto = false;
                        } elseif ($item->nama_penulis === 'John McCarthy') {
                            $avatarBg = 'bg-[#4b5563] text-white';
                            $avatarText = 'JM';
                            $hasPhoto = false;
                        } else {
                            $avatarBg = 'bg-[#e5e7eb] text-[#374151]';
                            $avatarText = $item->initials;
                            $hasPhoto = false;
                        }
                    @endphp
                    <tr class="hover:bg-[#fcfdfc] transition-colors group">
                        
                        {{-- Nama Penulis with Avatar --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                @if($hasPhoto)
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex-shrink-0 border border-gray-100">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="{{ $item->nama_penulis }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full {{ $avatarBg }} flex items-center justify-center font-bold text-[11.5px] flex-shrink-0">
                                        {{ $avatarText }}
                                    </div>
                                @endif
                                <span class="text-[13.5px] font-bold text-[#111827]">{{ $item->nama_penulis }}</span>
                            </div>
                        </td>

                        {{-- Biografi Singkat (Genre) --}}
                        <td class="px-4 py-4 align-middle">
                            <span class="text-[13px] text-[#4b5563] font-normal">{{ $item->bio ?: '-' }}</span>
                        </td>

                        {{-- Jumlah Buku --}}
                        <td class="px-4 py-4 align-middle">
                            <span class="text-[13.5px] text-[#111827] font-normal">{{ $displayCount }}</span>
                        </td>

                        {{-- Kewarganegaraan Badge --}}
                        <td class="px-4 py-4 align-middle">
                            @if(!empty($item->kewarganegaraan))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[12px] font-medium bg-slate-50 text-slate-700 border border-slate-200/80 shadow-2xs">
                                    @if($item->flag_url)
                                        <img src="{{ $item->flag_url }}" 
                                             alt="{{ $item->kewarganegaraan }}" 
                                             class="w-4 h-3 object-cover rounded-[2px] shadow-2xs border border-slate-200/90 shrink-0" 
                                             loading="lazy" 
                                             onerror="this.style.display='none'">
                                    @else
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                    <span>{{ $item->kewarganegaraan }}</span>
                                </span>
                            @else
                                <span class="text-[13px] text-[#9ca3af]">-</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 align-middle text-right">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                        onclick="toggleActionDropdown(event, 'dropdown-{{ $item->id }}')"
                                        title="Menu Aksi"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-[#6b7280] hover:text-[#111827] hover:bg-gray-100 transition-colors focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div id="dropdown-{{ $item->id }}"
                                     class="action-dropdown hidden absolute right-0 mt-1.5 w-40 bg-white rounded-2xl shadow-xl border border-[#eef0f2] overflow-hidden z-30 divide-y divide-[#f3f4f6]">
                                    
                                    {{-- Detail Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openDetailModal({{ json_encode($item) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-blue-600 hover:bg-blue-50/50 flex items-center gap-3 transition-colors">
                                            <svg class="w-[18px] h-[18px] text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span class="text-blue-600">Detail</span>
                                        </button>
                                    </div>

                                    {{-- Edit Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openEditModal({{ json_encode($item) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-[#d97706] hover:bg-amber-50/50 flex items-center gap-3 transition-colors">
                                            <svg class="w-[18px] h-[18px] text-[#d97706] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span class="text-[#d97706]">Edit</span>
                                        </button>
                                    </div>

                                    {{-- Delete Option --}}
                                    <div>
                                        <form method="POST" action="{{ route('penulis.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penulis \'{{ $item->nama_penulis }}\'?')">
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
                        <td colspan="5" class="px-6 py-16 text-center text-[#6b7280] text-[14px]">
                            Belum ada data penulis yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-[#f3f4f6] bg-white select-none">
            <p class="text-[13px] text-[#6b7280]">
                Menampilkan 1–4 dari 86 penulis
            </p>
            <div class="flex items-center gap-1.5">
                {{-- Prev --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center text-[#9ca3af] hover:text-[#374151] rounded-md transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                {{-- Page 1 (Active) --}}
                <span class="w-7 h-7 flex items-center justify-center rounded-md bg-[#2d4636] text-white text-[13px] font-semibold shadow-sm">1</span>

                {{-- Page 2 --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md text-[#4b5563] text-[13px] hover:bg-[#f3f4f6] transition-colors">2</button>

                {{-- Page 3 --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md text-[#4b5563] text-[13px] hover:bg-[#f3f4f6] transition-colors">3</button>
                
                {{-- Dots --}}
                <span class="px-1 text-[#9ca3af] text-[13px] tracking-widest">...</span>
                
                {{-- Page 9 --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md text-[#4b5563] text-[13px] hover:bg-[#f3f4f6] transition-colors">9</button>

                {{-- Next --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md text-[#4b5563] hover:text-[#111827] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

    </div>
</div>

{{-- MODAL: TAMBAH PENULIS BARU --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] rounded-t-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Tambah Penulis Baru</h3>
                        <p class="text-[12px] text-[#6b7280]">Lengkapi profil dan data penulis baru.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('penulis.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Lengkap Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_penulis" required placeholder="Contoh: Elena G. Wright" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kewarganegaraan Penulis</label>
                    <div class="relative" id="create-country-wrapper">
                        <input type="hidden" name="kewarganegaraan" id="create-kewarganegaraan" value="Indonesia">
                        <button type="button" 
                                id="create-country-btn"
                                onclick="toggleCountryDropdown('create')"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 text-[13px] bg-white border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] cursor-pointer text-left transition-colors">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <img id="create-selected-flag" src="https://flagcdn.com/w40/id.png" alt="Indonesia" class="w-5 h-3.5 object-cover rounded-[2px] shadow-2xs border border-gray-200 shrink-0">
                                <span id="create-selected-name" class="font-medium text-[#111827] truncate">Indonesia</span>
                            </div>
                            <svg class="w-4 h-4 text-[#6b7280] shrink-0 transition-transform duration-200" id="create-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="create-country-dropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-[#e5e7eb] rounded-xl shadow-2xl z-50 overflow-hidden">
                            <div class="p-2 border-b border-[#f3f4f6] bg-[#f9fafb]">
                                <div class="relative">
                                    <input type="text" 
                                           id="create-country-search" 
                                           oninput="filterCountryList('create')" 
                                           placeholder="Cari negara..." 
                                           class="w-full pl-8 pr-3 py-1.5 text-[12.5px] bg-white border border-[#e5e7eb] rounded-lg focus:outline-none focus:border-[#2d4636]">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#9ca3af]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div id="create-country-list" class="max-h-52 overflow-y-auto p-1 divide-y divide-gray-50">
                                @foreach($negaraList as $c)
                                    <button type="button"
                                            onclick="selectCountry('create', '{{ $c['name'] }}', '{{ $c['flag_url'] }}')"
                                            class="country-option w-full flex items-center justify-between px-3 py-2 rounded-lg text-left text-[12.5px] hover:bg-[#f3f4f6] transition-colors cursor-pointer group {{ $c['code'] === 'id' ? 'bg-[#edf3ee] font-semibold' : '' }}"
                                            data-name="{{ strtolower($c['name']) }}">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $c['flag_url'] }}" alt="{{ $c['name'] }}" class="w-5 h-3.5 object-cover rounded-[2px] shadow-2xs border border-gray-200 shrink-0" loading="lazy">
                                            <span class="text-[#374151] group-hover:text-[#111827]">{{ $c['name'] }}</span>
                                        </div>
                                        <span class="check-icon text-[#2d4636] {{ $c['code'] === 'id' ? '' : 'hidden' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-[#6b7280] mt-1">Pilih bendera negara asal atau kewarganegaraan penulis.</p>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Biografi Singkat (Genre)</label>
                    <textarea name="bio" rows="3" placeholder="Contoh: Fiksi & Sastra Kontemporer..." class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                        Simpan Penulis
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: EDIT PENULIS --}}
<div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] rounded-t-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Penulis</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui informasi profil penulis.</p>
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
                
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Lengkap Penulis <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-nama" name="nama_penulis" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kewarganegaraan Penulis</label>
                    <div class="relative" id="edit-country-wrapper">
                        <input type="hidden" name="kewarganegaraan" id="edit-kewarganegaraan" value="">
                        <button type="button" 
                                id="edit-country-btn"
                                onclick="toggleCountryDropdown('edit')"
                                class="w-full flex items-center justify-between px-3.5 py-2.5 text-[13px] bg-white border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] cursor-pointer text-left transition-colors">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <img id="edit-selected-flag" src="" alt="" class="w-5 h-3.5 object-cover rounded-[2px] shadow-2xs border border-gray-200 shrink-0 hidden">
                                <span id="edit-selected-name" class="font-medium text-[#111827] truncate">-- Pilih Kewarganegaraan --</span>
                            </div>
                            <svg class="w-4 h-4 text-[#6b7280] shrink-0 transition-transform duration-200" id="edit-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div id="edit-country-dropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-[#e5e7eb] rounded-xl shadow-2xl z-50 overflow-hidden">
                            <div class="p-2 border-b border-[#f3f4f6] bg-[#f9fafb]">
                                <div class="relative">
                                    <input type="text" 
                                           id="edit-country-search" 
                                           oninput="filterCountryList('edit')" 
                                           placeholder="Cari negara..." 
                                           class="w-full pl-8 pr-3 py-1.5 text-[12.5px] bg-white border border-[#e5e7eb] rounded-lg focus:outline-none focus:border-[#2d4636]">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-[#9ca3af]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div id="edit-country-list" class="max-h-52 overflow-y-auto p-1 divide-y divide-gray-50">
                                @foreach($negaraList as $c)
                                    <button type="button"
                                            onclick="selectCountry('edit', '{{ $c['name'] }}', '{{ $c['flag_url'] }}')"
                                            class="country-option w-full flex items-center justify-between px-3 py-2 rounded-lg text-left text-[12.5px] hover:bg-[#f3f4f6] transition-colors cursor-pointer group"
                                            data-name="{{ strtolower($c['name']) }}">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $c['flag_url'] }}" alt="{{ $c['name'] }}" class="w-5 h-3.5 object-cover rounded-[2px] shadow-2xs border border-gray-200 shrink-0" loading="lazy">
                                            <span class="text-[#374151] group-hover:text-[#111827]">{{ $c['name'] }}</span>
                                        </div>
                                        <span class="check-icon text-[#2d4636] hidden">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Biografi Singkat (Genre)</label>
                    <textarea id="edit-bio" name="bio" rows="3" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
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

{{-- MODAL: DETAIL PENULIS --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Profil Penulis</h3>
                        <p class="text-[12px] text-[#6b7280]">Informasi profil dan karya penulis.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-[#fafbfa] border border-[#eef0f2] flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full bg-[#edf3ee] text-[#2d4636] font-bold text-[16px] flex items-center justify-center flex-shrink-0">
                        <span id="detail-avatar">P</span>
                    </div>
                    <div>
                        <h2 id="detail-nama" class="text-[18px] font-bold text-[#111827] leading-tight">Nama Penulis</h2>
                        <div id="detail-kewarganegaraan-badge" class="mt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11.5px] font-medium bg-[#e5e7eb] text-[#4b5563]">Indonesia</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Kewarganegaraan</span>
                        <div id="detail-kewarganegaraan" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</div>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Total Karya Terdaftar</span>
                        <span id="detail-jumlah-buku" class="text-[13.5px] font-bold text-[#2d4636] mt-0.5 block">0 Buku</span>
                    </div>
                </div>

                <div class="p-3 bg-white rounded-xl border border-gray-100">
                    <span class="text-[11.5px] text-gray-500 font-medium block">Biografi Singkat / Genre</span>
                    <p id="detail-bio" class="text-[13px] text-gray-700 mt-1 leading-relaxed">-</p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-t border-[#eef0f2] rounded-b-2xl flex items-center justify-end">
                <button type="button" onclick="closeDetailModal()" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const negaraList = @json($negaraList);

    function openCreateModal() {
        selectCountry('create', 'Indonesia', 'https://flagcdn.com/w40/id.png');
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
        closeCountryDropdowns();
    }

    function openDetailModal(author) {
        var name = author.nama_penulis || '-';
        document.getElementById('detail-nama').textContent = name;
        document.getElementById('detail-avatar').textContent = name.substring(0, 1).toUpperCase();
        
        var kw = (author.kewarganegaraan || '').trim();
        var flagUrl = author.flag_url;
        if (!flagUrl && kw) {
            var found = negaraList.find(c => c.name.toLowerCase() === kw.toLowerCase() || kw.toLowerCase().includes(c.name.toLowerCase()));
            if (found) flagUrl = found.flag_url;
        }

        var flagImgHtml = flagUrl ? `<img src="${flagUrl}" alt="${kw}" class="w-4 h-3 object-cover rounded-[2px] shadow-2xs border border-gray-200 inline-block shrink-0 mr-1.5">` : '';

        if (kw) {
            document.getElementById('detail-kewarganegaraan-badge').innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11.5px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#d2e2d5]">${flagImgHtml}<span>${kw}</span></span>`;
            document.getElementById('detail-kewarganegaraan').innerHTML = `<span class="inline-flex items-center">${flagImgHtml}<span>${kw}</span></span>`;
        } else {
            document.getElementById('detail-kewarganegaraan-badge').innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11.5px] font-medium bg-[#e5e7eb] text-[#4b5563]">Warga Negara</span>`;
            document.getElementById('detail-kewarganegaraan').innerHTML = '-';
        }

        document.getElementById('detail-jumlah-buku').textContent = (author.buku_count !== undefined ? author.buku_count : 0) + ' Judul Buku';
        document.getElementById('detail-bio').textContent = author.bio || 'Tidak ada biografi singkat.';
        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(author) {
        document.getElementById('edit-form').action = '/penulis/' + author.id;
        document.getElementById('edit-nama').value = author.nama_penulis || '';
        
        var kw = (author.kewarganegaraan || '').trim();
        var flagUrl = author.flag_url;
        if (!flagUrl && kw) {
            var found = negaraList.find(c => c.name.toLowerCase() === kw.toLowerCase() || kw.toLowerCase().includes(c.name.toLowerCase()));
            if (found) {
                kw = found.name;
                flagUrl = found.flag_url;
            }
        }
        selectCountry('edit', kw, flagUrl);

        document.getElementById('edit-bio').value = author.bio || '';
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
        closeCountryDropdowns();
    }

    // ---- Custom Country Dropdown Logic ----
    function toggleCountryDropdown(type) {
        const dropdown = document.getElementById(type + '-country-dropdown');
        const chevron = document.getElementById(type + '-chevron');
        if (!dropdown) return;
        const isHidden = dropdown.classList.contains('hidden');
        
        closeCountryDropdowns();

        if (isHidden) {
            dropdown.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            const searchInput = document.getElementById(type + '-country-search');
            if (searchInput) {
                searchInput.value = '';
                filterCountryList(type);
                setTimeout(() => searchInput.focus(), 50);
            }
        }
    }

    function closeCountryDropdowns() {
        ['create', 'edit'].forEach(t => {
            const d = document.getElementById(t + '-country-dropdown');
            const c = document.getElementById(t + '-chevron');
            if (d) d.classList.add('hidden');
            if (c) c.classList.remove('rotate-180');
        });
    }

    function selectCountry(type, name, flagUrl) {
        const hiddenInput = document.getElementById(type + '-kewarganegaraan');
        const nameEl = document.getElementById(type + '-selected-name');
        const flagEl = document.getElementById(type + '-selected-flag');
        const dropdown = document.getElementById(type + '-country-dropdown');
        const chevron = document.getElementById(type + '-chevron');

        if (hiddenInput) hiddenInput.value = name || '';
        if (nameEl) nameEl.textContent = name || '-- Pilih Kewarganegaraan --';

        if (flagEl) {
            if (!flagUrl && name) {
                const found = negaraList.find(c => c.name.toLowerCase() === name.toLowerCase() || name.toLowerCase().includes(c.name.toLowerCase()));
                if (found) flagUrl = found.flag_url;
            }
            if (flagUrl) {
                flagEl.src = flagUrl;
                flagEl.alt = name;
                flagEl.classList.remove('hidden');
            } else {
                flagEl.src = '';
                flagEl.classList.add('hidden');
            }
        }

        const listEl = document.getElementById(type + '-country-list');
        if (listEl) {
            listEl.querySelectorAll('.country-option').forEach(btn => {
                const check = btn.querySelector('.check-icon');
                const optName = (btn.getAttribute('data-name') || '').toLowerCase();
                const targetName = (name || '').toLowerCase();
                if (optName === targetName) {
                    if (check) check.classList.remove('hidden');
                    btn.classList.add('bg-[#edf3ee]', 'font-semibold');
                } else {
                    if (check) check.classList.add('hidden');
                    btn.classList.remove('bg-[#edf3ee]', 'font-semibold');
                }
            });
        }

        if (dropdown) dropdown.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }

    function filterCountryList(type) {
        const searchEl = document.getElementById(type + '-country-search');
        const list = document.getElementById(type + '-country-list');
        if (!searchEl || !list) return;
        const query = searchEl.value.trim().toLowerCase();
        const options = list.querySelectorAll('.country-option');
        options.forEach(opt => {
            const name = opt.getAttribute('data-name') || '';
            if (name.includes(query)) {
                opt.style.display = 'flex';
            } else {
                opt.style.display = 'none';
            }
        });
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
        closeCountryDropdowns();
        if (isHidden) {
            target.classList.remove('hidden');
        }
    }

    // Close all dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        closeAllDropdowns();
        
        ['create', 'edit'].forEach(type => {
            const wrapper = document.getElementById(type + '-country-wrapper');
            const dropdown = document.getElementById(type + '-country-dropdown');
            const chevron = document.getElementById(type + '-chevron');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        });
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDetailModal();
            closeAllDropdowns();
            closeCountryDropdowns();
        }
    });
</script>
@endsection
