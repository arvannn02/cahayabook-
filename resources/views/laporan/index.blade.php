@extends('layouts.app')

@section('title', 'Laporan Inventaris & Statistik - Cahaya Buku')

@section('content')
@php
    $selectedTanggal = $selectedTanggal ?? null;
@endphp
<div class="px-8 py-8 w-full max-w-7xl mx-auto print:p-0 print:max-w-full">

    {{-- Official Print Header (Only visible when printing / downloading) --}}
    <div class="hidden print:block mb-6 border-b-2 border-gray-800 pb-4">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-wide uppercase">Cahaya Buku - Sistem Inventaris</h1>
                <p class="text-sm font-semibold text-gray-700">Laporan Resmi Pergerakan Stok & Mutasi Buku</p>
                <p class="text-xs text-gray-500 mt-1">Basis Data Riil Sistem Inventaris Gudang</p>
            </div>
            <div class="text-right text-xs text-gray-600">
                <p><span class="font-semibold">Tanggal Cetak:</span> {{ date('d F Y, H:i') }}</p>
                <p><span class="font-semibold">Operator:</span> {{ auth()->user()->nama ?? auth()->user()->name ?? 'Administrator' }}</p>
                <p><span class="font-semibold">Periode Laporan:</span> 
                    {{ $selectedTahun !== 'all' ? 'Tahun ' . $selectedTahun : 'Semua Tahun' }}
                    {{ $selectedBulan !== 'all' ? ' • ' . ($namaBulanLengkap[(int)$selectedBulan] ?? '') : '' }}
                </p>
            </div>
        </div>
    </div>

