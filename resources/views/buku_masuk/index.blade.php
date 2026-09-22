@extends('layouts.app')

@section('title', 'Manajemen Buku Masuk - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">
    
    {{-- Header Section --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-[28px] font-bold text-[#111827] tracking-tight leading-tight">Manajemen Buku Masuk</h1>
            <p class="text-[14px] text-[#6b7280] mt-1">Catat dan kelola buku yang baru masuk</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2.5 rounded-lg text-[13.5px] font-medium transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Buku Masuk</span>
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

    {{-- Stats Cards (3 Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-7">

        {{-- Card 1: Total Masuk (Bulan Ini) --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Total Masuk (Bulan Ini)</span>
                <div class="w-9 h-9 rounded-xl bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ number_format($totalMasukBulanIni, 0, ',', '.') }}</h3>
                <span class="text-[13px] font-normal text-[#6b7280] block mt-1.5">Unit</span>
            </div>
        </div>

        {{-- Card 2: Buku Baru Terdaftar --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Buku Baru Terdaftar</span>
                <div class="w-9 h-9 rounded-xl bg-[#f3f4f6] flex items-center justify-center text-[#6b7280]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $bukuBaruTerdaftar }}</h3>
                <span class="text-[13px] font-normal text-[#6b7280] block mt-1.5">Judul</span>
            </div>
        </div>

        {{-- Card 3: Pending Check --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Pending Check</span>
                <div class="w-9 h-9 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#ef4444]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $pendingCheck }}</h3>
                <span class="text-[13px] font-normal text-[#6b7280] block mt-1.5">menunggu verifikasi</span>
            </div>
        </div>

    </div>

    {{-- Filter & Search Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        {{-- Search Input --}}
        <form method="GET" action="{{ route('buku-masuk.index') }}" class="relative w-full sm:w-[340px]">
            @if(request('periode'))  <input type="hidden" name="periode"  value="{{ request('periode') }}">  @endif
            @if(request('status'))   <input type="hidden" name="status"   value="{{ request('status') }}">   @endif
            @if(request('penerbit')) <input type="hidden" name="penerbit" value="{{ request('penerbit') }}"> @endif
            @if(request('rak'))      <input type="hidden" name="rak"      value="{{ request('rak') }}">      @endif
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#9ca3af]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID, Judul, atau Penerbit..." 
                   class="block w-full pl-9 pr-3 py-2 text-[13px] bg-white border border-[#e5e7eb] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] transition-colors placeholder-[#9ca3af]">
        </form>

        <div class="flex items-center gap-2.5">
            {{-- Periode Dropdown --}}
            <div class="relative" id="periode-dropdown-container">
                <button type="button" 
                        id="periode-button"
                        onclick="toggleActionDropdown(event, 'periode-dropdown')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 border rounded-lg text-[13px] font-medium transition-colors cursor-pointer {{ (request('periode') && request('periode') !== 'semua') ? 'border-[#2d4636] bg-[#edf3ee] text-[#2d4636]' : 'border-[#e5e7eb] text-[#374151] bg-white hover:bg-[#f9fafb]' }}">
                    <svg class="w-4 h-4 {{ (request('periode') && request('periode') !== 'semua') ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $activePeriodeLabel }}</span>
                    <svg class="w-3.5 h-3.5 {{ (request('periode') && request('periode') !== 'semua') ? 'text-[#2d4636]' : 'text-[#9ca3af]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Periode Dropdown Panel -->
                <div id="periode-dropdown" 
                     class="action-dropdown hidden absolute right-0 sm:right-auto sm:left-0 top-full mt-2 w-60 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 animate-in fade-in zoom-in-95"
                     onclick="event.stopPropagation()">
                    <div class="px-4 py-2 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        Pilih Periode Transaksi
                    </div>
                    <div class="py-1">
                        @php
                            $buildPeriodUrl = function($val) {
                                $params = request()->except(['periode', 'page']);
                                if ($val) $params['periode'] = $val;
                                return route('buku-masuk.index', $params);
                            };
                        @endphp
                        <a href="{{ $buildPeriodUrl('bulan_ini') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('periode') === 'bulan_ini' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Bulan Ini (Sep 2026)</span>
                            @if(request('periode') === 'bulan_ini')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        <a href="{{ $buildPeriodUrl('bulan_lalu') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('periode') === 'bulan_lalu' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Bulan Lalu (Agt 2026)</span>
                            @if(request('periode') === 'bulan_lalu')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        <a href="{{ $buildPeriodUrl('tahun_2026') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('periode') === 'tahun_2026' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Tahun Ini (2026)</span>
                            @if(request('periode') === 'tahun_2026')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        <a href="{{ $buildPeriodUrl('tahun_2023') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ request('periode') === 'tahun_2023' ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Tahun 2023 (Database Awal)</span>
                            @if(request('periode') === 'tahun_2023')
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="{{ $buildPeriodUrl('semua') }}" class="flex items-center justify-between px-4 py-2.5 text-[13px] {{ (request('periode') === 'semua' || !request('periode')) ? 'font-semibold text-[#2d4636] bg-[#edf3ee]' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span>Semua Periode</span>
                            @if(request('periode') === 'semua' || !request('periode'))
                                <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filter Button & Popover --}}
            <div class="relative" id="filter-dropdown-container">
                <button type="button" 
                        id="filter-button"
                        onclick="toggleActionDropdown(event, 'filter-dropdown')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 border rounded-lg text-[13px] font-medium transition-colors cursor-pointer {{ (request('status') || request('penerbit') || request('rak')) ? 'border-[#2d4636] bg-[#edf3ee] text-[#2d4636]' : 'border-[#e5e7eb] text-[#374151] bg-white hover:bg-[#f9fafb]' }}">
                    <svg class="w-4 h-4 {{ (request('status') || request('penerbit') || request('rak')) ? 'text-[#2d4636]' : 'text-[#6b7280]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Filter</span>
                    @if(request('status') || request('penerbit') || request('rak'))
                        <span class="w-2 h-2 rounded-full bg-[#2d7a4d]"></span>
                    @endif
                </button>

                <!-- Filter Dropdown Popover Panel -->
                <div id="filter-dropdown" 
                     class="action-dropdown hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-5 z-50 text-left animate-in fade-in zoom-in-95"
                     onclick="event.stopPropagation()">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-[#edf3ee] text-[#2d4636] flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </div>
                            <h3 class="text-[13.5px] font-bold text-slate-800">Filter Transaksi Masuk</h3>
                        </div>
                        <button type="button" onclick="closeAllDropdowns()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('buku-masuk.index') }}" class="space-y-3.5">
                        @if(request('search'))  <input type="hidden" name="search"  value="{{ request('search') }}">  @endif
                        @if(request('periode')) <input type="hidden" name="periode" value="{{ request('periode') }}"> @endif

                        {{-- Status Transaksi --}}
                        <div>
                            <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Transaksi</label>
                            <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                <option value="">Semua Status</option>
                                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            </select>
                        </div>

                        {{-- Penerbit --}}
                        <div>
                            <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Penerbit</label>
                            <select name="penerbit" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                <option value="">Semua Penerbit</option>
                                @foreach($daftarPenerbit as $pub)
                                    <option value="{{ $pub->nama_penerbit }}" {{ request('penerbit') === $pub->nama_penerbit ? 'selected' : '' }}>
                                        {{ $pub->nama_penerbit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Rak Tujuan --}}
                        <div>
                            <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Rak Tujuan</label>
                            <select name="rak" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                                <option value="">Semua Rak</option>
                                @foreach($daftarRak as $rk)
                                    <option value="{{ $rk }}" {{ request('rak') === $rk ? 'selected' : '' }}>
                                        {{ $rk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100">
                            <a href="{{ route('buku-masuk.index', request()->only(['search', 'periode'])) }}" class="text-[12px] font-medium text-slate-500 hover:text-red-600 transition-colors">
                                Reset Filter
                            </a>
                            <button type="submit" class="px-4 py-2 bg-[#2d4636] hover:bg-[#23372b] text-white text-[12.5px] font-semibold rounded-xl transition-colors shadow-sm cursor-pointer">
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Filter Tags --}}
    @if(request('search') || (request('periode') && request('periode') !== 'semua') || request('status') || request('penerbit') || request('rak'))
    <div class="flex flex-wrap items-center gap-2 mb-5">
        <span class="text-[12px] text-slate-500 font-medium">Filter Aktif:</span>
        
        @if(request('search'))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Pencarian: "{{ request('search') }}"
                <a href="{{ route('buku-masuk.index', request()->except(['search', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        @if(request('periode') && request('periode') !== 'semua')
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Periode: {{ $activePeriodeLabel }}
                <a href="{{ route('buku-masuk.index', request()->except(['periode', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        @if(request('status'))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Status: {{ request('status') }}
                <a href="{{ route('buku-masuk.index', request()->except(['status', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        @if(request('penerbit'))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Penerbit: {{ request('penerbit') }}
                <a href="{{ route('buku-masuk.index', request()->except(['penerbit', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        @if(request('rak'))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium bg-[#edf3ee] text-[#2d4636] border border-[#2d4636]/20">
                Rak: {{ request('rak') }}
                <a href="{{ route('buku-masuk.index', request()->except(['rak', 'page'])) }}" class="hover:text-red-600 font-bold ml-0.5">&times;</a>
            </span>
        @endif

        <a href="{{ route('buku-masuk.index') }}" class="text-[12px] text-red-600 hover:underline font-semibold ml-1">
            Reset Semua
        </a>
    </div>
    @endif

    {{-- Section Title --}}
    <h2 class="text-[18px] font-bold text-[#111827] mb-4">Daftar Buku Masuk</h2>

    {{-- Table Container --}}
    <div class="bg-white rounded-2xl border border-[#eef0f2] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="w-full overflow-x-auto overflow-y-visible [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <thead>
                    <tr class="bg-[#fafbfc] border-b border-slate-200/80">
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-3 w-[12%] whitespace-nowrap">NO. REGISTRASI / BUKTI</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-3 w-[14%] whitespace-nowrap">TANGGAL</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 pl-5 pr-3 w-[26%]">JUDUL BUKU</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-3 w-[18%]">PENERBIT</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-2 w-[8%] text-center whitespace-nowrap">JUMLAH</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-2 w-[10%] text-center whitespace-nowrap">RAK TUJUAN</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-2 w-[9%] text-center whitespace-nowrap">STATUS</th>
                        <th class="text-[11px] font-bold text-slate-500 uppercase tracking-wider py-3.5 px-3 w-[5%] text-center whitespace-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f3f4f6]">
                    @forelse($bukuMasuk as $bm)
                    @php
                        $firstDetail = $bm->details->first();
                        $bukuObj = $firstDetail?->buku;
                        $penerbitName = $bukuObj?->penerbit?->nama_penerbit;

                        $noTx = $bm->no_transaksi;
                        $judul = $bukuObj?->judul ?? 'Buku Inventaris';
                        $penerbit = $penerbitName ?? '-';
                        $jumlah = $firstDetail?->jumlah ?? 0;
                        $rakTujuan = $bm->keterangan ?? $bukuObj?->rak?->kode_rak ?? '-';
                        $statusStr = $bm->status ?? 'Selesai';
                        $tglFormatted = \Carbon\Carbon::parse($bm->tanggal_masuk)->translatedFormat('d M Y');
                    @endphp
                    <tr class="hover:bg-[#fcfdfc] transition-colors group">
                        
                        {{-- 1. ID Buku / No Transaksi --}}
                        <td class="px-3 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-[#edf3ee] text-[#2d4636] font-mono font-bold text-[12px] tracking-tight border border-[#2d4636]/15 whitespace-nowrap">
                                {{ $noTx }}
                            </span>
                        </td>

                        {{-- 2. Tanggal --}}
                        <td class="px-3 py-3.5 align-middle whitespace-nowrap">
                            <span class="text-[12.5px] font-medium text-slate-700 whitespace-nowrap">{{ $tglFormatted }}</span>
                        </td>

                        {{-- 3. Judul Buku --}}
                        <td class="pl-5 pr-3 py-3.5 align-middle">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#2d4636] border border-[#2d4636]/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[13px] font-semibold text-slate-900 block truncate" title="{{ $judul }}">{{ $judul }}</span>
                                    @if($bukuObj?->kategori)
                                        <span class="text-[11px] text-slate-500 block truncate">{{ $bukuObj->kategori->nama_kategori }}</span>
                                    @elseif($bukuObj?->isbn)
                                        <span class="text-[10.5px] text-slate-400 font-mono block truncate">ISBN: {{ $bukuObj->isbn }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- 4. Penerbit --}}
                        <td class="px-3 py-3.5 align-middle">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-5.5 h-5.5 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="text-[12.5px] font-medium text-slate-700 truncate" title="{{ $penerbit }}">{{ $penerbit }}</span>
                            </div>
                        </td>

                        {{-- 5. Jumlah --}}
                        <td class="px-2 py-3.5 align-middle text-center whitespace-nowrap">
                            <span class="inline-flex items-baseline gap-1 px-2 py-0.5 rounded-md bg-slate-50 border border-slate-200/70">
                                <span class="text-[12.5px] font-bold text-slate-800">{{ number_format($jumlah) }}</span>
                                <span class="text-[10.5px] font-medium text-slate-500">Unit</span>
                            </span>
                        </td>

                        {{-- 6. Rak Tujuan --}}
                        <td class="px-2 py-3.5 align-middle text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11.5px] font-mono font-semibold bg-amber-50 text-amber-800 border border-amber-200/60 whitespace-nowrap">
                                <svg class="w-3 h-3 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>{{ $rakTujuan }}</span>
                            </span>
                        </td>

                        {{-- 7. Status Badge --}}
                        <td class="px-2 py-3.5 align-middle text-center whitespace-nowrap">
                            @if($statusStr === 'Diproses')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#fde8e8] text-[#c81e1e]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#e02424] animate-pulse"></span>
                                    <span>Diproses</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#edf7ee] text-[#2d7a4d]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2d7a4d]"></span>
                                    <span>Selesai</span>
                                </span>
                            @endif
                        </td>

                        {{-- 8. Aksi --}}
                        <td class="px-3 py-3.5 align-middle text-center whitespace-nowrap">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                        onclick="toggleActionDropdown(event, 'dropdown-{{ $bm->id }}')"
                                        title="Menu Aksi"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors focus:outline-none cursor-pointer mx-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div id="dropdown-{{ $bm->id }}"
                                     class="action-dropdown hidden absolute right-0 mt-1.5 w-40 bg-white rounded-2xl shadow-xl border border-[#eef0f2] overflow-hidden z-30 divide-y divide-[#f3f4f6]"
                                     onclick="event.stopPropagation()">
                                    
                                    {{-- Detail Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openDetailModal({{ json_encode($bm) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-blue-600 hover:bg-blue-50/50 flex items-center gap-3 transition-colors cursor-pointer">
                                            <svg class="w-[18px] h-[18px] text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span class="text-blue-600">Detail</span>
                                        </button>
                                    </div>

                                    {{-- Edit Option --}}
                                    <div>
                                        <button type="button"
                                                onclick="openEditModal({{ json_encode($bm) }}); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-[#d97706] hover:bg-amber-50/50 flex items-center gap-3 transition-colors cursor-pointer">
                                            <svg class="w-[18px] h-[18px] text-[#d97706] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span class="text-[#d97706]">Edit</span>
                                        </button>
                                    </div>

                                    {{-- Delete Option --}}
                                    <div>
                                        <form method="POST" action="{{ route('buku-masuk.destroy', $bm) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi \'{{ $bm->no_transaksi }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-[#dc2626] hover:bg-red-50/50 flex items-center gap-3 transition-colors cursor-pointer">
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
                        <td colspan="8" class="px-6 py-16 text-center text-[#6b7280] text-[14px]">
                            Belum ada transaksi buku masuk yang sesuai dengan filter atau pencarian Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        @if($bukuMasuk->total() > 0)
        <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-3.5 border-t border-[#f3f4f6] bg-white gap-3 select-none">
            {{-- Info --}}
            <p class="text-[12.5px] text-slate-500">
                Menampilkan
                <span class="font-semibold text-slate-700">{{ $bukuMasuk->firstItem() }}</span>–<span class="font-semibold text-slate-700">{{ $bukuMasuk->lastItem() }}</span>
                dari
                <span class="font-semibold text-slate-700">{{ $bukuMasuk->total() }}</span>
                transaksi
            </p>

            {{-- Pagination Buttons --}}
            @if($bukuMasuk->hasPages())
            <div class="flex items-center gap-1.5">
                {{-- Prev --}}
                @if($bukuMasuk->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $bukuMasuk->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-[#edf3ee] hover:border-[#2d4636] hover:text-[#2d4636] transition-colors bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach($bukuMasuk->getUrlRange(1, $bukuMasuk->lastPage()) as $page => $url)
                    @if($page == $bukuMasuk->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2d4636] text-white text-[13px] font-semibold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 text-[13px] hover:bg-[#edf3ee] hover:border-[#2d4636] hover:text-[#2d4636] transition-colors bg-white">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($bukuMasuk->hasMorePages())
                    <a href="{{ $bukuMasuk->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-[#edf3ee] hover:border-[#2d4636] hover:text-[#2d4636] transition-colors bg-white">
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

{{-- MODAL: TAMBAH BUKU MASUK --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Tambah Buku Masuk</h3>
                        <p class="text-[12px] text-[#6b7280]">Catat penerimaan stok buku baru.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('buku-masuk.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[12px] font-semibold text-[#374151]">No. Registrasi / Bukti <span class="text-red-500">*</span></label>
                            <span class="inline-flex items-center gap-1 text-[10.5px] font-semibold text-[#2d7a4d] bg-[#edf7ee] px-2 py-0.5 rounded-full border border-[#cbe6d0]">
                                <svg class="w-3 h-3 text-[#2d7a4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Otomatis
                            </span>
                        </div>
                        <div class="relative">
                            <input type="text" id="create-no-tx" name="no_transaksi" required readonly value="{{ $nextNoTransaksi }}" class="w-full px-3.5 py-2 text-[13px] font-bold text-[#2d4636] bg-[#f8faf8] border border-[#d1d5db] rounded-lg focus:outline-none cursor-default shadow-xs select-all">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                        <p class="text-[10.5px] text-gray-400 mt-1">ID mutasi penerimaan stok masuk</p>
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_masuk" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Pilih Buku <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="id_buku" id="create-buku" required onchange="handleBukuChange(this)" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                            <option value="">-- Pilih Judul Buku --</option>
                            @foreach($buku as $item)
                                <option value="{{ $item->id }}" data-harga="{{ $item->harga_beli }}" data-rak="{{ $item->rak?->kode_rak ?? '' }}">
                                    {{ $item->judul }} ({{ $item->penerbit?->nama_penerbit ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Jumlah Unit <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" required min="1" value="50" placeholder="Contoh: 100" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Harga Beli Satuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_satuan" id="create-harga" required min="0" value="50000" placeholder="Contoh: 75000" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Rak Tujuan <span class="text-gray-400 font-normal">(Lokasi Simpan)</span></label>
                        <div class="relative">
                            <select name="keterangan" id="create-rak" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                                <option value="">-- Pilih Rak Tujuan --</option>
                                @foreach($rak as $r)
                                    <option value="{{ $r->kode_rak }}">{{ $r->kode_rak }} {{ $r->lokasi ? '('.$r->lokasi.')' : '' }} {{ $r->kategori_buku ? '- '.$r->kategori_buku : '' }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Status <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="status" required class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                                <option value="Selesai">Selesai</option>
                                <option value="Diproses">Diproses</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                        Simpan Buku Masuk
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: EDIT BUKU MASUK --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Buku Masuk</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui tanggal, rak tujuan, atau status penerimaan.</p>
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
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="date" id="edit-tanggal" name="tanggal_masuk" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Rak Tujuan <span class="text-gray-400 font-normal">(Lokasi Simpan)</span></label>
                    <div class="relative">
                        <select name="keterangan" id="edit-rak" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                            <option value="">-- Pilih Rak Tujuan --</option>
                            @foreach($rak as $r)
                                <option value="{{ $r->kode_rak }}">{{ $r->kode_rak }} {{ $r->lokasi ? '('.$r->lokasi.')' : '' }} {{ $r->kategori_buku ? '- '.$r->kategori_buku : '' }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="edit-status" name="status" required class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                            <option value="Selesai">Selesai</option>
                            <option value="Diproses">Diproses</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
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

{{-- MODAL: DETAIL BUKU MASUK --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Data Buku Masuk</h3>
                        <p class="text-[12px] text-[#6b7280]">Rincian data penerimaan stok buku.</p>
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
                        <span class="text-[11.5px] text-gray-500 font-medium block">No. Registrasi / Bukti</span>
                        <h2 id="detail-no-tx" class="text-[18px] font-bold text-[#111827] mt-0.5">#IN-0001</h2>
                    </div>
                    <span id="detail-status-badge" class="px-3 py-1 rounded-full text-[12px] font-semibold bg-[#edf7ee] text-[#2d7a4d]">Selesai</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Tanggal Masuk</span>
                        <span id="detail-tanggal" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Rak Tujuan</span>
                        <span id="detail-rak" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                </div>

                <div class="p-4 bg-white rounded-xl border border-gray-100 space-y-2.5">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[11.5px] text-gray-500 font-medium block">Buku & Penerbit</span>
                            <span id="detail-buku" class="text-[14px] font-bold text-gray-900 block mt-0.5">-</span>
                            <span id="detail-penerbit" class="text-[12px] text-gray-500 block">-</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11.5px] text-gray-500 font-medium block">Jumlah Masuk</span>
                            <span id="detail-jumlah" class="text-[15px] font-bold text-[#2d4636] mt-0.5 block">0 Eks</span>
                        </div>
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
    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function handleBukuChange(selectElem) {
        const selected = selectElem.options[selectElem.selectedIndex];
        if (selected) {
            const harga = selected.getAttribute('data-harga');
            const rak = selected.getAttribute('data-rak');
            if (harga) document.getElementById('create-harga').value = parseInt(harga);
            if (rak) document.getElementById('create-rak').value = rak;
        }
    }

    function openDetailModal(bm) {
        document.getElementById('detail-no-tx').textContent = bm.no_transaksi || '-';
        document.getElementById('detail-tanggal').textContent = bm.tanggal_masuk ? bm.tanggal_masuk.split(' ')[0] : '-';
        document.getElementById('detail-rak').textContent = bm.keterangan || '-';

        var status = bm.status || 'Selesai';
        var badge = document.getElementById('detail-status-badge');
        badge.textContent = status;
        if (status === 'Diproses') {
            badge.className = 'px-3 py-1 rounded-full text-[12px] font-semibold bg-[#fde8e8] text-[#c81e1e]';
        } else {
            badge.className = 'px-3 py-1 rounded-full text-[12px] font-semibold bg-[#edf7ee] text-[#2d7a4d]';
        }

        var firstDetail = bm.details && bm.details.length > 0 ? bm.details[0] : null;
        if (firstDetail && firstDetail.buku) {
            document.getElementById('detail-buku').textContent = firstDetail.buku.judul || '-';
            document.getElementById('detail-penerbit').textContent = firstDetail.buku.penerbit ? firstDetail.buku.penerbit.nama_penerbit : '-';
            document.getElementById('detail-jumlah').textContent = (firstDetail.jumlah || 0).toLocaleString('id-ID') + ' Eks';
        } else {
            document.getElementById('detail-buku').textContent = 'Buku Inventaris';
            document.getElementById('detail-penerbit').textContent = '-';
            document.getElementById('detail-jumlah').textContent = '-';
        }

        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(bm) {
        document.getElementById('edit-form').action = '/buku-masuk/' + bm.id;
        
        let tgl = bm.tanggal_masuk;
        if (tgl && tgl.includes(' ')) {
            tgl = tgl.split(' ')[0];
        }
        var rakSelect = document.getElementById('edit-rak');
        if (rakSelect) {
            rakSelect.value = bm.keterangan || '';
            if (bm.keterangan && rakSelect.value !== bm.keterangan) {
                var opt = document.createElement('option');
                opt.value = bm.keterangan;
                opt.textContent = bm.keterangan + ' (Tersimpan)';
                opt.selected = true;
                rakSelect.appendChild(opt);
            }
        }
        document.getElementById('edit-status').value = bm.status || 'Selesai';
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
