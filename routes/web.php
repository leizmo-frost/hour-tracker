<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\ClockInOutController;
use App\Http\Controllers\Employee\CorrectionController;
use App\Http\Controllers\Manager\ApprovalController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function()
{
    //Employee Routes
    Route::middleware(['role:employee'])->group(function()
    {
        Route::get('/clock', [ClockInOutController::class, 'index'])->name('clock');
        Route::get('/corrections', [CorrectionController::class, 'index'])->name('corrections');
    });

    //Manager Routes
    Route::middleware(['role:manager'])->group(function()
    {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals');
        Route::get('/team-corrections', [ApprovalController::class, 'corrections'])->name('team-corrections');
    });
});

require __DIR__.'/settings.php';
