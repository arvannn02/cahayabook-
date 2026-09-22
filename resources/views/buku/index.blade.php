@extends('layouts.app')

@section('title', 'Data Buku - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-[1360px] mx-auto">
    
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
            <form action="{{ route('buku.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">ISBN <span class="text-red-500">*</span></label>
                        <input type="text" name="isbn" required placeholder="Contoh: 978-602-123-456-0" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" required placeholder="Masukkan judul buku" class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="id_kategori" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penulis <span class="text-red-500">*</span></label>
                        <select name="id_penulis" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penulis</option>
                            @foreach($penulis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penulis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penerbit <span class="text-red-500">*</span></label>
                        <select name="id_penerbit" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penerbit</option>
                            @foreach($penerbit as $pub)
                                <option value="{{ $pub->id }}">{{ $pub->nama_penerbit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak <span class="text-red-500">*</span></label>
                        <select name="id_rak" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Rak</option>
                            @foreach($rak as $r)
                                <option value="{{ $r->id }}">{{ $r->kode_rak }} ({{ $r->lokasi }})</option>
                            @endforeach
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
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">ISBN <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-isbn" name="isbn" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" id="edit-judul" name="judul" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select id="edit-kategori" name="id_kategori" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penulis <span class="text-red-500">*</span></label>
                        <select id="edit-penulis" name="id_penulis" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penulis</option>
                            @foreach($penulis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penulis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Penerbit <span class="text-red-500">*</span></label>
                        <select id="edit-penerbit" name="id_penerbit" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Penerbit</option>
                            @foreach($penerbit as $pub)
                                <option value="{{ $pub->id }}">{{ $pub->nama_penerbit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#374151] mb-1">Lokasi Rak <span class="text-red-500">*</span></label>
                        <select id="edit-rak" name="id_rak" required class="w-full px-3.5 py-2 text-[13px] border border-[#d1d5db] rounded-lg focus:outline-none focus:border-[#2d4636] focus:ring-1 focus:ring-[#2d4636]">
                            <option value="">Pilih Rak</option>
                            @foreach($rak as $r)
                                <option value="{{ $r->id }}">{{ $r->kode_rak }} ({{ $r->lokasi }})</option>
                            @endforeach
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

<script>
    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
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
        document.getElementById('edit-isbn').value = book.isbn || '';
        document.getElementById('edit-judul').value = book.judul || '';
        document.getElementById('edit-kategori').value = book.id_kategori || '';
        document.getElementById('edit-penulis').value = book.id_penulis || '';
        document.getElementById('edit-penerbit').value = book.id_penerbit || '';
        document.getElementById('edit-rak').value = book.id_rak || '';
        document.getElementById('edit-tahun').value = book.tahun_terbit || '';
        document.getElementById('edit-harga-beli').value = book.harga_beli ? parseInt(book.harga_beli) : '';
        document.getElementById('edit-harga-jual').value = book.harga_jual ? parseInt(book.harga_jual) : '';
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

    // Close modal on escape key
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
