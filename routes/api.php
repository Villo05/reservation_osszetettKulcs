<?php
use App\Http\Controllers\TravelController;
use Illuminate\Support\Facades\Route;
Route::get('/travel/{user_id}/{flight_id}', [TravelController::class, 'show'])->whereNumber(['user_id', 'flight_id']);
Route::delete('/travel/{user_id}/{flight_id}', [TravelController::class, 'destroy'])->whereNumber(['user_id', 'flight_id']);
