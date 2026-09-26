<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/posts');

Route::resource('posts', PostController::class);

Route::resource('events', EventController::class)->only(['index', 'show']);

Route::get('events/{event}/reservations/create', [ReservationController::class, 'create'])
    ->name('reservations.create');
Route::post('events/{event}/reservations', [ReservationController::class, 'store'])
    ->name('reservations.store');
Route::get('reservations', [ReservationController::class, 'index'])
    ->name('reservations.index');
Route::patch('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
    ->name('reservations.cancel');
