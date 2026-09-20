<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\AttendanceService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Run daily at 6:00 PM
Schedule::call(function () {
    app(AttendanceService::class)->checkForMissedPunches();
})->dailyAt('18:00')->withoutOverlapping();
