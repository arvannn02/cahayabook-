<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command('backup:run')
    ->dailyAt('02:00')
    ->timezone('Asia/Jakarta')
    ->appendOutputTo(storage_path('logs/backup.log'));

