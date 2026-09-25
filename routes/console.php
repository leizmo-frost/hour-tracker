<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Run daily at 6:00 PM
Artisan::command('hours:health', function () {
    $this->info('Employee Hours Tracking is ready.');
})->purpose('Verify the application is running.');
