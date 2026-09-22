@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Stok Buku -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 flex gap-5 items-center">
            <div class="w-[60px] h-[60px] rounded-[14px] bg-[#d5f0d5] flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-gray-400 tracking-wide mb-1 uppercase">TOTAL STOK BUKU</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-[28px] font-bold text-gray-800 leading-none">{{ number_format($totalStokFisik) }}</h3>
                    <span class="text-[12px] font-medium text-gray-500">unit</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1.5 text-[12px]">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-[#eaf4eb] text-[#355240]">
                        {{ $totalJudulBuku }} Judul
                    </span>
                    <span class="text-gray-400">terdaftar</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Stok Tersedia -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 flex gap-5 items-center">
            <div class="w-[60px] h-[60px] rounded-[14px] bg-[#d5e8db] flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-gray-400 tracking-wide mb-1 uppercase">TERSEDIA DI RAK</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-[28px] font-bold text-gray-800 leading-none">{{ number_format($totalStokFisik) }}</h3>
                    <span class="text-[12px] font-medium text-gray-500">unit</span>
                </div>
                <div class="flex items-center gap-1 mt-1.5 text-[12px]">
                    <span class="text-[#355240] font-semibold">{{ $persentaseTersedia }}%</span>
                    <span class="text-gray-400">dari total masuk</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Buku Baru Minggu Ini -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 flex gap-5 items-center">
            <div class="w-[60px] h-[60px] rounded-[14px] bg-gray-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold text-gray-400 tracking-wide mb-1 uppercase">BUKU MASUK BARU</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-[28px] font-bold text-gray-800 leading-none">{{ number_format($bukuBaruMingguIni) }}</h3>
                    <span class="text-[12px] font-medium text-gray-500">unit</span>
                </div>
                <div class="flex items-center gap-1 mt-1.5 text-[12px]">
                    <span class="text-gray-400">Minggu ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Chart: Statistik Inventaris Bulanan -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 lg:w-2/3 flex flex-col justify-between min-h-[420px]">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-4">
                <div>
                    <h3 id="chart-main-title" class="font-bold text-[16px] text-gray-800">Statistik Inventaris Bulanan</h3>
                    <p id="chart-main-subtitle" class="text-[13px] text-gray-500 mt-1">Pergerakan buku masuk dan keluar {{ $selectedYear === 'all' ? 'seluruh periode' : 'tahun ' . $selectedYear }}</p>
                </div>
                <!-- Filter & Toggle Tab Buttons -->
                <div class="flex items-center gap-2 self-start flex-wrap">
                    <!-- Year Filter -->
                    <div class="relative">
                        <select onchange="window.location.href='{{ route('dashboard') }}?tahun=' + this.value" 
                                class="appearance-none bg-gray-100 hover:bg-gray-200/80 border border-gray-200 text-gray-700 text-[12px] font-semibold pl-3 pr-7 py-1.5 rounded-lg focus:outline-none cursor-pointer transition-all">
                            <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ (string)$selectedYear === (string)$yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Toggle Tab Buttons -->
                    <div class="flex bg-gray-100 p-1 rounded-lg border border-gray-200">
                        <button id="btn-chart-volume" onclick="switchMainChart('volume')" 
                                class="px-4 py-1.5 text-[12px] font-bold bg-white text-gray-800 rounded-md shadow-sm transition-all">
                            Volume
                        </button>
                        <button id="btn-chart-kategori" onclick="switchMainChart('kategori')" 
                                class="px-4 py-1.5 text-[12px] font-medium text-gray-500 hover:text-gray-800 rounded-md transition-all">
                            Kategori
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Chart Container -->
            <div class="w-full flex-1 relative min-h-[280px]">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Donut Chart: Distribusi Kategori -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 lg:w-1/3 flex flex-col justify-between">
            <h3 class="font-bold text-[16px] text-gray-800 mb-4">Distribusi Kategori</h3>
            
            <div class="flex-1 flex flex-col items-center justify-center">
                <!-- Donut Canvas -->
                <div class="relative w-48 h-48 mb-6">
                    <canvas id="categoryDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold text-gray-800 leading-none">{{ $kategoriDominanPersen }}%</span>
                        <span class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-wider text-center px-2 truncate max-w-[120px]">
                            {{ $kategoriDominanNama }}
                        </span>
                    </div>
                </div>

                <!-- Dynamic Category Legend List -->
                <div class="w-full space-y-3 max-h-[180px] overflow-y-auto pr-1">
                    @forelse($kategoriList as $index => $k)
                        <div class="flex items-center justify-between text-[13px]">
                            <div class="flex items-center gap-2.5 truncate">
                                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $kategoriColors[$index % count($kategoriColors)] }};"></div>
                                <span class="text-gray-700 font-medium truncate">{{ $k->nama_kategori }}</span>
                            </div>
                            <span class="text-gray-600 font-bold ml-2">{{ number_format($k->total_stok) }} <span class="text-[11px] font-normal text-gray-400">unit</span></span>
                        </div>
                    @empty
                        <p class="text-center text-[12px] text-gray-400 py-2">Belum ada data kategori</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 lg:w-2/3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-[16px] text-gray-800">Aktivitas Terkini</h3>
                <a href="{{ route('laporan.index') }}" class="text-[13px] font-bold text-[#4a6b54] hover:text-[#355240]">Lihat Semua Laporan</a>
            </div>

            <div class="space-y-5">
                @if($aktivitasList->isNotEmpty())
                    @foreach($aktivitasList as $akt)
                        <div class="flex gap-4 items-start">
                            <div class="w-9 h-9 rounded-full bg-[#eaf4eb] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="flex-1 flex justify-between items-start">
                                <div>
                                    <p class="text-[14px] font-semibold text-gray-800">{{ $akt->aksi }}</p>
                                    <p class="text-[13px] text-gray-500 mt-0.5">{{ $akt->detail }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Oleh: {{ $akt->user->nama ?? 'Sistem' }}</p>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 whitespace-nowrap ml-2">
                                    {{ \Carbon\Carbon::parse($akt->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @elseif($recentTransactions->isNotEmpty())
                    @foreach($recentTransactions as $item)
                        <div class="flex gap-4 items-start">
                            <div class="w-9 h-9 rounded-full {{ $item['type'] === 'masuk' ? 'bg-[#eaf4eb] text-[#4a6b54]' : 'bg-orange-50 text-orange-600' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                @if($item['type'] === 'masuk')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 flex justify-between items-start">
                                <div>
                                    <p class="text-[14px] font-semibold text-gray-800">{{ $item['title'] }}</p>
                                    <p class="text-[13px] text-gray-500 mt-0.5">{{ $item['subtitle'] }}</p>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 whitespace-nowrap ml-2">
                                    {{ $item['time'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-[13px] text-gray-400 text-center py-4">Belum ada aktivitas tercatat.</p>
                @endif
            </div>
        </div>

        <!-- Stock Status Card -->
        <div class="bg-white rounded-[16px] p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-100 lg:w-1/3 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7h6m-6 4h4"></path></svg>
                    <h3 class="font-bold text-[15px] text-gray-800">Status Stok Inventaris</h3>
                </div>

                <p class="text-[13px] text-gray-500 font-medium leading-relaxed mb-5">
                    Ringkasan kondisi fisik stok buku yang tersimpan di sistem Cahaya Book.
                </p>

                <div class="bg-gray-50 rounded-lg border border-gray-100 divide-y divide-gray-100">
                    <div class="px-4 py-3 flex justify-between items-center">
                        <span class="text-[13px] font-bold text-gray-800">Total Stok Fisik</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#eaf4eb] text-[#355240] text-[11px] font-bold">
                            {{ number_format($totalStokFisik) }} unit
                        </span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center">
                        <span class="text-[13px] font-bold text-gray-800">Total Buku Masuk</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">
                            {{ number_format($totalStokMasuk) }} unit
                        </span>
                    </div>
                    <div class="px-4 py-3 flex justify-between items-center">
                        <span class="text-[13px] font-bold text-gray-800">Total Buku Keluar</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">
                            {{ number_format($totalStokKeluar) }} unit
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('stok.index') }}" class="inline-flex items-center gap-1 mt-6 text-[13px] font-bold text-[#4a6b54] hover:text-[#355240] transition-colors">
                <span>Kelola & Lihat Data Stok</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data passed from controller
    const monthlyLabels = @json($monthlyLabels);
    const monthlyMasuk = @json($monthlyMasuk);
    const monthlyKeluar = @json($monthlyKeluar);
    const kategoriLabels = @json($kategoriLabels);
    const kategoriStok = @json($kategoriStok);
    const kategoriColors = @json($kategoriColors);

    // 1. Monthly Main Chart (Volume / Kategori)
    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    let currentChartMode = 'volume';
    let mainChart;

    function initMonthlyChart() {
        const volumeConfig = {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [
                    {
                        label: 'Buku Masuk',
                        data: monthlyMasuk,
                        backgroundColor: '#355240',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    },
                    {
                        label: 'Buku Keluar',
                        data: monthlyKeluar,
                        backgroundColor: '#ea580c',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 12, family: 'Inter, sans-serif', weight: '500' },
                            padding: 16
                        }
                    },
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
                        ticks: { font: { size: 11 }, color: '#9ca3af', precision: 0 }
                    }
                }
            }
        };

        mainChart = new Chart(ctxMonthly, volumeConfig);
    }

    function switchMainChart(mode) {
        if (mode === currentChartMode) return;
        currentChartMode = mode;

        const btnVolume = document.getElementById('btn-chart-volume');
        const btnKategori = document.getElementById('btn-chart-kategori');
        const titleEl = document.getElementById('chart-main-title');
        const subtitleEl = document.getElementById('chart-main-subtitle');

        if (mode === 'volume') {
            btnVolume.classList.add('bg-white', 'text-gray-800', 'shadow-sm', 'font-bold');
            btnVolume.classList.remove('text-gray-500', 'font-medium');
            btnKategori.classList.remove('bg-white', 'text-gray-800', 'shadow-sm', 'font-bold');
            btnKategori.classList.add('text-gray-500', 'font-medium');

            titleEl.textContent = 'Statistik Inventaris Bulanan';
            subtitleEl.textContent = 'Pergerakan buku masuk dan keluar tahun {{ $currentYear }}';

            mainChart.destroy();
            mainChart = new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [
                        {
                            label: 'Buku Masuk',
                            data: monthlyMasuk,
                            backgroundColor: '#355240',
                            borderRadius: 6,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Buku Keluar',
                            data: monthlyKeluar,
                            backgroundColor: '#ea580c',
                            borderRadius: 6,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, pointStyle: 'circle', font: { size: 12 } }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { precision: 0 } }
                    }
                }
            });
        } else {
            btnKategori.classList.add('bg-white', 'text-gray-800', 'shadow-sm', 'font-bold');
            btnKategori.classList.remove('text-gray-500', 'font-medium');
            btnVolume.classList.remove('bg-white', 'text-gray-800', 'shadow-sm', 'font-bold');
            btnVolume.classList.add('text-gray-500', 'font-medium');

            titleEl.textContent = 'Stok Buku per Kategori';
            subtitleEl.textContent = 'Jumlah stok unit fisik buku berdasarkan kategori';

            mainChart.destroy();
            mainChart = new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        label: 'Total Stok (Unit)',
                        data: kategoriStok,
                        backgroundColor: '#4a6b54',
                        borderRadius: 6,
                        barPercentage: 0.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` Total Stok: ${context.parsed.y} unit`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { precision: 0 } }
                    }
                }
            });
        }
    }

    // 2. Category Donut Chart
    const ctxDonut = document.getElementById('categoryDonutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: kategoriStok,
                backgroundColor: kategoriColors.slice(0, kategoriLabels.length),
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const val = context.raw || 0;
                            return ` ${label}: ${val} unit`;
                        }
                    }
                }
            }
        }
    });

    // Initialize on page load
    initMonthlyChart();
</script>
@endsection