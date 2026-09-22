@extends('layouts.app')

@section('title', 'Manajemen Rak Buku - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">
    
    {{-- Header Section --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-[28px] font-bold text-[#111827] tracking-tight leading-tight">Manajemen Rak Buku</h1>
            <p class="text-[14px] text-[#6b7280] mt-1">Kelola lokasi penyimpanan dan kapasitas rak buku</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2.5 rounded-lg text-[13.5px] font-medium transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Rak</span>
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

    {{-- Stats Cards (4 Cards - Data Riil Database) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">

        {{-- Card 1: Total Rak --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#edf3ee] flex items-center justify-center text-[#2d4636]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Semua Area</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Total Rak</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $totalRak }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">unit</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Rak Penuh --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#ef4444]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Perhatian</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Rak Penuh</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $rakPenuh }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">unit</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Kapasitas Tersedia --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Tersedia</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Kapasitas Tersedia</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ number_format($kapasitasTersedia, 0, ',', '.') }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">slot</span>
                </div>
            </div>
        </div>

        {{-- Card 4: Kondisi Rak --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#f1f5f9] flex items-center justify-center text-[#475569]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Inspeksi</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Kondisi Rak</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $persenKapasitasTersedia }}%</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">Baik</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Container --}}
    <div class="bg-white rounded-2xl border border-[#eef0f2] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-visible">
        
        {{-- Toolbar Actions Header --}}
        <div class="px-6 py-4 border-b border-[#f3f4f6] flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">

                {{-- 1. Filter Button & Popover --}}
                <div class="relative">
                    <button type="button" 
                            onclick="toggleActionDropdown(event, 'rak-filter-dropdown')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 border {{ (request('status') || request('kategori') || request('lokasi')) ? 'border-[#2d4636] bg-[#edf7ee] text-[#2d4636] font-semibold' : 'border-[#e5e7eb] bg-white text-[#374151]' }} hover:bg-[#f9fafb] rounded-lg text-[13px] font-medium transition-colors cursor-pointer shadow-sm focus:outline-none"
                            title="Filter Data Rak">
                        <svg class="w-3.5 h-3.5 {{ (request('status') || request('kategori') || request('lokasi')) ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span>Filter</span>
                        @if(request('status') || request('kategori') || request('lokasi'))
                            <span class="w-2 h-2 rounded-full bg-[#2d7a4d]"></span>
                        @endif
                    </button>

                    <!-- Filter Dropdown Popover Panel -->
                    <div id="rak-filter-dropdown" class="action-dropdown hidden absolute left-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-5 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-[#edf3ee] text-[#2d4636] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                </div>
                                <h3 class="text-[13.5px] font-bold text-slate-800">Filter Data Rak</h3>
                            </div>
                            <button type="button" onclick="closeAllDropdowns()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form method="GET" action="{{ route('rak.index') }}" class="space-y-3.5">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('sort'))   <input type="hidden" name="sort"   value="{{ request('sort') }}"> @endif

                            {{-- Filter Status Kapasitas --}}
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Kapasitas</label>
                                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                    <option value="">Semua Status</option>
                                    <option value="terisi" {{ request('status') === 'terisi' ? 'selected' : '' }}>Terisi Buku (> 0)</option>
                                    <option value="penuh" {{ request('status') === 'penuh' ? 'selected' : '' }}>Rak Penuh (Maksimal)</option>
                                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Rak Kosong / Tersedia (0 Buku)</option>
                                </select>
                            </div>

                            {{-- Filter Kategori Buku --}}
                            @if(isset($daftarKategori) && $daftarKategori->count() > 0)
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Kategori Buku</label>
                                <select name="kategori" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                    <option value="">Semua Kategori</option>
                                    @foreach($daftarKategori as $kat)
                                        <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Filter Lokasi / Area --}}
                            @if(isset($daftarLokasi) && $daftarLokasi->count() > 0)
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Lokasi / Area</label>
                                <select name="lokasi" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                    <option value="">Semua Lokasi</option>
                                    @foreach($daftarLokasi as $lok)
                                        <option value="{{ $lok }}" {{ request('lokasi') === $lok ? 'selected' : '' }}>{{ $lok }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="pt-2.5 flex items-center justify-between gap-2 border-t border-slate-100 mt-2">
                                <a href="{{ route('rak.index', request()->only(['search', 'sort'])) }}" class="px-3 py-2 text-[12px] font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                                    Reset Filter
                                </a>
                                <button type="submit" class="px-4 py-2 bg-[#2d4636] hover:bg-[#22362a] text-white text-[12.5px] font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- 2. Urutkan Button & Popover --}}
                @php
                    $sortLabels = [
                        'terbaru'        => 'Rak Terbaru',
                        'terlama'        => 'Rak Terlama',
                        'kode_asc'       => 'Kode Rak (A — Z)',
                        'kode_desc'      => 'Kode Rak (Z — A)',
                        'buku_desc'      => 'Buku Terbanyak',
                        'buku_asc'       => 'Buku Tersedikit',
                        'kapasitas_desc' => 'Kapasitas Terbesar',
                        'kapasitas_asc'  => 'Kapasitas Terkecil',
                    ];
                    $activeSort = request('sort');
                    $activeSortLabel = $sortLabels[$activeSort] ?? null;
                @endphp
                <div class="relative">
                    <button type="button" 
                            onclick="toggleActionDropdown(event, 'rak-sort-dropdown')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 border {{ $activeSortLabel ? 'border-[#2d4636] bg-[#edf7ee] text-[#2d4636] font-semibold' : 'border-[#e5e7eb] bg-white text-[#374151]' }} hover:bg-[#f9fafb] rounded-lg text-[13px] font-medium transition-colors cursor-pointer shadow-sm focus:outline-none"
                            title="Urutkan Data Rak">
                        <svg class="w-3.5 h-3.5 {{ $activeSortLabel ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        <span>Urutkan{{ $activeSortLabel ? ': ' . $activeSortLabel : '' }}</span>
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Sort Dropdown Popover Menu -->
                    <div id="rak-sort-dropdown" class="action-dropdown hidden absolute left-0 top-full mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-slate-200/90 py-2 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            Urutkan Berdasarkan
                        </div>
                        @foreach($sortLabels as $key => $label)
                            @php
                                $isActive = ($activeSort === $key) || (!$activeSort && $key === 'terlama');
                            @endphp
                            <a href="{{ route('rak.index', array_merge(request()->query(), ['sort' => $key])) }}" 
                               class="flex items-center justify-between px-4 py-2.5 text-[12.5px] {{ $isActive ? 'bg-[#edf7ee] text-[#2d4636] font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium' }} transition-colors">
                                <span>{{ $label }}</span>
                                @if($isActive)
                                    <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- 3. Filter Aktif Reset Badge --}}
                @if(request('status') || request('kategori') || request('lokasi') || request('sort'))
                    <a href="{{ route('rak.index', request()->only('search')) }}" 
                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11.5px] font-semibold bg-[#edf7ee] hover:bg-emerald-100 text-[#2d7a4d] border border-[#cbe6d0] transition-colors" 
                       title="Klik untuk reset filter & pengurutan">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2d7a4d]"></span>
                        <span>Filter Aktif</span>
                        <svg class="w-3 h-3 text-[#2d7a4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="w-full overflow-x-auto overflow-y-visible [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <thead>
                    <tr class="bg-[#f9faf9] border-b border-slate-200/80">
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-6 w-[16%]">Kode Rak</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-4 w-[24%]">Lokasi/Area</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-4 w-[22%]">Kategori Buku</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-4 w-[22%]">Kapasitas</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-2 w-[10%] text-center">Status</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-4 w-[6%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rak as $item)
                    @php
                        // Real calculation based on database records
                        $max = $item->kapasitas_max ?: 100;
                        $currentBuku = $item->buku_count ?? 0;
                        $percentage = min(100, round(($currentBuku / max(1, $max)) * 100));

                        if ($currentBuku >= $max) {
                            $statusType = 'Penuh';
                            $textColor = 'text-[#e02424]';
                            $barBg = 'bg-[#e02424]';
                            $badgeClass = 'bg-[#fee2e2] text-[#b91c1c] border border-red-200';
                        } elseif ($currentBuku > 0) {
                            $statusType = 'Aktif';
                            $textColor = 'text-[#2d4636]';
                            $barBg = 'bg-[#2d4636]';
                            $badgeClass = 'bg-[#edf7ee] text-[#2d4636] border border-[#cbe6d0]';
                        } else {
                            $statusType = 'Tersedia';
                            $textColor = 'text-[#4b5563]';
                            $barBg = 'bg-[#9ca3af]';
                            $badgeClass = 'bg-gray-100 text-gray-600 border border-gray-200';
                        }
                    @endphp
                    <tr class="hover:bg-[#fbfcfb] transition-colors group">
                        
                        {{-- Kode Rak --}}
                        <td class="px-6 py-4 align-middle">
                            <span class="text-[13.5px] font-bold text-[#111827] block">{{ $item->kode_rak }}</span>
                        </td>

                        {{-- Lokasi/Area --}}
                        <td class="px-4 py-4 align-middle">
                            <span class="text-[13px] text-[#4b5563] font-medium block truncate" title="{{ $item->lokasi }}">{{ $item->lokasi }}</span>
                        </td>

                        {{-- Kategori Buku --}}
                        <td class="px-4 py-4 align-middle">
                            <span class="text-[13px] text-[#4b5563] font-medium block truncate" title="{{ $item->kategori_buku ?: '-' }}">{{ $item->kategori_buku ?: '-' }}</span>
                        </td>

                        {{-- Kapasitas with Progress Bar --}}
                        <td class="px-4 py-4 align-middle">
                            <div class="w-full max-w-[190px]">
                                <div class="flex items-center justify-between text-[11.5px] mb-1.5 font-medium">
                                    <span class="{{ $textColor }} font-bold">{{ $currentBuku }} Buku</span>
                                    <span class="text-[#9ca3af] font-normal">{{ $max }} Max</span>
                                </div>
                                <div class="w-full bg-[#e5e7eb] rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $barBg }} h-1.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-2 py-4 align-middle text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                {{ $statusType }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-4 align-middle text-center">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                        onclick="toggleActionDropdown(event, 'dropdown-{{ $item->id }}')"
                                        title="Menu Aksi"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-[#6b7280] hover:text-[#111827] hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer mx-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div id="dropdown-{{ $item->id }}"
                                     class="action-dropdown hidden absolute right-0 mt-1.5 w-40 bg-white rounded-2xl shadow-xl border border-[#eef0f2] overflow-hidden z-30 divide-y divide-[#f3f4f6]"
                                     onclick="event.stopPropagation()">
                                    
                                    {{-- Detail Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openDetailModal({{ json_encode($item) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13px] font-medium text-blue-600 hover:bg-blue-50/50 flex items-center gap-2.5 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Detail</span>
                                        </button>
                                    </div>

                                    {{-- Edit Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openEditModal({{ json_encode($item) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13px] font-medium text-[#d97706] hover:bg-amber-50/50 flex items-center gap-2.5 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 text-[#d97706] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span>Edit</span>
                                        </button>
                                    </div>

                                    {{-- Delete Option --}}
                                    <div>
                                        <form method="POST" action="{{ route('rak.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rak \'{{ $item->kode_rak }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full text-left px-4 py-2.5 text-[13px] font-medium text-[#dc2626] hover:bg-red-50/50 flex items-center gap-2.5 transition-colors cursor-pointer">
                                                <svg class="w-4 h-4 text-[#dc2626] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-[#6b7280] text-[14px]">
                            Belum ada data rak yang sesuai dengan filter atau pencarian Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        @if($rak->hasPages() || $rak->total() > 0)
        <div class="flex flex-col sm:flex-row items-center justify-between px-7 py-4 border-t border-[#f3f4f6] bg-white gap-3 select-none">
            <p class="text-[12.5px] text-[#6b7280]">
                @if($rak->total() > 0)
                    Menampilkan {{ $rak->firstItem() }}–{{ $rak->lastItem() }} dari {{ $rak->total() }} rak
                @else
                    Tidak ada data rak
                @endif
            </p>
            
            <div>
                {{ $rak->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

{{-- MODAL: TAMBAH RAK BARU --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Tambah Rak Baru</h3>
                        <p class="text-[12px] text-[#6b7280]">Lengkapi kode lokasi dan kapasitas rak.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('rak.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[12px] font-semibold text-[#374151]">Kode Rak <span class="text-red-500">*</span></label>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#2d7a4d] bg-[#edf7ee] px-2.5 py-0.5 rounded-full border border-[#cbe6d0]">
                            <svg class="w-3 h-3 text-[#2d7a4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Otomatis Tersusun
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text" id="create-kode-rak" name="kode_rak" required readonly value="{{ $nextKodeRakDefault }}" class="w-full px-3.5 py-2.5 text-[14px] font-bold tracking-wide text-[#2d4636] bg-[#f8faf8] border border-[#d1d5db] rounded-lg focus:outline-none cursor-default shadow-xs select-all">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Kode rak otomatis tersusun berurutan dari data awal dan menyesuaikan kategori terpilih.</span>
                    </p>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kategori Buku <span class="text-gray-400 font-normal">(Sesuai Master Kategori Sistem)</span></label>
                    <div class="relative">
                        <select id="create-kategori" name="kategori_buku" onchange="handleCategoryChange(this.value)" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                            <option value="">-- Pilih Kategori Buku (Opsional) --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }} {{ $kat->kode_kategori ? '('.$kat->kode_kategori.')' : '' }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi / Area <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" required placeholder="Contoh: Lantai 1 - Sayap Kiri" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kapasitas Maksimal (Buku) <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas_max" required min="1" placeholder="Contoh: 100" value="100" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                        Simpan Rak
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: EDIT RAK --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Rak</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui informasi dan kapasitas rak buku.</p>
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
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kode Rak <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-kode-rak" name="kode_rak" required class="w-full px-3.5 py-2 text-[13px] font-semibold border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kategori Buku <span class="text-gray-400 font-normal">(Sesuai Master Kategori Sistem)</span></label>
                    <div class="relative">
                        <select id="edit-kategori" name="kategori_buku" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                            <option value="">-- Pilih Kategori Buku (Opsional) --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }} {{ $kat->kode_kategori ? '('.$kat->kode_kategori.')' : '' }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi / Area <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-lokasi" name="lokasi" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kapasitas Maksimal (Buku) <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-kapasitas" name="kapasitas_max" required min="1" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
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

{{-- MODAL: DETAIL RAK --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Informasi Rak</h3>
                        <p class="text-[12px] text-[#6b7280]">Informasi kapasitas dan lokasi penempatan rak buku.</p>
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
                        <span class="text-[11.5px] text-gray-500 font-medium block">Kode Rak</span>
                        <h2 id="detail-kode-rak" class="text-[20px] font-bold text-[#2d4636] mt-0.5">R-A1</h2>
                    </div>
                    <span id="detail-status-badge" class="px-3 py-1 rounded-md text-[12px] font-semibold bg-[#dcfce7] text-[#166534]">Tersedia</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Lokasi Fisik / Baris</span>
                        <span id="detail-lokasi" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Kategori Rak</span>
                        <span id="detail-kategori" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Buku Tersimpan</span>
                        <span id="detail-terisi" class="text-[13.5px] font-bold text-gray-900 mt-0.5 block">0 Buku</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Kapasitas Maksimal</span>
                        <span id="detail-kapasitas-max" class="text-[13.5px] font-bold text-[#2d4636] mt-0.5 block">0 Buku</span>
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
    const kodeRakMap = @json($kodeRakMap ?? []);
    const defaultKodeRak = @json($nextKodeRakDefault ?? 'RAK-A01');

    function handleCategoryChange(selectedKat) {
        const input = document.getElementById('create-kode-rak');
        if (input) {
            input.value = kodeRakMap[selectedKat] || defaultKodeRak;
        }
    }

    function openCreateModal() {
        const select = document.getElementById('create-kategori');
        if (select) {
            select.value = '';
            handleCategoryChange('');
        }
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function openDetailModal(rak) {
        document.getElementById('detail-kode-rak').textContent = rak.kode_rak || '-';
        document.getElementById('detail-lokasi').textContent = rak.lokasi || '-';
        document.getElementById('detail-kategori').textContent = rak.kategori_buku || 'Umum';
        
        var count = rak.buku_count !== undefined ? rak.buku_count : 0;
        var maxCap = rak.kapasitas_max || 100;
        document.getElementById('detail-terisi').textContent = count + ' Buku';
        document.getElementById('detail-kapasitas-max').textContent = maxCap + ' Buku';

        var badge = document.getElementById('detail-status-badge');
        if (count >= maxCap) {
            badge.textContent = 'Penuh';
            badge.className = 'px-3 py-1 rounded-md text-[12px] font-semibold bg-[#fee2e2] text-[#b91c1c]';
        } else {
            badge.textContent = 'Tersedia';
            badge.className = 'px-3 py-1 rounded-md text-[12px] font-semibold bg-[#dcfce7] text-[#166534]';
        }

        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(rak) {
        document.getElementById('edit-form').action = '/rak/' + rak.id;
        document.getElementById('edit-kode-rak').value = rak.kode_rak || '';
        document.getElementById('edit-lokasi').value = rak.lokasi || '';
        
        var select = document.getElementById('edit-kategori');
        if (select) {
            select.value = rak.kategori_buku || '';
            // Jika kategori rak lama tidak ada di master kategori_buku (misal input manual lama), buat option khusus agar data tetap terpilih
            if (rak.kategori_buku && select.value !== rak.kategori_buku) {
                var opt = document.createElement('option');
                opt.value = rak.kategori_buku;
                opt.textContent = rak.kategori_buku + ' (Khusus)';
                opt.selected = true;
                select.appendChild(opt);
            }
        }

        document.getElementById('edit-kapasitas').value = rak.kapasitas_max || '';
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

    // Close modals on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDetailModal();
            closeAllDropdowns();
        }
    });
</script>
@endsection
