<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Promo;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Promo::synchronizeStatuses();
})->everyMinute();

// Nonaktifkan slot yang tanggalnya sudah lewat setiap hari.
// Pakai command (sinkron) alih-alih job (async via queue) agar tidak
// tergantung queue worker yang mungkin tidak jalan di shared hosting.
Schedule::command('slots:update-expired')->dailyAt('00:05');