{{-- Custom Styling for Real Interactive Calendar matching Cahaya Buku theme --}}
<style>
    @keyframes calFadeIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .cal-dropdown-animate {
        animation: calFadeIn 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

    {{-- Sub-Header Section & Filter Controls (Posisi persis sesuai foto: Kategori di atas, Tahun & Unduh Laporan berdampingan di bawah) --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8 print:hidden">
        <div>
            <h1 class="text-[28px] font-bold text-gray-900 leading-tight">Laporan Inventaris & Statistik</h1>
            <p class="text-[14px] text-gray-500 mt-1">
                Analisis data pergerakan buku dan performa gudang secara periodik dari basis data nyata.
            </p>
        </div>

        {{-- Filter Form --}}
        <form id="filterForm" method="GET" action="{{ route('laporan.index') }}" class="flex flex-col items-start lg:items-end gap-2.5">
            {{-- Baris 1: Dropdown Semua Kategori --}}
            <div class="relative w-full sm:w-[190px]">
                <select name="kategori" onchange="this.form.submit()" 
                        class="w-full appearance-none bg-white border border-gray-200 hover:border-gray-300 rounded-lg text-[13px] font-medium text-gray-700 pl-3.5 pr-9 py-2 focus:outline-none focus:ring-2 focus:ring-[#355240]/20 cursor-pointer shadow-sm transition-all">
                    <option value="all" {{ $selectedKategori === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ (string)$selectedKategori === (string)$kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            {{-- Baris 2: Kalender Real (Pilih Tanggal) & Tombol Unduh Laporan --}}
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5">

                {{-- Kalender Real yang Asli (Interactive Date Picker Popup) --}}
                <div class="relative" id="realCalendarContainer">
                    <input type="hidden" name="tanggal" id="filterTanggalInput" value="{{ $selectedTanggal }}">
                    
                    {{-- Trigger Button Kalender --}}
                    <button type="button" id="realCalendarTrigger" onclick="toggleRealCalendar(event)"
                            class="inline-flex items-center justify-between gap-2 min-w-[175px] bg-white border {{ $selectedTanggal && $selectedTanggal !== 'all' ? 'border-[#2d4636] ring-1 ring-[#2d4636]/30' : 'border-gray-200 hover:border-gray-300' }} rounded-lg text-[13px] font-medium text-gray-700 px-3 py-2 shadow-sm transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#2d4636]/20">
                        <span class="flex items-center gap-2 truncate text-gray-800 font-medium">
                            <svg class="w-4 h-4 {{ $selectedTanggal && $selectedTanggal !== 'all' ? 'text-[#2d4636]' : 'text-gray-500' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span id="calendarButtonText" class="truncate font-semibold">
                                @if($selectedTanggal && $selectedTanggal !== 'all')
                                    {{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d M Y') }}
                                @else
                                    Pilih Tanggal...
                                @endif
                            </span>
                        </span>
                        
                        @if($selectedTanggal && $selectedTanggal !== 'all')
                            <span onclick="event.stopPropagation(); clearDateFilter();" title="Hapus filter tanggal" 
                                  class="text-gray-400 hover:text-red-600 p-0.5 rounded-full hover:bg-gray-100 transition-colors cursor-pointer ml-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </span>
                        @else
                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        @endif
                    </button>

                    {{-- Popover Kalender Real yang Asli --}}
                    <div id="realCalendarDropdown" class="hidden absolute right-0 mt-2 w-[310px] bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 z-50 cal-dropdown-animate">
                        {{-- Header Kalender: Bulan, Tahun, Prev, Next --}}
                        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-gray-100">
                            <button type="button" onclick="calPrevMonth()" title="Bulan Sebelumnya"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>

                            <div class="flex items-center gap-1.5">
                                <select id="calMonthSelect" onchange="onCalMonthSelect(this.value)" 
                                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-[12.5px] font-bold text-gray-800 px-2 py-1 cursor-pointer focus:outline-none">
                                    <option value="0">Januari</option>
                                    <option value="1">Februari</option>
                                    <option value="2">Maret</option>
                                    <option value="3">April</option>
                                    <option value="4">Mei</option>
                                    <option value="5">Juni</option>
                                    <option value="6">Juli</option>
                                    <option value="7">Agustus</option>
                                    <option value="8">September</option>
                                    <option value="9">Oktober</option>
                                    <option value="10">November</option>
                                    <option value="11">Desember</option>
                                </select>

                                <select id="calYearSelect" onchange="onCalYearSelect(this.value)"
                                        class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-[12.5px] font-bold text-gray-800 px-2 py-1 cursor-pointer focus:outline-none">
                                </select>
                            </div>

                            <button type="button" onclick="calNextMonth()" title="Bulan Berikutnya"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>

                        {{-- Nama-Nama Hari --}}
                        <div class="grid grid-cols-7 gap-1 text-center mb-1.5">
                            <span class="text-[11px] font-bold text-red-500 py-0.5">Min</span>
                            <span class="text-[11px] font-semibold text-gray-500 py-0.5">Sen</span>
                            <span class="text-[11px] font-semibold text-gray-500 py-0.5">Sel</span>
                            <span class="text-[11px] font-semibold text-gray-500 py-0.5">Rab</span>
                            <span class="text-[11px] font-semibold text-gray-500 py-0.5">Kam</span>
                            <span class="text-[11px] font-semibold text-gray-500 py-0.5">Jum</span>
                            <span class="text-[11px] font-semibold text-gray-500 py-0.5">Sab</span>
                        </div>

                        {{-- Matriks Tanggal Kalender (Grid 7 Kolom) --}}
                        <div id="calGrid" class="grid grid-cols-7 gap-1 text-center">
                            <!-- Populated dynamically by JS -->
                        </div>

                        {{-- Footer: Aksi Cepat --}}
                        <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100 text-[12px]">
                            <button type="button" onclick="calPickToday()" class="font-bold text-[#2d4636] hover:underline cursor-pointer">
                                Hari Ini
                            </button>
                            @if($selectedTanggal && $selectedTanggal !== 'all')
                                <button type="button" onclick="clearDateFilter()" class="font-semibold text-red-600 hover:underline cursor-pointer">
                                    Hapus Filter
                                </button>
                            @endif
                            <button type="button" onclick="closeRealCalendar()" class="font-medium text-gray-500 hover:text-gray-700 cursor-pointer">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Reset Filter Button (opsional jika filter diterapkan) --}}
                @if($selectedKategori !== 'all' || ($selectedTanggal && $selectedTanggal !== 'all'))
                    <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1.5 px-2.5 py-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-[12.5px] font-semibold transition-colors" title="Reset filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif

                {{-- Tombol Unduh Laporan (Membuka Modal Konfirmasi) --}}
                <button type="button" onclick="openConfirmDownloadModal(event)" class="inline-flex items-center gap-2 bg-[#2d4636] hover:bg-[#22362a] text-white px-4 py-2 rounded-lg text-[13px] font-semibold transition-colors shadow-sm whitespace-nowrap cursor-pointer active:scale-[0.98]" title="Unduh Dokumen Laporan Resmi (Format PDF)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Unduh Laporan (PDF)</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Stats Cards (4 Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start">
                <div class="w-9 h-9 rounded-full bg-[#e8f3ea] flex items-center justify-center text-[#4a6b54]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="bg-gray-100 text-gray-600 rounded-full px-2.5 py-0.5 text-[11px] font-semibold">
                    {{ $totalMasukTrx }} In &bull; {{ $totalKeluarTrx }} Out
                </span>
            </div>
            <div class="mt-3">
                <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-1">TOTAL TRANSAKSI</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-[28px] font-bold text-gray-900 leading-none">{{ number_format($totalTransaksi) }}</span>
                    <span class="text-[12px] text-gray-400 font-medium">transaksi</span>
                </div>
            </div>
        </div>

        {{-- Buku Terlaris --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start">
                <div class="w-9 h-9 rounded-full bg-[#e8f3ea] flex items-center justify-center text-[#4a6b54]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                </div>
                <span class="bg-[#edf7ee] text-[#2d7a4d] rounded-full px-2.5 py-0.5 text-[11px] font-semibold">Terbanyak Keluar</span>
            </div>
            <div class="mt-3">
                <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-1">BUKU TERLARIS</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-[24px] font-bold text-gray-900 leading-none">{{ number_format($bukuTerlarisQty) }}</span>
                    <span class="text-[12px] text-gray-500 font-medium">Unit</span>
                </div>
                <p class="text-[12px] text-gray-500 truncate mt-1 max-w-[200px]" title="{{ $bukuTerlarisJudul }}">
                    {{ $bukuTerlarisJudul }}
                </p>
            </div>
        </div>

        {{-- Pertumbuhan Stok (Net Movement) --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start">
                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="bg-gray-100 text-gray-600 rounded-full px-2.5 py-0.5 text-[11px] font-semibold">
                    Net Fisik
                </span>
            </div>
            <div class="mt-3">
                <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-1">PERGERAKAN STOK</p>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-[28px] font-bold {{ $netMovement >= 0 ? 'text-[#2d7a4d]' : 'text-amber-600' }} leading-none">
                        {{ $netMovement >= 0 ? '+' : '' }}{{ number_format($netMovement) }}
                    </span>
                    <span class="text-[12px] text-gray-400 font-medium">unit</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">
                    +{{ number_format($totalUnitMasuk) }} masuk &bull; -{{ number_format($totalUnitKeluar) }} keluar
                </p>
            </div>
        </div>

        {{-- Efisiensi Rak --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start">
                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="bg-[#e8f3ea] text-[#4a6b54] rounded-full px-2.5 py-0.5 text-[11px] font-semibold">
                    {{ number_format($totalStokFisik) }} / {{ number_format($totalKapasitasRak) }}
                </span>
            </div>
            <div class="mt-3">
                <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-1">EFISIENSI RAK</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-[28px] font-bold text-gray-900 leading-none">{{ $efisiensiRak }}%</span>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-[#2d4636] h-full rounded-full transition-all duration-500" style="width: {{ min($efisiensiRak, 100) }}%"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts Section (2 Columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8 print:hidden">

        {{-- Left: Dynamic Bar Chart Card (7 cols) --}}
        <div class="lg:col-span-7 bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80 flex flex-col justify-between min-h-[380px]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-[17px] font-bold text-gray-900">Tren Pergerakan Buku</h3>
                    <p class="text-[12.5px] text-gray-500 mt-0.5">
                        Volume masuk & keluar {{ $selectedTahun !== 'all' ? 'tahun ' . $selectedTahun : 'semua periode' }}
                    </p>
                </div>
                <div class="flex items-center gap-4 text-[12px] font-medium text-gray-600">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2d4636]"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#ea580c]"></span> Keluar
                    </span>
                </div>
            </div>

            {{-- Canvas for Chart.js --}}
            <div class="w-full flex-1 relative min-h-[260px]">
                <canvas id="laporanTrendChart"></canvas>
            </div>
        </div>

        {{-- Right: Dynamic Donut Chart Card (5 cols) --}}
        <div class="lg:col-span-5 bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80 flex flex-col justify-between min-h-[380px]">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-[17px] font-bold text-gray-900">Distribusi Kategori</h3>
                <span class="text-[12px] font-medium text-gray-400">{{ $kategoriDistribution->count() }} Kategori</span>
            </div>

            {{-- Donut Chart Container --}}
            <div class="relative w-[180px] h-[180px] mx-auto my-2 flex items-center justify-center">
                <canvas id="laporanCategoryDonut"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center px-2">
                    <span class="text-[20px] font-bold text-gray-900 leading-none block">{{ number_format($totalStokDonut) }}</span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mt-0.5">TOTAL STOK</span>
                </div>
            </div>

            {{-- Dynamic Legend List --}}
            <div class="space-y-2 mt-2 pt-3 border-t border-gray-100 text-[13px] max-h-[140px] overflow-y-auto pr-1">
                @forelse($kategoriDistribution as $index => $item)
                    @php
                        $percent = $totalStokDonut > 0 ? round(($item->total_stok / $totalStokDonut) * 100) : 0;
                        $color = $donutColors[$index % count($donutColors)];
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-gray-700 font-medium truncate max-w-[200px]" title="{{ $item->nama_kategori }}">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $color }};"></span>
                            <span class="truncate">{{ $item->nama_kategori }}</span>
                        </span>
                        <div class="flex items-center gap-2 font-bold text-gray-900 flex-shrink-0 ml-2">
                            <span>{{ $percent }}%</span>
                            <span class="text-[11px] font-normal text-gray-400">({{ number_format($item->total_stok) }})</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-[12px] py-4">Belum ada data stok kategori</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- SECTION 1: Ringkasan Laporan Bulanan (5 Baris per Halaman dengan Slide Pagination) --}}
    <div class="bg-white border border-gray-100 rounded-[14px] shadow-sm overflow-hidden mb-8">
        {{-- Header persis sesuai foto ke-1 --}}
        <div class="flex items-center justify-between px-6 py-4.5 border-b border-gray-100">
            <div>
                <h3 class="text-[18px] font-bold text-gray-900">Ringkasan Laporan Bulanan</h3>
                <p class="text-[13px] text-gray-500 mt-0.5">Rincian mutasi buku bulanan terhitung dari database riil.</p>
            </div>
            <span class="text-[12px] font-semibold text-[#2d7a4d] bg-[#edf7ee] px-3.5 py-1 rounded-full border border-[#cbe6d0]">
                {{ count($monthlySummary) }} Periode Tercatat
            </span>
        </div>

        {{-- Tabel 5 Kolom persis sesuai foto ke-1 --}}
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/40">
                        <th class="text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider px-6 py-3.5 w-[28%]">BULAN</th>
                        <th class="text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider px-4 py-3.5 w-[18%]">BUKU MASUK</th>
                        <th class="text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider px-4 py-3.5 w-[18%]">BUKU KELUAR</th>
                        <th class="text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider px-4 py-3.5 w-[18%]">SELISIH STOK</th>
                        <th class="text-center text-[11px] font-bold text-gray-400 uppercase tracking-wider px-6 py-3.5 w-[18%]">STATUS</th>
                    </tr>
                </thead>
                <tbody id="summaryTableBody" class="divide-y divide-gray-100">
                    @forelse($monthlySummary as $index => $row)
                        @php
                            // Pembagian halaman: 5 baris per halaman (slide)
                            $pageNumber = floor($index / 5) + 1;
                        @endphp
                        <tr class="summary-slide-row hover:bg-gray-50/50 transition-colors" data-page="{{ $pageNumber }}" style="{{ $pageNumber > 1 ? 'display: none;' : '' }}">
                            <td class="px-6 py-4.5">
                                <span class="text-[14px] font-bold text-gray-900">{{ $row['bulan'] }}</span>
                            </td>
                            <td class="px-4 py-4.5 text-center">
                                <span class="text-[14px] font-bold text-gray-900">{{ number_format($row['masuk']) }}</span>
                                <span class="text-[12px] text-gray-400 font-normal"> unit</span>
                            </td>
                            <td class="px-4 py-4.5 text-center">
                                <span class="text-[14px] font-bold text-gray-900">{{ number_format($row['keluar']) }}</span>
                                <span class="text-[12px] text-gray-400 font-normal"> unit</span>
                            </td>
                            <td class="px-4 py-4.5 text-center">
                                <span class="text-[14px] font-bold text-[#2d7a4d]">
                                    {{ $row['selisih'] >= 0 ? '+' : '' }}{{ number_format($row['selisih']) }}
                                </span>
                                <span class="text-[12px] text-gray-400 font-normal"> unit</span>
                            </td>
                            <td class="px-6 py-4.5 text-center">
                                <span class="inline-flex px-3.5 py-1 bg-[#e8f3ea] text-[#4a6b54] rounded-full text-[11.5px] font-semibold">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-[13px]">
                                Tidak ada data transaksi untuk kategori atau periode yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Slide Pagination Component persis sesuai foto ke-2 --}}
        @php
            $totalSummaryPages = ceil(count($monthlySummary) / 5);
        @endphp
        @if($totalSummaryPages > 1)
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between print:hidden bg-white">
                <p class="text-[12.5px] text-gray-500" id="summaryPaginationInfo">
                    Menampilkan <strong>1 - {{ min(5, count($monthlySummary)) }}</strong> dari <strong>{{ count($monthlySummary) }}</strong> periode
                </p>
                <div class="flex items-center gap-1.5" id="summaryPaginationControls">
                    {{-- Previous Button (<) --}}
                    <button type="button" onclick="goToSummaryPage(currentSummaryPage - 1)" id="btnSummaryPrev"
                            class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    {{-- Page Buttons (1, 2, 3...) sesuai desain foto ke-2 --}}
                    @for($p = 1; $p <= $totalSummaryPages; $p++)
                        <button type="button" onclick="goToSummaryPage({{ $p }})" id="summaryPageBtn-{{ $p }}"
                                class="w-8 h-8 rounded-lg font-bold text-[13px] flex items-center justify-center transition-colors {{ $p === 1 ? 'bg-[#2d4636] text-white shadow-sm border border-[#2d4636]' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                            {{ $p }}
                        </button>
                    @endfor

                    {{-- Next Button (>) --}}
                    <button type="button" onclick="goToSummaryPage(currentSummaryPage + 1)" id="btnSummaryNext"
                            class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
        @endif
    </div>


</div>

{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ==================== 0. REAL INTERACTIVE CALENDAR ENGINE ====================
let calSelectedDate = @json($selectedTanggal); // e.g. "2026-09-17" or null
let calCurrentYear = 2026;
let calCurrentMonth = 8; // 0-indexed (8 = September)

// Parse existing selected date or active year
if (calSelectedDate && calSelectedDate !== 'all') {
    const parts = calSelectedDate.split('-');
    if (parts.length === 3) {
        calCurrentYear = parseInt(parts[0], 10);
        calCurrentMonth = parseInt(parts[1], 10) - 1;
    }
} else {
    const activeTahun = @json($selectedTahun);
    if (activeTahun && activeTahun !== 'all') {
        calCurrentYear = parseInt(activeTahun, 10);
    } else {
        calCurrentYear = (new Date()).getFullYear();
    }
    calCurrentMonth = (new Date()).getMonth();
}

function initRealCalendar() {
    const yearSelect = document.getElementById('calYearSelect');
    if (!yearSelect) return;

    // Populate Year Select (only current/future years, 2023 excluded)
    yearSelect.innerHTML = '';
    const availableYears = @json($availableYears);
    const yearsList = new Set([...(availableYears || []), 2026]);
    const sortedYears = Array.from(yearsList).sort((a, b) => b - a);

    sortedYears.forEach(y => {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        if (y === calCurrentYear) opt.selected = true;
        yearSelect.appendChild(opt);
    });

    renderCalendar();
}

function renderCalendar() {
    const monthSelect = document.getElementById('calMonthSelect');
    const yearSelect = document.getElementById('calYearSelect');
    const grid = document.getElementById('calGrid');
    if (!monthSelect || !yearSelect || !grid) return;

    monthSelect.value = calCurrentMonth;
    yearSelect.value = calCurrentYear;
    grid.innerHTML = '';

    const firstDayIndex = new Date(calCurrentYear, calCurrentMonth, 1).getDay();
    const daysInMonth = new Date(calCurrentYear, calCurrentMonth + 1, 0).getDate();
    const daysInPrevMonth = new Date(calCurrentYear, calCurrentMonth, 0).getDate();

    const today = new Date();
    const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

    // Prev month padding days
    for (let i = firstDayIndex - 1; i >= 0; i--) {
        const prevDay = daysInPrevMonth - i;
        const cell = document.createElement('div');
        cell.className = 'text-[11.5px] text-gray-300 py-1.5 rounded-lg select-none';
        cell.textContent = prevDay;
        grid.appendChild(cell);
    }

    // Days of current month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayStr = String(day).padStart(2, '0');
        const monthStr = String(calCurrentMonth + 1).padStart(2, '0');
        const formattedDate = `${calCurrentYear}-${monthStr}-${dayStr}`;

        const isSelected = (calSelectedDate === formattedDate);
        const isToday = (todayStr === formattedDate);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = day;
        btn.setAttribute('data-date', formattedDate);
        btn.onclick = () => selectCalDate(formattedDate);

        let classes = 'text-[12px] py-1.5 rounded-lg transition-all font-medium flex items-center justify-center cursor-pointer ';
        if (isSelected) {
            classes += 'bg-[#2d4636] text-white font-bold shadow-sm ring-2 ring-[#2d4636]/30';
        } else if (isToday) {
            classes += 'border border-[#2d4636] text-[#2d4636] font-bold hover:bg-[#e8f3ea]';
        } else {
            classes += 'text-gray-700 hover:bg-[#e8f3ea] hover:text-[#2d4636]';
        }

        btn.className = classes;
        grid.appendChild(btn);
    }
}

function selectCalDate(dateStr) {
    document.getElementById('filterTanggalInput').value = dateStr;
    closeRealCalendar();
    document.getElementById('filterForm').submit();
}

function calPickToday() {
    const today = new Date();
    const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    selectCalDate(todayStr);
}

function clearDateFilter() {
    document.getElementById('filterTanggalInput').value = '';
    document.getElementById('filterForm').submit();
}

function calPrevMonth() {
    if (calCurrentMonth === 0) {
        calCurrentMonth = 11;
        calCurrentYear--;
    } else {
        calCurrentMonth--;
    }
    renderCalendar();
}

function calNextMonth() {
    if (calCurrentMonth === 11) {
        calCurrentMonth = 0;
        calCurrentYear++;
    } else {
        calCurrentMonth++;
    }
    renderCalendar();
}

function onCalMonthSelect(val) {
    calCurrentMonth = parseInt(val, 10);
    renderCalendar();
}

function onCalYearSelect(val) {
    calCurrentYear = parseInt(val, 10);
    renderCalendar();
}

function toggleRealCalendar(e) {
    if (e) e.stopPropagation();
    const dropdown = document.getElementById('realCalendarDropdown');
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        renderCalendar();
    } else {
        dropdown.classList.add('hidden');
    }
}

function closeRealCalendar() {
    const dropdown = document.getElementById('realCalendarDropdown');
    if (dropdown) dropdown.classList.add('hidden');
}

// Close calendar when clicking outside
document.addEventListener('click', function(e) {
    const container = document.getElementById('realCalendarContainer');
    if (container && !container.contains(e.target)) {
        closeRealCalendar();
    }
});

// ==================== 1. SLIDE PAGINATION FOR RINGKASAN LAPORAN BULANAN (5 Baris per Slide) ====================
let currentSummaryPage = 1;
const totalSummaryPages = {{ $totalSummaryPages }};
const totalSummaryItems = {{ count($monthlySummary) }};

function goToSummaryPage(page) {
    if (page < 1 || page > totalSummaryPages) return;
    currentSummaryPage = page;

    const rows = document.querySelectorAll('.summary-slide-row');
    rows.forEach(row => {
        if (parseInt(row.getAttribute('data-page')) === page) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Update buttons styling (desain foto ke-2: aktif hijau gelap, non-aktif border abu)
    for (let p = 1; p <= totalSummaryPages; p++) {
        const btn = document.getElementById(`summaryPageBtn-${p}`);
        if (btn) {
            if (p === page) {
                btn.className = 'w-8 h-8 rounded-lg font-bold text-[13px] flex items-center justify-center transition-colors bg-[#2d4636] text-white shadow-sm border border-[#2d4636]';
            } else {
                btn.className = 'w-8 h-8 rounded-lg font-semibold text-[13px] flex items-center justify-center transition-colors bg-white text-gray-700 border border-gray-200 hover:bg-gray-50';
            }
        }
    }

    // Update prev/next button state
    const btnPrev = document.getElementById('btnSummaryPrev');
    const btnNext = document.getElementById('btnSummaryNext');
    if (btnPrev) btnPrev.disabled = (page === 1);
    if (btnNext) btnNext.disabled = (page === totalSummaryPages);

    // Update pagination info text
    const info = document.getElementById('summaryPaginationInfo');
    if (info) {
        const start = (page - 1) * 5 + 1;
        const end = Math.min(page * 5, totalSummaryItems);
        info.innerHTML = `Menampilkan <strong>${start} - ${end}</strong> dari <strong>${totalSummaryItems}</strong> periode`;
    }
}

// Initialize calendar and prev button on load
document.addEventListener('DOMContentLoaded', () => {
    initRealCalendar();
    const btnPrev = document.getElementById('btnSummaryPrev');
    if (btnPrev) btnPrev.disabled = true;
});




// ==================== 3. CHART.JS INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function () {
    // Bar Chart
    const labels = @json($monthlyLabels);
    const masukData = @json($monthlyMasuk);
    const keluarData = @json($monthlyKeluar);

    const ctxTrend = document.getElementById('laporanTrendChart');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Buku Masuk',
                        data: masukData,
                        backgroundColor: '#2d4636',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.65,
                    },
                    {
                        label: 'Buku Keluar',
                        data: keluarData,
                        backgroundColor: '#ea580c',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.65,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.parsed.y} unit`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' }, color: '#6b7280' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            precision: 0,
                            font: { size: 11 },
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });
    }

    // Donut Chart
    const donutLabels = @json($donutLabels);
    const donutData = @json($donutData);
    const donutColors = @json($donutColors);

    const ctxDonut = document.getElementById('laporanCategoryDonut');
    if (ctxDonut) {
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutData.length > 0 ? donutData : [1],
                    backgroundColor: donutData.length > 0 ? donutColors.slice(0, donutData.length) : ['#e5e7eb'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 8,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.parsed} unit`;
                            }
                        }
                    }
                }
            }
        });
    }
});

