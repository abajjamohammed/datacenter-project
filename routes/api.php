<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve']);

});