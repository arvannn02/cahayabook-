<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Verifikasi 2FA') }} - Cahaya Buku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(circle at top left, #eef5ef 0%, transparent 45%),
                              radial-gradient(circle at bottom right, #e8f0eb 0%, transparent 45%);
            min-height: 100vh;
        }
        .otp-input {
            letter-spacing: 0.35em;
        }
        @media (max-width: 400px) {
            .otp-input {
                letter-spacing: 0.22em;
                font-size: 20px;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-3 sm:p-6 bg-[#f8fafc] relative overflow-x-hidden">

    <!-- Ambient background glow -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[420px] sm:w-[540px] h-[320px] bg-[#d5f0d5] rounded-full mix-blend-multiply filter blur-[95px] opacity-60 pointer-events-none"></div>

    <!-- Main Card (Matching Modal Style in Photo 2) -->
    <div class="w-full max-w-[420px] sm:max-w-[440px] bg-white rounded-2xl sm:rounded-[22px] shadow-xl border border-gray-100 overflow-hidden relative z-10 transition-all animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal-Style Header Bar -->
        <div class="px-5 sm:px-6 py-4 bg-[#f8faf9] border-b border-[#eef0f2] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-[#2d4636] rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-[14.5px] sm:text-[15.5px] font-bold text-gray-900 leading-tight">Verifikasi 2FA Authenticator</h1>
                    <p class="text-[11px] sm:text-[11.5px] text-gray-500 leading-tight mt-0.5">Verifikasi dua langkah akun</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50/80 border border-emerald-200/60 text-emerald-800 text-[11px] font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                <span>Cahaya Buku</span>
            </div>
        </div>

        <!-- Modal Body Content -->
        <div class="p-5 sm:p-6 space-y-4">

            <!-- 2FA Active Status Banner -->
            <div class="flex items-center justify-between p-3 sm:p-3.5 bg-emerald-50/90 border border-emerald-200/70 rounded-xl">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <div>
                        <p class="text-[12px] sm:text-[12.5px] font-bold text-emerald-950">Status 2FA: Aktif</p>
                        <p class="text-[10.5px] sm:text-[11px] text-emerald-700">Akun terhubung & dilindungi Google Authenticator</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] sm:text-[10.5px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200/80 flex-shrink-0">
                    Terproteksi
                </span>
            </div>

            <!-- Instruction & Target Account -->
            <div class="text-center pt-1 pb-1">
                <p class="text-[12px] sm:text-[12.5px] text-gray-600 leading-relaxed">
                    Buka aplikasi <strong class="text-gray-800">Google Authenticator</strong> atau gunakan Kunci Cadangan:
                </p>
                <div class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 rounded-lg border border-gray-200 font-mono text-[11.5px] text-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ $user->email ?? 'admin@cahayabook.com' }}</span>
                </div>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
            <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[12px] flex items-start gap-2.5 animate-in fade-in">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ $errors->first('recovery_code') ?? $errors->first('otp_code') ?? $errors->first() }}</span>
            </div>
            @endif

            @php
                $isRecoveryMode = old('recovery_code') || session('use_recovery_mode') || $errors->has('recovery_code');
            @endphp

            <!-- 2FA Input Form -->
            <form id="form-2fa" action="{{ route('login.2fa.verify') }}" method="POST" class="space-y-4" autocomplete="off">
                @csrf

                <!-- MODE 1: OTP Code 6 Digit (Default) -->
                <div id="container-otp-mode" class="{{ $isRecoveryMode ? 'hidden' : '' }}">
                    <label for="otp_code" class="block text-[11.5px] sm:text-[12px] font-semibold text-gray-700 mb-1.5 text-center">
                        Kode Verifikasi (6 Digit)
                    </label>
                    <div class="relative">
                        <input id="otp_code" 
                               name="otp_code" 
                               type="text" 
                               maxlength="6" 
                               inputmode="numeric" 
                               pattern="[0-9]*" 
                               {{ $isRecoveryMode ? '' : 'required autofocus' }} 
                               autocomplete="one-time-code"
                               placeholder="------"
                               class="otp-input block w-full py-3 sm:py-3.5 px-3 sm:px-4 border border-gray-300 rounded-xl bg-white text-gray-900 text-center font-mono text-[22px] sm:text-[24px] font-bold focus:outline-none focus:ring-2 focus:ring-[#2d4636]/25 focus:border-[#2d4636] transition-all placeholder:text-gray-300 shadow-2xs">
                    </div>
                    <p class="mt-2 text-[10.5px] sm:text-[11px] text-gray-400 text-center">
                        Kode OTP diperbarui otomatis setiap 30 detik di ponsel Anda.
                    </p>
                </div>

                <!-- MODE 2: Kunci Cadangan (Recovery Code) -->
                <div id="container-recovery-mode" class="{{ $isRecoveryMode ? '' : 'hidden' }} space-y-1.5">
                    <label for="recovery_code" class="block text-[11.5px] sm:text-[12px] font-semibold text-gray-700 mb-1 text-center">
                        Kunci Cadangan (Backup Key)
                    </label>
                    <div class="relative">
                        <input id="recovery_code" 
                               name="recovery_code" 
                               type="text" 
                               maxlength="16" 
                               value="{{ old('recovery_code') }}"
                               {{ $isRecoveryMode ? 'required autofocus' : '' }}
                               placeholder="Contoh: CB26-A1B2"
                               class="block w-full py-3 px-3 sm:px-4 border border-gray-300 rounded-xl bg-white text-gray-900 text-center font-mono text-[16px] sm:text-[17px] font-bold uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-[#2d4636]/25 focus:border-[#2d4636] transition-all placeholder:text-gray-300 placeholder:font-normal placeholder:tracking-normal shadow-2xs">
                    </div>
                    <p class="mt-1.5 text-[10.5px] sm:text-[11px] text-gray-500 text-center leading-relaxed">
                        Gunakan salah satu Kunci Cadangan akun Anda. Setiap kunci hanya dapat digunakan 1 kali.
                    </p>
                </div>

                <!-- Toggle Option: Switch between OTP and Recovery Key (di bawah kode verifikasi) -->
                <div class="text-center pt-0.5">
                    <button type="button" 
                            id="btn-toggle-mode"
                            onclick="toggleAuthMode()"
                            class="text-[12px] font-semibold text-[#2d4636] hover:text-[#1c2c22] hover:underline inline-flex items-center gap-1.5 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span id="btn-toggle-label">
                            {{ $isRecoveryMode ? '← Masuk dengan Google Authenticator' : 'Masuk menggunakan Kunci Cadangan' }}
                        </span>
                    </button>
                </div>

                <!-- Action Button -->
                <button type="submit" 
                        id="btn-submit-2fa"
                        class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-xs hover:shadow text-[13px] sm:text-[13.5px] font-semibold text-white bg-[#2d4636] hover:bg-[#23372b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2d4636] transition-all duration-150 cursor-pointer">
                    <span id="btn-submit-label">
                        {{ $isRecoveryMode ? 'Verifikasi Kunci Cadangan' : 'Konfirmasi & Masuk ke Dashboard' }}
                    </span>
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <!-- Bottom Cancel Button -->
            <form action="{{ route('login.2fa.cancel') }}" method="POST" class="pt-1">
                @csrf
                <button type="submit" 
                        class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl text-center text-[12px] sm:text-[12.5px] transition-colors cursor-pointer flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </button>
            </form>

        </div>
    </div>

    <script>
    const otpInput = document.getElementById('otp_code');
    const recoveryInput = document.getElementById('recovery_code');
    const form2fa = document.getElementById('form-2fa');

    // Auto-format OTP numbers and auto-submit on 6 digits
    if (otpInput && form2fa) {
        otpInput.addEventListener('input', function() {
            const val = this.value.replace(/\D/g, '');
            this.value = val;
            if (val.length === 6) {
                const btn = document.getElementById('btn-submit-2fa');
                if (btn) {
                    btn.innerHTML = '<svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memverifikasi...</span>';
                }
                form2fa.submit();
            }
        });
    }

    // Toggle between OTP and Recovery Code input
    function toggleAuthMode() {
        const otpContainer = document.getElementById('container-otp-mode');
        const recoveryContainer = document.getElementById('container-recovery-mode');
        const toggleLabel = document.getElementById('btn-toggle-label');
        const submitLabel = document.getElementById('btn-submit-label');

        const isCurrentlyRecovery = !recoveryContainer.classList.contains('hidden');

        if (isCurrentlyRecovery) {
            // Switch back to OTP
            recoveryContainer.classList.add('hidden');
            otpContainer.classList.remove('hidden');
            if (recoveryInput) recoveryInput.removeAttribute('required');
            if (otpInput) {
                otpInput.setAttribute('required', 'required');
                setTimeout(() => otpInput.focus(), 150);
            }
            if (toggleLabel) toggleLabel.textContent = 'Masuk menggunakan Kunci Cadangan';
            if (submitLabel) submitLabel.textContent = 'Konfirmasi & Masuk ke Dashboard';
        } else {
            // Switch to Recovery Code
            otpContainer.classList.add('hidden');
            recoveryContainer.classList.remove('hidden');
            if (otpInput) otpInput.removeAttribute('required');
            if (recoveryInput) {
                recoveryInput.setAttribute('required', 'required');
                setTimeout(() => recoveryInput.focus(), 150);
            }
            if (toggleLabel) toggleLabel.textContent = '← Masuk dengan Google Authenticator';
            if (submitLabel) submitLabel.textContent = 'Verifikasi Kunci Cadangan';
        }
    }
    </script>
</body>
</html>
