<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// pengunjung boleh membuat booking tanpa login
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// halaman admin
Route::middleware('auth')->prefix('bookings')->name('bookings.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/{booking}/edit', [BookingController::class, 'edit'])->name('edit');
    Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
    Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
});
