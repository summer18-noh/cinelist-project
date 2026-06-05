<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');

// Protected routes
Route::middleware('auth.custom')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Movies CRUD
    Route::resource('movies', MovieController::class);

    // Users CRUD
    Route::resource('users', UserController::class);

    // Profile
    Route::get('/profile',        [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/reset-admin', function() {
    \App\Models\User::where('email', 'admin@gmail.com')
        ->update(['password' => \Illuminate\Support\Facades\Hash::make('password')]);
    return 'Done! Admin password reset to: password';
});