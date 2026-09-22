@extends('layouts.app')

@section('title', 'Manajemen Penerbit - Cahaya Buku')
@section('search_placeholder', 'Cari penerbit...')
@section('search_action', route('penerbit.index'))

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">

    {{-- Header Section --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-[26px] font-bold text-[#111827] tracking-tight leading-tight">Manajemen Penerbit</h1>
            <p class="text-[13.5px] text-[#6b7280] mt-1">Kelola kemitraan dan data distributor buku dalam inventaris.</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2.5 rounded-lg text-[13.5px] font-medium transition-all shadow-sm cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Penerbit Baru</span>
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

    {{-- Stats Cards (4 Cards - Data Nyata Database) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">

        {{-- Card 1: Total Penerbit --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#edf3ee] flex items-center justify-center text-[#2d4636]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Semua Mitra</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Total Penerbit</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $totalPenerbit }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">unit</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Perlu Kerjasama / Belum Ada Buku --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#ef4444]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Perhatian</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Belum Ada Buku</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $penerbitKosong }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">unit</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Judul Buku Terdaftar --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Terdaftar</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Total Judul Buku</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $totalBukuPenerbit }}</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">judul</span>
                </div>
            </div>
        </div>

        {{-- Card 4: Kondisi Kemitraan --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-9 h-9 rounded-xl bg-[#f1f5f9] flex items-center justify-center text-[#475569]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <span class="text-[12px] font-medium text-[#9ca3af]">Status</span>
            </div>
            <div class="mt-3">
                <span class="text-[13px] font-medium text-[#6b7280] block">Kondisi Kemitraan</span>
                <div class="flex items-baseline gap-1 mt-1">
                    <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $persenAktif }}%</h3>
                    <span class="text-[13px] font-normal text-[#6b7280]">Aktif</span>
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
                            onclick="toggleActionDropdown(event, 'penerbit-filter-dropdown')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 border {{ (request('status') || request('kota')) ? 'border-[#2d4636] bg-[#edf7ee] text-[#2d4636] font-semibold' : 'border-[#e5e7eb] bg-white text-[#374151]' }} hover:bg-[#f9fafb] rounded-lg text-[13px] font-medium transition-colors cursor-pointer shadow-sm focus:outline-none"
                            title="Filter Data Penerbit">
                        <svg class="w-3.5 h-3.5 {{ (request('status') || request('kota')) ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span>Filter</span>
                        @if(request('status') || request('kota'))
                            <span class="w-2 h-2 rounded-full bg-[#2d7a4d]"></span>
                        @endif
                    </button>

                    <!-- Filter Dropdown Popover Panel -->
                    <div id="penerbit-filter-dropdown" class="action-dropdown hidden absolute left-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-5 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-[#edf3ee] text-[#2d4636] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                </div>
                                <h3 class="text-[13.5px] font-bold text-slate-800">Filter Data Penerbit</h3>
                            </div>
                            <button type="button" onclick="closeAllDropdowns()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form method="GET" action="{{ route('penerbit.index') }}" class="space-y-3.5">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('sort'))   <input type="hidden" name="sort"   value="{{ request('sort') }}"> @endif

                            {{-- Filter Status Koleksi Buku --}}
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Kemitraan</label>
                                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                    <option value="">Semua Penerbit</option>
                                    <option value="has_book" {{ request('status') === 'has_book' ? 'selected' : '' }}>Aktif (Ada Buku Terbit)</option>
                                    <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Belum Ada Buku Terdaftar</option>
                                </select>
                            </div>

                            {{-- Filter Wilayah / Kota Asal --}}
                            @if(isset($daftarKota) && $daftarKota->count() > 0)
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Wilayah / Kota</label>
                                <select name="kota" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                    <option value="">Semua Wilayah</option>
                                    @foreach($daftarKota as $kt)
                                        <option value="{{ $kt }}" {{ request('kota') === $kt ? 'selected' : '' }}>{{ $kt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="pt-2.5 flex items-center justify-between gap-2 border-t border-slate-100 mt-2">
                                <a href="{{ route('penerbit.index', request()->only(['search', 'sort'])) }}" class="px-3 py-2 text-[12px] font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
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
                        'nama_asc'   => 'Nama (A - Z)',
                        'nama_desc'  => 'Nama (Z - A)',
                        'buku_desc'  => 'Buku Terbanyak',
                        'buku_asc'   => 'Buku Tersedikit',
                        'terbaru'    => 'Penerbit Terbaru',
                        'terlama'    => 'Penerbit Terlama',
                    ];
                    $activeSort = request('sort');
                    $activeSortLabel = $sortLabels[$activeSort] ?? null;
                @endphp
                <div class="relative">
                    <button type="button" 
                            onclick="toggleActionDropdown(event, 'penerbit-sort-dropdown')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 border {{ $activeSortLabel ? 'border-[#2d4636] bg-[#edf7ee] text-[#2d4636] font-semibold' : 'border-[#e5e7eb] bg-white text-[#374151]' }} hover:bg-[#f9fafb] rounded-lg text-[13px] font-medium transition-colors cursor-pointer shadow-sm focus:outline-none"
                            title="Urutkan Data Penerbit">
                        <svg class="w-3.5 h-3.5 {{ $activeSortLabel ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        <span>Urutkan{{ $activeSortLabel ? ': ' . $activeSortLabel : '' }}</span>
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Sort Dropdown Popover Menu -->
                    <div id="penerbit-sort-dropdown" class="action-dropdown hidden absolute left-0 top-full mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-slate-200/90 py-2 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            Urutkan Berdasarkan
                        </div>
                        @foreach($sortLabels as $key => $label)
                            @php
                                $isActive = ($activeSort === $key) || (!$activeSort && $key === 'terlama');
                            @endphp
                            <a href="{{ route('penerbit.index', array_merge(request()->query(), ['sort' => $key])) }}" 
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
                @if(request('status') || request('kota') || request('sort'))
                    <a href="{{ route('penerbit.index', request()->only('search')) }}" 
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
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3 px-4 w-[28%]">NAMA PENERBIT</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3 px-3 w-[20%]">KONTAK / TELEPON</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3 px-3 w-[23%]">LOKASI / ALAMAT</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3 px-3 w-[15%]">JUDUL BUKU</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3 px-2 w-[8%] text-center">STATUS</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3 px-3 w-[6%] text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penerbit as $item)
                    @php
                        $bukuCount = $item->buku_count ?? 0;
                        $hasBooks = $bukuCount > 0;

                        // Publisher initials
                        $words = array_values(array_filter(explode(' ', trim($item->nama_penerbit))));
                        if (count($words) >= 2) {
                            $initials = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(mb_substr(trim($item->nama_penerbit), 0, 2));
                        }

                        // Parse alamat for city and street cleanly
                        $alamatRaw = trim($item->alamat ?? '');
                        if (empty($alamatRaw)) {
                            $namaKota = 'Belum Ada Alamat';
                            $alamatDetail = 'Lokasi belum diatur';
                        } else {
                            $alamatParts = array_map('trim', explode(',', $alamatRaw));
                            if (count($alamatParts) > 1) {
                                $namaKota = end($alamatParts);
                                $alamatDetail = $alamatParts[0];
                            } else {
                                $namaKota = $alamatRaw;
                                $alamatDetail = 'Domisili Penerbit';
                            }
                        }
                    @endphp
                    <tr class="hover:bg-[#fbfcfb] transition-colors group">

                        {{-- 1. NAMA PENERBIT --}}
                        <td class="px-4 py-3.5 align-middle">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8.5 h-8.5 rounded-lg bg-[#edf4ee] text-[#2d4636] border border-[#d0e3d3] flex items-center justify-center font-bold text-[12px] flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                                    {{ $initials }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[13px] font-bold text-slate-900 leading-snug block capitalize truncate" title="{{ $item->nama_penerbit }}">
                                        {{ $item->nama_penerbit }}
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9.5px] font-mono font-semibold bg-slate-100 text-slate-500 mt-0.5 border border-slate-200/70">
                                        ID-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- 2. KONTAK / TELEPON --}}
                        <td class="px-3 py-3.5 align-middle">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-[#f0f4f1] text-[#2d4636] border border-[#dbe6dc] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    @if($item->telepon)
                                        <span class="text-[12.5px] font-semibold text-slate-800 block font-mono leading-tight truncate" title="{{ $item->telepon }}">
                                            {{ $item->telepon }}
                                        </span>
                                        <span class="text-[10.5px] text-slate-400 font-medium block mt-0.5">Kontak Resmi</span>
                                    @else
                                        <span class="text-[11.5px] text-slate-400 italic block">Tidak ada</span>
                                        <span class="text-[10.5px] text-slate-400 font-medium block mt-0.5">—</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- 3. LOKASI / ALAMAT --}}
                        <td class="px-3 py-3.5 align-middle">
                            <div class="flex items-start gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-[#f0f4f1] text-[#2d4636] border border-[#dbe6dc] flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[12.5px] font-semibold text-slate-800 block capitalize truncate leading-tight" title="{{ $namaKota }}">
                                        {{ $namaKota }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 block truncate mt-0.5 font-normal" title="{{ $alamatDetail }}">
                                        {{ $alamatDetail }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- 4. JUDUL BUKU --}}
                        <td class="px-3 py-3.5 align-middle">
                            <div class="flex flex-col gap-0.5">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11.5px] font-bold w-fit {{ $hasBooks ? 'bg-[#edf7ee] text-[#2d4636] border border-[#cbe6d0]' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    <svg class="w-3.5 h-3.5 {{ $hasBooks ? 'text-[#2d4636]' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span>{{ $bukuCount }} Judul</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium pl-0.5 truncate">
                                    {{ $hasBooks ? 'Katalog Terbit' : 'Belum Ada' }}
                                </span>
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-2 py-3.5 align-middle text-center">
                            @if($hasBooks)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-[#e8f3ea] text-[#2d4636] border border-[#cbe6d0]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2d7a4d]"></span>
                                    <span>Aktif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <span>0 Buku</span>
                                </span>
                            @endif
                        </td>

                        {{-- AKSI (3-dots vertical kebab menu) --}}
                        <td class="px-3 py-3.5 align-middle text-center">
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
                                        <form method="POST" action="{{ route('penerbit.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penerbit \'{{ $item->nama_penerbit }}\'?')">
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
                            Belum ada data penerbit yang sesuai dengan filter atau pencarian Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        @if($penerbit->hasPages() || $penerbit->total() > 0)
        <div class="flex flex-col sm:flex-row items-center justify-between px-7 py-4 border-t border-[#f3f4f6] bg-white gap-3 select-none">
            <p class="text-[12.5px] text-[#6b7280]">
                @if($penerbit->total() > 0)
                    Menampilkan {{ $penerbit->firstItem() }}–{{ $penerbit->lastItem() }} dari {{ $penerbit->total() }} penerbit
                @else
                    Tidak ada data penerbit
                @endif
            </p>
            
            <div>
                {{ $penerbit->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

{{-- MODAL: TAMBAH PENERBIT BARU --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">

            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Tambah Penerbit Baru</h3>
                        <p class="text-[12px] text-[#6b7280]">Lengkapi data penerbit baru.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('penerbit.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Penerbit <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_penerbit" required placeholder="Contoh: Gramedia Pustaka Utama" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Telepon</label>
                    <input type="text" name="telepon" maxlength="15" placeholder="Contoh: 021-5365011" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    <p class="text-[11px] text-gray-400 mt-1">Maksimal 15 digit.</p>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Contoh: Jl. Palmerah Barat No.29–37, Jakarta Pusat" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm cursor-pointer">
                        Simpan Penerbit
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: EDIT PENERBIT --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Penerbit</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui informasi data penerbit.</p>
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
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Penerbit <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-nama" name="nama_penerbit" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Telepon</label>
                    <input type="text" id="edit-telepon" name="telepon" maxlength="15" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    <p class="text-[11px] text-gray-400 mt-1">Maksimal 15 digit.</p>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Alamat</label>
                    <textarea id="edit-alamat" name="alamat" rows="3" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: DETAIL PENERBIT --}}
<div id="detail-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">

            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Informasi Penerbit</h3>
                        <p class="text-[12px] text-[#6b7280]">Informasi lengkap kemitraan penerbit.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-[#fafbfa] border border-[#eef0f2]">
                    <span id="detail-id" class="px-2.5 py-0.5 rounded-md text-[11.5px] font-mono font-medium bg-[#edf3ee] text-[#2d4636]">ID-001</span>
                    <h2 id="detail-nama" class="text-[18px] font-bold text-[#111827] mt-1.5">Nama Penerbit</h2>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Kontak / Telepon</span>
                        <span id="detail-telepon" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Total Buku Terdaftar</span>
                        <span id="detail-jumlah-buku" class="text-[13.5px] font-bold text-[#2d4636] mt-0.5 block">0 Buku</span>
                    </div>
                </div>

                <div class="p-3 bg-white rounded-xl border border-gray-100">
                    <span class="text-[11.5px] text-gray-500 font-medium block">Alamat Lengkap</span>
                    <p id="detail-alamat" class="text-[13px] text-gray-700 mt-1 leading-relaxed">-</p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-t border-[#eef0f2] flex items-center justify-end">
                <button type="button" onclick="closeDetailModal()" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function openDetailModal(penerbit) {
        document.getElementById('detail-nama').textContent = penerbit.nama_penerbit || '-';
        document.getElementById('detail-id').textContent = 'ID-' + String(penerbit.id).padStart(3, '0');
        document.getElementById('detail-telepon').textContent = penerbit.telepon || '-';
        document.getElementById('detail-jumlah-buku').textContent = (penerbit.buku_count !== undefined ? penerbit.buku_count : 0) + ' Judul Buku';
        document.getElementById('detail-alamat').textContent = penerbit.alamat || 'Alamat tidak dicantumkan.';
        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(penerbit) {
        document.getElementById('edit-form').action = '/penerbit/' + penerbit.id;
        document.getElementById('edit-nama').value = penerbit.nama_penerbit || '';
        document.getElementById('edit-telepon').value = penerbit.telepon || '';
        document.getElementById('edit-alamat').value = penerbit.alamat || '';
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    // ---- Popover & Dropdown Logic ----
    function closeAllDropdowns() {
        document.querySelectorAll('.action-dropdown').forEach(function(el) {
            el.classList.add('hidden');
        });
    }

    function toggleActionDropdown(event, dropdownId) {
        event.stopPropagation();
        var target = document.getElementById(dropdownId);
        if (!target) return;
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

    // Close modal and dropdowns on Escape key
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
