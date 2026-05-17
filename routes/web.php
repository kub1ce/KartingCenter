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

Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
Route::get('/tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

Route::view('/admin/bookings', 'stub')->name('admin.bookings.index');
Route::view('/content/news', 'stub')->name('content.news.index');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});


Route::middleware(['auth', 'role:User'])->group(function () {
    Route::get('/my-bookings', function () {
        return view('stub', ['title' => 'Мои бронирования']);
    })->name('bookings.index');

    Route::get('/bookings/create', function () {
        return view('stub', ['title' => 'Новое бронирование']);
    })->name('bookings.create');

    Route::post('/bookings', function () {
        return redirect()->route('bookings.index');
    })->name('bookings.store');

    Route::get('/my-bookings/{booking}', function ($booking) {
        return view('stub', ['title' => 'Детали бронирования #' . $booking]);
    })->name('bookings.show');

    Route::patch('/my-bookings/{booking}/cancel', function ($booking) {
        return redirect()->route('bookings.index');
    })->name('bookings.cancel');
});


Route::middleware(['auth', 'role:Administrator'])->group(function () {
    Route::get('/admin/bookings', function () {
        return view('stub', ['title' => 'Все бронирования (Администратор)']);
    })->name('admin.bookings.index');

    Route::get('/admin/users', function () {
        return view('stub', ['title' => 'Управление пользователями']);
    })->name('admin.users.index');

    Route::get('/admin/slots', function () {
        return view('stub', ['title' => 'Управление слотами']);
    })->name('admin.slots.index');

    Route::get('/admin/karts', function () {
        return view('stub', ['title' => 'Управление картами']);
    })->name('admin.karts.index');
});


Route::middleware(['auth', 'role:Administrator,ContentManager'])->group(function () {
    Route::get('/content/news', function () {
        return view('stub', ['title' => 'Управление новостями']);
    })->name('content.news.index');

    Route::get('/content/promotions', function () {
        return view('stub', ['title' => 'Управление акциями']);
    })->name('content.promotions.index');

    Route::get('/content/tracks', function () {
        return view('stub', ['title' => 'Редактирование трасс']);
    })->name('content.tracks.index');
});


Route::middleware(['auth', 'role:User'])->group(function () {

    Route::get('/my-bookings', [BookingController::class, 'index'])
        ->name('bookings.index');

    Route::get('/bookings/create', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/my-bookings/{booking}', [BookingController::class, 'show'])
        ->name('bookings.show');

    Route::patch('/my-bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');
});


Route::middleware(['auth', 'role:Administrator'])->group(function () {
    Route::view('/admin/bookings', 'stub')->name('admin.bookings.index');
});

Route::middleware(['auth', 'role:Administrator,ContentManager'])->group(function () {
    Route::view('/content/news', 'stub')->name('content.news.index');
});
