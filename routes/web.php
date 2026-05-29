<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('schedule.index');
});

// паблик
Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
Route::get('/tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

// новости
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'publicIndex'])->name('public.news.index');
Route::get('/news/{news}', [\App\Http\Controllers\NewsController::class, 'publicShow'])->name('public.news.show');

// учетная запись
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// выход
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// лк клиента
Route::middleware(['auth', 'role:User'])->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/my-bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Админка
Route::middleware(['auth', 'role:Administrator,ContentManager'])->prefix('admin')->name('admin.')->group(function () {
    
    // админ + контент-менеджер
    Route::resource('news', \App\Http\Controllers\NewsController::class);
    Route::resource('promotions', \App\Http\Controllers\PromotionController::class);
    Route::resource('tracks', \App\Http\Controllers\AdminTrackController::class);
    
    // онли адмик
    Route::middleware(['role:Administrator'])->group(function () {
        Route::resource('karts', \App\Http\Controllers\KartController::class);
        Route::resource('slots', \App\Http\Controllers\SlotController::class)->only(['index', 'edit', 'update']);
        Route::get('bookings', [\App\Http\Controllers\BookingAdminController::class, 'index'])->name('bookings.index');
        Route::patch('bookings/{booking}/confirm', [\App\Http\Controllers\BookingAdminController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\BookingAdminController::class, 'cancel'])->name('bookings.cancel');
        Route::get('bookings/create', [\App\Http\Controllers\BookingAdminController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [\App\Http\Controllers\BookingAdminController::class, 'store'])->name('bookings.store');
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    });
});