// Modal Konfirmasi Unduh Laporan PDF
function openConfirmDownloadModal(e) {
    if (e) e.preventDefault();
    const modal = document.getElementById('modal-confirm-download');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

function closeConfirmDownloadModal() {
    const modal = document.getElementById('modal-confirm-download');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function prosesUnduhPdf() {
    closeConfirmDownloadModal();
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    window.location.href = "{{ route('laporan.exportPdf') }}?" + params.toString();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmDownloadModal();
    }
});
</script>

{{-- GLOBAL MODAL KONFIRMASI UNDUH LAPORAN PDF (DESAIN PERSIS FOTO KE-2) --}}
<div id="modal-confirm-download" class="hidden fixed inset-0 z-[9999] overflow-y-auto bg-black/50 backdrop-blur-sm transition-all duration-200" onclick="if(event.target === this) closeConfirmDownloadModal()">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150 p-6" onclick="event.stopPropagation()">
            <div class="flex items-start gap-4">
                {{-- Icon PDF Download --}}
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#2d4636] flex items-center justify-center flex-shrink-0 border border-emerald-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-[17px] font-bold text-[#111827] leading-snug">Konfirmasi Unduh Laporan?</h3>
                    <p class="text-[13px] text-[#6b7280] mt-1.5 leading-relaxed">
                        Apakah Anda yakin ingin membuat dan mengunduh berkas laporan inventaris ini dalam format PDF?
                    </p>
                    
                    {{-- Notice Box (Amber Notice sesuai foto ke-2) --}}
                    <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-[12px] text-amber-900 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="leading-relaxed">
                            Pastikan filter data yang Anda pilih sudah sesuai. Berkas PDF resmi akan langsung diunduh ke perangkat Anda.
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeConfirmDownloadModal()" class="px-4 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="button" onclick="prosesUnduhPdf()" class="inline-flex items-center gap-2 px-5 py-2.5 text-[13px] font-semibold text-white bg-[#2d4636] hover:bg-[#203426] rounded-xl transition-all shadow-sm cursor-pointer active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Ya, Unduh Laporan</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
