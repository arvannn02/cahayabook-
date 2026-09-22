<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class TotpService
{
    public const DEFAULT_SECRET = 'CAHAYABUKU234567';

    /**
     * Get 2FA secret key.
     */
    public static function getSecret(): string
    {
        $prefs = self::getPreferences();
        return $prefs['two_factor_secret'] ?? self::DEFAULT_SECRET;
    }

    /**
     * Check whether 2FA is currently active.
     */
    public static function is2faEnabled(): bool
    {
        $prefs = self::getPreferences();
        return !empty($prefs['two_factor_enabled']);
    }

    /**
     * Update 2FA status in preferences.
     */
    public static function set2faEnabled(bool $enabled): void
    {
        $filePath = storage_path('app/preferensi.json');
        $prefs = self::getPreferences();
        $prefs['two_factor_enabled'] = $enabled;
        if (!isset($prefs['two_factor_secret'])) {
            $prefs['two_factor_secret'] = self::DEFAULT_SECRET;
        }

        if (!isset($prefs['two_factor_recovery_codes']) || !is_array($prefs['two_factor_recovery_codes'])) {
            $prefs['two_factor_recovery_codes'] = self::generateRecoveryCodesArray();
        }

        if (!File::isDirectory(storage_path('app'))) {
            File::makeDirectory(storage_path('app'), 0755, true);
        }

        File::put($filePath, json_encode($prefs, JSON_PRETTY_PRINT));
    }

    /**
     * Get current recovery codes. If none exists, generates a fresh set.
     */
    public static function getRecoveryCodes(): array
    {
        $prefs = self::getPreferences();
        if (empty($prefs['two_factor_recovery_codes']) || !is_array($prefs['two_factor_recovery_codes'])) {
            return self::generateRecoveryCodes();
        }
        return $prefs['two_factor_recovery_codes'];
    }

    /**
     * Generate a new set of recovery codes and persist to preferences.
     */
    public static function generateRecoveryCodes(int $count = 8): array
    {
        $filePath = storage_path('app/preferensi.json');
        $prefs = self::getPreferences();
        $codes = self::generateRecoveryCodesArray($count);

        $prefs['two_factor_recovery_codes'] = $codes;

        if (!File::isDirectory(storage_path('app'))) {
            File::makeDirectory(storage_path('app'), 0755, true);
        }

        File::put($filePath, json_encode($prefs, JSON_PRETTY_PRINT));

        return $codes;
    }

    /**
     * Helper to create the recovery codes array.
     */
    protected static function generateRecoveryCodesArray(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $part1 = 'CB26';
            $part2 = strtoupper(bin2hex(random_bytes(2)));
            $codes[] = [
                'code' => $part1 . '-' . $part2,
                'used' => false,
                'used_at' => null,
            ];
        }
        return $codes;
    }

    /**
     * Verify and consume a recovery code.
     * Each code can only be used once.
     * When all codes have been used, automatically resets and generates new codes.
     */
    public static function verifyRecoveryCode(string $inputCode): bool
    {
        $input = strtoupper(trim(str_replace([' ', '-'], '', $inputCode)));
        if (empty($input)) {
            return false;
        }

        $codes = self::getRecoveryCodes();
        $matchedIndex = -1;

        foreach ($codes as $index => $item) {
            $cleanedStored = strtoupper(str_replace([' ', '-'], '', $item['code']));
            if ($cleanedStored === $input && empty($item['used'])) {
                $matchedIndex = $index;
                break;
            }
        }

        if ($matchedIndex === -1) {
            return false;
        }

        // Mark this specific code as used
        $codes[$matchedIndex]['used'] = true;
        $codes[$matchedIndex]['used_at'] = date('Y-m-d H:i:s');

        // Check remaining unused codes
        $remaining = array_filter($codes, fn($c) => empty($c['used']));

        if (count($remaining) === 0) {
            // All recovery codes exhausted -> automatically reset and generate brand new codes!
            self::generateRecoveryCodes();
        } else {
            // Save updated used state
            $filePath = storage_path('app/preferensi.json');
            $prefs = self::getPreferences();
            $prefs['two_factor_recovery_codes'] = $codes;
            File::put($filePath, json_encode($prefs, JSON_PRETTY_PRINT));
        }

        return true;
    }

    /**
     * Verify an OTP code against a Base32 secret.
     */
    public static function verifyCode(string $code, ?string $secret = null, int $window = 1): bool
    {
        $code = trim(str_replace(' ', '', $code));
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            return false;
        }

        $secret = $secret ?: self::getSecret();
        $secretBytes = self::base32Decode($secret);
        if ($secretBytes === false) {
            return false;
        }

        $currentTime = floor(time() / 30);

        for ($slice = -$window; $slice <= $window; $slice++) {
            $time = $currentTime + $slice;
            $timeBytes = pack('N*', 0) . pack('N*', $time);
            $hash = hash_hmac('sha1', $timeBytes, $secretBytes, true);
            $offset = ord($hash[19]) & 0x0f;
            $binary = (
                ((ord($hash[$offset + 0]) & 0x7f) << 24) |
                ((ord($hash[$offset + 1]) & 0xff) << 16) |
                ((ord($hash[$offset + 2]) & 0xff) << 8) |
                (ord($hash[$offset + 3]) & 0xff)
            ) % 1000000;

            $generated = sprintf('%06d', $binary);
            if (hash_equals($generated, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Decode a Base32 string to binary bytes according to RFC 3548/4648.
     */
    protected static function base32Decode(string $b32): string|false
    {
        $lut = [
            'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7,
            'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15,
            'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
            'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27, '4' => 28, '5' => 29, '6' => 30, '7' => 31,
        ];

        $b32 = strtoupper(str_replace([' ', '-'], '', $b32));
        $bytes = '';
        $buffer = 0;
        $bitsLeft = 0;

        for ($i = 0; $i < strlen($b32); $i++) {
            $c = $b32[$i];
            if (!isset($lut[$c])) {
                return false;
            }
            $buffer = ($buffer << 5) | $lut[$c];
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $bytes .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }

        return $bytes;
    }

    /**
     * Load current system preferences from JSON.
     */
    public static function getPreferences(): array
    {
        $filePath = storage_path('app/preferensi.json');
        if (File::exists($filePath)) {
            $data = json_decode(File::get($filePath), true);
            if (is_array($data)) {
                return $data;
            }
        }

        return [
            'bahasa' => 'id',
            'zona_waktu' => 'WIB',
            'per_page' => 5,
            'format_tanggal' => 'DD/MM/YYYY',
            'two_factor_enabled' => false,
            'two_factor_secret' => self::DEFAULT_SECRET,
        ];
    }
}
