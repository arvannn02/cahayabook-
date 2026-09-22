@extends('layouts.app')

@section('title', 'Pengguna - Cahaya Buku')

@section('content')
<div class="px-8 py-8 w-full max-w-7xl mx-auto">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 flex items-center justify-between bg-[#edf7ee] border border-[#cbe6d0] text-[#22543d] px-5 py-3.5 rounded-2xl text-[13.5px] shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-full bg-[#3a5d43] text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#2d7a4d] hover:text-[#1c452c] text-xl font-bold leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center justify-between bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-[13.5px] shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-xl font-bold leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    @if(isset($errors) && $errors->any() && !(($errors ?? null)?->has('current_password') || ($errors ?? null)?->has('password')))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-[13.5px] shadow-xs">
            <p class="font-semibold mb-1">Periksa kembali data Anda:</p>
            <ul class="list-disc list-inside text-[12.5px] space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Info Banner (Kebijakan Akun Tunggal) --}}
    <div class="bg-gray-50 rounded-[16px] p-6 mb-8 border border-gray-100">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-[15px] font-bold text-gray-900 mb-1">Kebijakan Akun Tunggal (Single Account Policy)</h3>
                <p class="text-[13px] text-gray-600 leading-relaxed">Sistem ini menggunakan model akun tunggal bersama ( <code class="bg-white px-1.5 py-0.5 rounded text-[12px] font-mono text-gray-700 border border-gray-200">petugas_cahaya</code> ) untuk seluruh staf gudang. Model ini diimplementasikan untuk efisiensi operasional tinggi tanpa hambatan login berulang. Akuntabilitas individu dikelola melalui pencatatan aktivitas terpusat (Activity Log) yang melacak Terminal ID dan jenis transaksi.</p>
            </div>
        </div>
    </div>

    {{-- Two Column Section (Profile + Hak Akses) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Left: Profil Akun Bersama --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80">
            <h3 class="text-[17px] font-bold text-gray-900 mb-6">Profil Akun Bersama</h3>

            <div class="flex flex-col items-center text-center mb-6">
                {{-- Avatar --}}
                <div class="w-[80px] h-[80px] rounded-full bg-[#e8f3ea] flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>

                <h4 class="text-[18px] font-bold text-gray-900 mb-0.5">petugas_cahaya</h4>
                <p class="text-[13px] text-gray-500 mb-3">Shared Warehouse Account</p>

                {{-- Status Badge --}}
                <span class="inline-flex items-center gap-1.5 bg-[#e8f3ea] text-[#4a6b54] rounded-full px-3.5 py-1 text-[12px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4a6b54]"></span>
                    Aktif
                </span>
            </div>

            {{-- Account Info --}}
            <div class="border-t border-gray-100 pt-4 space-y-3 mb-6">
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-gray-500">Dibuat pada</span>
                    <span class="font-semibold text-gray-900">{{ $accountCreatedAt }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-gray-500">Terakhir Update Sandi</span>
                    <span class="font-semibold text-gray-900">{{ $lastPasswordUpdate }}</span>
                </div>
            </div>

            {{-- Change Password Button --}}
            <button type="button" onclick="openPasswordModal()" class="w-full bg-[#4a6b54] hover:bg-[#3d5945] active:scale-[0.99] text-white py-2.5 rounded-lg text-[13px] font-semibold transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                <span>Ubah Kata Sandi Akses</span>
            </button>
        </div>

        {{-- Right: Hak Akses Tersedia --}}
        <div class="bg-white rounded-[16px] p-6 shadow-sm border border-gray-100/80">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-[17px] font-bold text-gray-900">Hak Akses Tersedia</h3>
                    <p class="text-[12px] text-gray-500 mt-0.5">Konfigurasi izin modul operasional staf gudang</p>
                </div>
                <button type="button" onclick="openHakAksesModal()" class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-gray-800 flex items-center justify-center transition-colors cursor-pointer" title="Kelola Hak Akses">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-x-6 gap-y-5">
                @foreach($hakAkses as $key => $item)
                <div class="flex items-start gap-3">
                    @if($item['status'])
                        {{-- Icon Aktif (Centang Hijau) --}}
                        <div class="w-6 h-6 rounded-full bg-[#e8f3ea] flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-gray-900">{{ $item['nama'] }}</p>
                            <p class="text-[12px] text-gray-500 leading-snug">{{ $item['deskripsi_aktif'] }}</p>
                        </div>
                    @else
                        {{-- Icon Dibatasi (Silang Merah) --}}
                        <div class="w-6 h-6 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-bold text-gray-900">{{ $item['nama'] }}</p>
                            <p class="text-[12px] text-gray-500 leading-snug">{{ $item['deskripsi_nonaktif'] }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Log Aktivitas Keamanan & Transaksi --}}
    <div class="bg-white border border-gray-100 rounded-[14px] shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100">
            <div>
                <h3 class="text-[17px] font-bold text-gray-900">Log Aktivitas Keamanan & Transaksi</h3>
                <p class="text-[13px] text-gray-500 mt-0.5">Pemantauan aktivitas real-time untuk akuntabilitas operasional.</p>
            </div>
        </div>

        <div class="w-full overflow-hidden">
            <table class="w-full table-fixed">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider px-5 py-3.5 w-[18%]">WAKTU (WIB)</th>
                        <th class="text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider px-3 py-3.5 w-[20%]">AKSI</th>
                        <th class="text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider px-3 py-3.5 w-[42%]">DESKRIPSI</th>
                        <th class="text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider px-5 py-3.5 w-[20%]">TERMINAL / IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($aktivitas as $index => $log)
                    @php
                        $pageNumber = floor($index / 5) + 1;
                        $waktu = \Carbon\Carbon::parse($log->created_at);
                        $isToday = $waktu->isToday();
                        $waktuStr = $isToday ? 'Hari ini, ' . $waktu->format('H:i') : $waktu->format('d M Y, H:i');

                        $aksiIcons = [
                            'Buku Masuk' => '📦',
                            'Update Stok' => '✏️',
                            'Sesi Login' => '🔑',
                            'Ubah Kata Sandi' => '🔐',
                            'Update Hak Akses' => '🛡️',
                        ];
                        $icon = $aksiIcons[$log->aksi] ?? '📋';
                    @endphp
                    <tr data-log-row data-log-page="{{ $pageNumber }}" class="hover:bg-gray-50/50 transition-colors {{ $pageNumber > 1 ? 'hidden' : '' }}">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-[12.5px] text-gray-500">{{ $waktuStr }}</span>
                        </td>
                        <td class="px-3 py-3.5 whitespace-nowrap">
                            <span class="text-[12.5px] font-semibold text-gray-800">{{ $icon }} {{ $log->aksi }}</span>
                        </td>
                        <td class="px-3 py-3.5">
                            <p class="text-[12.5px] text-gray-600 leading-snug break-words">{{ $log->detail }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <span class="inline-flex px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[11px] font-mono font-medium">{{ $log->terminal ?? 'N/A' }} / {{ $log->ip_address ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-400">
                            <p class="text-[14px] font-medium">Belum ada log aktivitas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Slide Pagination Component (Maksimal 5 Baris per Halaman) --}}
        @php
            $totalLogPages = ceil(count($aktivitas) / 5);
        @endphp
        @if($totalLogPages > 1)
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-white">
                <p class="text-[12.5px] text-gray-500" id="logPaginationInfo">
                    Menampilkan <strong>1 - {{ min(5, count($aktivitas)) }}</strong> dari <strong>{{ count($aktivitas) }}</strong> log aktivitas
                </p>
                <div class="flex items-center gap-1.5" id="logPaginationControls">
                    {{-- Previous Button (<) --}}
                    <button type="button" onclick="goToLogPage(currentLogPage - 1)" id="btnLogPrev"
                            class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer" disabled>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    {{-- Page Buttons (1, 2, 3...) sesuai desain foto ke-2 --}}
                    @for($p = 1; $p <= $totalLogPages; $p++)
                        <button type="button" onclick="goToLogPage({{ $p }})" id="logPageBtn-{{ $p }}"
                                class="w-8 h-8 rounded-lg font-bold text-[13px] flex items-center justify-center transition-colors cursor-pointer {{ $p === 1 ? 'bg-[#2d4636] text-white shadow-sm border border-[#2d4636]' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                            {{ $p }}
                        </button>
                    @endfor

                    {{-- Next Button (>) --}}
                    <button type="button" onclick="goToLogPage(currentLogPage + 1)" id="btnLogNext"
                            class="w-8 h-8 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL: UBAH KATA SANDI AKSES --}}
<div id="password-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Ubah Kata Sandi Akses</h3>
                        <p class="text-[12px] text-[#6b7280]">Perbarui kredensial sandi akses bersama <code class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded text-[11px] font-mono">petugas_cahaya</code></p>
                    </div>
                </div>
                <button type="button" onclick="closePasswordModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body / Form --}}
            <form action="{{ route('pengguna.updatePassword') }}" method="POST" class="p-6 space-y-4">
                @csrf

                {{-- Notice Box --}}
                <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/60 rounded-xl text-[12.5px] text-emerald-800 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#2d4636] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="leading-relaxed">Kata sandi akses baru ini akan digunakan bersama oleh staf gudang dan aktivitas perubahan akan dicatat secara otomatis.</p>
                </div>

                {{-- Kata Sandi Saat Ini --}}
                <div>
                    <label class="block text-[12.5px] font-semibold text-gray-700 mb-1">Kata Sandi Saat Ini <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <div class="relative">
                        <input type="password" name="current_password" id="input_current_password" placeholder="Masukkan kata sandi saat ini" class="w-full pl-3.5 pr-10 py-2.5 text-[13px] border {{ ($errors ?? null)?->has('current_password') ? 'border-red-400 bg-red-50/20' : 'border-gray-300' }} rounded-xl focus:outline-none focus:border-[#2d4636] focus:ring-2 focus:ring-[#2d4636]/15 transition-all">
                        <button type="button" onclick="togglePasswordVisibility('input_current_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-[12px] text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata Sandi Baru --}}
                <div>
                    <label class="block text-[12.5px] font-semibold text-gray-700 mb-1">Kata Sandi Akses Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="input_new_password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full pl-3.5 pr-10 py-2.5 text-[13px] border {{ ($errors ?? null)?->has('password') ? 'border-red-400 bg-red-50/20' : 'border-gray-300' }} rounded-xl focus:outline-none focus:border-[#2d4636] focus:ring-2 focus:ring-[#2d4636]/15 transition-all">
                        <button type="button" onclick="togglePasswordVisibility('input_new_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[12px] text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Kata Sandi Baru --}}
                <div>
                    <label class="block text-[12.5px] font-semibold text-gray-700 mb-1">Ulangi Kata Sandi Akses Baru <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="input_confirm_password" required minlength="6" placeholder="Ulangi kata sandi baru" class="w-full pl-3.5 pr-10 py-2.5 text-[13px] border border-gray-300 rounded-xl focus:outline-none focus:border-[#2d4636] focus:ring-2 focus:ring-[#2d4636]/15 transition-all">
                        <button type="button" onclick="togglePasswordVisibility('input_confirm_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                            <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg class="w-4 h-4 eye-off-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closePasswordModal()" class="px-4 py-2 border border-gray-200 rounded-xl text-[13px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#2d4636] hover:bg-[#203426] text-white rounded-xl text-[13px] font-semibold transition-colors shadow-sm cursor-pointer">
                        Simpan Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: KELOLA HAK AKSES TERSEDIA --}}
<div id="hak-akses-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/40 backdrop-blur-sm">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-in fade-in zoom-in duration-150">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-bold text-[#111827]">Kelola Hak Akses Tersedia</h3>
                        <p class="text-[12px] text-[#6b7280]">Konfigurasi perizinan modul untuk akun bersama staf gudang.</p>
                    </div>
                </div>
                <button type="button" onclick="closeHakAksesModal()" class="w-8 h-8 rounded-lg text-[#9ca3af] hover:text-[#374151] hover:bg-gray-100 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body / Form --}}
            <form action="{{ route('pengguna.updateHakAkses') }}" method="POST" class="p-6 space-y-4">
                @csrf

                {{-- Notice Box --}}
                <div class="p-3.5 bg-gray-50 border border-gray-200/80 rounded-xl text-[12.5px] text-gray-700 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-[#2d4636] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="leading-relaxed">Gunakan toggle switch di bawah untuk mengaktifkan atau membatasi akses modul staf gudang. Setiap perubahan hak akses otomatis dicatat dalam <span class="font-semibold text-gray-900">Log Aktivitas Keamanan</span>.</p>
                </div>

                {{-- Module Items --}}
                <div class="space-y-3.5 divide-y divide-gray-100">
                    @foreach($hakAkses as $key => $item)
                    <div class="pt-3.5 first:pt-0 flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[13.5px] font-bold text-gray-900">{{ $item['nama'] }}</span>
                                <span id="badge_{{ $key }}" class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $item['status'] ? 'bg-[#e8f3ea] text-[#4a6b54]' : 'bg-red-50 text-red-500' }}">
                                    {{ $item['status'] ? 'Aktif' : 'Dibatasi' }}
                                </span>
                            </div>
                            <p class="text-[12px] text-gray-500 mt-0.5">{{ $item['deskripsi_aktif'] }}</p>
                        </div>

                        {{-- Modern Toggle Switch --}}
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="hak_akses[{{ $key }}]" value="1" {{ $item['status'] ? 'checked' : '' }} onchange="updateHakAksesBadge('{{ $key }}', this)" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2d4636]"></div>
                        </label>
                    </div>
                    @endforeach
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeHakAksesModal()" class="px-4 py-2 border border-gray-200 rounded-xl text-[13px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#2d4636] hover:bg-[#203426] text-white rounded-xl text-[13px] font-semibold transition-colors shadow-sm cursor-pointer">
                        Simpan Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPasswordModal() {
        const modal = document.getElementById('password-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                const input = document.getElementById('input_new_password');
                if (input) input.focus();
            }, 100);
        }
    }

    function closePasswordModal() {
        const modal = document.getElementById('password-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function openHakAksesModal() {
        const modal = document.getElementById('hak-akses-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeHakAksesModal() {
        const modal = document.getElementById('hak-akses-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const eyeIcon = btn.querySelector('.eye-icon');
        const eyeOffIcon = btn.querySelector('.eye-off-icon');

        if (input.type === 'password') {
            input.type = 'text';
            if (eyeIcon) eyeIcon.classList.add('hidden');
            if (eyeOffIcon) eyeOffIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            if (eyeIcon) eyeIcon.classList.remove('hidden');
            if (eyeOffIcon) eyeOffIcon.classList.add('hidden');
        }
    }

    function updateHakAksesBadge(key, checkbox) {
        const badge = document.getElementById('badge_' + key);
        if (!badge) return;
        if (checkbox.checked) {
            badge.textContent = 'Aktif';
            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#e8f3ea] text-[#4a6b54]';
        } else {
            badge.textContent = 'Dibatasi';
            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-500';
        }
    }

    // Close modals when clicking backdrop outside modal card
    window.addEventListener('click', function(e) {
        const passModal = document.getElementById('password-modal');
        const hakModal = document.getElementById('hak-akses-modal');
        if (e.target === passModal) closePasswordModal();
        if (e.target === hakModal) closeHakAksesModal();
    });

    // Close on Escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePasswordModal();
            closeHakAksesModal();
        }
    });

    // Slide Pagination for Log Aktivitas (Max 5 rows per page)
    let currentLogPage = 1;
    const totalLogPages = {{ $totalLogPages ?? 1 }};
    const totalLogRows = {{ count($aktivitas) }};

    function goToLogPage(page) {
        if (page < 1 || page > totalLogPages) return;
        currentLogPage = page;

        // Toggle row visibility
        document.querySelectorAll('[data-log-row]').forEach(row => {
            if (parseInt(row.getAttribute('data-log-page')) === page) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });

        // Update page buttons
        for (let p = 1; p <= totalLogPages; p++) {
            const btn = document.getElementById('logPageBtn-' + p);
            if (btn) {
                if (p === page) {
                    btn.className = 'w-8 h-8 rounded-lg font-bold text-[13px] flex items-center justify-center transition-colors bg-[#2d4636] text-white shadow-sm border border-[#2d4636] cursor-pointer';
                } else {
                    btn.className = 'w-8 h-8 rounded-lg font-semibold text-[13px] flex items-center justify-center transition-colors bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 cursor-pointer';
                }
            }
        }

        // Update Prev / Next buttons
        const btnPrev = document.getElementById('btnLogPrev');
        const btnNext = document.getElementById('btnLogNext');
        if (btnPrev) btnPrev.disabled = (page === 1);
        if (btnNext) btnNext.disabled = (page === totalLogPages);

        // Update info text
        const info = document.getElementById('logPaginationInfo');
        if (info) {
            const start = (page - 1) * 5 + 1;
            const end = Math.min(page * 5, totalLogRows);
            info.innerHTML = `Menampilkan <strong>${start} - ${end}</strong> dari <strong>${totalLogRows}</strong> log aktivitas`;
        }
    }

    @if(($errors ?? null)?->has('current_password') || ($errors ?? null)?->has('password'))
        document.addEventListener('DOMContentLoaded', function() {
            openPasswordModal();
        });
    @endif
</script>
@endsection
