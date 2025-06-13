<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\FlareController;
use App\Http\Resources\UserResource;
use App\Models\User;

// Public test + dev
Route::get('/ping', fn() => response()->json(['message' => 'pong']));
Route::get('/users', fn() => UserResource::collection(User::all()));

// Public auth
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);


// Public known places
Route::get('/known-places/nearby', [FlareController::class, 'nearbyKnownPlaces']);
Route::get('/known-places', fn() => \App\Models\KnownPlace::select('id', 'name', 'lat', 'lon')->get());

// Authenticated flares
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/flares', [FlareController::class, 'index']);
    Route::post('/flares', [FlareController::class, 'store']);
    Route::get('/flares/{id}', [FlareController::class, 'show']);
    Route::delete('/flares/{id}', [FlareController::class, 'destroy']);
});
