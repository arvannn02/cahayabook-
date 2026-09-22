@extends('layouts.app')

@section('title', 'Manajemen Kategori Buku - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">
    
    {{-- Header Section --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-[28px] font-bold text-[#111827] tracking-tight leading-tight">Manajemen Kategori Buku</h1>
            <p class="text-[14px] text-[#6b7280] mt-1">Kelola dan organisir pengelompokan buku dalam inventaris.</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2.5 rounded-lg text-[13.5px] font-medium transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Kategori Baru</span>
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
        
        {{-- Card 1: Total Kategori --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Total Kategori</span>
                <div class="w-8 h-8 rounded-full bg-[#dcfce7] flex items-center justify-center text-[#16a34a]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $totalKategori }}</h3>
                <p class="text-[12px] font-medium text-[#16a34a] mt-2.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Aktif terdata</span>
                </p>
            </div>
        </div>

        {{-- Card 2: Kategori Populer --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Kategori Populer</span>
                <div class="w-8 h-8 rounded-full bg-[#f3f4f6] flex items-center justify-center text-[#6b7280]">
                    <svg class="w-4 h-4 text-[#9ca3af]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[20px] font-bold text-[#111827] leading-tight tracking-tight truncate" title="{{ $kategoriPopuler?->nama_kategori ?? '-' }}">
                    {{ $kategoriPopuler?->nama_kategori ?? '-' }}
                </h3>
                <p class="text-[12px] font-normal text-[#6b7280] mt-1.5">{{ $kategoriPopuler?->buku_count ?? 0 }} Judul Buku</p>
            </div>
        </div>

        {{-- Card 3: Kategori Kosong --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Kategori Kosong</span>
                <div class="w-8 h-8 rounded-full bg-[#fee2e2] flex items-center justify-center text-[#ef4444]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $kategoriKosong }}</h3>
                <p class="text-[12px] font-normal text-[#6b7280] mt-2.5">Perlu ditinjau ulang</p>
            </div>
        </div>

        {{-- Card 4: Rak Terisi --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-5 shadow-[0_1px_2px_rgba(0,0,0,0.02)] relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <span class="text-[13px] font-medium text-[#6b7280]">Rak Terisi</span>
                <div class="w-8 h-8 rounded-lg bg-[#f3f4f6] flex items-center justify-center text-[#6b7280]">
                    <svg class="w-4 h-4 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <h3 class="text-[32px] font-bold text-[#111827] leading-none tracking-tight">{{ $rakTerisiPersen }}%</h3>
                <div class="w-full bg-[#e5e7eb] h-2 rounded-full overflow-hidden mt-3">
                    <div class="bg-[#2d4636] h-full rounded-full transition-all duration-500" style="width: {{ min($rakTerisiPersen, 100) }}%"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Table Container: Daftar Kategori --}}
    <div class="bg-white rounded-2xl border border-[#eef0f2] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Card Header & Actions --}}
        <div class="px-6 py-4 border-b border-[#f3f4f6] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="text-[17px] font-bold text-[#111827]">Daftar Kategori</h2>
                @if(request('status') || request('sort') || request('rak'))
                    <a href="{{ route('kategori.index') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#edf7ee] hover:bg-emerald-100 text-[#2d7a4d] border border-[#cbe6d0] transition-colors" title="Klik untuk reset filter">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2d7a4d]"></span>
                        <span>Filter Aktif</span>
                        <svg class="w-3 h-3 text-[#2d7a4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
            
            <div class="flex items-center gap-2 relative">
                {{-- 1. Filter Button & Popover --}}
                <div class="relative">
                    <button type="button" 
                            onclick="toggleActionDropdown(event, 'kategori-filter-dropdown')"
                            class="p-2 border {{ (request('status') || request('sort') || request('rak')) ? 'border-[#3a5d43] bg-[#edf3ee] text-[#2d4636] font-semibold' : 'border-[#e5e7eb] text-[#6b7280] hover:bg-[#f9fafb]' }} rounded-xl transition-all cursor-pointer shadow-2xs relative group focus:outline-none" 
                            title="Filter & Urutkan Kategori">
                        <svg class="w-4 h-4 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        @if(request('status') || request('sort') || request('rak'))
                            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#2d7a4d] rounded-full ring-2 ring-white"></span>
                        @endif
                    </button>

                    <!-- Filter Dropdown Popover Panel -->
                    <div id="kategori-filter-dropdown" class="action-dropdown hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-5 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-[#edf3ee] text-[#2d4636] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                </div>
                                <h3 class="text-[13.5px] font-bold text-slate-800">Filter & Pengurutan</h3>
                            </div>
                            <button type="button" onclick="closeAllDropdowns()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <form method="GET" action="{{ route('kategori.index') }}" class="space-y-3.5">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            {{-- Filter Status Buku --}}
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Status Koleksi</label>
                                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#3a5d43] focus:ring-1 focus:ring-[#3a5d43]">
                                    <option value="">Semua Kategori</option>
                                    <option value="has_book" {{ request('status') === 'has_book' ? 'selected' : '' }}>Ada Koleksi Buku (> 0)</option>
                                    <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Kategori Kosong (0 Buku)</option>
                                </select>
                            </div>

                            {{-- Filter Urutan / Sort --}}
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Urutan Data</label>
                                <select name="sort" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#3a5d43] focus:ring-1 focus:ring-[#3a5d43]">
                                    <option value="">Default (ID)</option>
                                    <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama Kategori (A — Z)</option>
                                    <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama Kategori (Z — A)</option>
                                    <option value="buku_desc" {{ request('sort') === 'buku_desc' ? 'selected' : '' }}>Jumlah Buku (Terbanyak)</option>
                                    <option value="buku_asc" {{ request('sort') === 'buku_asc' ? 'selected' : '' }}>Jumlah Buku (Tersedikit)</option>
                                </select>
                            </div>

                            {{-- Filter Lokasi Rak --}}
                            @if(isset($daftarRak) && $daftarRak->count() > 0)
                            <div>
                                <label class="block text-[11.5px] font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Lokasi Rak</label>
                                <select name="rak" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[12.5px] font-medium text-slate-700 focus:outline-none focus:border-[#3a5d43] focus:ring-1 focus:ring-[#3a5d43]">
                                    <option value="">Semua Lokasi Rak</option>
                                    @foreach($daftarRak as $rakItem)
                                        <option value="{{ $rakItem }}" {{ request('rak') === $rakItem ? 'selected' : '' }}>{{ $rakItem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            {{-- Buttons --}}
                            <div class="pt-2 flex items-center justify-between gap-2 border-t border-slate-100 mt-2">
                                <a href="{{ route('kategori.index') }}" class="px-3 py-2 text-[12px] font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                                    Reset
                                </a>
                                <button type="submit" class="px-4 py-2 bg-[#2d4636] hover:bg-[#22362a] text-white text-[12.5px] font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                {{-- 2. More Options Button & Dropdown --}}
                <div class="relative">
                    <button type="button" 
                            onclick="toggleActionDropdown(event, 'kategori-options-dropdown')"
                            class="p-2 border border-[#e5e7eb] rounded-xl text-[#6b7280] hover:bg-[#f9fafb] hover:text-[#111827] transition-all cursor-pointer shadow-2xs group focus:outline-none" 
                            title="Menu Opsi Kategori">
                        <svg class="w-4 h-4 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>

                    <!-- Options Dropdown Menu -->
                    <div id="kategori-options-dropdown" class="action-dropdown hidden absolute right-0 top-full mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-2 z-50 text-left animate-in fade-in zoom-in-95" onclick="event.stopPropagation()">
                        <div class="px-3 py-2 border-b border-slate-100 mb-1">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi & Pintasan</p>
                        </div>

                        <button type="button" onclick="closeAllDropdowns(); openCreateModal();" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[12.5px] font-medium text-slate-700 hover:bg-[#edf3ee] hover:text-[#2d4636] transition-colors cursor-pointer text-left">
                            <svg class="w-4 h-4 text-[#3a5d43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>Tambah Kategori Baru</span>
                        </button>

                        <button type="button" onclick="closeAllDropdowns(); window.print();" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[12.5px] font-medium text-slate-700 hover:bg-[#edf3ee] hover:text-[#2d4636] transition-colors cursor-pointer text-left">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Cetak Daftar Kategori</span>
                        </button>

                        <a href="{{ route('kategori.index') }}" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[12.5px] font-medium text-slate-700 hover:bg-[#edf3ee] hover:text-[#2d4636] transition-colors text-left">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Muat Ulang (Refresh)</span>
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <a href="{{ route('buku.index') }}" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-[12.5px] font-medium text-slate-700 hover:bg-[#edf3ee] hover:text-[#2d4636] transition-colors text-left">
                            <svg class="w-4 h-4 text-[#3a5d43]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Buka Data Buku</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#fcfdfc] border-b border-[#f3f4f6]">
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-6 w-[28%]">Nama Kategori</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-4 w-[20%]">Kode Kategori</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-4 w-[16%]">Jumlah Buku</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-4 w-[24%]">Lokasi Rak</th>
                        <th class="text-[11.5px] font-semibold text-[#6b7280] py-3.5 px-6 w-[12%] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f3f4f6]">
                    @forelse($kategoris as $item)
                    @php
                        $count = $item->buku_count ?? 0;
                        $displayCount = number_format($count, 0, ',', '.');
                        $isZero = ($count === 0);

                        // Icon selection based on category name
                        $nameLower = strtolower($item->nama_kategori);
                    @endphp
                    <tr class="hover:bg-[#fcfdfc] transition-colors group">
                        
                        {{-- Nama Kategori with Icon --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-9 h-9 rounded-lg bg-[#f3f4f6] flex items-center justify-center text-[#6b7280] flex-shrink-0">
                                    @if(str_contains($nameLower, 'fiksi') || str_contains($nameLower, 'sastra'))
                                        {{-- Newspaper / Document Icon --}}
                                        <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                    @elseif(str_contains($nameLower, 'sains') || str_contains($nameLower, 'teknologi'))
                                        {{-- Science / Flask Icon --}}
                                        <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    @elseif(str_contains($nameLower, 'sejarah') || str_contains($nameLower, 'biografi'))
                                        {{-- Scroll / Certificate Icon --}}
                                        <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @elseif(str_contains($nameLower, 'komik') || str_contains($nameLower, 'manga'))
                                        {{-- Comic / Search-Bubble Icon --}}
                                        <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    @elseif(str_contains($nameLower, 'anak'))
                                        {{-- Baby / Smile Icon --}}
                                        <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        {{-- Folder / Tag Icon --}}
                                        <svg class="w-4 h-4 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    @endif
                                </div>
                                <span class="text-[13.5px] font-bold text-[#111827]">{{ $item->nama_kategori }}</span>
                            </div>
                        </td>

                        {{-- Kode Kategori --}}
                        <td class="px-4 py-4 align-middle">
                            <span class="text-[13px] text-[#4b5563] font-normal">{{ $item->formatted_kode }}</span>
                        </td>

                        {{-- Jumlah Buku Badge --}}
                        <td class="px-4 py-4 align-middle">
                            @if($isZero)
                                <span class="inline-flex items-center justify-center px-3 py-0.5 rounded-full text-[12px] font-semibold bg-[#fee2e2] text-[#dc2626]">
                                    0
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center px-3 py-0.5 rounded-full text-[12px] font-semibold bg-[#bbf7d0] text-[#166534]">
                                    {{ $displayCount }}
                                </span>
                            @endif
                        </td>

                        {{-- Lokasi Rak --}}
                        <td class="px-4 py-4 align-middle">
                            @if(!empty($item->lokasi_rak))
                                <div class="flex items-center gap-1.5 text-[13px] text-[#4b5563]">
                                    <svg class="w-3.5 h-3.5 text-[#6b7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <span>{{ $item->lokasi_rak }}</span>
                                </div>
                            @else
                                <span class="text-[13px] text-[#9ca3af]">Belum dialokasikan</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
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
                                        <button type="button"
                                                onclick="showDeleteModal('{{ route('kategori.destroy', $item) }}', '{{ addslashes($item->nama_kategori) }}'); closeAllDropdowns();"
                                                class="w-full text-left px-4 py-2.5 text-[13.5px] font-medium text-[#dc2626] hover:bg-red-50/50 flex items-center gap-3 transition-colors">
                                            <svg class="w-[18px] h-[18px] text-[#dc2626] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="text-[#dc2626]">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-[#6b7280] text-[14px]">
                            Belum ada data kategori yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-[#f3f4f6] bg-white select-none">
            <p class="text-[13px] text-[#6b7280]">
                Menampilkan 1–5 dari 24 Kategori
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
                
                {{-- Page 5 --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md text-[#4b5563] text-[13px] hover:bg-[#f3f4f6] transition-colors">5</button>

                {{-- Next --}}
                <button type="button" class="w-7 h-7 flex items-center justify-center rounded-md text-[#4b5563] hover:text-[#111827] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

    </div>
</div>

{{-- MODAL: TAMBAH KATEGORI BARU --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Tambah Kategori Baru</h3>
                        <p class="text-[12px] text-[#6b7280]">Masukkan data kategori buku untuk inventaris.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('kategori.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="create-nama" name="nama_kategori" required placeholder="Contoh: Fiksi & Sastra" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kode Kategori</label>
                        <input type="text" id="create-kode" name="kode_kategori" placeholder="Contoh: KAT - FKS" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak</label>
                        <div class="relative">
                            <select name="lokasi_rak" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                                <option value="">-- Pilih Lokasi Rak --</option>
                                @foreach($semuaRak as $r)
                                    @php
                                        $rakVal = $r->kode_rak . (!empty($r->lokasi) ? ' (' . $r->lokasi . ')' : '');
                                    @endphp
                                    <option value="{{ $rakVal }}">{{ $rakVal }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat mengenai kategori..." class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                        Simpan Kategori
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: EDIT KATEGORI --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Kategori</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui informasi kategori buku pada inventaris.</p>
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
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-nama" name="nama_kategori" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kode Kategori</label>
                        <input type="text" id="edit-kode" name="kode_kategori" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak</label>
                        <div class="relative">
                            <select id="edit-rak" name="lokasi_rak" class="w-full appearance-none px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white cursor-pointer pr-9 text-[#374151]">
                                <option value="">-- Pilih Lokasi Rak --</option>
                                @foreach($semuaRak as $r)
                                    @php
                                        $rakVal = $r->kode_rak . (!empty($r->lokasi) ? ' (' . $r->lokasi . ')' : '');
                                    @endphp
                                    <option value="{{ $rakVal }}">{{ $rakVal }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#9ca3af]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#374151] mb-1">Deskripsi</label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="3" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
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

{{-- MODAL: DETAIL KATEGORI --}}
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
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Kategori Buku</h3>
                        <p class="text-[12px] text-[#6b7280]">Informasi lengkap pengelompokan buku.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-[#fafbfa] border border-[#eef0f2]">
                    <span id="detail-kode" class="px-2.5 py-0.5 rounded-md text-[11.5px] font-mono font-medium bg-[#edf3ee] text-[#2d4636]">KAT-001</span>
                    <h2 id="detail-nama" class="text-[18px] font-bold text-[#111827] mt-1.5">Nama Kategori</h2>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Lokasi Rak</span>
                        <span id="detail-rak" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Total Buku Terdaftar</span>
                        <span id="detail-jumlah-buku" class="text-[13.5px] font-bold text-[#2d4636] mt-0.5 block">0 Judul</span>
                    </div>
                </div>

                <div class="p-3 bg-white rounded-xl border border-gray-100">
                    <span class="text-[11.5px] text-gray-500 font-medium block">Deskripsi</span>
                    <p id="detail-deskripsi" class="text-[13px] text-gray-700 mt-1 leading-relaxed">-</p>
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
    function generateKodeKategori(name) {
        if (!name || !name.trim()) return '';
        const words = name.trim().split(/[\s&+\-_/]+/).filter(w => {
            const low = w.toLowerCase();
            return low.length > 0 && !['&', 'dan', 'of', 'the', 'di', 'ke'].includes(low);
        });

        let code = '';
        if (words.length >= 3) {
            code = (words[0][0] + words[1][0] + words[2][0]).toUpperCase();
        } else if (words.length === 2) {
            const c1 = words[0].replace(/[aeiouAEIOU^a-zA-Z]/g, '');
            if (c1.length >= 2) {
                code = (c1[0] + c1[1] + words[1][0]).toUpperCase();
            } else if (words[0].length >= 2) {
                code = (words[0][0] + words[0][1] + words[1][0]).toUpperCase();
            } else {
                code = (words[0][0] + words[1].substring(0, 2)).toUpperCase();
            }
        } else if (words.length === 1) {
            code = words[0].replace(/[^a-zA-Z]/g, '').substring(0, 3).toUpperCase();
        }

        if (!code) {
            code = name.replace(/[^a-zA-Z]/g, '').substring(0, 3).toUpperCase();
        }

        return code ? `KAT - ${code}` : '';
    }

    let createKodeManuallyEdited = false;
    const createNamaInput = document.getElementById('create-nama');
    const createKodeInput = document.getElementById('create-kode');

    if (createNamaInput && createKodeInput) {
        createNamaInput.addEventListener('input', function() {
            if (!createKodeManuallyEdited || createKodeInput.value.trim() === '') {
                createKodeInput.value = generateKodeKategori(this.value);
            }
        });

        createKodeInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                createKodeManuallyEdited = false;
            } else {
                createKodeManuallyEdited = true;
            }
        });
    }

    const editNamaInput = document.getElementById('edit-nama');
    const editKodeInput = document.getElementById('edit-kode');
    if (editNamaInput && editKodeInput) {
        editNamaInput.addEventListener('input', function() {
            if (editKodeInput.value.trim() === '') {
                editKodeInput.value = generateKodeKategori(this.value);
            }
        });
    }

    function openCreateModal() {
        createKodeManuallyEdited = false;
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function openDetailModal(category) {
        document.getElementById('detail-nama').textContent = category.nama_kategori || '-';
        document.getElementById('detail-kode').textContent = category.kode_kategori || 'KAT-GEN';
        document.getElementById('detail-rak').textContent = category.lokasi_rak || 'Belum ditentukan';
        document.getElementById('detail-jumlah-buku').textContent = (category.buku_count !== undefined ? category.buku_count : 0) + ' Judul Buku';
        document.getElementById('detail-deskripsi').textContent = category.deskripsi || 'Tidak ada deskripsi.';
        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(category) {
        document.getElementById('edit-form').action = '/kategori/' + category.id;
        document.getElementById('edit-nama').value = category.nama_kategori || '';
        document.getElementById('edit-kode').value = category.kode_kategori || '';
        
        const editRak = document.getElementById('edit-rak');
        if (editRak) {
            const rakVal = category.lokasi_rak || '';
            let found = false;
            for (let i = 0; i < editRak.options.length; i++) {
                if (editRak.options[i].value === rakVal) {
                    found = true;
                    break;
                }
            }
            if (!found && rakVal !== '') {
                const opt = document.createElement('option');
                opt.value = rakVal;
                opt.textContent = rakVal;
                editRak.appendChild(opt);
            }
            editRak.value = rakVal;
        }

        document.getElementById('edit-deskripsi').value = category.deskripsi || '';
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

    // Close modal on Escape key
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
