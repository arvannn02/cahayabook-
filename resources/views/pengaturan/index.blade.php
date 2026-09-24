@extends('layouts.app')

@section('title', __('Pengaturan Sistem') . ' - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-[28px] font-bold text-gray-900 leading-tight">{{ __('Pengaturan Sistem') }}</h1>
        <p class="text-[14px] text-gray-500 mt-1">{{ __('Konfigurasi dan preferensi sistem inventaris Cahaya Buku.') }}</p>
    </div>

    {{-- Flash Message Success --}}
    @if(session('success'))
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-[13.5px] font-medium flex items-center justify-between shadow-2xs animate-in fade-in duration-200">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    {{-- Info Banner --}}
    <div class="bg-amber-50 rounded-[16px] p-5 mb-8 border border-amber-200/60">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-white border border-amber-200 flex items-center justify-center text-amber-500 flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-[14px] font-bold text-amber-800 mb-0.5">{{ __('Akses Terbatas') }}</h3>
                <p class="text-[13px] text-amber-700 leading-relaxed">{{ __('Beberapa pengaturan memerlukan hak akses Admin IT. Hubungi administrator untuk perubahan konfigurasi tingkat lanjut.') }}</p>
            </div>
        </div>
    </div>

    {{-- Hidden Form for Preference Updates --}}
    <form id="preference-form" action="{{ route('pengaturan.updatePreferensi') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="bahasa" id="pref-bahasa-input" value="{{ $preferensi['bahasa'] ?? 'id' }}">
        <input type="hidden" name="zona_waktu" id="pref-zona_waktu-input" value="{{ $preferensi['zona_waktu'] ?? 'WIB' }}">
        <input type="hidden" name="per_page" id="pref-per_page-input" value="{{ $preferensi['per_page'] ?? 5 }}">
        <input type="hidden" name="format_tanggal" id="pref-format_tanggal-input" value="{{ $preferensi['format_tanggal'] ?? 'DD/MM/YYYY' }}">
    </form>

    {{-- Settings Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Informasi Sistem --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-[#e8f3ea] flex items-center justify-center text-[#4a6b54]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-gray-900">{{ __('Informasi Sistem') }}</h3>
            </div>

            <div class="space-y-3.5">
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-[13px] text-gray-500">{{ __('Nama Aplikasi') }}</span>
                    <span class="text-[13px] font-semibold text-gray-900">Cahaya Buku Inventory</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-[13px] text-gray-500">{{ __('Versi Sistem') }}</span>
                    <span class="inline-flex px-2.5 py-0.5 bg-[#e8f3ea] text-[#4a6b54] rounded-full text-[11px] font-semibold">v2.1.0</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-[13px] text-gray-500">{{ __('Framework') }}</span>
                    <span class="text-[13px] font-semibold text-gray-900">{{ $frameworkVersion ?? ('Laravel ' . app()->version()) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-[13px] text-gray-500">{{ __('Database') }}</span>
                    <span class="text-[13px] font-semibold text-gray-900">{{ $databaseVersion ?? 'MySQL 8.4.3 (LTS)' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-[13px] text-gray-500">{{ __('Status Server') }}</span>
                    <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-green-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ __('Online') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Preferensi Tampilan --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-[#e8f3ea] flex items-center justify-center text-[#4a6b54]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-gray-900">{{ __('Preferensi Tampilan') }}</h3>
            </div>

            <div class="space-y-4">
                {{-- 1. Bahasa --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Bahasa') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Bahasa antarmuka sistem') }}</p>
                    </div>
                    <div class="relative" id="dropdown-bahasa-container">
                        <button type="button" onclick="togglePrefDropdown('bahasa')" id="dropdown-bahasa-btn"
                            class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-gray-200 rounded-xl text-[12.5px] font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-[#3a5d43]/40 focus:outline-none focus:ring-2 focus:ring-[#3a5d43]/20 shadow-2xs transition-all cursor-pointer">
                            @if(($preferensi['bahasa'] ?? 'id') === 'en')
                                {{-- UK SVG Flag --}}
                                <svg class="w-5 h-3.5 rounded-[2px] shadow-2xs border border-gray-200/80 overflow-hidden flex-shrink-0" viewBox="0 0 60 40">
                                    <clipPath id="uk-flag-clip-active"><rect width="60" height="40" rx="2"/></clipPath>
                                    <g clip-path="url(#uk-flag-clip-active)">
                                        <rect width="60" height="40" fill="#012169"/>
                                        <path d="M0 0L60 40M60 0L0 40" stroke="#fff" stroke-width="8"/>
                                        <path d="M0 0L60 40M60 0L0 40" stroke="#C8102E" stroke-width="4"/>
                                        <path d="M30 0v40M0 20h60" stroke="#fff" stroke-width="12"/>
                                        <path d="M30 0v40M0 20h60" stroke="#C8102E" stroke-width="7"/>
                                    </g>
                                </svg>
                                <span>English</span>
                            @else
                                {{-- Indonesia SVG Flag --}}
                                <svg class="w-5 h-3.5 rounded-[2px] shadow-2xs border border-gray-200/80 overflow-hidden flex-shrink-0" viewBox="0 0 640 480">
                                    <g fill-rule="evenodd" stroke-width="1pt">
                                        <path fill="#e11d48" d="M0 0h640v240H0z"/>
                                        <path fill="#ffffff" d="M0 240h640v240H0z"/>
                                    </g>
                                </svg>
                                <span>Indonesia</span>
                            @endif
                            <svg class="w-3.5 h-3.5 text-gray-400 ml-0.5 transition-transform duration-200" id="chevron-bahasa" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="dropdown-bahasa-menu" class="hidden absolute right-0 top-full mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-gray-100 p-1.5 z-30 dropdown-menu-animate">
                            {{-- Pilihan Indonesia --}}
                            <button type="button" onclick="selectPreference('bahasa', 'id')" 
                                class="w-full flex items-center justify-between px-3 py-2 text-[12.5px] rounded-lg transition-colors cursor-pointer {{ ($preferensi['bahasa'] ?? 'id') === 'id' ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-5 h-3.5 rounded-[2px] shadow-2xs border border-gray-200/80 overflow-hidden flex-shrink-0" viewBox="0 0 640 480">
                                        <g fill-rule="evenodd" stroke-width="1pt">
                                            <path fill="#e11d48" d="M0 0h640v240H0z"/>
                                            <path fill="#ffffff" d="M0 240h640v240H0z"/>
                                        </g>
                                    </svg>
                                    <span>Indonesia</span>
                                </div>
                                @if(($preferensi['bahasa'] ?? 'id') === 'id')
                                    <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </button>

                            {{-- Pilihan English --}}
                            <button type="button" onclick="selectPreference('bahasa', 'en')" 
                                class="w-full flex items-center justify-between px-3 py-2 text-[12.5px] rounded-lg transition-colors cursor-pointer {{ ($preferensi['bahasa'] ?? 'id') === 'en' ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-5 h-3.5 rounded-[2px] shadow-2xs border border-gray-200/80 overflow-hidden flex-shrink-0" viewBox="0 0 60 40">
                                        <clipPath id="uk-flag-clip-opt"><rect width="60" height="40" rx="2"/></clipPath>
                                        <g clip-path="url(#uk-flag-clip-opt)">
                                            <rect width="60" height="40" fill="#012169"/>
                                            <path d="M0 0L60 40M60 0L0 40" stroke="#fff" stroke-width="8"/>
                                            <path d="M0 0L60 40M60 0L0 40" stroke="#C8102E" stroke-width="4"/>
                                            <path d="M30 0v40M0 20h60" stroke="#fff" stroke-width="12"/>
                                            <path d="M30 0v40M0 20h60" stroke="#C8102E" stroke-width="7"/>
                                        </g>
                                    </svg>
                                    <span>English</span>
                                </div>
                                @if(($preferensi['bahasa'] ?? 'id') === 'en')
                                    <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 2. Zona Waktu --}}
                <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Zona Waktu') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Zona waktu untuk pencatatan') }}</p>
                    </div>
                    <div class="relative" id="dropdown-zona_waktu-container">
                        @php
                            $tzVal = $preferensi['zona_waktu'] ?? 'WIB';
                            $tzLabels = [
                                'WIB' => 'WIB (UTC+7)',
                                'WITA' => 'WITA (UTC+8)',
                                'WIT' => 'WIT (UTC+9)',
                                'UTC' => 'UTC (GMT+0)',
                            ];
                        @endphp
                        <button type="button" onclick="togglePrefDropdown('zona_waktu')" id="dropdown-zona_waktu-btn"
                            class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-xl text-[12.5px] font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-[#3a5d43]/40 focus:outline-none focus:ring-2 focus:ring-[#3a5d43]/20 shadow-2xs transition-all cursor-pointer">
                            <span>{{ $tzLabels[$tzVal] ?? 'WIB (UTC+7)' }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400 ml-0.5 transition-transform duration-200" id="chevron-zona_waktu" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="dropdown-zona_waktu-menu" class="hidden absolute right-0 top-full mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-gray-100 p-1.5 z-30 dropdown-menu-animate">
                            @foreach(['WIB' => 'WIB (UTC+7)', 'WITA' => 'WITA (UTC+8)', 'WIT' => 'WIT (UTC+9)', 'UTC' => 'UTC (GMT+0)'] as $key => $label)
                                <button type="button" onclick="selectPreference('zona_waktu', '{{ $key }}')"
                                    class="w-full flex items-center justify-between px-3 py-2 text-[12.5px] rounded-lg transition-colors cursor-pointer {{ ($preferensi['zona_waktu'] ?? 'WIB') === $key ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>{{ $label }}</span>
                                    @if(($preferensi['zona_waktu'] ?? 'WIB') === $key)
                                        <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 3. Item per Halaman --}}
                <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Item per Halaman') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Jumlah data di setiap tabel') }}</p>
                    </div>
                    <div class="relative" id="dropdown-per_page-container">
                        <button type="button" onclick="togglePrefDropdown('per_page')" id="dropdown-per_page-btn"
                            class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-xl text-[12.5px] font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-[#3a5d43]/40 focus:outline-none focus:ring-2 focus:ring-[#3a5d43]/20 shadow-2xs transition-all cursor-pointer">
                            <span>{{ $preferensi['per_page'] ?? 5 }} {{ __('item') }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400 ml-0.5 transition-transform duration-200" id="chevron-per_page" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="dropdown-per_page-menu" class="hidden absolute right-0 top-full mt-1.5 w-36 bg-white rounded-xl shadow-xl border border-gray-100 p-1.5 z-30 dropdown-menu-animate">
                            @foreach([5, 10, 25, 50] as $count)
                                <button type="button" onclick="selectPreference('per_page', '{{ $count }}')"
                                    class="w-full flex items-center justify-between px-3 py-2 text-[12.5px] rounded-lg transition-colors cursor-pointer {{ (int)($preferensi['per_page'] ?? 5) === $count ? 'bg-[#edf3ee] text-[#2d4636] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>{{ $count }} {{ __('item') }}</span>
                                    @if((int)($preferensi['per_page'] ?? 5) === $count)
                                        <svg class="w-4 h-4 text-[#2d4636]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 4. Format Tanggal (Otomatis berjalan sesuai kalender asli, tidak dapat dirubah manual) --}}
                <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Format Tanggal') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Format tampilan tanggal') }}</p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-xl text-[12.5px] font-medium text-gray-700 bg-[#fbfcfc] shadow-2xs select-none" title="{{ __('Tanggal otomatis tersinkronisasi') }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="{{ __('Sinkron Otomatis') }}"></span>
                        <svg class="w-3.5 h-3.5 text-[#3a5d43]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span id="live-system-date" class="font-semibold text-gray-800 tracking-tight font-mono">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Backup & Pemulihan --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                </div>
                <div>
                    <h3 class="text-[16px] font-bold text-gray-900 leading-tight">{{ __('Backup & Pemulihan') }}</h3>
                    <p class="text-[12px] text-gray-500 leading-tight mt-0.5">{{ __('Pencadangan database sistem') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-[13px] text-gray-500">{{ __('Backup Terakhir') }}</span>
                    <span id="live-last-backup-text" class="text-[13px] font-semibold text-gray-900">{{ $lastBackup['formatted'] ?? 'Belum pernah di-backup' }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-[13px] text-gray-500">{{ __('Jadwal Backup') }}</span>
                    <span class="inline-flex px-2.5 py-0.5 bg-[#e8f3ea] text-[#4a6b54] rounded-full text-[11px] font-semibold">{{ $backupSchedule ?? 'Harian (02:00 WIB)' }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-[13px] text-gray-500">{{ __('Ukuran Database') }}</span>
                    <span id="live-db-size-text" class="text-[13px] font-semibold text-gray-900">{{ $dbSize['formatted'] ?? '0.66 MB' }}</span>
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                <button type="button" 
                        id="btn-backup-now"
                        onclick="triggerBackupNow()" 
                        class="flex-1 bg-[#4a6b54] hover:bg-[#3d5945] text-white py-2.5 rounded-lg text-[12px] font-semibold transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span id="btn-backup-now-text">{{ __('Backup Sekarang') }}</span>
                </button>
                <button type="button" 
                        onclick="openRestoreModal()" 
                        class="flex-1 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 py-2.5 rounded-lg text-[12px] font-semibold transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>{{ __('Pulihkan Data') }}</span>
                </button>
            </div>
        </div>

        {{-- Keamanan --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-[16px] font-bold text-gray-900">{{ __('Keamanan') }}</h3>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Timeout Sesi') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Durasi sesi aktif otomatis') }}</p>
                    </div>
                    <span class="text-[13px] font-semibold text-gray-900">30 {{ __('menit') }}</span>
                </div>

                <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Log Aktivitas') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Pencatatan setiap aksi') }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-green-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ __('Aktif') }}
                    </span>
                </div>

                <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('Mode Soft-Delete') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Data dihapus ke recycle bin') }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-green-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ __('Aktif') }}
                    </span>
                </div>

                <div class="border-t border-gray-50 pt-4 flex items-center justify-between">
                    <div>
                        <p class="text-[13px] font-semibold text-gray-900">{{ __('2FA Google Authenticator') }}</p>
                        <p class="text-[12px] text-gray-500">{{ __('Verifikasi dua langkah akun') }}</p>
                    </div>
                    @if(!empty($preferensi['two_factor_enabled']))
                    <button type="button" onclick="open2faModal()" class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100/80 px-2.5 py-1 rounded-full transition-colors cursor-pointer border border-green-200/50" title="{{ __('Konfigurasi 2FA Google Authenticator') }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ __('Aktif') }}
                        <svg class="w-3.5 h-3.5 text-green-600 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    @else
                    <button type="button" onclick="open2faModal()" class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200/80 px-2.5 py-1 rounded-full transition-colors cursor-pointer border border-gray-200" title="{{ __('Aktifkan 2FA Google Authenticator') }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> {{ __('Nonaktif') }}
                        <svg class="w-3.5 h-3.5 text-gray-500 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL: 2FA GOOGLE AUTHENTICATOR --}}
<div id="modal-2fa" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold text-[#111827]">{{ __('Konfigurasi 2FA Google Authenticator') }}</h3>
                        <p class="text-[12px] text-[#6b7280]">{{ __('Verifikasi dua langkah akun') }}</p>
                    </div>
                </div>
                <button type="button" onclick="close2faModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                @if(!empty($preferensi['two_factor_enabled']))
                {{-- TAMPILAN JIKA 2FA SUDAH AKTIF --}}
                <div class="space-y-4">
                    {{-- Status Banner --}}
                    <div class="flex items-center justify-between p-3 bg-emerald-50/80 border border-emerald-200/60 rounded-xl">
                        <div class="flex items-center gap-2.5">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <div>
                                <p class="text-[12.5px] font-semibold text-emerald-900">Status 2FA: {{ __('Aktif') }}</p>
                                <p class="text-[11px] text-emerald-700">Akun terhubung & dilindungi Google Authenticator</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Terproteksi
                        </span>
                    </div>

                    {{-- QR Code Box --}}
                    <div class="flex flex-col items-center justify-center p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                        <p class="text-[12px] text-center text-gray-600 mb-3 leading-relaxed">
                            {{ __('Scan kode QR di bawah ini menggunakan aplikasi Google Authenticator di ponsel Anda.') }}
                        </p>
                        
                        <div class="p-2.5 bg-white rounded-xl shadow-xs border border-gray-200">
                            <img src="{{ asset('2fa-qr.png') }}?v={{ time() }}" 
                                 alt="QR Code Google Authenticator" 
                                 class="w-40 h-40 object-contain rounded-md"
                                 onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=otpauth%3A%2F%2Ftotp%2FCahaya%20Buku%3Aadmin%40cahayabook.com%3Fsecret%3DCAHAYABUKU234567%26issuer%3DCahaya%20Buku';" />
                        </div>
                        <p class="text-[11.5px] text-gray-400 mt-2.5 font-mono">admin@cahayabook.com</p>
                    </div>

                    {{-- Manual Key Box --}}
                    <div>
                        <label class="block text-[11.5px] font-semibold text-gray-700 mb-1.5">{{ __('Kunci Penyiapan Manual') }} (Base32)</label>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl font-mono text-[13px] tracking-wider text-gray-800 select-all font-semibold" id="totp-secret-key">
                                CAHAYABUKU234567
                            </div>
                            <button type="button" onclick="copy2faKey()" id="btn-copy-2fa" class="px-3.5 py-2 bg-[#2d4636] hover:bg-[#23372b] text-white rounded-xl text-[12px] font-semibold flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span id="btn-copy-text">{{ __('Salin') }}</span>
                            </button>
                        </div>
                        <p class="mt-1.5 text-[11px] text-gray-400">
                            {{ __('*Ketik tanpa tanda strip (-), atau klik tombol Salin lalu tempel langsung.') }}
                        </p>
                    </div>

                    {{-- Kunci Cadangan (Backup Recovery Keys) --}}
                    <div class="border-t border-gray-100 pt-3.5 space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <label class="block text-[12.5px] font-bold text-gray-900 leading-tight">
                                    🔑 {{ __('Kunci Cadangan (Backup Keys)') }}
                                </label>
                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    {{ __('Gunakan kunci ini jika tidak bisa mengakses Google Authenticator.') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <button type="button" 
                                        onclick="copyAllRecoveryCodes()" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold text-[#2d4636] hover:text-[#1c2c22] bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/70 rounded-lg transition-colors cursor-pointer whitespace-nowrap shadow-2xs"
                                        title="{{ __('Salin seluruh 8 kunci cadangan') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span id="btn-copy-all-text">{{ __('Salin Semua') }}</span>
                                </button>
                                <form action="{{ route('pengaturan.regenerateRecoveryCodes') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-semibold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg transition-colors cursor-pointer whitespace-nowrap shadow-2xs" 
                                            title="{{ __('Perbarui set kunci cadangan baru') }}">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        <span>{{ __('Perbarui') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Grid Daftar Kunci Cadangan --}}
                        <div class="grid grid-cols-2 gap-2 p-2.5 bg-gray-50/80 rounded-xl border border-gray-200/70">
                            @foreach($recoveryCodes as $item)
                            @php
                                $isUsed = !empty($item['used']);
                            @endphp
                            <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg border transition-all duration-150 {{ $isUsed ? 'bg-gray-100/70 border-gray-200 text-gray-400' : 'bg-white border-gray-200/90 shadow-2xs hover:border-emerald-300 hover:shadow-xs group' }}">
                                <span class="font-mono text-[12px] font-bold tracking-wider {{ $isUsed ? 'line-through text-gray-400' : 'text-gray-800 group-hover:text-emerald-950' }} select-all">
                                    {{ $item['code'] }}
                                </span>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    @if($isUsed)
                                    <span class="text-[9.5px] font-semibold text-gray-500 bg-gray-200 px-1.5 py-0.5 rounded leading-none">
                                        {{ __('Terpakai') }}
                                    </span>
                                    <button type="button" disabled class="p-1 rounded text-gray-300 cursor-not-allowed" title="{{ __('Kunci ini sudah terpakai') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </button>
                                    @else
                                    <span class="status-badge text-[9.5px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-1.5 py-0.5 rounded leading-none transition-colors">
                                        {{ __('Tersedia') }}
                                    </span>
                                    <button type="button" 
                                            onclick="copySingleRecoveryCode('{{ $item['code'] }}', this)" 
                                            class="p-1 rounded text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors cursor-pointer" 
                                            title="{{ __('Salin kunci') }} {{ $item['code'] }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="flex items-start gap-1.5 text-[11px] text-gray-500 bg-gray-50/70 rounded-lg p-2 border border-gray-100">
                            <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="leading-relaxed">
                                {{ __('*Setiap kunci hanya dapat digunakan 1 kali. Jika seluruh kunci habis terpakai, sistem akan otomatis mereset dan menerbitkan kunci baru.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Opsi Nonaktifkan 2FA --}}
                    <div class="border-t border-gray-100 pt-3.5 space-y-2">
                        <button type="button" 
                                onclick="openDisable2faConfirmModal()" 
                                class="w-full py-2.5 px-4 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200/80 rounded-xl text-[12.5px] font-semibold transition-colors cursor-pointer text-center flex items-center justify-center gap-2 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>{{ __('Nonaktifkan 2FA') }}</span>
                        </button>
                    </div>

                    {{-- Modal Footer / Close --}}
                    <div class="pt-2">
                        <button type="button" onclick="close2faModal()" class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[13px] font-semibold transition-colors cursor-pointer text-center">
                            {{ __('Tutup') }}
                        </button>
                    </div>
                </div>
                @else
                {{-- TAMPILAN JIKA 2FA MASIH NONAKTIF --}}
                
                {{-- 1. INTRO: Barcode & Kunci Manual TIDAK LANGSUNG MUNCUL sebelum klik "Aktifkan 2FA" --}}
                <div id="box-2fa-inactive-intro" class="space-y-4">
                    {{-- Status Banner --}}
                    <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                            <div>
                                <p class="text-[12.5px] font-semibold text-gray-800">Status 2FA: {{ __('Nonaktif') }}</p>
                                <p class="text-[11px] text-gray-500">{{ __('Autentikasi dua langkah belum aktif') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-semibold bg-gray-200 text-gray-700">
                            Belum Aktif
                        </span>
                    </div>

                    {{-- Info Card Pengenalan 2FA --}}
                    <div class="p-5 bg-gradient-to-b from-[#f8faf9] to-[#f0f5f1] rounded-2xl border border-gray-200/80 text-center space-y-3">
                        <div class="w-12 h-12 bg-white text-[#2d4636] border border-emerald-200/80 rounded-2xl flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-[14.5px] font-bold text-gray-900">{{ __('Amankan Akun Anda') }}</h4>
                            <p class="text-[12px] text-gray-600 mt-1 leading-relaxed">
                                {{ __('Verifikasi dua langkah (2FA) mencegah pihak lain mengakses akun Anda. Setiap kali masuk, sistem akan meminta 6 digit kode keamanan dari aplikasi Google Authenticator di ponsel Anda.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Klik untuk Mengaktifkan 2FA --}}
                    <div class="pt-1 space-y-2.5">
                        <button type="button" 
                                onclick="show2faSetup()" 
                                id="btn-start-setup"
                                class="w-full py-3 px-4 bg-[#2d4636] hover:bg-[#23372b] text-white rounded-xl text-[13px] font-semibold transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ __('Aktifkan 2FA') }}</span>
                        </button>
                        
                        <button type="button" onclick="close2faModal()" class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[13px] font-semibold transition-colors cursor-pointer text-center">
                            {{ __('Tutup') }}
                        </button>
                    </div>
                </div>

                {{-- 2. SETUP: Barcode & Kunci Manual HANYA MUNCUL SETELAH klik "Aktifkan 2FA" --}}
                <div id="box-2fa-setup" class="hidden space-y-4 animate-in fade-in duration-200">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-[#2d4636] text-white text-[11px] font-bold flex items-center justify-center">1</span>
                            <span class="text-[12.5px] font-bold text-gray-900">{{ __('Scan Barcode / Kunci Manual') }}</span>
                        </div>
                        <button type="button" onclick="hide2faSetup()" class="inline-flex items-center gap-1 text-[11.5px] font-semibold text-gray-500 hover:text-gray-800 transition-colors cursor-pointer">
                            <span>← Batal</span>
                        </button>
                    </div>

                    {{-- QR Code Box --}}
                    <div class="flex flex-col items-center justify-center p-3.5 bg-gray-50/80 rounded-xl border border-gray-100">
                        <p class="text-[11.5px] text-center text-gray-600 mb-2.5 leading-relaxed">
                            {{ __('Scan kode QR di bawah ini menggunakan aplikasi Google Authenticator di ponsel Anda.') }}
                        </p>
                        
                        <div class="p-2 bg-white rounded-xl shadow-xs border border-gray-200">
                            <img src="{{ asset('2fa-qr.png') }}?v={{ time() }}" 
                                 alt="QR Code Google Authenticator" 
                                 class="w-36 h-36 object-contain rounded-md"
                                 onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=otpauth%3A%2F%2Ftotp%2FCahaya%20Buku%3Aadmin%40cahayabook.com%3Fsecret%3DCAHAYABUKU234567%26issuer%3DCahaya%20Buku';" />
                        </div>
                        <p class="text-[11px] text-gray-400 mt-2 font-mono">admin@cahayabook.com</p>
                    </div>

                    {{-- Manual Key Box --}}
                    <div>
                        <label class="block text-[11.5px] font-semibold text-gray-700 mb-1.5">{{ __('Kunci Penyiapan Manual') }} (Base32)</label>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl font-mono text-[13px] tracking-wider text-gray-800 select-all font-semibold" id="totp-secret-key-setup">
                                CAHAYABUKU234567
                            </div>
                            <button type="button" onclick="copy2faKey()" id="btn-copy-2fa" class="px-3.5 py-2 bg-[#2d4636] hover:bg-[#23372b] text-white rounded-xl text-[12px] font-semibold flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span id="btn-copy-text-setup">{{ __('Salin') }}</span>
                            </button>
                        </div>
                        <p class="mt-1.5 text-[11px] text-gray-400">
                            {{ __('*Ketik tanpa tanda strip (-), atau klik tombol Salin lalu tempel langsung.') }}
                        </p>
                    </div>

                    {{-- Kunci Cadangan (Backup Recovery Keys) saat Setup --}}
                    <div class="border-t border-gray-100 pt-3 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <label class="block text-[12px] font-bold text-gray-900 leading-tight">
                                    🔑 {{ __('Kunci Cadangan (Backup Keys)') }}
                                </label>
                                <p class="text-[10.5px] text-gray-500 mt-0.5">
                                    {{ __('Simpan kunci ini jika nanti ponsel Anda hilang.') }}
                                </p>
                            </div>
                            <button type="button" onclick="copyAllRecoveryCodes()" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold text-[#2d4636] hover:text-[#1c2c22] bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/70 rounded-lg transition-colors cursor-pointer whitespace-nowrap shadow-2xs" title="{{ __('Salin seluruh kunci cadangan') }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span id="btn-copy-all-setup-text">{{ __('Salin Semua') }}</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-1.5 p-2 bg-gray-50/80 rounded-xl border border-gray-200/70">
                            @foreach($recoveryCodes as $item)
                            <div class="flex items-center justify-between px-2 py-1.5 rounded-lg border bg-white border-gray-200/80 text-gray-800 shadow-2xs hover:border-emerald-300 transition-all group">
                                <span class="font-mono text-[11.5px] font-bold text-gray-800 tracking-wider group-hover:text-emerald-950 select-all">
                                    {{ $item['code'] }}
                                </span>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <span class="status-badge text-[9px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-1 py-0.5 rounded leading-none transition-colors">
                                        {{ __('Tersedia') }}
                                    </span>
                                    <button type="button" 
                                            onclick="copySingleRecoveryCode('{{ $item['code'] }}', this)" 
                                            class="p-0.5 rounded text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors cursor-pointer" 
                                            title="{{ __('Salin kunci') }} {{ $item['code'] }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Input Verifikasi & Aktivasi Kode OTP --}}
                    <div class="border-t border-gray-100 pt-3 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-[#2d4636] text-white text-[11px] font-bold flex items-center justify-center">2</span>
                            <label for="input-otp-code" class="block text-[12px] font-semibold text-gray-800">
                                {{ __('Masukkan 6 Digit Kode dari Google Authenticator') }}
                            </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="text" 
                                       id="input-otp-code" 
                                       maxlength="6" 
                                       inputmode="numeric" 
                                       pattern="[0-9]*" 
                                       placeholder="Contoh: 123456" 
                                       autocomplete="one-time-code"
                                       class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-xl font-mono text-center text-[18px] tracking-[0.25em] font-bold text-gray-900 focus:ring-2 focus:ring-[#2d4636] focus:border-[#2d4636] outline-hidden transition-all placeholder:tracking-normal placeholder:text-[12.5px] placeholder:font-sans placeholder:font-normal placeholder:text-gray-400" 
                                       onkeypress="if(event.key === 'Enter') { verifyAndActivate2fa(); }" />
                            </div>
                            <button type="button" 
                                    onclick="verifyAndActivate2fa()" 
                                    id="btn-verify-otp" 
                                    class="px-4 py-2.5 bg-[#2d4636] hover:bg-[#23372b] text-white rounded-xl text-[12.5px] font-semibold transition-all shadow-xs cursor-pointer whitespace-nowrap flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span id="btn-verify-text">{{ __('Aktifkan 2FA') }}</span>
                            </button>
                        </div>

                        {{-- Feedback / Status Verifikasi --}}
                        <div id="otp-feedback" class="hidden p-2.5 rounded-xl text-[12px] transition-all"></div>
                    </div>

                    {{-- Modal Footer / Close --}}
                    <div class="pt-2">
                        <button type="button" onclick="close2faModal()" class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[13px] font-semibold transition-colors cursor-pointer text-center">
                            {{ __('Tutup') }}
                        </button>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- MODAL: KONFIRMASI NONAKTIFKAN 2FA (TEMA REAL CAHAYA BUKU) --}}
<div id="modal-confirm-disable-2fa" class="hidden fixed inset-0 z-[60] overflow-y-auto bg-black/50 backdrop-blur-sm transition-all duration-200">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150 p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 border border-red-100 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-[17px] font-bold text-[#111827] leading-snug">{{ __('Nonaktifkan 2FA Google Authenticator?') }}</h3>
                    <p class="text-[13px] text-[#6b7280] mt-1.5 leading-relaxed">
                        {{ __('Apakah Anda yakin ingin menonaktifkan Verifikasi 2 Langkah (2FA)? Proteksi tambahan akun Anda akan dimatikan.') }}
                    </p>
                    <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200/80 text-[12px] text-amber-800 flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ __('Login berikutnya tidak akan meminta kode OTP lagi dan hanya memerlukan email serta kata sandi.') }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaturan.toggle2fa') }}" method="POST" class="mt-6 flex items-center justify-end gap-2.5">
                @csrf
                <input type="hidden" name="action" value="disable">
                <button type="button" onclick="closeDisable2faConfirmModal()" class="px-4 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors cursor-pointer">
                    {{ __('Batal') }}
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-[13px] font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>{{ __('Ya, Nonaktifkan 2FA') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: PEMULIHAN DATA & RIWAYAT BACKUP --}}
<div id="modal-backup-restore" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold text-[#111827]">{{ __('Backup & Pemulihan Database') }}</h3>
                        <p class="text-[12px] text-[#6b7280]">{{ __('Kelola riwayat cadangan dan pemulihan data sistem') }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeRestoreModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                
                {{-- Alert Info / Warning --}}
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200/80 text-[12px] text-amber-900 flex items-start gap-2.5">
                    <svg class="w-4.5 h-4.5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="leading-relaxed">
                        <strong class="font-semibold">{{ __('Peringatan Pemulihan Data:') }}</strong>
                        {{ __('Memulihkan data akan menggantikan seluruh data aktif database dengan isi data dari file cadangan yang dipilih.') }}
                    </div>
                </div>

                {{-- Tabs Navigation --}}
                <div class="flex items-center gap-2 border-b border-gray-100 pb-1">
                    <button type="button" 
                            id="tab-btn-server" 
                            onclick="switchBackupTab('server')" 
                            class="px-3.5 py-2 text-[12.5px] font-semibold border-b-2 border-[#2d4636] text-[#2d4636] transition-all cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span>{{ __('File Cadangan di Server') }}</span>
                        <span id="backup-file-count-badge" class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-bold ml-0.5">{{ count($backupFiles ?? []) }}</span>
                    </button>
                    <button type="button" 
                            id="tab-btn-upload" 
                            onclick="switchBackupTab('upload')" 
                            class="px-3.5 py-2 text-[12.5px] font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition-all cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span>{{ __('Unggah File (.sql)') }}</span>
                    </button>
                </div>

                {{-- TAB 1: Server Files --}}
                <div id="tab-content-server" class="space-y-3">
                    <div class="flex items-center justify-between text-[11.5px] text-gray-500 px-1">
                        <span>{{ __('Tersimpan di storage/app/backups/') }}</span>
                        <button type="button" onclick="triggerBackupNow()" class="text-[#2d4636] hover:underline font-semibold flex items-center gap-1 cursor-pointer">
                            <span>+ {{ __('Buat Backup Baru') }}</span>
                        </button>
                    </div>

                    <div id="backup-files-list-container" class="max-h-64 overflow-y-auto space-y-2 pr-1">
                        @forelse($backupFiles ?? [] as $file)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-emerald-50/30 border border-gray-200/80 hover:border-emerald-200 transition-all">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100/80 text-emerald-800 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[12.5px] font-bold text-gray-900 truncate font-mono" title="{{ $file['filename'] }}">
                                        {{ $file['filename'] }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-0.5 text-[11px] text-gray-500">
                                        <span>{{ $file['formatted_date'] }}</span>
                                        <span>•</span>
                                        <span class="font-semibold text-gray-700 bg-gray-200/70 px-1.5 py-0.2 rounded">{{ $file['formatted_size'] }}</span>
                                        <span class="text-gray-400">({{ $file['relative_time'] }})</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 flex-shrink-0 ml-3">
                                {{-- Tombol Download --}}
                                <a href="{{ route('pengaturan.backup.download', ['filename' => $file['filename']]) }}" 
                                   class="p-1.5 text-gray-500 hover:text-[#2d4636] hover:bg-gray-200/60 rounded-lg transition-colors cursor-pointer" 
                                   title="{{ __('Unduh file ke komputer') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>

                                {{-- Tombol Restore --}}
                                <button type="button" 
                                        onclick="confirmRestoreServerFile('{{ $file['filename'] }}')" 
                                        class="px-2.5 py-1 text-[11.5px] font-semibold text-emerald-800 hover:text-emerald-950 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-colors cursor-pointer flex items-center gap-1 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    <span>{{ __('Pulihkan') }}</span>
                                </button>

                                {{-- Tombol Hapus --}}
                                <button type="button" 
                                        onclick="confirmDeleteBackup('{{ $file['filename'] }}')" 
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" 
                                        title="{{ __('Hapus file backup') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div id="backup-empty-state" class="py-8 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200 space-y-2">
                            <svg class="w-8 h-8 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <p class="text-[13px] font-medium text-gray-700">{{ __('Belum ada file cadangan yang tersimpan.') }}</p>
                            <p class="text-[11.5px] text-gray-400">{{ __('Klik tombol di bawah ini untuk membuat cadangan database pertama Anda.') }}</p>
                            <button type="button" onclick="triggerBackupNow()" class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 bg-[#2d4636] hover:bg-[#23372b] text-white text-[12px] font-semibold rounded-xl transition-all shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>{{ __('Backup Sekarang') }}</span>
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- TAB 2: Upload File --}}
                <div id="tab-content-upload" class="hidden space-y-3">
                    <form id="form-upload-restore" action="{{ route('pengaturan.backup.restore') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="border-2 border-dashed border-gray-300 hover:border-[#2d4636] rounded-2xl p-6 text-center transition-all bg-gray-50/60 hover:bg-emerald-50/20 group cursor-pointer" onclick="document.getElementById('input-backup-file').click()">
                            <input type="file" id="input-backup-file" name="backup_file" accept=".sql" class="hidden" onchange="handleBackupFileSelect(this)">
                            <div class="w-12 h-12 bg-white text-[#2d4636] border border-gray-200 rounded-2xl flex items-center justify-center mx-auto shadow-xs group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <div class="mt-3">
                                <p class="text-[13px] font-bold text-gray-800" id="upload-label-filename">{{ __('Pilih file backup (.sql) dari komputer') }}</p>
                                <p class="text-[11.5px] text-gray-500 mt-0.5" id="upload-label-desc">{{ __('Klik di sini untuk menjelajahi file (Maks. 50 MB)') }}</p>
                            </div>
                        </div>

                        <button type="button" 
                                id="btn-submit-upload-restore" 
                                onclick="confirmUploadRestore()" 
                                disabled
                                class="w-full py-3 px-4 bg-[#2d4636] hover:bg-[#23372b] disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-xl text-[13px] font-semibold transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>{{ __('Pulihkan dari File Terpilih') }}</span>
                        </button>
                    </form>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
                <button type="button" onclick="closeRestoreModal()" class="px-4 py-2 bg-gray-200/80 hover:bg-gray-300 text-gray-700 text-[12.5px] font-semibold rounded-xl transition-colors cursor-pointer">
                    {{ __('Tutup') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: KONFIRMASI RESTORE DATABASE (TEMA CAHAYA BUKU) --}}
<div id="modal-confirm-restore" class="hidden fixed inset-0 z-[70] overflow-y-auto bg-black/50 backdrop-blur-sm transition-all duration-200">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150 p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 border border-amber-200 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-[17px] font-bold text-gray-900 leading-snug">{{ __('Pulihkan Database?') }}</h3>
                    <p class="text-[12.5px] text-gray-600 mt-1 leading-relaxed">
                        {{ __('Anda akan memulihkan data dari file:') }}
                    </p>
                    <p class="font-mono text-[12px] font-bold text-gray-900 bg-gray-100 p-2.5 rounded-lg mt-1 break-all select-all" id="confirm-restore-target-name">
                        cahayabook-backup.sql
                    </p>
                    <div class="mt-3 p-3 rounded-xl bg-red-50 border border-red-200/80 text-[11.5px] text-red-800 flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ __('Seluruh data katalog buku, transaksi, dan akun saat ini akan digantikan dengan data dari file cadangan ini.') }}</span>
                    </div>
                </div>
            </div>

            <form id="form-confirm-restore-action" action="{{ route('pengaturan.backup.restore') }}" method="POST" class="mt-6 flex items-center justify-end gap-2.5">
                @csrf
                <input type="hidden" name="filename" id="confirm-restore-filename-input" value="">
                <button type="button" onclick="closeRestoreConfirmModal()" class="px-4 py-2.5 text-[13px] font-semibold text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors cursor-pointer">
                    {{ __('Batal') }}
                </button>
                <button type="submit" id="btn-submit-restore-action" class="inline-flex items-center gap-2 px-5 py-2.5 text-[13px] font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl transition-all shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span id="btn-submit-restore-text">{{ __('Ya, Pulihkan Database') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: KONFIRMASI HAPUS FILE BACKUP (TEMA CAHAYA BUKU) --}}
<div id="modal-confirm-delete-backup" class="hidden fixed inset-0 z-[70] overflow-y-auto bg-black/50 backdrop-blur-sm transition-all duration-200">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden p-5">
            <h4 class="text-[15px] font-bold text-gray-900">{{ __('Hapus File Backup?') }}</h4>
            <p class="text-[12px] text-gray-500 mt-1 leading-relaxed">
                {{ __('File backup berikut akan dihapus secara permanen dari server:') }}
            </p>
            <p class="font-mono text-[11.5px] font-bold text-gray-800 bg-gray-100 p-2 rounded-lg mt-1 break-all" id="confirm-delete-backup-name"></p>
            <form id="form-delete-backup-action" method="POST" class="mt-4 flex items-center justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteBackupConfirmModal()" class="px-3 py-2 text-[12px] font-semibold text-gray-600 hover:bg-gray-100 rounded-xl cursor-pointer">
                    {{ __('Batal') }}
                </button>
                <button type="submit" class="px-4 py-2 text-[12px] font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl cursor-pointer">
                    {{ __('Hapus Permanen') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function togglePrefDropdown(name) {
    const allDropdowns = ['bahasa', 'zona_waktu', 'per_page'];
    allDropdowns.forEach(d => {
        const el = document.getElementById('dropdown-' + d + '-menu');
        const chev = document.getElementById('chevron-' + d);
        if (d === name) {
            const isHidden = el.classList.contains('hidden');
            if (isHidden) {
                el.classList.remove('hidden');
                chev?.classList.add('rotate-180');
            } else {
                el.classList.add('hidden');
                chev?.classList.remove('rotate-180');
            }
        } else {
            el?.classList.add('hidden');
            chev?.classList.remove('rotate-180');
        }
    });
}

function selectPreference(field, value) {
    const input = document.getElementById('pref-' + field + '-input');
    if (input) {
        input.value = value;
        document.getElementById('preference-form').submit();
    }
}

// Close when clicking outside
document.addEventListener('click', function(e) {
    const allDropdowns = ['bahasa', 'zona_waktu', 'per_page'];
    allDropdowns.forEach(d => {
        const container = document.getElementById('dropdown-' + d + '-container');
        if (container && !container.contains(e.target)) {
            const el = document.getElementById('dropdown-' + d + '-menu');
            const chev = document.getElementById('chevron-' + d);
            el?.classList.add('hidden');
            chev?.classList.remove('rotate-180');
        }
    });
});

// Update tanggal asli agar selalu sinkron dan berganti otomatis setiap hari
function updateLiveSystemDate() {
    const el = document.getElementById('live-system-date');
    if (el) {
        const now = new Date();
        const d = String(now.getDate()).padStart(2, '0');
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const y = now.getFullYear();
        el.textContent = `${d}/${m}/${y}`;
    }
}
updateLiveSystemDate();
setInterval(updateLiveSystemDate, 10000);

// Modal 2FA Functions
function show2faSetup() {
    const intro = document.getElementById('box-2fa-inactive-intro');
    const setup = document.getElementById('box-2fa-setup');
    if (intro && setup) {
        intro.classList.add('hidden');
        setup.classList.remove('hidden');
        const input = document.getElementById('input-otp-code');
        if (input) {
            setTimeout(() => input.focus(), 150);
        }
    }
}

function hide2faSetup() {
    const intro = document.getElementById('box-2fa-inactive-intro');
    const setup = document.getElementById('box-2fa-setup');
    if (intro && setup) {
        setup.classList.add('hidden');
        intro.classList.remove('hidden');
    }
}

function open2faModal() {
    const modal = document.getElementById('modal-2fa');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        hide2faSetup();
        const input = document.getElementById('input-otp-code');
        const feedback = document.getElementById('otp-feedback');
        if (input) {
            input.value = '';
            input.classList.remove('border-emerald-500', 'bg-emerald-50/40', 'border-red-400', 'bg-red-50/20');
            input.classList.add('border-gray-300');
        }
        if (feedback) {
            feedback.classList.add('hidden');
            feedback.innerHTML = '';
        }
    }
}

function close2faModal() {
    const modal = document.getElementById('modal-2fa');
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    closeDisable2faConfirmModal();
    hide2faSetup();
}

function openDisable2faConfirmModal() {
    const modal = document.getElementById('modal-confirm-disable-2fa');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeDisable2faConfirmModal() {
    const modal = document.getElementById('modal-confirm-disable-2fa');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function copy2faKey() {
    const key = 'CAHAYABUKU234567';
    navigator.clipboard.writeText(key).then(() => {
        ['btn-copy-text', 'btn-copy-text-setup'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const orig = el.textContent;
                el.textContent = "{{ __('Berhasil Disalin') }}";
                setTimeout(() => {
                    el.textContent = orig;
                }, 2000);
            }
        });
    });
}

// Verification & Activation of 2FA
async function verifyAndActivate2fa() {
    const input = document.getElementById('input-otp-code');
    const feedback = document.getElementById('otp-feedback');
    const btn = document.getElementById('btn-verify-otp');
    if (!input || !feedback || !btn) return;

    const code = input.value.trim().replace(/\s+/g, '');
    if (code.length !== 6 || !/^\d{6}$/.test(code)) {
        feedback.className = 'p-2.5 rounded-xl text-[12px] flex items-center gap-2 bg-amber-50 text-amber-800 border border-amber-200';
        feedback.innerHTML = '<svg class="w-4 h-4 flex-shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg><span>Masukkan 6 digit angka dari aplikasi Google Authenticator.</span>';
        feedback.classList.remove('hidden');
        input.focus();
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memverifikasi...</span>';

    try {
        const response = await fetch("{{ route('pengaturan.toggle2fa') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action: 'enable', otp_code: code })
        });
        const data = await response.json();

        if (response.ok && data.success) {
            feedback.className = 'p-3 rounded-xl text-[12px] flex items-start gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200';
            feedback.innerHTML = '<svg class="w-4 h-4 flex-shrink-0 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><div><strong>2FA Berhasil Diaktifkan!</strong><br>Google Authenticator kini aktif. Login berikutnya akan tertahan dan meminta kode OTP. Halaman akan disegarkan...</div>';
            feedback.classList.remove('hidden');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>{{ __("Aktifkan 2FA") }}</span>';
            feedback.className = 'p-3 rounded-xl text-[12px] flex items-start gap-2 bg-red-50 text-red-700 border border-red-200';
            feedback.innerHTML = '<svg class="w-4 h-4 flex-shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><div><strong>Kode Tidak Cocok atau Kedaluwarsa.</strong><br>' + (data.message || 'Kode OTP salah. Silakan periksa kembali aplikasi Google Authenticator Anda.') + '</div>';
            feedback.classList.remove('hidden');
            input.focus();
        }
    } catch (e) {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <span>{{ __("Aktifkan 2FA") }}</span>';
        feedback.className = 'p-3 rounded-xl text-[12px] flex items-start gap-2 bg-red-50 text-red-700 border border-red-200';
        feedback.innerHTML = '<span>Terjadi kesalahan saat memverifikasi. Silakan coba kembali.</span>';
        feedback.classList.remove('hidden');
    }
}

function copySingleRecoveryCode(code, btn) {
    if (!code) return;

    function onSuccess() {
        if (!btn) return;
        const origSvg = btn.innerHTML;
        const origTitle = btn.getAttribute('title') || '';
        
        btn.setAttribute('title', 'Tersalin!');
        btn.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-600 animate-in zoom-in duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>`;
        
        const parent = btn.parentElement;
        const badge = parent ? parent.querySelector('.status-badge') : null;
        let origBadgeText = '';
        if (badge) {
            origBadgeText = badge.textContent;
            badge.textContent = "{{ __('Tersalin!') }}";
            badge.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-200/70', 'border-emerald-200/60');
            badge.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
        }
        
        setTimeout(() => {
            btn.innerHTML = origSvg;
            btn.setAttribute('title', origTitle);
            if (badge && origBadgeText) {
                badge.textContent = origBadgeText;
                badge.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
                badge.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200/70');
            }
        }, 1500);
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(onSuccess).catch(() => {
            fallbackCopy(code, onSuccess);
        });
    } else {
        fallbackCopy(code, onSuccess);
    }
}

function fallbackCopy(text, cb) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-9999px";
    textArea.style.top = "-9999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        if (cb) cb();
    } catch (e) {
        console.error('Fallback copy failed', e);
    }
    document.body.removeChild(textArea);
}

function copyAllRecoveryCodes() {
    const codes = @json(array_column($recoveryCodes ?? [], 'code'));
    const text = "KUNCI CADANGAN 2FA - CAHAYA BUKU (admin@cahayabook.com):\n" + codes.join("\n") + "\n\n*Catatan: Setiap kunci hanya berlaku 1 kali.";
    
    function onSuccess() {
        ['btn-copy-all-text', 'btn-copy-all-setup-text'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const orig = el.textContent;
                el.textContent = "{{ __('Tersalin!') }}";
                setTimeout(() => { el.textContent = orig; }, 2000);
            }
        });
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(onSuccess).catch(() => fallbackCopy(text, onSuccess));
    } else {
        fallbackCopy(text, onSuccess);
    }
}

// Backup Sekarang Action via AJAX
async function triggerBackupNow() {
    const btn = document.getElementById('btn-backup-now');
    const origHtml = btn ? btn.innerHTML : '';

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Membuat Backup...</span>';
    }

    try {
        const response = await fetch("{{ route('pengaturan.backup.create') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        if (response.ok && data.success) {
            if (data.last_backup && data.last_backup.formatted) {
                const elLast = document.getElementById('live-last-backup-text');
                if (elLast) elLast.textContent = data.last_backup.formatted;
            }
            if (data.db_size && data.db_size.formatted) {
                const elSize = document.getElementById('live-db-size-text');
                if (elSize) elSize.textContent = data.db_size.formatted;
            }

            if (btn) {
                btn.innerHTML = '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> <span>Berhasil Dibuat!</span>';
            }

            setTimeout(() => {
                window.location.reload();
            }, 1200);
        } else {
            alert(data.message || 'Gagal membuat backup database.');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }
    } catch (e) {
        alert('Terjadi kesalahan koneksi saat membuat backup.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }
}

function openRestoreModal() {
    const modal = document.getElementById('modal-backup-restore');
    if (modal) modal.classList.remove('hidden');
}

function closeRestoreModal() {
    const modal = document.getElementById('modal-backup-restore');
    if (modal) modal.classList.add('hidden');
}

function switchBackupTab(tab) {
    const btnServer = document.getElementById('tab-btn-server');
    const btnUpload = document.getElementById('tab-btn-upload');
    const contentServer = document.getElementById('tab-content-server');
    const contentUpload = document.getElementById('tab-content-upload');

    if (!btnServer || !btnUpload || !contentServer || !contentUpload) return;

    if (tab === 'server') {
        btnServer.className = 'px-3.5 py-2 text-[12.5px] font-semibold border-b-2 border-[#2d4636] text-[#2d4636] transition-all cursor-pointer flex items-center gap-1.5';
        btnUpload.className = 'px-3.5 py-2 text-[12.5px] font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition-all cursor-pointer flex items-center gap-1.5';
        contentServer.classList.remove('hidden');
        contentUpload.classList.add('hidden');
    } else {
        btnUpload.className = 'px-3.5 py-2 text-[12.5px] font-semibold border-b-2 border-[#2d4636] text-[#2d4636] transition-all cursor-pointer flex items-center gap-1.5';
        btnServer.className = 'px-3.5 py-2 text-[12.5px] font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition-all cursor-pointer flex items-center gap-1.5';
        contentUpload.classList.remove('hidden');
        contentServer.classList.add('hidden');
    }
}

function handleBackupFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const labelName = document.getElementById('upload-label-filename');
        const labelDesc = document.getElementById('upload-label-desc');
        const submitBtn = document.getElementById('btn-submit-upload-restore');
        if (labelName) labelName.textContent = file.name;
        if (labelDesc) labelDesc.textContent = (file.size / 1024).toFixed(1) + ' KB - Klik untuk mengganti';
        if (submitBtn) submitBtn.disabled = false;
    }
}

function confirmRestoreServerFile(filename) {
    const targetName = document.getElementById('confirm-restore-target-name');
    const inputVal = document.getElementById('confirm-restore-filename-input');
    const modal = document.getElementById('modal-confirm-restore');
    const form = document.getElementById('form-confirm-restore-action');
    
    if (targetName) targetName.textContent = filename;
    if (inputVal) inputVal.value = filename;
    if (form) form.onsubmit = null; // Standard form submit
    if (modal) modal.classList.remove('hidden');
}

function closeRestoreConfirmModal() {
    const modal = document.getElementById('modal-confirm-restore');
    if (modal) modal.classList.add('hidden');
}

function confirmUploadRestore() {
    const fileInput = document.getElementById('input-backup-file');
    if (!fileInput.files || !fileInput.files[0]) return;

    const file = fileInput.files[0];
    const targetName = document.getElementById('confirm-restore-target-name');
    const inputVal = document.getElementById('confirm-restore-filename-input');
    const modal = document.getElementById('modal-confirm-restore');
    const form = document.getElementById('form-confirm-restore-action');

    if (targetName) targetName.textContent = file.name + ' (File Upload)';
    if (inputVal) inputVal.value = '';

    if (form) {
        form.onsubmit = function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-restore-action');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memulihkan Database...</span>';
            }
            document.getElementById('form-upload-restore').submit();
        };
    }

    if (modal) modal.classList.remove('hidden');
}

function confirmDeleteBackup(filename) {
    const targetName = document.getElementById('confirm-delete-backup-name');
    const form = document.getElementById('form-delete-backup-action');
    const modal = document.getElementById('modal-confirm-delete-backup');

    if (targetName) targetName.textContent = filename;
    if (form) form.action = "{{ url('/pengaturan/backup') }}/" + encodeURIComponent(filename);
    if (modal) modal.classList.remove('hidden');
}

function closeDeleteBackupConfirmModal() {
    const modal = document.getElementById('modal-confirm-delete-backup');
    if (modal) modal.classList.add('hidden');
}

// Close modals on Escape key or backdrop click
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteBackupConfirmModal();
        closeRestoreConfirmModal();
        closeRestoreModal();
        closeDisable2faConfirmModal();
        close2faModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    @if(session('open_2fa_modal'))
    open2faModal();
    @endif

    @if(session('open_restore_modal'))
    openRestoreModal();
    @endif

    const modalRestore = document.getElementById('modal-backup-restore');
    if (modalRestore) {
        modalRestore.addEventListener('click', function(e) {
            if (e.target === this || (e.target.firstElementChild && e.target.classList.contains('min-h-full'))) {
                closeRestoreModal();
            }
        });
    }

    const modalConfirmRestore = document.getElementById('modal-confirm-restore');
    if (modalConfirmRestore) {
        modalConfirmRestore.addEventListener('click', function(e) {
            if (e.target === this || (e.target.firstElementChild && e.target.classList.contains('min-h-full'))) {
                closeRestoreConfirmModal();
            }
        });
    }

    const modalConfirmDelete = document.getElementById('modal-confirm-delete-backup');
    if (modalConfirmDelete) {
        modalConfirmDelete.addEventListener('click', function(e) {
            if (e.target === this || (e.target.firstElementChild && e.target.classList.contains('min-h-full'))) {
                closeDeleteBackupConfirmModal();
            }
        });
    }

    const modal2fa = document.getElementById('modal-2fa');
    if (modal2fa) {
        modal2fa.addEventListener('click', function(e) {
            if (e.target === this || (e.target.firstElementChild && e.target.classList.contains('min-h-full'))) {
                close2faModal();
            }
        });
    }

    const modalConfirm = document.getElementById('modal-confirm-disable-2fa');
    if (modalConfirm) {
        modalConfirm.addEventListener('click', function(e) {
            if (e.target === this || (e.target.firstElementChild && e.target.classList.contains('min-h-full'))) {
                closeDisable2faConfirmModal();
            }
        });
    }
});
</script>
@endsection
