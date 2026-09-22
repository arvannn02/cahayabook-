<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Services\TotpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PengaturanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check automated backup schedule
        BackupService::checkAndRunScheduledBackup();

        $preferensi = TotpService::getPreferences();
        $recoveryCodes = TotpService::getRecoveryCodes();
        $dbSize = BackupService::getDatabaseSize();
        $lastBackup = BackupService::getLastBackupInfo();
        $backupSchedule = BackupService::getBackupSchedule();
        $backupFiles = BackupService::getBackupFiles();

        return view('pengaturan.index', compact(
            'user',
            'preferensi',
            'recoveryCodes',
            'dbSize',
            'lastBackup',
            'backupSchedule',
            'backupFiles'
        ));
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        TotpService::generateRecoveryCodes();
        \App\Models\Aktivitas::log('Keamanan 2FA', 'Kunci cadangan (Backup Keys) 2FA baru berhasil diterbitkan');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'codes' => TotpService::getRecoveryCodes(),
                'message' => __('Kunci cadangan 2FA baru berhasil diterbitkan.'),
            ]);
        }

        return redirect()->back()->with('success', __('Kunci cadangan 2FA baru berhasil diterbitkan.'))->with('open_2fa_modal', true);
    }

    public function updatePreferensi(Request $request)
    {
        $validated = $request->validate([
            'bahasa' => 'nullable|string|in:id,en',
            'zona_waktu' => 'nullable|string|in:WIB,WITA,WIT,UTC',
            'per_page' => 'nullable|integer|in:5,10,25,50',
            'format_tanggal' => 'nullable|string|in:DD/MM/YYYY,YYYY-MM-DD,DD MMMM YYYY',
        ]);

        $filePath = storage_path('app/preferensi.json');
        $current = TotpService::getPreferences();

        foreach ($validated as $key => $val) {
            if ($val !== null && $val !== '') {
                $current[$key] = $key === 'per_page' ? (int) $val : $val;
            }
        }

        if (!File::isDirectory(storage_path('app'))) {
            File::makeDirectory(storage_path('app'), 0755, true);
        }

        File::put($filePath, json_encode($current, JSON_PRETTY_PRINT));

        if (!empty($validated['bahasa'])) {
            session(['locale' => $validated['bahasa']]);
        }
        session(['preferensi' => $current]);

        \App\Models\Aktivitas::log('Ubah Preferensi', 'Preferensi tampilan dan format sistem berhasil diperbarui');

        return redirect()->back()->with('success', __('Preferensi tampilan berhasil diperbarui!'));
    }

    public function toggle2fa(Request $request)
    {
        $action = $request->input('action', 'enable');

        if ($action === 'disable') {
            TotpService::set2faEnabled(false);
            \App\Models\Aktivitas::log('Keamanan 2FA', 'Autentikasi dua faktor (2FA) Google Authenticator dinonaktifkan');
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'enabled' => false,
                    'message' => __('2FA Google Authenticator berhasil dinonaktifkan.'),
                ]);
            }
            return redirect()->back()->with('success', __('2FA Google Authenticator berhasil dinonaktifkan.'));
        }

        $code = $request->input('otp_code');
        if (empty($code) || strlen($code) !== 6 || !ctype_digit($code)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Masukkan 6 digit angka dari aplikasi Google Authenticator.'),
                ], 422);
            }
            return redirect()->back()
                ->with('error_2fa', __('Masukkan 6 digit angka dari aplikasi Google Authenticator.'))
                ->with('open_2fa_modal', true);
        }

        if (!TotpService::verifyCode($code)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Kode OTP salah atau sudah kedaluwarsa. Silakan periksa kembali aplikasi Google Authenticator Anda.'),
                ], 422);
            }
            return redirect()->back()
                ->with('error_2fa', __('Kode OTP salah atau sudah kedaluwarsa. Silakan periksa kembali aplikasi Google Authenticator Anda.'))
                ->with('open_2fa_modal', true);
        }

        TotpService::set2faEnabled(true);
        \App\Models\Aktivitas::log('Keamanan 2FA', 'Autentikasi dua faktor (2FA) Google Authenticator berhasil diaktifkan');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'enabled' => true,
                'message' => __('2FA Google Authenticator berhasil diaktifkan dan terhubung dengan akun Anda.'),
            ]);
        }

        return redirect()->back()->with('success', __('2FA Google Authenticator berhasil diaktifkan dan terhubung dengan akun Anda.'));
    }

    /**
     * Create a new database backup.
     */
    public function createBackup(Request $request)
    {
        try {
            $result = BackupService::createBackup();
            \App\Models\Aktivitas::log('Backup Database', "Backup database baru berhasil dibuat: {$result['filename']}");

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'filename' => $result['filename'],
                    'download_url' => route('pengaturan.backup.download', ['filename' => $result['filename']]),
                    'last_backup' => BackupService::getLastBackupInfo(),
                    'db_size' => BackupService::getDatabaseSize(),
                    'backup_files' => BackupService::getBackupFiles(),
                ]);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat backup: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Download a specific backup file.
     */
    public function downloadBackup(string $filename)
    {
        $filename = basename($filename);
        $path = BackupService::getBackupDirectory() . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($path) || !is_file($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    /**
     * Restore database from an existing file or uploaded file.
     */
    public function restoreBackup(Request $request)
    {
        try {
            $filePath = null;

            if ($request->hasFile('backup_file')) {
                $request->validate([
                    'backup_file' => 'required|file|max:51200', // max 50MB
                ]);
                $file = $request->file('backup_file');
                $extension = strtolower($file->getClientOriginalExtension());
                if ($extension !== 'sql') {
                    throw new \InvalidArgumentException('Format file harus berupa file .sql');
                }

                $savedFilename = 'uploaded_' . date('Ymd_His') . '_' . basename($file->getClientOriginalName());
                $destDir = BackupService::getBackupDirectory();
                $filePath = $destDir . DIRECTORY_SEPARATOR . $savedFilename;
                $file->move($destDir, $savedFilename);
            } elseif ($request->filled('filename')) {
                $filename = basename($request->input('filename'));
                $filePath = BackupService::getBackupDirectory() . DIRECTORY_SEPARATOR . $filename;
            } else {
                throw new \InvalidArgumentException('Silakan pilih file backup yang ada di server atau unggah file .sql.');
            }

            $result = BackupService::restoreBackup($filePath);
            \App\Models\Aktivitas::log('Pemulihan Database', 'Database sistem berhasil dipulihkan dari arsip: ' . basename($filePath));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'db_size' => BackupService::getDatabaseSize(),
                ]);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memulihkan database: ' . $e->getMessage(),
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal memulihkan database: ' . $e->getMessage());
        }
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup(Request $request, string $filename)
    {
        $success = BackupService::deleteBackup($filename);
        if ($success) {
            \App\Models\Aktivitas::log('Hapus Backup', "File arsip backup '{$filename}' telah dihapus.");
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $success ? "File backup '{$filename}' berhasil dihapus." : 'File tidak ditemukan.',
                'backup_files' => BackupService::getBackupFiles(),
                'last_backup' => BackupService::getLastBackupInfo(),
            ]);
        }

        return redirect()->back()->with($success ? 'success' : 'error', $success ? "File backup '{$filename}' berhasil dihapus." : 'File tidak ditemukan.');
    }
}
