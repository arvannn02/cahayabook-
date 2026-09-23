@extends('layouts.app')

@section('title', 'Data Buku - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto relative">
    
    {{-- Floating Toast Notification for ISBN Alert --}}
    <div id="isbn-toast" class="fixed top-6 right-6 z-[9999] hidden max-w-sm w-full bg-white rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1),0_8px_10px_-6px_rgba(0,0,0,0.1)] border border-amber-300 p-4 transition-all duration-300 transform translate-y-[-10px] opacity-0 pointer-events-auto">
        <div class="flex items-start gap-3">
            <div id="isbn-toast-icon-wrapper" class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5 border border-amber-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 id="isbn-toast-title" class="text-[13px] font-bold text-[#111827]">Peringatan Validasi ISBN</h4>
                <p id="isbn-toast-message" class="text-[12px] text-[#4b5563] mt-0.5 leading-relaxed"></p>
            </div>
            <button type="button" onclick="hideIsbnToast()" class="text-[#9ca3af] hover:text-[#374151] rounded-md p-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="w-full bg-gray-100 h-1 rounded-full mt-3 overflow-hidden">
            <div id="isbn-toast-progress" class="bg-amber-500 h-full w-full"></div>
        </div>
    </div>

    {{-- Header Section --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-[28px] font-bold text-[#111827] tracking-tight leading-tight">Katalog Buku Utama</h1>
            <p class="text-[14px] text-[#6b7280] mt-1">Kelola seluruh basis data buku dari berbagai kategori dan penerbit secara efisien.</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2.5 rounded-lg text-[13.5px] font-medium transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Buku Baru</span>
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

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-7">
        {{-- Card 1: TOTAL BUKU --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-6 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
            <p class="text-[11px] font-bold text-[#9ca3af] uppercase tracking-wider">TOTAL BUKU</p>
            <h3 class="text-[32px] font-bold text-[#111827] mt-1.5 leading-none tracking-tight">{{ number_format($totalBuku) }}</h3>
            <p class="text-[12px] font-medium text-[#2d7a4d] mt-2">{{ number_format($totalStokFisik) }} unit fisik di rak</p>
        </div>

        {{-- Card 2: KATEGORI --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-6 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
            <p class="text-[11px] font-bold text-[#9ca3af] uppercase tracking-wider">KATEGORI</p>
            <h3 class="text-[32px] font-bold text-[#111827] mt-1.5 leading-none tracking-tight">{{ $totalKategori }}</h3>
            <p class="text-[12px] font-normal text-[#6b7280] mt-2">Aktif digunakan</p>
        </div>

        {{-- Card 3: PENERBIT --}}
        <div class="bg-white rounded-2xl border border-[#eef0f2] p-6 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
            <p class="text-[11px] font-bold text-[#9ca3af] uppercase tracking-wider">PENERBIT</p>
            <h3 class="text-[32px] font-bold text-[#111827] mt-1.5 leading-none tracking-tight">{{ $totalPenerbit }}</h3>
            <p class="text-[12px] font-normal text-[#6b7280] mt-2">Mitra terdaftar</p>
        </div>
    </div>

    {{-- Search Feedback Banner --}}
    @if(request('search'))
        <div class="mb-6 flex items-center justify-between bg-[#edf7ee] border border-[#cbe6d0] text-[#22543d] px-5 py-3.5 rounded-2xl shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#2d7a4d]/10 flex items-center justify-center text-[#2d7a4d]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div>
                    <span class="text-[13.5px] font-medium">Hasil pencarian untuk: <strong class="font-bold text-[#1a432d]">"{{ request('search') }}"</strong></span>
                    <span class="text-[12px] text-[#427a55] block">Ditemukan <strong>{{ $buku->total() }}</strong> buku sesuai kata kunci</span>
                </div>
            </div>
            <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#cbe6d0] hover:bg-[#f3f9f4] text-[#22543d] rounded-xl text-[12.5px] font-semibold transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset Pencarian
            </a>
        </div>
    @endif

    {{-- Main Table Container --}}
    <div class="bg-white rounded-2xl border border-[#eef0f2] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden">

        {{-- Toolbar Filters --}}
        <div class="px-6 py-4 border-b border-[#f3f4f6] flex items-center justify-between">
            <form method="GET" action="{{ route('buku.index') }}" id="filter-form" class="flex items-center gap-3">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                
                {{-- Dropdown Kategori --}}
                <div class="relative">
                    <select name="kategori" onchange="document.getElementById('filter-form').submit()"
                            class="appearance-none cursor-pointer pl-4 pr-9 py-2 text-[13px] font-medium text-[#374151] bg-[#f3f4f6] hover:bg-[#eaecee] border-0 rounded-lg focus:outline-none transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#6b7280]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Dropdown Status --}}
                <div class="relative">
                    <select name="status" onchange="document.getElementById('filter-form').submit()"
                            class="appearance-none cursor-pointer pl-4 pr-9 py-2 text-[13px] font-medium text-[#374151] bg-[#f3f4f6] hover:bg-[#eaecee] border-0 rounded-lg focus:outline-none transition-colors">
                        <option value="">Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>Stok Menipis</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#6b7280]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </form>

            {{-- Sort Options --}}
            <form method="GET" action="{{ route('buku.index') }}" id="sort-form" class="flex items-center">
                @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                @if(request('status'))  <input type="hidden" name="status"   value="{{ request('status') }}"> @endif
                @if(request('search'))  <input type="hidden" name="search"   value="{{ request('search') }}"> @endif
                
                <div class="relative">
                    <select name="sort" onchange="document.getElementById('sort-form').submit()"
                            class="appearance-none cursor-pointer pl-4 pr-9 py-2 text-[13px] font-medium text-[#374151] bg-[#f3f4f6] hover:bg-[#eaecee] border-0 rounded-lg focus:outline-none transition-colors">
                        <option value="terbaru"  {{ (request('sort','terbaru') === 'terbaru')  ? 'selected' : '' }}>Urutkan: Terbaru</option>
                        <option value="terlama"  {{ request('sort') === 'terlama'  ? 'selected' : '' }}>Urutkan: Terlama</option>
                        <option value="judul_az" {{ request('sort') === 'judul_az' ? 'selected' : '' }}>Urutkan: Judul A–Z</option>
                        <option value="judul_za" {{ request('sort') === 'judul_za' ? 'selected' : '' }}>Urutkan: Judul Z–A</option>
                        <option value="stok_desc"{{ request('sort') === 'stok_desc'? 'selected' : '' }}>Urutkan: Stok Terbanyak</option>
                        <option value="stok_asc" {{ request('sort') === 'stok_asc' ? 'selected' : '' }}>Urutkan: Stok Tersedikit</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#6b7280]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto min-h-[340px] overflow-visible">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#f3f4f6] bg-white">
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-6 py-4 w-[28%]">JUDUL & ISBN</th>
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-4 py-4 w-[16%]">PENULIS</th>
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-4 py-4 w-[15%]">PENERBIT</th>
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-4 py-4 w-[13%]">KATEGORI</th>
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-4 py-4 w-[10%]">JUMLAH</th>
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-4 py-4 w-[11%]">HARGA</th>
                        <th class="text-[11px] font-bold text-[#374151] uppercase tracking-wider px-6 py-4 w-[7%] text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f3f4f6]">
                    @forelse($buku as $item)
                    @php
                        $stokSisa  = $item->stok->stok_sisa  ?? 0;
                        $minStok   = $item->stok->min_stok   ?? 5;
                        $isLow     = $stokSisa <= $minStok;
                        $kategoriName = $item->kategori->nama_kategori ?? '-';
                        
                        // Badge styling based on category
                        $isPsikologi = str_contains(strtolower($kategoriName), 'psikologi');
                        $badgeBg = $isPsikologi ? 'bg-[#d2ded5]' : 'bg-[#c2e7cc]';
                        $badgeText = $isPsikologi ? 'text-[#556b5a]' : 'text-[#2b6b3e]';
                    @endphp
                    <tr class="hover:bg-[#fcfdfc] transition-colors group">
                        
                        {{-- Judul & ISBN --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                {{-- Book Cover Thumbnail --}}
                                <div class="w-[42px] h-[58px] rounded-[4px] shadow-sm border border-gray-200/80 overflow-hidden flex-shrink-0 relative select-none">
                                    @if($item->judul == 'The Silent Forest')
                                        <div class="w-full h-full bg-[#182626] flex flex-col items-center justify-center relative p-1">
                                            <div class="w-4 h-4 rounded-full border-[1.5px] border-[#4dd0e1] flex items-center justify-center shadow-[0_0_8px_rgba(77,208,225,0.6)]">
                                                <div class="w-1.5 h-1.5 rounded-full bg-[#4dd0e1]"></div>
                                            </div>
                                            <span class="text-[3.5px] text-teal-100/70 uppercase tracking-widest mt-2 font-mono">SILENT FOREST</span>
                                        </div>
                                    @elseif($item->judul == 'Membangun Fokus')
                                        <div class="w-full h-full bg-[#f4f7f4] border border-[#dce5dc] flex flex-col items-center justify-center p-1 relative">
                                            <div class="w-[30px] h-[44px] border border-[#a3b8a6] flex flex-col items-center justify-center text-center p-0.5">
                                                <span class="text-[4px] font-serif text-[#2f4233] uppercase leading-tight font-bold">Membangun<br>Fokus</span>
                                            </div>
                                        </div>
                                    @elseif($item->judul == 'Jejak Nusantara')
                                        <div class="w-full h-full bg-[#dfceb3] flex flex-col justify-between p-1.5 relative border border-[#cdb797]">
                                            <div class="w-full h-[3px] bg-[#8c5932] rounded-full mt-1"></div>
                                            <div class="text-center">
                                                <div class="w-2 h-2 rounded-full bg-[#8c5932] mx-auto mb-1"></div>
                                                <span class="text-[3.5px] font-bold text-[#5c371c] uppercase tracking-wider block">NUSANTARA</span>
                                            </div>
                                            <div class="w-full h-[2px] bg-[#8c5932]/40 rounded-full"></div>
                                        </div>
                                    @elseif($item->judul == 'Kuliner Tradisional')
                                        <div class="w-full h-full bg-[#1a1715] flex flex-col justify-between overflow-hidden relative">
                                            <div class="w-full h-[55%] bg-gradient-to-b from-[#3d251a] to-[#1a1715] p-1 flex items-center justify-center">
                                                <div class="w-4 h-4 rounded-full bg-gradient-to-tr from-[#d97706] to-[#fbbf24] shadow-[0_0_6px_rgba(245,158,11,0.5)]"></div>
                                            </div>
                                            <div class="p-1 bg-[#b91c1c] text-center">
                                                <span class="text-[3.5px] font-bold text-white uppercase tracking-wider block">KULINER</span>
                                            </div>
                                        </div>
                                    @elseif($item->sampul && file_exists(public_path('storage/' . $item->sampul)))
                                        <img src="{{ asset('storage/' . $item->sampul) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#2d4636] flex flex-col items-center justify-center text-white p-1 text-center">
                                            <span class="text-[4px] font-bold uppercase leading-tight">{{ \Illuminate\Support\Str::limit($item->judul, 15) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold text-[#111827] leading-snug">{{ $item->judul }}</p>
                                    <p class="text-[11.5px] text-[#6b7280] font-mono mt-0.5">ISBN: {{ $item->isbn }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Penulis --}}
                        <td class="px-4 py-5 align-middle">
                            <span class="text-[13.5px] text-[#374151] font-normal">{{ $item->penulis->nama_penulis ?? '-' }}</span>
                        </td>

                        {{-- Penerbit --}}
                        <td class="px-4 py-5 align-middle">
                            <span class="text-[13.5px] text-[#374151] font-normal">{{ $item->penerbit->nama_penerbit ?? '-' }}</span>
                        </td>

                        {{-- Kategori Badge --}}
                        <td class="px-4 py-5 align-middle">
                            @if($item->kategori)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9.5px] font-bold tracking-wider uppercase {{ $badgeBg }} {{ $badgeText }}">
                                    {{ $item->kategori->nama_kategori }}
                                </span>
                            @else
                                <span class="text-[#9ca3af] text-[13px]">-</span>
                            @endif
                        </td>

                        {{-- Jumlah --}}
                        <td class="px-4 py-5 align-middle">
                            <div class="flex items-start gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0 {{ $isLow ? 'bg-[#dc2626]' : 'bg-[#3b7c53]' }}"></span>
                                <div class="flex flex-col leading-tight">
                                    <span class="text-[13.5px] font-normal text-[#111827]">{{ $stokSisa }}</span>
                                    <span class="text-[12px] text-[#6b7280] mt-0.5">Eks</span>
                                </div>
                            </div>
                        </td>

                        {{-- Harga --}}
                        <td class="px-4 py-5 align-middle">
                            <div class="flex flex-col leading-tight">
                                <span class="text-[11px] font-bold text-[#2d4636] leading-none">Rp</span>
                                <span class="text-[14px] font-bold text-[#2d4636] leading-snug mt-0.5">
                                    {{ number_format($item->harga_jual, 0, ',', '.') }}
                                </span>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-5 align-middle text-right">
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
                                                onclick="showDeleteModal('{{ route('buku.destroy', $item) }}', '{{ addslashes($item->judul) }}'); closeAllDropdowns();"
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
                        <td colspan="7" class="px-6 py-16 text-center text-[#6b7280] text-[14px]">
                            Belum ada data buku yang sesuai dengan pencarian atau filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination --}}
        @if($buku->total() > 0)
        <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-[#f3f4f6] bg-white gap-3 select-none">
            <p class="text-[13px] text-[#6b7280]">
                Menampilkan <span class="font-semibold text-[#111827]">{{ $buku->firstItem() }}</span> – <span class="font-semibold text-[#111827]">{{ $buku->lastItem() }}</span> dari <span class="font-semibold text-[#111827]">{{ number_format($buku->total()) }}</span> buku
            </p>
            @if($buku->hasPages())
            <div class="flex items-center gap-1.5">
                {{-- Prev --}}
                @if($buku->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#9ca3af] cursor-not-allowed bg-white" title="Halaman Sebelumnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </span>
                @else
                    <a href="{{ $buku->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] hover:bg-[#f9fafb] hover:text-[#111827] transition-colors bg-white" title="Halaman Sebelumnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @endif

                {{-- Page Links (Sliding Window) --}}
                @php
                    $currentPage = $buku->currentPage();
                    $lastPage = $buku->lastPage();
                    $window = 1;
                    $start = max(1, $currentPage - $window);
                    $end = min($lastPage, $currentPage + $window);
                @endphp

                @if($start > 1)
                    <a href="{{ $buku->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] text-[13px] hover:bg-[#f9fafb] transition-colors bg-white">1</a>
                    @if($start > 2)
                        <span class="px-1 text-[#9ca3af] text-[13px] tracking-widest">...</span>
                    @endif
                @endif

                @for($p = $start; $p <= $end; $p++)
                    @if($p == $currentPage)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#2d4636] text-white text-[13px] font-semibold shadow-sm">{{ $p }}</span>
                    @else
                        <a href="{{ $buku->url($p) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] text-[13px] hover:bg-[#f9fafb] transition-colors bg-white">{{ $p }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span class="px-1 text-[#9ca3af] text-[13px] tracking-widest">...</span>
                    @endif
                    <a href="{{ $buku->url($lastPage) }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] text-[13px] hover:bg-[#f9fafb] transition-colors bg-white">{{ $lastPage }}</a>
                @endif

                {{-- Next --}}
                @if($buku->hasMorePages())
                    <a href="{{ $buku->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#374151] hover:bg-[#f9fafb] transition-colors bg-white" title="Halaman Selanjutnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-[#e5e7eb] text-[#9ca3af] cursor-not-allowed bg-white" title="Halaman Selanjutnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                @endif
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- MODAL: TAMBAH BUKU BARU --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Tambah Buku Baru</h3>
                        <p class="text-[12px] text-[#6b7280]">Lengkapi rincian informasi buku untuk inventaris.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body / Form --}}
            <form id="create-form" action="{{ route('buku.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[12px] font-semibold text-[#374151]">ISBN <span class="text-red-500">*</span></label>
                            <span id="create-isbn-counter" class="text-[11px] font-mono font-medium text-[#9ca3af]">0 / 13 digit</span>
                        </div>
                        <div class="relative">
                            <input type="text" 
                                   id="create-isbn" 
                                   name="isbn" 
                                   value="{{ old('isbn') }}" 
                                   required 
                                   inputmode="numeric" 
                                   maxlength="13" 
                                   placeholder="Contoh: 9786021234560" 
                                   autocomplete="off" 
                                   class="w-full px-3.5 py-2 text-[13px] font-mono border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] transition-all">
                        </div>
                        <div id="create-isbn-feedback" class="text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#6b7280]">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>ISBN harus tepat 13 digit angka (standar ISBN-13).</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul buku" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select id="create-kategori" name="id_kategori" required onchange="handleDropdownChange(this, 'kategori')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Kategori Baru...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penulis <span class="text-red-500">*</span></label>
                        <select id="create-penulis" name="id_penulis" required onchange="handleDropdownChange(this, 'penulis')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penulis</option>
                            @foreach($penulis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penulis }}</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Penulis Baru...</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penerbit <span class="text-red-500">*</span></label>
                        <select id="create-penerbit" name="id_penerbit" required onchange="handleDropdownChange(this, 'penerbit')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penerbit</option>
                            @foreach($penerbit as $pub)
                                <option value="{{ $pub->id }}">{{ $pub->nama_penerbit }}</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Penerbit Baru...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak <span class="text-red-500">*</span></label>
                        <select id="create-rak" name="id_rak" required onchange="handleDropdownChange(this, 'rak')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Rak</option>
                            @foreach($rak as $r)
                                <option value="{{ $r->id }}">{{ $r->kode_rak }} ({{ $r->lokasi }})</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Lokasi Rak Baru...</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" min="1900" max="{{ date('Y') }}" placeholder="2024" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Harga Beli (Rp)</label>
                        <input type="number" name="harga_beli" min="0" placeholder="0" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga_jual" min="0" required placeholder="0" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-[#eef0f2] flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm">
                        Simpan Buku
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- MODAL: EDIT BUKU --}}
<div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Edit Data Buku</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui informasi buku pada inventaris.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body / Form --}}
            <form id="edit-form" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[12px] font-semibold text-[#374151]">ISBN <span class="text-red-500">*</span></label>
                            <span id="edit-isbn-counter" class="text-[11px] font-mono font-medium text-[#9ca3af]">0 / 13 digit</span>
                        </div>
                        <div class="relative">
                            <input type="text" 
                                   id="edit-isbn" 
                                   name="isbn" 
                                   required 
                                   inputmode="numeric" 
                                   maxlength="13" 
                                   placeholder="Contoh: 9786021234560" 
                                   autocomplete="off" 
                                   class="w-full px-3.5 py-2 text-[13px] font-mono border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] transition-all">
                        </div>
                        <div id="edit-isbn-feedback" class="text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#6b7280]">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>ISBN harus tepat 13 digit angka (standar ISBN-13).</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-judul" name="judul" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select id="edit-kategori" name="id_kategori" required onchange="handleDropdownChange(this, 'kategori')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Kategori Baru...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penulis <span class="text-red-500">*</span></label>
                        <select id="edit-penulis" name="id_penulis" required onchange="handleDropdownChange(this, 'penulis')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penulis</option>
                            @foreach($penulis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penulis }}</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Penulis Baru...</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penerbit <span class="text-red-500">*</span></label>
                        <select id="edit-penerbit" name="id_penerbit" required onchange="handleDropdownChange(this, 'penerbit')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penerbit</option>
                            @foreach($penerbit as $pub)
                                <option value="{{ $pub->id }}">{{ $pub->nama_penerbit }}</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Penerbit Baru...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak <span class="text-red-500">*</span></label>
                        <select id="edit-rak" name="id_rak" required onchange="handleDropdownChange(this, 'rak')" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Rak</option>
                            @foreach($rak as $r)
                                <option value="{{ $r->id }}">{{ $r->kode_rak }} ({{ $r->lokasi }})</option>
                            @endforeach
                            <option value="__new__" class="font-bold text-[#2d4636] bg-[#edf3ee]">+ Tambah Lokasi Rak Baru...</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Tahun Terbit</label>
                        <input type="number" id="edit-tahun" name="tahun_terbit" min="1900" max="{{ date('Y') }}" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Harga Beli (Rp)</label>
                        <input type="number" id="edit-harga-beli" name="harga_beli" min="0" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="edit-harga-jual" name="harga_jual" min="0" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
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

{{-- MODAL: DETAIL BUKU --}}
<div id="detail-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Detail Informasi Buku</h3>
                        <p class="text-[12px] text-[#6b7280]">Informasi lengkap spesifikasi dan data inventaris buku.</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-5">
                {{-- Main Title Header --}}
                <div class="p-4 rounded-xl bg-[#fafbfa] border border-[#eef0f2]">
                    <div class="flex items-center gap-2 mb-1">
                        <span id="detail-badge-kategori" class="px-2.5 py-0.5 rounded-md text-[11.5px] font-medium bg-[#edf3ee] text-[#2d4636]">Kategori</span>
                        <span id="detail-badge-rak" class="px-2.5 py-0.5 rounded-md text-[11.5px] font-medium bg-blue-50 text-blue-700">Rak</span>
                    </div>
                    <h2 id="detail-judul" class="text-[18px] font-bold text-[#111827] leading-snug">Judul Buku</h2>
                    <p id="detail-isbn" class="text-[12.5px] text-[#6b7280] font-mono mt-0.5">ISBN: -</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Penulis</span>
                        <span id="detail-penulis" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Penerbit</span>
                        <span id="detail-penerbit" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Tahun Terbit</span>
                        <span id="detail-tahun" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Harga Beli</span>
                        <span id="detail-harga-beli" class="text-[13.5px] font-semibold text-gray-900 mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Harga Jual</span>
                        <span id="detail-harga-jual" class="text-[13.5px] font-semibold text-[#2d4636] mt-0.5 block">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <span class="text-[11.5px] text-gray-500 font-medium block">Sisa Stok</span>
                        <span id="detail-stok" class="text-[13.5px] font-bold text-gray-900 mt-0.5 block">-</span>
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

{{-- MODAL: TAMBAH CEPAT (QUICK ADD) UNTUK KATEGORI, PENULIS, PENERBIT, DAN RAK --}}
<div id="quick-modal" class="hidden fixed inset-0 z-[70] overflow-y-auto bg-black/50 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-[#e5e7eb] overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="quick-modal-icon-container" class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm flex-shrink-0">
                        {{-- Dynamic SVG Icon --}}
                    </div>
                    <div>
                        <h3 id="quick-modal-title" class="text-[15px] font-bold text-[#111827]">Tambah Cepat</h3>
                        <p id="quick-modal-subtitle" class="text-[11.5px] text-[#6b7280]">Tambahkan data baru langsung ke sistem.</p>
                    </div>
                </div>
                <button type="button" onclick="closeQuickModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Error Notification Box inside Modal --}}
            <div id="quick-modal-error" class="hidden mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-[12px] flex items-start gap-2">
                <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div id="quick-modal-error-msg" class="flex-1"></div>
            </div>

            {{-- Modal Body Form --}}
            <form id="quick-modal-form" onsubmit="submitQuickModal(event)" class="p-6 space-y-3.5">

                {{-- Fields for Kategori --}}
                <div id="quick-fields-kategori" class="space-y-3">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" id="quick-kat-nama" placeholder="Contoh: Fiksi Populer, Sejarah Islam" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Deskripsi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea id="quick-kat-deskripsi" rows="2" placeholder="Penjelasan singkat kategori buku..." class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                    </div>
                </div>

                {{-- Fields for Penulis --}}
                <div id="quick-fields-penulis" class="space-y-3 hidden">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Penulis <span class="text-red-500">*</span></label>
                        <input type="text" id="quick-penulis-nama" placeholder="Contoh: Tere Liye, Andrea Hirata" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kewarganegaraan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" id="quick-penulis-negara" placeholder="Contoh: Indonesia, Inggris" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Biografi Singkat <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea id="quick-penulis-bio" rows="2" placeholder="Biografi atau profil ringkas penulis..." class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                    </div>
                </div>

                {{-- Fields for Penerbit --}}
                <div id="quick-fields-penerbit" class="space-y-3.5 hidden">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Nama Penerbit <span class="text-red-500">*</span></label>
                        <input type="text" id="quick-penerbit-nama" placeholder="Contoh: Penerbit Bentang, Gramedia" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>

                    {{-- Negara / Wilayah Penerbit & Dropdown (Sesuai Foto-2) --}}
                    <div class="relative" id="country-dropdown-container">
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Negara / Wilayah Penerbit</label>
                        <button type="button" 
                                id="penerbit-country-btn" 
                                onclick="toggleCountryDropdown(event)" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 text-[13px] border border-[#d1d5db] rounded-xl bg-white hover:border-[#2d4636] focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] transition-all cursor-pointer shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <span id="penerbit-selected-flag-wrapper" class="inline-flex items-center justify-center w-5 h-3.5 rounded-[2px] overflow-hidden border border-gray-200/80 bg-gray-50 flex-shrink-0">
                                    <img id="penerbit-selected-flag-img" src="https://flagcdn.com/w40/id.png" alt="Indonesia" class="w-full h-full object-cover" onerror="this.style.display='none'; document.getElementById('penerbit-selected-flag-emoji').style.display='inline';">
                                    <span id="penerbit-selected-flag-emoji" style="display:none;" class="text-[12px]">🇮🇩</span>
                                </span>
                                <span id="penerbit-selected-name" class="font-medium text-gray-800">Indonesia</span>
                                <span id="penerbit-selected-dial" class="text-[11.5px] font-mono text-gray-500 font-semibold bg-gray-100 px-1.5 py-0.5 rounded">(+62)</span>
                            </div>
                            <svg id="penerbit-country-chevron" class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        {{-- Dropdown Menu (In-Flow, Tidak Terpotong, 195 Negara Bisa Di-Scroll Penuh) --}}
                        <div id="penerbit-country-menu" class="hidden mt-2 bg-white border border-[#d1d5db] rounded-xl shadow-md overflow-hidden transition-all duration-150" onclick="event.stopPropagation()">
                            {{-- Header with counter --}}
                            <div class="px-3.5 py-2 bg-[#f8faf9] border-b border-gray-100 flex items-center justify-between text-[11px] font-semibold text-gray-600">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Daftar Lengkap 195 Negara</span>
                                </span>
                                <span id="penerbit-country-counter-badge" class="px-2 py-0.5 rounded-full bg-[#edf3ee] text-[#2d4636] font-bold text-[10.5px]">195 Negara</span>
                            </div>

                            {{-- Search input --}}
                            <div class="p-2 border-b border-gray-100 bg-[#f9fafb]">
                                <div class="relative">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" 
                                           id="penerbit-country-search" 
                                           placeholder="Ketik untuk mencari dari 195 negara..." 
                                           autocomplete="off"
                                           oninput="filterCountries(this.value)"
                                           onkeydown="handleCountrySearchKeydown(event)"
                                           class="w-full pl-9 pr-3 py-1.5 text-[12.5px] border border-gray-300 rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-white">
                                </div>
                            </div>

                            {{-- List of 195 countries (Scrollable, Always Visible Scrollbar) --}}
                            <div id="penerbit-country-list" style="height: 250px; max-height: 250px; overflow-y: scroll; -webkit-overflow-scrolling: touch;" class="overscroll-contain divide-y divide-gray-50 py-1 text-[13px]">
                                {{-- Populated dynamically by JS with all 195 countries --}}
                            </div>
                            <div id="penerbit-country-empty" class="hidden p-4 text-center text-xs text-gray-400">
                                Negara tidak ditemukan
                            </div>

                            {{-- Footer with scroll indicator --}}
                            <div class="px-3 py-1.5 bg-[#f9fafb] border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500 select-none">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                    <span>Gulir ke bawah untuk melihat semua negara</span>
                                </span>
                                <span class="text-[10px] text-gray-400 font-mono">195 Negara Dunia</span>
                            </div>
                        </div>
                    </div>

                    {{-- Nomor Telepon (Maksimal 12 Digit Saja) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[12px] font-semibold text-[#374151]">Nomor Telepon <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <span id="quick-penerbit-telepon-counter" class="text-[11px] font-mono font-medium text-[#9ca3af]">0 / 12 digit</span>
                        </div>
                        <div class="flex rounded-lg border border-[#d1d5db] focus-within:border-[#2d4636] focus-within:ring-1 focus-within:ring-[#2d4636] overflow-hidden bg-white transition-all shadow-2xs">
                            <div id="quick-penerbit-prefix-badge" class="px-3 py-2 bg-[#f9fafb] border-r border-[#d1d5db] text-[#374151] font-mono text-[13px] font-bold flex items-center gap-1.5 select-none flex-shrink-0">
                                <span id="quick-penerbit-prefix-dial">+62</span>
                            </div>
                            <input type="text" 
                                   id="quick-penerbit-telepon" 
                                   inputmode="numeric" 
                                   maxlength="12" 
                                   placeholder="Contoh: 81234567890" 
                                   autocomplete="off" 
                                   class="w-full px-3.5 py-2 text-[13px] font-mono border-0 focus:outline-none bg-transparent text-[#111827]">
                        </div>
                        <div id="quick-penerbit-telepon-feedback" class="text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#6b7280]">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Maksimal 12 digit angka tanpa kode negara (Contoh: 81234567890).</span>
                        </div>
                    </div>

                    {{-- Alamat Kantor --}}
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Alamat Kantor <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea id="quick-penerbit-alamat" rows="2" placeholder="Alamat lengkap penerbit..." class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]"></textarea>
                    </div>
                </div>

                {{-- Fields for Rak --}}
                <div id="quick-fields-rak" class="space-y-3 hidden">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak <span class="text-red-500">*</span></label>
                        <input type="text" id="quick-rak-lokasi" placeholder="Contoh: Lantai 1 - Sayap Kanan, Rak D-02" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kode Rak <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="text" id="quick-rak-kode" placeholder="Otomatis (RAK-XX)" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kapasitas Max</label>
                            <input type="number" id="quick-rak-kapasitas" min="1" value="100" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-3 border-t border-[#eef0f2] flex items-center justify-end gap-3 mt-4">
                    <button type="button" onclick="closeQuickModal()" class="px-4 py-2 text-[13px] font-medium text-[#4b5563] hover:bg-[#f3f4f6] rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="quick-modal-submit-btn" class="px-5 py-2 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#22362a] rounded-lg transition-colors shadow-sm flex items-center gap-2">
                        <span id="quick-modal-btn-text">Simpan & Pilih</span>
                        <svg id="quick-modal-spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out;
    }
    /* Always visible prominent scrollbar for 195 countries list */
    #penerbit-country-list {
        scrollbar-width: thin;
        scrollbar-color: #2d4636 #e2e8f0;
    }
    #penerbit-country-list::-webkit-scrollbar {
        width: 10px;
    }
    #penerbit-country-list::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    #penerbit-country-list::-webkit-scrollbar-thumb {
        background-color: #2d4636;
        border-radius: 8px;
        border: 2px solid #f1f5f9;
    }
    #penerbit-country-list::-webkit-scrollbar-thumb:hover {
        background-color: #1b2e23;
    }
</style>

<script src="{{ asset('js/countries195.js') }}"></script>
<script>
    // ---- Toast Notification for ISBN ----
    var isbnToastTimeout;
    function showIsbnToast(title, message, isWarning) {
        if (typeof isWarning === 'undefined') isWarning = true;
        var toast = document.getElementById('isbn-toast');
        var toastTitle = document.getElementById('isbn-toast-title');
        var toastDesc = document.getElementById('isbn-toast-message');
        var iconWrapper = document.getElementById('isbn-toast-icon-wrapper');
        var progressBar = document.getElementById('isbn-toast-progress');

        if (!toast) return;

        clearTimeout(isbnToastTimeout);

        toastTitle.textContent = title;
        toastDesc.textContent = message;

        if (isWarning) {
            toast.className = toast.className.replace(/border-[a-z0-9-]+/g, 'border-amber-300');
            iconWrapper.className = 'w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5 border border-amber-200';
            iconWrapper.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
            progressBar.className = 'bg-amber-500 h-full w-full';
        } else {
            toast.className = toast.className.replace(/border-[a-z0-9-]+/g, 'border-red-300');
            iconWrapper.className = 'w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5 border border-red-200';
            iconWrapper.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            progressBar.className = 'bg-red-500 h-full w-full';
        }

        toast.classList.remove('hidden');
        void toast.offsetWidth;
        toast.classList.remove('translate-y-[-10px]', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        isbnToastTimeout = setTimeout(function() {
            hideIsbnToast();
        }, 5000);
    }

    function hideIsbnToast() {
        var toast = document.getElementById('isbn-toast');
        if (!toast) return;
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-[-10px]', 'opacity-0');
        setTimeout(function() {
            toast.classList.add('hidden');
        }, 300);
    }

    // ---- Setup ISBN Live Validation & Form Submission Guard ----
    function initIsbnValidation(inputId, counterId, feedbackId, formId) {
        var input = document.getElementById(inputId);
        var counter = document.getElementById(counterId);
        var feedback = document.getElementById(feedbackId);
        var form = document.getElementById(formId);

        if (!input || !counter || !feedback || !form) return null;

        function updateState() {
            var raw = input.value;
            var clean = raw.replace(/\D/g, '');

            if (raw.length > 13 || clean.length > 13) {
                clean = clean.slice(0, 13);
                showIsbnToast('Peringatan ISBN', 'Nomor ISBN maksimal 13 digit angka, tidak boleh lebih!', true);
            }

            if (input.value !== clean) {
                input.value = clean;
            }

            var len = clean.length;
            counter.textContent = len + ' / 13 digit';

            if (len === 0) {
                counter.className = 'text-[11px] font-mono font-medium text-[#9ca3af]';
                input.className = 'w-full px-3.5 py-2 text-[13px] font-mono border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] transition-all';
                feedback.innerHTML = '<svg class="w-3.5 h-3.5 flex-shrink-0 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>ISBN harus tepat 13 digit angka (standar ISBN-13).</span>';
                feedback.className = 'text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#6b7280]';
            } else if (len < 13) {
                counter.className = 'text-[11px] font-mono font-semibold text-amber-600';
                input.className = 'w-full px-3.5 py-2 text-[13px] font-mono border border-amber-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all';
                feedback.innerHTML = '<svg class="w-3.5 h-3.5 flex-shrink-0 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span>Kurang dari 13 digit (saat ini: <b>' + len + '</b> digit, kurang <b>' + (13 - len) + '</b> digit lagi).</span>';
                feedback.className = 'text-[11.5px] mt-1.5 flex items-center gap-1.5 text-amber-600';
            } else {
                counter.className = 'text-[11px] font-mono font-semibold text-[#2d7a4d]';
                input.className = 'w-full px-3.5 py-2 text-[13px] font-mono border border-[#2d7a4d] focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636] bg-green-50/20 transition-all';
                feedback.innerHTML = '<svg class="w-3.5 h-3.5 flex-shrink-0 text-[#2d7a4d]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span>Format ISBN valid (tepat 13 digit angka standar ISBN-13).</span>';
                feedback.className = 'text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#2d7a4d] font-medium';
            }
        }

        input.addEventListener('input', updateState);
        input.addEventListener('paste', function() {
            setTimeout(updateState, 10);
        });

        form.addEventListener('submit', function(e) {
            var clean = input.value.replace(/\D/g, '');
            if (clean.length !== 13) {
                e.preventDefault();
                e.stopPropagation();
                input.focus();
                input.classList.remove('animate-shake');
                void input.offsetWidth;
                input.classList.add('animate-shake');

                if (clean.length === 0) {
                    showIsbnToast('ISBN Wajib Diisi', 'Silakan masukkan nomor ISBN sebanyak 13 digit angka sebelum menyimpan.', false);
                } else if (clean.length < 13) {
                    showIsbnToast('ISBN Kurang dari 13 Digit', 'Nomor ISBN harus tepat 13 digit angka! Saat ini baru ' + clean.length + ' digit (kurang ' + (13 - clean.length) + ' digit lagi).', true);
                } else {
                    showIsbnToast('ISBN Melebihi Batas', 'Nomor ISBN tidak boleh lebih dari 13 digit angka!', true);
                }
                return false;
            }
        });

        return updateState;
    }

    var updateCreateIsbn = initIsbnValidation('create-isbn', 'create-isbn-counter', 'create-isbn-feedback', 'create-form');
    var updateEditIsbn = initIsbnValidation('edit-isbn', 'edit-isbn-counter', 'edit-isbn-feedback', 'edit-form');

    function openCreateModal() {
        ['create-kategori', 'create-penulis', 'create-penerbit', 'create-rak'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.dataset.prevValue = el.value || '';
        });
        document.getElementById('create-modal').classList.remove('hidden');
        if (updateCreateIsbn) updateCreateIsbn();
    }
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function openDetailModal(book) {
        document.getElementById('detail-judul').textContent = book.judul || '-';
        document.getElementById('detail-isbn').textContent = 'ISBN: ' + (book.isbn || '-');
        document.getElementById('detail-badge-kategori').textContent = book.kategori ? book.kategori.nama_kategori : 'Kategori Umum';
        document.getElementById('detail-badge-rak').textContent = book.rak ? (book.rak.kode_rak + ' (' + book.rak.lokasi + ')') : 'Lokasi Rak: -';
        document.getElementById('detail-penulis').textContent = book.penulis ? book.penulis.nama_penulis : '-';
        document.getElementById('detail-penerbit').textContent = book.penerbit ? book.penerbit.nama_penerbit : '-';
        document.getElementById('detail-tahun').textContent = book.tahun_terbit || '-';
        document.getElementById('detail-harga-beli').textContent = book.harga_beli ? 'Rp ' + parseInt(book.harga_beli).toLocaleString('id-ID') : '-';
        document.getElementById('detail-harga-jual').textContent = book.harga_jual ? 'Rp ' + parseInt(book.harga_jual).toLocaleString('id-ID') : '-';
        
        var stokVal = book.stok ? book.stok.stok_sisa : '-';
        document.getElementById('detail-stok').textContent = stokVal + ' Eks';

        document.getElementById('detail-modal').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
    }

    function openEditModal(book) {
        document.getElementById('edit-form').action = '/buku/' + book.id;
        var cleanIsbn = (book.isbn || '').replace(/\D/g, '');
        document.getElementById('edit-isbn').value = cleanIsbn;
        document.getElementById('edit-judul').value = book.judul || '';
        
        var editKat = document.getElementById('edit-kategori');
        if (editKat) { editKat.value = book.id_kategori || ''; editKat.dataset.prevValue = editKat.value; }
        
        var editPen = document.getElementById('edit-penulis');
        if (editPen) { editPen.value = book.id_penulis || ''; editPen.dataset.prevValue = editPen.value; }
        
        var editPub = document.getElementById('edit-penerbit');
        if (editPub) { editPub.value = book.id_penerbit || ''; editPub.dataset.prevValue = editPub.value; }
        
        var editRak = document.getElementById('edit-rak');
        if (editRak) { editRak.value = book.id_rak || ''; editRak.dataset.prevValue = editRak.value; }
        
        document.getElementById('edit-tahun').value = book.tahun_terbit || '';
        document.getElementById('edit-harga-beli').value = book.harga_beli ? parseInt(book.harga_beli) : '';
        document.getElementById('edit-harga-jual').value = book.harga_jual ? parseInt(book.harga_jual) : '';
        document.getElementById('edit-modal').classList.remove('hidden');
        if (updateEditIsbn) updateEditIsbn();
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    // Auto-reopen modal if validation errors exist
    @if($errors->has('isbn'))
        openCreateModal();
        showIsbnToast('Validasi Gagal', '{{ $errors->first('isbn') }}', false);
    @elseif($errors->any() && old('isbn'))
        openCreateModal();
    @endif

    // ---- 195 Countries Selector & Phone Input (Max 12 Digits) Sesuai Foto-2 ----
    var ALL_COUNTRIES_195 = [
        { code: "ID", name: "Indonesia", en: "Indonesia", dial: "+62", flag: "🇮🇩" },
        { code: "US", name: "Amerika Serikat", en: "United States", dial: "+1", flag: "🇺🇸" },
        { code: "GB", name: "Inggris", en: "United Kingdom", dial: "+44", flag: "🇬🇧" },
        { code: "MY", name: "Malaysia", en: "Malaysia", dial: "+60", flag: "🇲🇾" },
        { code: "SG", name: "Singapura", en: "Singapore", dial: "+65", flag: "🇸🇬" },
        { code: "JP", name: "Jepang", en: "Japan", dial: "+81", flag: "🇯🇵" },
        { code: "CN", name: "Tiongkok (China)", en: "China", dial: "+86", flag: "🇨🇳" },
        { code: "AF", name: "Afghanistan", en: "Afghanistan", dial: "+93", flag: "🇦🇫" },
        { code: "ZA", name: "Afrika Selatan", en: "South Africa", dial: "+27", flag: "🇿🇦" },
        { code: "AL", name: "Albania", en: "Albania", dial: "+355", flag: "🇦🇱" },
        { code: "DZ", name: "Aljazair", en: "Algeria", dial: "+213", flag: "🇩🇿" },
        { code: "AD", name: "Andorra", en: "Andorra", dial: "+376", flag: "🇦🇩" },
        { code: "AO", name: "Angola", en: "Angola", dial: "+244", flag: "🇦🇴" },
        { code: "AG", name: "Antigua dan Barbuda", en: "Antigua and Barbuda", dial: "+1268", flag: "🇦🇬" },
        { code: "SA", name: "Arab Saudi", en: "Saudi Arabia", dial: "+966", flag: "🇸🇦" },
        { code: "AR", name: "Argentina", en: "Argentina", dial: "+54", flag: "🇦🇷" },
        { code: "AM", name: "Armenia", en: "Armenia", dial: "+374", flag: "🇦🇲" },
        { code: "AU", name: "Australia", en: "Australia", dial: "+61", flag: "🇦🇺" },
        { code: "AT", name: "Austria", en: "Austria", dial: "+43", flag: "🇦🇹" },
        { code: "AZ", name: "Azerbaijan", en: "Azerbaijan", dial: "+994", flag: "🇦🇿" },
        { code: "BS", name: "Bahama", en: "Bahamas", dial: "+1242", flag: "🇧🇸" },
        { code: "BH", name: "Bahrain", en: "Bahrain", dial: "+973", flag: "🇧🇭" },
        { code: "BD", name: "Bangladesh", en: "Bangladesh", dial: "+880", flag: "🇧🇩" },
        { code: "BB", name: "Barbados", en: "Barbados", dial: "+1246", flag: "🇧🇧" },
        { code: "NL", name: "Belanda", en: "Netherlands", dial: "+31", flag: "🇳🇱" },
        { code: "BY", name: "Belarus", en: "Belarus", dial: "+375", flag: "🇧🇾" },
        { code: "BE", name: "Belgia", en: "Belgium", dial: "+32", flag: "🇧🇪" },
        { code: "BZ", name: "Belize", en: "Belize", dial: "+501", flag: "🇧🇿" },
        { code: "BJ", name: "Benin", en: "Benin", dial: "+229", flag: "🇧🇯" },
        { code: "BT", name: "Bhutan", en: "Bhutan", dial: "+975", flag: "🇧🇹" },
        { code: "BO", name: "Bolivia", en: "Bolivia", dial: "+591", flag: "🇧🇴" },
        { code: "BA", name: "Bosnia dan Herzegovina", en: "Bosnia and Herzegovina", dial: "+387", flag: "🇧🇦" },
        { code: "BW", name: "Botswana", en: "Botswana", dial: "+267", flag: "🇧🇼" },
        { code: "BR", name: "Brasil", en: "Brazil", dial: "+55", flag: "🇧🇷" },
        { code: "BN", name: "Brunei Darussalam", en: "Brunei", dial: "+673", flag: "🇧🇳" },
        { code: "BG", name: "Bulgaria", en: "Bulgaria", dial: "+359", flag: "🇧🇬" },
        { code: "BF", name: "Burkina Faso", en: "Burkina Faso", dial: "+226", flag: "🇧🇫" },
        { code: "BI", name: "Burundi", en: "Burundi", dial: "+257", flag: "🇧🇮" },
        { code: "CZ", name: "Ceko", en: "Czech Republic", dial: "+420", flag: "🇨🇿" },
        { code: "TD", name: "Chad", en: "Chad", dial: "+235", flag: "🇹🇩" },
        { code: "CL", name: "Chili", en: "Chile", dial: "+56", flag: "🇨🇱" },
        { code: "DK", name: "Denmark", en: "Denmark", dial: "+45", flag: "🇩🇰" },
        { code: "DJ", name: "Djibouti", en: "Djibouti", dial: "+253", flag: "🇩🇯" },
        { code: "DM", name: "Dominika", en: "Dominica", dial: "+1767", flag: "🇩🇲" },
        { code: "EC", name: "Ekuador", en: "Ecuador", dial: "+593", flag: "🇪🇨" },
        { code: "SV", name: "El Salvador", en: "El Salvador", dial: "+503", flag: "🇸🇻" },
        { code: "ER", name: "Eritrea", en: "Eritrea", dial: "+291", flag: "🇪🇷" },
        { code: "EE", name: "Estonia", en: "Estonia", dial: "+372", flag: "🇪🇪" },
        { code: "SZ", name: "Eswatini", en: "Eswatini", dial: "+268", flag: "🇸🇿" },
        { code: "ET", name: "Etiopia", en: "Ethiopia", dial: "+251", flag: "🇪🇹" },
        { code: "FJ", name: "Fiji", en: "Fiji", dial: "+679", flag: "🇫🇯" },
        { code: "PH", name: "Filipina", en: "Philippines", dial: "+63", flag: "🇵🇭" },
        { code: "FI", name: "Finlandia", en: "Finland", dial: "+358", flag: "🇫🇮" },
        { code: "GA", name: "Gabon", en: "Gabon", dial: "+241", flag: "🇬🇦" },
        { code: "GM", name: "Gambia", en: "Gambia", dial: "+220", flag: "🇬🇲" },
        { code: "GE", name: "Georgia", en: "Georgia", dial: "+995", flag: "🇬🇪" },
        { code: "GH", name: "Ghana", en: "Ghana", dial: "+233", flag: "🇬🇭" },
        { code: "GD", name: "Grenada", en: "Grenada", dial: "+1473", flag: "🇬🇩" },
        { code: "GT", name: "Guatemala", en: "Guatemala", dial: "+502", flag: "🇬🇹" },
        { code: "GN", name: "Guinea", en: "Guinea", dial: "+224", flag: "🇬🇳" },
        { code: "GW", name: "Guinea-Bissau", en: "Guinea-Bissau", dial: "+245", flag: "🇬🇼" },
        { code: "GQ", name: "Guinea Khatulistiwa", en: "Equatorial Guinea", dial: "+240", flag: "🇬🇶" },
        { code: "GY", name: "Guyana", en: "Guyana", dial: "+592", flag: "🇬🇾" },
        { code: "HT", name: "Haiti", en: "Haiti", dial: "+509", flag: "🇭🇹" },
        { code: "HN", name: "Honduras", en: "Honduras", dial: "+504", flag: "🇭🇳" },
        { code: "HU", name: "Hongaria", en: "Hungary", dial: "+36", flag: "🇭🇺" },
        { code: "IN", name: "India", en: "India", dial: "+91", flag: "🇮🇳" },
        { code: "IQ", name: "Irak", en: "Iraq", dial: "+964", flag: "🇮🇶" },
        { code: "IR", name: "Iran", en: "Iran", dial: "+98", flag: "🇮🇷" },
        { code: "IE", name: "Irlandia", en: "Ireland", dial: "+353", flag: "🇮🇪" },
        { code: "IS", name: "Islandia", en: "Iceland", dial: "+354", flag: "🇮🇸" },
        { code: "IL", name: "Israel", en: "Israel", dial: "+972", flag: "🇮🇱" },
        { code: "IT", name: "Italia", en: "Italy", dial: "+39", flag: "🇮🇹" },
        { code: "JM", name: "Jamaika", en: "Jamaica", dial: "+1876", flag: "🇯🇲" },
        { code: "DE", name: "Jerman", en: "Germany", dial: "+49", flag: "🇩🇪" },
        { code: "JO", name: "Yordania", en: "Jordan", dial: "+962", flag: "🇯🇴" },
        { code: "KH", name: "Kamboja", en: "Cambodia", dial: "+855", flag: "🇰🇭" },
        { code: "CM", name: "Kamerun", en: "Cameroon", dial: "+237", flag: "🇨🇲" },
        { code: "CA", name: "Kanada", en: "Canada", dial: "+1", flag: "🇨🇦" },
        { code: "KZ", name: "Kazakhstan", en: "Kazakhstan", dial: "+7", flag: "🇰🇿" },
        { code: "KE", name: "Kenya", en: "Kenya", dial: "+254", flag: "🇰🇪" },
        { code: "KG", name: "Kirgizstan", en: "Kyrgyzstan", dial: "+996", flag: "🇰🇬" },
        { code: "KI", name: "Kiribati", en: "Kiribati", dial: "+686", flag: "🇰🇮" },
        { code: "CO", name: "Kolombia", en: "Colombia", dial: "+57", flag: "🇨🇴" },
        { code: "KM", name: "Komoro", en: "Comoros", dial: "+269", flag: "🇰🇲" },
        { code: "CG", name: "Kongo", en: "Congo", dial: "+242", flag: "🇨🇬" },
        { code: "CD", name: "Kongo (DR)", en: "DR Congo", dial: "+243", flag: "🇨🇩" },
        { code: "KR", name: "Korea Selatan", en: "South Korea", dial: "+82", flag: "🇰🇷" },
        { code: "KP", name: "Korea Utara", en: "North Korea", dial: "+850", flag: "🇰🇵" },
        { code: "CR", name: "Kosta Rika", en: "Costa Rica", dial: "+506", flag: "🇨🇷" },
        { code: "HR", name: "Kroasia", en: "Croatia", dial: "+385", flag: "🇭🇷" },
        { code: "CU", name: "Kuba", en: "Cuba", dial: "+53", flag: "🇨🇺" },
        { code: "KW", name: "Kuwait", en: "Kuwait", dial: "+965", flag: "🇰🇼" },
        { code: "LA", name: "Laos", en: "Laos", dial: "+856", flag: "🇱🇦" },
        { code: "LV", name: "Latvia", en: "Latvia", dial: "+371", flag: "🇱🇻" },
        { code: "LB", name: "Lebanon", en: "Lebanon", dial: "+961", flag: "🇱🇧" },
        { code: "LS", name: "Lesotho", en: "Lesotho", dial: "+266", flag: "🇱🇸" },
        { code: "LR", name: "Liberia", en: "Liberia", dial: "+231", flag: "🇱🇷" },
        { code: "LY", name: "Libya", en: "Libya", dial: "+218", flag: "🇱🇾" },
        { code: "LI", name: "Liechtenstein", en: "Liechtenstein", dial: "+423", flag: "🇱🇮" },
        { code: "LT", name: "Lituania", en: "Lithuania", dial: "+370", flag: "🇱🇹" },
        { code: "LU", name: "Luksemburg", en: "Luxembourg", dial: "+352", flag: "🇱🇺" },
        { code: "MG", name: "Madagaskar", en: "Madagascar", dial: "+261", flag: "🇲🇬" },
        { code: "MK", name: "Makedonia Utara", en: "North Macedonia", dial: "+389", flag: "🇲🇰" },
        { code: "MV", name: "Maladewa", en: "Maldives", dial: "+960", flag: "🇲🇻" },
        { code: "MW", name: "Malawi", en: "Malawi", dial: "+265", flag: "🇲🇼" },
        { code: "ML", name: "Mali", en: "Mali", dial: "+223", flag: "🇲🇱" },
        { code: "MT", name: "Malta", en: "Malta", dial: "+356", flag: "🇲🇹" },
        { code: "MA", name: "Maroko", en: "Morocco", dial: "+212", flag: "🇲🇦" },
        { code: "MH", name: "Kepulauan Marshall", en: "Marshall Islands", dial: "+692", flag: "🇲🇭" },
        { code: "MR", name: "Mauritania", en: "Mauritania", dial: "+222", flag: "🇲🇷" },
        { code: "MU", name: "Mauritius", en: "Mauritius", dial: "+230", flag: "🇲🇺" },
        { code: "MX", name: "Meksiko", en: "Mexico", dial: "+52", flag: "🇲🇽" },
        { code: "EG", name: "Mesir", en: "Egypt", dial: "+20", flag: "🇪🇬" },
        { code: "FM", name: "Mikronesia", en: "Micronesia", dial: "+691", flag: "🇫🇲" },
        { code: "MD", name: "Moldova", en: "Moldova", dial: "+373", flag: "🇲🇩" },
        { code: "MC", name: "Monako", en: "Monaco", dial: "+377", flag: "🇲🇨" },
        { code: "MN", name: "Mongolia", en: "Mongolia", dial: "+976", flag: "🇲🇳" },
        { code: "ME", name: "Montenegro", en: "Montenegro", dial: "+382", flag: "🇲🇪" },
        { code: "MZ", name: "Mozambik", en: "Mozambique", dial: "+258", flag: "🇲🇿" },
        { code: "MM", name: "Myanmar", en: "Myanmar", dial: "+95", flag: "🇲🇲" },
        { code: "NA", name: "Namibia", en: "Namibia", dial: "+264", flag: "🇳🇦" },
        { code: "NR", name: "Nauru", en: "Nauru", dial: "+674", flag: "🇳🇷" },
        { code: "NP", name: "Nepal", en: "Nepal", dial: "+977", flag: "🇳🇵" },
        { code: "NE", name: "Niger", en: "Niger", dial: "+227", flag: "🇳🇪" },
        { code: "NG", name: "Nigeria", en: "Nigeria", dial: "+234", flag: "🇳🇬" },
        { code: "NI", name: "Nikaragua", en: "Nicaragua", dial: "+505", flag: "🇳🇮" },
        { code: "NO", name: "Norwegia", en: "Norway", dial: "+47", flag: "🇳🇴" },
        { code: "OM", name: "Oman", en: "Oman", dial: "+968", flag: "🇴🇲" },
        { code: "PK", name: "Pakistan", en: "Pakistan", dial: "+92", flag: "🇵🇰" },
        { code: "PW", name: "Palau", en: "Palau", dial: "+680", flag: "🇵🇼" },
        { code: "PS", name: "Palestina", en: "Palestine", dial: "+970", flag: "🇵🇸" },
        { code: "PA", name: "Panama", en: "Panama", dial: "+507", flag: "🇵🇦" },
        { code: "CI", name: "Pantai Gading", en: "Ivory Coast", dial: "+225", flag: "🇨🇮" },
        { code: "PG", name: "Papua Nugini", en: "Papua New Guinea", dial: "+675", flag: "🇵🇬" },
        { code: "PY", name: "Paraguay", en: "Paraguay", dial: "+595", flag: "🇵🇾" },
        { code: "PE", name: "Peru", en: "Peru", dial: "+51", flag: "🇵🇪" },
        { code: "PL", name: "Polandia", en: "Poland", dial: "+48", flag: "🇵🇱" },
        { code: "PT", name: "Portugal", en: "Portugal", dial: "+351", flag: "🇵🇹" },
        { code: "FR", name: "Prancis", en: "France", dial: "+33", flag: "🇫🇷" },
        { code: "QA", name: "Qatar", en: "Qatar", dial: "+974", flag: "🇶🇦" },
        { code: "CF", name: "Republik Afrika Tengah", en: "Central African Republic", dial: "+236", flag: "🇨🇫" },
        { code: "DO", name: "Republik Dominika", en: "Dominican Republic", dial: "+1809", flag: "🇩🇴" },
        { code: "RO", name: "Rumania", en: "Romania", dial: "+40", flag: "🇷🇴" },
        { code: "RU", name: "Rusia", en: "Russia", dial: "+7", flag: "🇷🇺" },
        { code: "RW", name: "Rwanda", en: "Rwanda", dial: "+250", flag: "🇷🇼" },
        { code: "KN", name: "Saint Kitts dan Nevis", en: "Saint Kitts and Nevis", dial: "+1869", flag: "🇰🇳" },
        { code: "LC", name: "Saint Lucia", en: "Saint Lucia", dial: "+1758", flag: "🇱🇨" },
        { code: "VC", name: "Saint Vincent dan Grenadines", en: "Saint Vincent and the Grenadines", dial: "+1784", flag: "🇻🇨" },
        { code: "WS", name: "Samoa", en: "Samoa", dial: "+685", flag: "🇼🇸" },
        { code: "SM", name: "San Marino", en: "San Marino", dial: "+378", flag: "🇸🇲" },
        { code: "ST", name: "Sao Tome dan Principe", en: "Sao Tome and Principe", dial: "+239", flag: "🇸🇹" },
        { code: "NZ", name: "Selandia Baru", en: "New Zealand", dial: "+64", flag: "🇳🇿" },
        { code: "SN", name: "Senegal", en: "Senegal", dial: "+221", flag: "🇸🇳" },
        { code: "RS", name: "Serbia", en: "Serbia", dial: "+381", flag: "🇷🇸" },
        { code: "SC", name: "Seychelles", en: "Seychelles", dial: "+248", flag: "🇸🇨" },
        { code: "SL", name: "Sierra Leone", en: "Sierra Leone", dial: "+232", flag: "🇸🇱" },
        { code: "CY", name: "Siprus", en: "Cyprus", dial: "+357", flag: "🇨🇾" },
        { code: "SI", name: "Slovenia", en: "Slovenia", dial: "+386", flag: "🇸🇮" },
        { code: "SK", name: "Slowakia", en: "Slovakia", dial: "+421", flag: "🇸🇰" },
        { code: "SB", name: "Kepulauan Solomon", en: "Solomon Islands", dial: "+677", flag: "🇸🇧" },
        { code: "SO", name: "Somalia", en: "Somalia", dial: "+252", flag: "🇸🇴" },
        { code: "ES", name: "Spanyol", en: "Spain", dial: "+34", flag: "🇪🇸" },
        { code: "LK", name: "Sri Lanka", en: "Sri Lanka", dial: "+94", flag: "🇱🇰" },
        { code: "SD", name: "Sudan", en: "Sudan", dial: "+249", flag: "🇸🇩" },
        { code: "SS", name: "Sudan Selatan", en: "South Sudan", dial: "+211", flag: "🇸🇸" },
        { code: "SY", name: "Suriah", en: "Syria", dial: "+963", flag: "🇸🇾" },
        { code: "SR", name: "Suriname", en: "Suriname", dial: "+597", flag: "🇸🇷" },
        { code: "SE", name: "Swedia", en: "Sweden", dial: "+46", flag: "🇸🇪" },
        { code: "CH", name: "Swiss", en: "Switzerland", dial: "+41", flag: "🇨🇭" },
        { code: "TJ", name: "Tajikistan", en: "Tajikistan", dial: "+992", flag: "🇹🇯" },
        { code: "CV", name: "Tanjung Verde", en: "Cape Verde", dial: "+238", flag: "🇨🇻" },
        { code: "TZ", name: "Tanzania", en: "Tanzania", dial: "+255", flag: "🇹🇿" },
        { code: "TH", name: "Thailand", en: "Thailand", dial: "+66", flag: "🇹🇭" },
        { code: "TL", name: "Timor Leste", en: "Timor-Leste", dial: "+670", flag: "🇹🇱" },
        { code: "TG", name: "Togo", en: "Togo", dial: "+228", flag: "🇹🇬" },
        { code: "TO", name: "Tonga", en: "Tonga", dial: "+676", flag: "🇹🇴" },
        { code: "TT", name: "Trinidad dan Tobago", en: "Trinidad and Tobago", dial: "+1868", flag: "🇹🇹" },
        { code: "TN", name: "Tunisia", en: "Tunisia", dial: "+216", flag: "🇹🇳" },
        { code: "TR", name: "Turki", en: "Turkey", dial: "+90", flag: "🇹🇷" },
        { code: "TM", name: "Turkmenistan", en: "Turkmenistan", dial: "+993", flag: "🇹🇲" },
        { code: "TV", name: "Tuvalu", en: "Tuvalu", dial: "+688", flag: "🇹🇻" },
        { code: "UG", name: "Uganda", en: "Uganda", dial: "+256", flag: "🇺🇬" },
        { code: "UA", name: "Ukraina", en: "Ukraine", dial: "+380", flag: "🇺🇦" },
        { code: "AE", name: "Uni Emirat Arab", en: "United Arab Emirates", dial: "+971", flag: "🇦🇪" },
        { code: "UY", name: "Uruguay", en: "Uruguay", dial: "+598", flag: "🇺🇾" },
        { code: "UZ", name: "Uzbekistan", en: "Uzbekistan", dial: "+998", flag: "🇺🇿" },
        { code: "VU", name: "Vanuatu", en: "Vanuatu", dial: "+678", flag: "🇻🇺" },
        { code: "VA", name: "Vatikan", en: "Vatican City", dial: "+379", flag: "🇻🇦" },
        { code: "VE", name: "Venezuela", en: "Venezuela", dial: "+58", flag: "🇻🇪" },
        { code: "VN", name: "Vietnam", en: "Vietnam", dial: "+84", flag: "🇻🇳" },
        { code: "YE", name: "Yaman", en: "Yemen", dial: "+967", flag: "🇾🇪" },
        { code: "GR", name: "Yunani", en: "Greece", dial: "+30", flag: "🇬🇷" },
        { code: "ZM", name: "Zambia", en: "Zambia", dial: "+260", flag: "🇿🇲" },
        { code: "ZW", name: "Zimbabwe", en: "Zimbabwe", dial: "+263", flag: "🇿🇼" }
    ];

    var currentSelectedCountry = ALL_COUNTRIES_195[0];

    function getCountryFlagHtml(c) {
        var lowerCode = c.code.toLowerCase();
        return '<span class="inline-flex items-center justify-center w-5 h-3.5 rounded-[2px] overflow-hidden border border-gray-200/80 bg-gray-50 flex-shrink-0">' +
               '<img src="https://flagcdn.com/w40/' + lowerCode + '.png" alt="' + c.name + '" class="w-full h-full object-cover" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'inline\';">' +
               '<span style="display:none;" class="text-[12px] leading-none">' + c.flag + '</span>' +
               '</span>';
    }

    function renderCountryList(query) {
        var listContainer = document.getElementById('penerbit-country-list');
        var emptyNotice = document.getElementById('penerbit-country-empty');
        if (!listContainer) return;

        var countries = (window.COUNTRIES_195 && window.COUNTRIES_195.length >= 195) ? window.COUNTRIES_195 : ALL_COUNTRIES_195;
        var counterBadge = document.getElementById('penerbit-country-counter-badge');
        var q = (query || '').toLowerCase().trim();

        var filtered = countries.filter(function(c) {
            if (!q) return true;
            return c.name.toLowerCase().includes(q) ||
                   c.en.toLowerCase().includes(q) ||
                   c.code.toLowerCase().includes(q) ||
                   c.dial.includes(q);
        });

        if (counterBadge) {
            counterBadge.textContent = q ? (filtered.length + ' dari 195 Negara') : (filtered.length + ' Negara');
        }

        if (filtered.length === 0) {
            listContainer.innerHTML = '';
            if (emptyNotice) emptyNotice.classList.remove('hidden');
            return;
        }

        if (emptyNotice) emptyNotice.classList.add('hidden');

        var html = '';
        filtered.forEach(function(c) {
            var isSelected = (currentSelectedCountry && currentSelectedCountry.code === c.code);
            var flagHtml = getCountryFlagHtml(c);
            var checkIcon = isSelected ?
                '<svg class="w-4 h-4 text-[#2d4636] flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>' : '';

            var itemBg = isSelected ? 'bg-gray-100/80 font-semibold text-[#111827]' : 'text-gray-700 hover:bg-gray-50';

            html += '<button type="button" onclick="selectPenerbitCountry(\'' + c.code + '\')" class="w-full px-3.5 py-2 text-left flex items-center justify-between transition-colors ' + itemBg + ' cursor-pointer">' +
                    '<div class="flex items-center gap-2.5 overflow-hidden">' +
                    flagHtml +
                    '<span class="truncate">' + c.name + '</span>' +
                    '<span class="text-[11px] font-mono text-gray-400 font-semibold flex-shrink-0">(' + c.dial + ')</span>' +
                    '</div>' +
                    checkIcon +
                    '</button>';
        });

        listContainer.innerHTML = html;
    }

    function toggleCountryDropdown(e) {
        if (e) e.stopPropagation();
        var menu = document.getElementById('penerbit-country-menu');
        var chevron = document.getElementById('penerbit-country-chevron');
        if (!menu) return;

        var isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
            var searchInput = document.getElementById('penerbit-country-search');
            if (searchInput) {
                searchInput.value = '';
                setTimeout(function() { searchInput.focus(); }, 50);
            }
            renderCountryList('');
            setupCountryListScroll();
        } else {
            closeCountryDropdown();
        }
    }

    function setupCountryListScroll() {
        var list = document.getElementById('penerbit-country-list');
        if (list && !list.dataset.wheelBound) {
            list.dataset.wheelBound = 'true';
            list.addEventListener('wheel', function(e) {
                list.scrollTop += e.deltaY;
                e.stopPropagation();
            }, { passive: true });
        }
    }

    function handleCountrySearchKeydown(e) {
        var list = document.getElementById('penerbit-country-list');
        if (!list) return;
        if (e.key === 'ArrowDown') {
            list.scrollTop += 42;
            e.preventDefault();
        } else if (e.key === 'ArrowUp') {
            list.scrollTop -= 42;
            e.preventDefault();
        } else if (e.key === 'Enter') {
            var firstBtn = list.querySelector('button');
            if (firstBtn) {
                firstBtn.click();
                e.preventDefault();
            }
        }
    }

    function closeCountryDropdown() {
        var menu = document.getElementById('penerbit-country-menu');
        var chevron = document.getElementById('penerbit-country-chevron');
        if (menu) menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }

    function filterCountries(q) {
        renderCountryList(q);
        setupCountryListScroll();
    }

    function selectPenerbitCountry(code) {
        var countries = (window.COUNTRIES_195 && window.COUNTRIES_195.length >= 195) ? window.COUNTRIES_195 : ALL_COUNTRIES_195;
        var found = countries.find(function(c) { return c.code === code; });
        if (!found) return;

        currentSelectedCountry = found;

        // Update trigger button (flag + name + dial)
        var nameEl = document.getElementById('penerbit-selected-name');
        var dialEl = document.getElementById('penerbit-selected-dial');
        var flagWrapper = document.getElementById('penerbit-selected-flag-wrapper');

        if (nameEl) nameEl.textContent = found.name;
        if (dialEl) dialEl.textContent = '(' + found.dial + ')';
        if (flagWrapper) flagWrapper.innerHTML = getCountryFlagHtml(found);

        // Update phone prefix badge
        var prefixDial = document.getElementById('quick-penerbit-prefix-dial');
        if (prefixDial) prefixDial.textContent = found.dial;

        closeCountryDropdown();

        // Focus phone input
        var phoneInput = document.getElementById('quick-penerbit-telepon');
        if (phoneInput) phoneInput.focus();
    }

    // Phone input restriction: max 12 digits, digits only, live counter & feedback
    function initPenerbitPhoneInput() {
        var input = document.getElementById('quick-penerbit-telepon');
        var counter = document.getElementById('quick-penerbit-telepon-counter');
        var feedback = document.getElementById('quick-penerbit-telepon-feedback');
        if (!input) return function() {};

        function update() {
            var raw = input.value;
            var clean = raw.replace(/\D/g, '');
            if (clean.length > 12) {
                clean = clean.substring(0, 12);
            }
            if (input.value !== clean) {
                input.value = clean;
            }

            var len = clean.length;
            if (counter) {
                counter.textContent = len + ' / 12 digit';
                if (len === 0) {
                    counter.className = 'text-[11px] font-mono font-medium text-[#9ca3af]';
                } else if (len < 12) {
                    counter.className = 'text-[11px] font-mono font-semibold text-[#2d4636]';
                } else {
                    counter.className = 'text-[11px] font-mono font-bold text-emerald-700';
                }
            }

            if (feedback) {
                if (len === 0) {
                    feedback.innerHTML = '<svg class="w-3.5 h-3.5 flex-shrink-0 text-[#9ca3af]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Maksimal 12 digit angka tanpa kode negara (Contoh: 81234567890).</span>';
                    feedback.className = 'text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#6b7280]';
                } else if (len < 12) {
                    feedback.innerHTML = '<svg class="w-3.5 h-3.5 flex-shrink-0 text-[#2d7a4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>Nomor valid (' + len + ' digit). Tersisa ' + (12 - len) + ' digit lagi.</span>';
                    feedback.className = 'text-[11.5px] mt-1.5 flex items-center gap-1.5 text-[#2d7a4d]';
                } else {
                    feedback.innerHTML = '<svg class="w-3.5 h-3.5 flex-shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg><span>Batas maksimal 12 digit telah tercapai.</span>';
                    feedback.className = 'text-[11.5px] mt-1.5 flex items-center gap-1.5 text-emerald-700 font-medium';
                }
            }
        }

        input.addEventListener('input', update);
        input.addEventListener('paste', function() {
            setTimeout(update, 10);
        });

        return update;
    }

    var updatePenerbitPhone = initPenerbitPhoneInput();

    // ---- Quick Add Modal Handling ----
    var currentQuickType = '';
    var currentQuickTargetSelectId = '';

    var quickModalConfigs = {
        kategori: {
            title: 'Tambah Kategori Baru',
            subtitle: 'Tambahkan master kategori buku baru langsung ke inventaris.',
            icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>',
            fieldsId: 'quick-fields-kategori',
            url: "{{ route('kategori.store') }}",
            focusId: 'quick-kat-nama'
        },
        penulis: {
            title: 'Tambah Penulis Baru',
            subtitle: 'Tambahkan data profil penulis baru ke basis data buku.',
            icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>',
            fieldsId: 'quick-fields-penulis',
            url: "{{ route('penulis.store') }}",
            focusId: 'quick-penulis-nama'
        },
        penerbit: {
            title: 'Tambah Penerbit Baru',
            subtitle: 'Tambahkan data perusahaan/lembaga penerbit buku baru.',
            icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
            fieldsId: 'quick-fields-penerbit',
            url: "{{ route('penerbit.store') }}",
            focusId: 'quick-penerbit-nama'
        },
        rak: {
            title: 'Tambah Lokasi Rak Baru',
            subtitle: 'Tambahkan titik lokasi rak penyimpanan buku di gudang/toko.',
            icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>',
            fieldsId: 'quick-fields-rak',
            url: "{{ route('rak.store') }}",
            focusId: 'quick-rak-lokasi'
        }
    };

    function handleDropdownChange(selectEl, type) {
        if (selectEl.value === '__new__') {
            selectEl.value = selectEl.dataset.prevValue || '';
            openQuickModal(type, selectEl.id);
        } else {
            selectEl.dataset.prevValue = selectEl.value;
        }
    }

    function openQuickModal(type, targetSelectId) {
        currentQuickType = type;
        currentQuickTargetSelectId = targetSelectId || ('create-' + type);

        var config = quickModalConfigs[type];
        if (!config) return;

        document.getElementById('quick-modal-title').textContent = config.title;
        document.getElementById('quick-modal-subtitle').textContent = config.subtitle;
        document.getElementById('quick-modal-icon-container').innerHTML = config.icon;

        ['quick-fields-kategori', 'quick-fields-penulis', 'quick-fields-penerbit', 'quick-fields-rak'].forEach(function(fid) {
            var el = document.getElementById(fid);
            if (el) el.classList.add('hidden');
        });

        var activeFieldset = document.getElementById(config.fieldsId);
        if (activeFieldset) activeFieldset.classList.remove('hidden');

        var errorBox = document.getElementById('quick-modal-error');
        if (errorBox) errorBox.classList.add('hidden');

        var form = document.getElementById('quick-modal-form');
        if (form) form.reset();
        
        if (type === 'rak') {
            var kapInput = document.getElementById('quick-rak-kapasitas');
            if (kapInput) kapInput.value = '100';
        } else if (type === 'penerbit') {
            selectPenerbitCountry('ID');
            var telInput = document.getElementById('quick-penerbit-telepon');
            if (telInput) telInput.value = '';
            if (updatePenerbitPhone) updatePenerbitPhone();
            closeCountryDropdown();
        }

        document.getElementById('quick-modal').classList.remove('hidden');

        setTimeout(function() {
            var focusEl = document.getElementById(config.focusId);
            if (focusEl) focusEl.focus();
        }, 100);
    }

    function closeQuickModal() {
        closeCountryDropdown();
        document.getElementById('quick-modal').classList.add('hidden');
    }

    async function submitQuickModal(e) {
        e.preventDefault();

        var config = quickModalConfigs[currentQuickType];
        if (!config) return;

        var payload = {};
        if (currentQuickType === 'kategori') {
            var nama = document.getElementById('quick-kat-nama').value.trim();
            if (!nama) {
                showQuickError('Nama Kategori wajib diisi.');
                return;
            }
            payload.nama_kategori = nama;
            payload.deskripsi = document.getElementById('quick-kat-deskripsi').value.trim();
        } else if (currentQuickType === 'penulis') {
            var nama = document.getElementById('quick-penulis-nama').value.trim();
            if (!nama) {
                showQuickError('Nama Penulis wajib diisi.');
                return;
            }
            payload.nama_penulis = nama;
            payload.kewarganegaraan = document.getElementById('quick-penulis-negara').value.trim();
            payload.bio = document.getElementById('quick-penulis-bio').value.trim();
        } else if (currentQuickType === 'penerbit') {
            var nama = document.getElementById('quick-penerbit-nama').value.trim();
            if (!nama) {
                showQuickError('Nama Penerbit wajib diisi.');
                return;
            }
            payload.nama_penerbit = nama;

            var rawDigits = document.getElementById('quick-penerbit-telepon').value.replace(/\D/g, '');
            if (rawDigits.length > 12) {
                rawDigits = rawDigits.substring(0, 12);
            }
            var dial = (currentSelectedCountry && currentSelectedCountry.dial) ? currentSelectedCountry.dial : '+62';
            payload.telepon = rawDigits ? (dial + ' ' + rawDigits) : '';
            payload.alamat = document.getElementById('quick-penerbit-alamat').value.trim();
        } else if (currentQuickType === 'rak') {
            var lokasi = document.getElementById('quick-rak-lokasi').value.trim();
            if (!lokasi) {
                showQuickError('Lokasi Rak wajib diisi.');
                return;
            }
            payload.lokasi = lokasi;
            payload.kode_rak = document.getElementById('quick-rak-kode').value.trim();
            payload.kapasitas_max = document.getElementById('quick-rak-kapasitas').value.trim() || 100;
        }

        var submitBtn = document.getElementById('quick-modal-submit-btn');
        var btnText = document.getElementById('quick-modal-btn-text');
        var spinner = document.getElementById('quick-modal-spinner');

        submitBtn.disabled = true;
        btnText.textContent = 'Menyimpan...';
        spinner.classList.remove('hidden');

        try {
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var response = await fetch(config.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            var result = await response.json();

            if (!response.ok) {
                var errorMsg = result.message || 'Gagal menyimpan data.';
                if (result.errors) {
                    var firstErr = Object.values(result.errors)[0];
                    if (Array.isArray(firstErr)) errorMsg = firstErr[0];
                }
                showQuickError(errorMsg);
                return;
            }

            var newItem = result.data;
            var newId = newItem.id;
            var newText = newItem.text;

            var selectPairs = [
                document.getElementById('create-' + currentQuickType),
                document.getElementById('edit-' + currentQuickType)
            ];

            selectPairs.forEach(function(sel) {
                if (!sel) return;
                var exists = false;
                for (var i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value == newId) {
                        exists = true;
                        break;
                    }
                }
                if (!exists) {
                    var opt = document.createElement('option');
                    opt.value = newId;
                    opt.textContent = newText;
                    
                    var newOpt = sel.querySelector('option[value="__new__"]');
                    if (newOpt) {
                        sel.insertBefore(opt, newOpt);
                    } else {
                        sel.appendChild(opt);
                    }
                }
            });

            var targetSel = document.getElementById(currentQuickTargetSelectId);
            if (targetSel) {
                targetSel.value = newId;
                targetSel.dataset.prevValue = newId;
            }

            closeQuickModal();
            showIsbnToast('Berhasil Ditambahkan', (config.title.replace('Tambah ', '')) + ' "' + newText + '" berhasil dibuat dan otomatis dipilih!', false);

        } catch (err) {
            showQuickError('Terjadi kesalahan jaringan atau server saat menyimpan data.');
        } finally {
            submitBtn.disabled = false;
            btnText.textContent = 'Simpan & Pilih';
            spinner.classList.add('hidden');
        }
    }

    function showQuickError(msg) {
        var errorBox = document.getElementById('quick-modal-error');
        var errorMsg = document.getElementById('quick-modal-error-msg');
        if (errorBox && errorMsg) {
            errorMsg.textContent = msg;
            errorBox.classList.remove('hidden');
        }
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
    document.addEventListener('click', function(event) {
        closeAllDropdowns();
        var countryCont = document.getElementById('country-dropdown-container');
        if (countryCont && !countryCont.contains(event.target)) {
            closeCountryDropdown();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCountryDropdown();
            closeQuickModal();
            closeCreateModal();
            closeEditModal();
            closeDetailModal();
            closeAllDropdowns();
        }
    });
</script>
@endsection
