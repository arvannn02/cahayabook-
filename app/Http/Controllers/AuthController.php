<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(?Request $request = null)
    {
        $siteKey = config('services.recaptcha.site_key');

        return view('auth.login', compact('siteKey'));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string'],
        ]);

        $rateLimitKey = 'login|'.strtolower($credentials['email']).'|'.$request->ip();
        $lockoutKey = $this->lockoutKey($rateLimitKey);

        if (Cache::has($lockoutKey) || RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return $this->lockoutResponse($request, $rateLimitKey, $lockoutKey);
        }

        // Validate Real Google reCAPTCHA v2
        $recaptchaResponse = $request->input('g-recaptcha-response');

        if (empty($recaptchaResponse)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['captcha' => 'Silakan centang verifikasi "Saya bukan robot" (reCAPTCHA) untuk melanjutkan.']);
        }

        $secretKey = config('services.recaptcha.secret_key');
        try {
            $verifyResponse = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip(),
            ]);

            $verifyData = $verifyResponse->json();

            if (! ($verifyData['success'] ?? false)) {
                Log::warning('reCAPTCHA verification failed: ' . json_encode($verifyData));

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['captcha' => 'Verifikasi reCAPTCHA gagal. Silakan centang ulang kotak "Saya bukan robot".']);
            }
        } catch (\Throwable $e) {
            Log::error('Google reCAPTCHA verification connection error: ' . $e->getMessage());
        }

        // Check credentials
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        if (! $user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($rateLimitKey, 180);

            if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
                return $this->lockoutResponse($request, $rateLimitKey, $lockoutKey);
            }

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ]);
        }

        // Credentials are valid! Clear rate limiter & lockout
        RateLimiter::clear($rateLimitKey);
        Cache::forget($lockoutKey);

        // Check if 2FA Google Authenticator is enabled
        if (\App\Services\TotpService::is2faEnabled()) {
            session([
                '2fa:user_id' => $user->id,
                '2fa:remember' => $request->boolean('remember-me'),
            ]);

            return redirect()->route('login.2fa');
        }

        // 2FA is not enabled -> login directly
        Auth::login($user, $request->boolean('remember-me'));
        $request->session()->regenerate();

        \App\Models\Aktivitas::log('Sesi Login', "Pengguna {$user->nama} ({$user->email}) berhasil masuk ke sistem.", $user->id);

        return redirect()->intended('/dashboard');
    }

    public function show2faChallenge(Request $request)
    {
        if (! $request->session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('2fa:user_id');
        $user = \App\Models\User::find($userId);
        if (! $user) {
            $request->session()->forget(['2fa:user_id', '2fa:remember']);
            return redirect()->route('login');
        }

        return view('auth.2fa', compact('user'));
    }

    public function verify2faChallenge(Request $request)
    {
        if (! $request->session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('2fa:user_id');
        $remember = (bool) $request->session()->get('2fa:remember', false);

        $recoveryCode = trim((string) $request->input('recovery_code'));
        $otpCode = trim((string) $request->input('otp_code'));

        // Jika login menggunakan Kunci Cadangan (Recovery Code)
        if ($recoveryCode !== '') {
            if (! \App\Services\TotpService::verifyRecoveryCode($recoveryCode)) {
                return back()->withErrors([
                    'recovery_code' => 'Kunci cadangan tidak valid atau sudah pernah digunakan.',
                ])->withInput()->with('use_recovery_mode', true);
            }

            $request->session()->forget(['2fa:user_id', '2fa:remember']);
            Auth::loginUsingId($userId, $remember);
            $request->session()->regenerate();

            \App\Models\Aktivitas::log('Sesi Login', 'Pengguna berhasil masuk menggunakan Kunci Cadangan 2FA.', $userId);

            return redirect()->intended('/dashboard')->with('success', 'Berhasil masuk menggunakan Kunci Cadangan.');
        }

        // Validasi kode OTP Google Authenticator standar
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ], [
            'otp_code.required' => 'Kode verifikasi 2FA wajib diisi.',
            'otp_code.size' => 'Kode verifikasi harus berupa 6 digit angka.',
        ]);

        if (! \App\Services\TotpService::verifyCode($otpCode)) {
            return back()->withErrors([
                'otp_code' => 'Kode verifikasi salah atau sudah kedaluwarsa. Silakan periksa kembali aplikasi Google Authenticator Anda.',
            ])->withInput();
        }

        $request->session()->forget(['2fa:user_id', '2fa:remember']);
        Auth::loginUsingId($userId, $remember);
        $request->session()->regenerate();

        \App\Models\Aktivitas::log('Sesi Login', 'Pengguna berhasil masuk setelah verifikasi OTP Google Authenticator.', $userId);

        return redirect()->intended('/dashboard');
    }

    public function cancel2faChallenge(Request $request)
    {
        $request->session()->forget(['2fa:user_id', '2fa:remember']);
        return redirect()->route('login');
    }

    private function lockoutResponse(Request $request, string $rateLimitKey, string $lockoutKey)
    {
        Cache::put($lockoutKey, true, 180);
        $request->session()->put(
            'login_locked_until',
            now()->addSeconds(180)->timestamp
        );

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Terlalu banyak percobaan login gagal. Silakan tunggu 3 menit sebelum mencoba kembali.']);
    }

    private function lockoutKey(string $rateLimitKey): string
    {
        return 'login-lockout:'.sha1($rateLimitKey);
    }

    public function logout(Request $request)
    {
        $isTimeout = $request->boolean('timeout') || $request->query('timeout');
        $currentUser = Auth::user();
        if ($currentUser) {
            \App\Models\Aktivitas::log('Sesi Logout', "Pengguna {$currentUser->nama} keluar dari sistem.", $currentUser->id);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isTimeout) {
            return redirect()->route('login')->with('warning', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 30 menit. Silakan masuk kembali.');
        }

        return redirect('/');
    }
}



