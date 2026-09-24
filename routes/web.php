<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/clock', App\Livewire\Clock::class)->name('clock');
    Route::get('/timesheets', App\Livewire\Timesheets::class)->name('timesheets');
    Route::get('/leave', App\Livewire\LeaveRequests::class)->name('leave');
    Route::view('/system-design', 'system-design')->name('system-design');

    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/approvals', App\Livewire\Approvals::class)->name('approvals');
        Route::get('/employees', App\Livewire\Employees::class)->name('employees');
        Route::get('/payroll', App\Livewire\Payroll::class)->name('payroll');
        Route::get('/reports', App\Livewire\Reports::class)->name('reports');
        Route::get('/settings', App\Livewire\Settings::class)->name('settings');
    });
});

require __DIR__.'/settings.php';
