<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cahaya Buku</title>
    <!-- Include Vite's CSS/JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Real Google reCAPTCHA v2 Script -->
    <script src="https://www.google.com/recaptcha/api.js?hl=id" async defer></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* subtle radial gradient for a premium feel */
            background-image: radial-gradient(circle at top left, #eef5ef, transparent 40%),
                              radial-gradient(circle at bottom right, #e8f0eb, transparent 40%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 sm:p-8 relative overflow-x-hidden">

    <!-- Greenish glow top left (as in the screenshot) -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-[#d5f0d5] rounded-full mix-blend-multiply filter blur-[100px] opacity-40 pointer-events-none -translate-x-1/2 -translate-y-1/2"></div>

    <div class="bg-white rounded-[24px] shadow-2xl overflow-hidden max-w-[1024px] w-full flex flex-col md:flex-row relative z-10 border border-gray-100">
        
        <!-- Left Panel -->
        <div class="hidden md:flex md:w-1/2 bg-[#f0f4ef] p-8 lg:p-10 flex-col justify-center relative rounded-l-[24px]">
            <!-- Photo container with floating mini-card -->
            <div class="relative w-full aspect-[4/4.8] rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white/50">
                <!-- Background Image (Library/Office) -->
                <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Library Interior" 
                     class="absolute inset-0 w-full h-full object-cover">
                
                <!-- Floating Mini-Card (Decorative Mockup) -->
                <div class="absolute inset-0 flex items-center justify-center p-6 bg-black/10">
                    <div class="bg-white p-5 rounded-xl shadow-2xl w-full max-w-[250px] transform transition-transform hover:scale-105 duration-500">
                        <h3 class="text-center font-bold text-[#355240] text-sm tracking-wide mb-1">Sistem Pendataan Buku</h3>
                        <p class="text-center text-[11px] font-medium text-gray-800 mb-3">Masuk Ke Akun Anda</p>
                        
                        <div class="space-y-2.5">
                            <!-- Mini Input 1 -->
                            <div class="border border-gray-400 rounded-lg px-3 py-1.5 flex items-center gap-2 bg-white">
                                <svg class="w-3.5 h-3.5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-[9px] font-medium text-gray-700">Nomor Anggota / E-mail</div>
                            </div>
                            
                            <!-- Mini Input 2 -->
                            <div class="border border-gray-400 rounded-lg px-3 py-1.5 flex items-center gap-2 bg-white">
                                <svg class="w-3.5 h-3.5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                <div class="text-[9px] font-medium text-gray-700">Kata Sandi</div>
                            </div>

                            <!-- Mini Button -->
                            <div class="w-full mt-3 bg-[#355240] rounded-lg py-1.5 flex justify-center shadow-md">
                                <span class="text-white text-[10px] font-medium">Masuk</span>
                            </div>

                            <p class="text-center text-[9px] text-gray-600 font-medium mt-2">Lupa Kata Sandi?</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Panel Text (Rapat dan presisi di bawah foto) -->
            <div class="mt-5 px-1">
                <h2 class="text-[23px] font-bold text-[#355240] tracking-tight mb-1.5">Preserving Knowledge</h2>
                <p class="text-[#5a6b60] text-[13px] leading-relaxed font-medium">
                    The ultimate inventory solution for PT Cahaya Buku Indonesia.<br>
                    Managing the flow of literature with precision and care.
                </p>
            </div>
        </div>

        <!-- Right Panel (Actual Login Form) -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col justify-center bg-white rounded-r-[24px]">
            
            <!-- Logo area -->
            <div class="flex items-center gap-2 mb-8 md:mb-12">
                <div class="bg-[#4a6b54] p-1.5 rounded-md shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="font-bold text-[#4a6b54] text-[17px] tracking-tight">Cahaya Buku</span>
            </div>

            <!-- Form Headers -->
            <div class="mb-10">
                <h1 class="text-[32px] font-bold text-gray-900 tracking-tight mb-2">Welcome Back</h1>
                <p class="text-gray-500 text-[14px]">Sistem Pendataan Buku PT Cahaya Buku Indonesia</p>
            </div>

            @php
                $loginLockedUntil = (int) session('login_locked_until', 0);
                $loginIsLocked = $loginLockedUntil > now()->timestamp;
            @endphp

            @if(session('warning') || request('timeout'))
            <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-[13px] font-medium flex items-start gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>{{ session('warning') ?? 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 30 menit. Silakan masuk kembali.' }}</span>
            </div>
            @endif

            <!-- The Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6" data-login-form data-locked-until="{{ $loginLockedUntil }}" autocomplete="off">
                @csrf
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[13px] font-bold text-gray-700 mb-1.5">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#4a6b54] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" required autocomplete="off" @disabled($loginIsLocked)
                               class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-[10px] bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#4a6b54]/20 focus:border-[#4a6b54] sm:text-[14px] transition-all placeholder:text-gray-400 placeholder:font-normal" 
                               placeholder="Masukkan email Anda" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-[12px] text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[13px] font-bold text-gray-700">Password</label>
                        <a href="#" class="text-[12px] font-bold text-[#4a6b54] hover:text-[#355240] transition-colors">Forgot password?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#4a6b54] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="new-password" @disabled($loginIsLocked)
                               class="block w-full pl-11 pr-11 py-3 border border-gray-300 rounded-[10px] bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#4a6b54]/20 focus:border-[#4a6b54] sm:text-[14px] tracking-[0.2em] transition-all placeholder:text-gray-400 placeholder:tracking-normal placeholder:font-normal" 
                               placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" @disabled($loginIsLocked) class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1 rounded-md hover:bg-gray-100 cursor-pointer" title="Tampilkan kata sandi" aria-label="Tampilkan kata sandi" aria-pressed="false">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeOffIcon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-[12px] text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center pt-1 pb-2">
                    <input id="remember-me" name="remember-me" type="checkbox" @disabled($loginIsLocked)
                           class="h-4 w-4 text-[#4a6b54] focus:ring-[#4a6b54] border-gray-300 rounded cursor-pointer transition-colors accent-[#4a6b54]">
                    <label for="remember-me" class="ml-2.5 block text-[13px] font-medium text-gray-600 cursor-pointer">
                        Remember Me
                    </label>
                </div>

                <!-- Real Google reCAPTCHA v2 ("Saya bukan robot") -->
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-start">
                        <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
                    </div>
                    @error('captcha')
                        <p class="text-[12px] text-red-600 font-medium flex items-center gap-1 mt-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" @disabled($loginIsLocked)
                    data-login-submit
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-[10px] shadow-[0_4px_14px_0_rgba(74,107,84,0.39)] text-[14px] font-bold text-white bg-[#4e7158] hover:bg-[#3d5a46] hover:shadow-[0_6px_20px_rgba(74,107,84,0.23)] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4a6b54] transition-all duration-200">
                    <span data-login-submit-label>{{ $loginIsLocked ? 'Login dikunci sementara' : 'Masuk ke Dashboard' }}</span>
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-14 text-center border-t border-gray-100 pt-6">
                <p class="text-[12px] font-medium text-gray-400">© 2026 PT Cahaya Buku Indonesia</p>
            </div>
            
        </div>
    </div>

    <script>
        const loginForm = document.querySelector('[data-login-form]');
        const loginSubmit = document.querySelector('[data-login-submit]');
        const loginSubmitLabel = document.querySelector('[data-login-submit-label]');
        const lockedUntil = Number(loginForm?.dataset.lockedUntil || 0);

        if (loginForm && loginSubmit && lockedUntil > Math.floor(Date.now() / 1000)) {
            const updateLockout = () => {
                const secondsRemaining = Math.max(0, lockedUntil - Math.floor(Date.now() / 1000));
                if (secondsRemaining === 0) {
                    loginForm.querySelectorAll('input, button').forEach((element) => {
                        element.disabled = false;
                    });
                    loginSubmitLabel.textContent = 'Masuk ke Dashboard';
                    return;
                }

                const minutes = Math.floor(secondsRemaining / 60);
                const seconds = String(secondsRemaining % 60).padStart(2, '0');
                loginSubmitLabel.textContent = `Tunggu ${minutes}:${seconds}`;
                window.setTimeout(updateLockout, 1000);
            };

            updateLockout();
        }

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        togglePassword?.addEventListener('click', function () {
            const shouldShowPassword = passwordInput.type === 'password';
            passwordInput.type = shouldShowPassword ? 'text' : 'password';
            passwordInput.classList.toggle('tracking-[0.2em]', !shouldShowPassword);
            eyeIcon?.classList.toggle('hidden', shouldShowPassword);
            eyeOffIcon?.classList.toggle('hidden', !shouldShowPassword);

            const label = shouldShowPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi';
            togglePassword.setAttribute('title', label);
            togglePassword.setAttribute('aria-label', label);
            togglePassword.setAttribute('aria-pressed', String(shouldShowPassword));
        });

        // Clear any browser autofilled credentials so user must type manually
        window.addEventListener('load', function () {
            setTimeout(function () {
                @if(!$errors->any() && !old('email'))
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                if (emailInput) emailInput.value = '';
                if (passwordInput) passwordInput.value = '';
                @endif
            }, 80);
        });
    </script>
</body>
</html>
