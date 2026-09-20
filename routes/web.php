<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Employee\ClockInOut;
use App\Livewire\Employee\RequestCorrection;
use App\Livewire\Manager\TimesheetApprovals;
use App\Livewire\Manager\ReviewCorrections;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function()
{
    Route::get('/dashboard', function () {
        return view('dashboard'); //your main layout
    })->name('dashboard');

    //Employee Routes
    Route::middleware(['role:employee'])->group(function()
    {
        Route::get('/clock', ClockInOut::class)->name('clock');
        Route::get('/corrections', RequestCorrection::class)->name('corrections');
    });

    //Manager Routes
    Route::middleware(['role:manager'])->group(function()
    {
        Route::get('/approvals', TimesheetApprovals::class)->name('approvals');
        Route::get('/team-corrections', ReviewCorrections::class)->name('team-corrections');
    });
});

require __DIR__.'/settings.php';
