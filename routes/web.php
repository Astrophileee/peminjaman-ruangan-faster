<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListRoomsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome.welcome');
})->name('welcome');


Route::middleware(['role:staff'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::patch('/{user}', [UsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [UsersController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::post('/', [RoomController::class, 'store'])->name('store');
        Route::patch('/{room}', [RoomController::class, 'update'])->name('update');
        Route::delete('/{room}', [RoomController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('update');
    });

    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [OrderController::class, 'approval'])->name('index');
    });
});


Route::middleware(['role:mahasiswa'])->group(function () {
    Route::prefix('listRooms')->name('listRooms.')->group(function () {
        Route::get('/', [ListRoomsController::class, 'index'])->name('index');
        Route::get('/history', [ListRoomsController::class, 'history'])->name('history');
        Route::get('/{room}', [ListRoomsController::class, 'show'])->name('show');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/check-availability/{room}/{date}', [ListRoomsController::class, 'checkAvailability'])->name('checkAvailability');
    });
    Route::get('/mahasiswa/profile', [UsersController::class, 'edit'])->name('mahasiswa.profile.edit');
    Route::patch('/mahasiswa/profile', [UsersController::class, 'updateMahasiswa'])->name('mahasiswa.profile.update');
});
require __DIR__.'/auth.php';
