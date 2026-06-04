<?php

use App\Http\Controllers\AdminTrackController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingAdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KartController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AdminSlotApiController;

use Illuminate\Support\Facades\Route;

Route::get('/', [ScheduleController::class, 'welcome'])->name('welcome');

// паблик
Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
Route::get('/tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

// новости
Route::get('/news', [NewsController::class, 'publicIndex'])->name('public.news.index');
Route::get('/news/{news}', [NewsController::class, 'publicShow'])->name('public.news.show');

// акции
Route::get('/promotions', [PromotionController::class, 'publicIndex'])->name('public.promotions.index');
Route::get('/promotions/{promotion}', [PromotionController::class, 'publicShow'])->name('public.promotions.show');

// учетная запись
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// выход
Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

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
    Route::resource('news', NewsController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('tracks', AdminTrackController::class)->except('destroy');

    // онли адмик
    Route::middleware(['role:Administrator'])->group(function () {

        Route::get('slots/generate', [SlotController::class, 'createGenerate'])->name('slots.generate');
        Route::post('slots/generate', [SlotController::class, 'storeGenerate'])->name('slots.generate.store');
        Route::resource('slots', SlotController::class)->only(['index', 'edit', 'update']);

        Route::resource('karts', KartController::class);

        Route::get('bookings', [BookingAdminController::class, 'index'])->name('bookings.index');
        Route::get('bookings/create', [BookingAdminController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [BookingAdminController::class, 'store'])->name('bookings.store');
        Route::patch('bookings/{booking}/confirm', [BookingAdminController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('bookings/{booking}/cancel', [BookingAdminController::class, 'cancel'])->name('bookings.cancel');

        Route::get('users', [UserController::class, 'index'])->name('users.index');

        Route::prefix('api')->name('api.')->group(function () {
            Route::get('tracks-by-date', [AdminSlotApiController::class, 'tracksByDate'])->name('tracks-by-date');
            Route::get('slots-by-track', [AdminSlotApiController::class, 'slotsByTrack'])->name('slots-by-track');
            Route::get('slot-details/{slot}', [AdminSlotApiController::class, 'slotDetails'])->name('slot-details');
        });
    });
});