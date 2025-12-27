<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Database Backup Schedule - runs daily at 2 AM
Schedule::command('db:backup --keep=7')
    ->daily()
    ->at('02:00')
    ->onSuccess(function () {
        \Log::info('✅ Database backup completed successfully');
    })
    ->onFailure(function () {
        \Log::error('❌ Database backup failed');
    });
