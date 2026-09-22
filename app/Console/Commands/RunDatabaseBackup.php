<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RunDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:run {--name= : Optional custom filename for backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Jalankan proses backup database Cahaya Buku secara manual atau terjadwal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pencadangan database Cahaya Buku...');

        try {
            $customName = $this->option('name');
            $result = BackupService::createBackup($customName);

            $this->info('✓ ' . $result['message']);
            $this->line('  File: ' . $result['path']);
            $this->line('  Ukuran: ' . $result['size_formatted']);

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Gagal mencadangkan database: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
