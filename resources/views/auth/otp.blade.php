<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Email - Cahaya Buku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(circle at top left, #eef5ef, transparent 40%),
                              radial-gradient(circle at bottom right, #e8f0eb, transparent 40%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 sm:p-8 relative overflow-x-hidden">

    <!-- Ambient glow -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-[#d5f0d5] rounded-full mix-blend-multiply filter blur-[100px] opacity-40 pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>

    <div class="bg-white rounded-[24px] shadow-2xl overflow-hidden max-w-[1024px] w-full flex flex-col md:flex-row relative z-10 border border-gray-100">
        
        <!-- Left Panel -->
        <div class="hidden md:flex md:w-1/2 bg-[#f0f4ef] p-10 flex-col justify-between relative rounded-l-[24px]">
            <div class="relative w-full aspect-[4/5] rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white/50">
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Library Interior" 
                     class="absolute inset-0 w-full h-full object-cover">
                
                <div class="absolute inset-0 flex items-center justify-center p-6 bg-black/10">
                    <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-[260px] text-center">
                        <div class="w-12 h-12 rounded-full bg-[#dcfce7] flex items-center justify-center mx-auto mb-3 text-[#16a34a]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">Verifikasi Email 2FA</h4>
                        <p class="text-[11px] text-gray-500">Keamanan akun terlindungi dengan One-Time Password ke email Anda.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <h4 class="font-bold text-gray-900 text-[15px] mb-1">Akses Aman & Terproteksi</h4>
                <p class="text-gray-500 text-[13px] leading-relaxed">
                    Setiap percobaan login membutuhkan kode verifikasi yang dikirimkan langsung ke email pemilik akun untuk mencegah akses tanpa izin.
                </p>
            </div>
        </div>

        <!-- Right Panel (OTP Form) -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-between">
            
            <div>
                <!-- Brand Header -->
                <div class="flex items-center gap-2.5 mb-8">
                    <div class="w-9 h-9 rounded-xl bg-[#4a6b54] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-[18px] tracking-tight">CAHAYA BOOK</span>
                </div>

                <!-- Title & Instructions -->
                <div class="mb-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#e8f3ea] text-[#355240] text-[12px] font-semibold mb-3">
                        <span class="w-2 h-2 rounded-full bg-[#16a34a] animate-pulse"></span>
                        Verifikasi 2-Langkah (Email OTP)
                    </div>
                    <h2 class="text-[26px] font-bold text-gray-900 leading-tight">Periksa Email Anda</h2>
                    <p class="text-[13px] text-gray-600 mt-2 leading-relaxed">
                        Kami telah mengirimkan 6 digit kode verifikasi ke alamat email:
                    </p>
                    <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-900 font-bold text-[14px]">
                        <svg class="w-4 h-4 text-[#4a6b54]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $email }}</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">*(Jangan lupa periksa folder <strong>Spam / Promosi</strong> jika email tidak ada di Kotak Masuk).*</p>
                </div>

                @if(session('error'))
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[13px] flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 p-3 rounded-xl bg-green-50 border border-green-200 text-green-800 text-[13px] flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- OTP Form -->
                <form method="POST" action="{{ route('login.otp.verify') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="otp" class="block text-[13px] font-bold text-gray-700 mb-2">
                            Masukkan 6-Digit Kode OTP
                        </label>
                        <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" autofocus required
                               class="block w-full rounded-xl border border-gray-300 bg-white py-3.5 px-4 text-center font-mono text-[28px] font-bold tracking-[0.4em] text-gray-900 focus:border-[#4a6b54] focus:outline-none focus:ring-4 focus:ring-[#4a6b54]/15 transition-all placeholder:tracking-normal placeholder:font-normal placeholder:text-gray-300 placeholder:text-base"
                               placeholder="123456" autocomplete="one-time-code">
                        
                        @error('otp')
                            <p class="mt-2 text-[13px] text-red-600 font-medium flex items-center gap-1.5">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-[10px] shadow-[0_4px_14px_0_rgba(74,107,84,0.39)] text-[14px] font-bold text-white bg-[#4e7158] hover:bg-[#3d5a46] hover:shadow-[0_6px_20px_rgba(74,107,84,0.23)] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4a6b54] transition-all duration-200">
                        <span>Verifikasi & Masuk Dashboard</span>
                        <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <!-- Resend and Cancel Actions -->
                <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[13px]">
                    <!-- Resend OTP -->
                    <form method="POST" action="{{ route('login.otp.resend') }}" id="resend-form">
                        @csrf
                        <button type="submit" id="btn-resend" disabled
                                class="font-semibold text-gray-400 cursor-not-allowed hover:text-gray-600 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Kirim Ulang (<span id="timer">60</span>s)</span>
                        </button>
                    </form>

                    <!-- Change Account / Cancel -->
                    <form method="POST" action="{{ route('login.otp.cancel') }}">
                        @csrf
                        <button type="submit" class="font-semibold text-[#4a6b54] hover:text-[#355240] hover:underline transition-colors flex items-center gap-1">
                            <span>Ganti Akun / Login Ulang</span>
                            <span>&rarr;</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-[12px] font-medium text-gray-400">© 2026 PT Cahaya Buku Indonesia</p>
            </div>
            
        </div>
    </div>

    <script>
        // Countdown timer for Resend OTP
        const resendAt = {{ $resendAt }};
        const btnResend = document.getElementById('btn-resend');
        const timerSpan = document.getElementById('timer');

        function updateCountdown() {
            const now = Math.floor(Date.now() / 1000);
            const remaining = Math.max(0, resendAt - now);

            if (remaining > 0) {
                btnResend.disabled = true;
                btnResend.classList.remove('text-[#4a6b54]', 'cursor-pointer', 'hover:underline');
                btnResend.classList.add('text-gray-400', 'cursor-not-allowed');
                timerSpan.textContent = remaining;
                btnResend.querySelector('span').textContent = `Kirim Ulang (${remaining}s)`;
            } else {
                btnResend.disabled = false;
                btnResend.classList.remove('text-gray-400', 'cursor-not-allowed');
                btnResend.classList.add('text-[#4a6b54]', 'cursor-pointer', 'hover:underline');
                btnResend.querySelector('span').textContent = 'Kirim Ulang Kode ke Email';
            }
        }

        updateCountdown();
        const countdownInterval = setInterval(() => {
            updateCountdown();
            const now = Math.floor(Date.now() / 1000);
            if (resendAt - now <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    </script>
</body>
</html>
