<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\FlareController;
use App\Http\Resources\UserResource;
use App\Models\User;

Route::get('/users', function () {
    return UserResource::collection(User::all());
});

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});


Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/flares', [FlareController::class, 'index']); // Get all flares
    Route::post('/flares', [FlareController::class, 'store']); // Create a new flare
    Route::get('/flares/{id}', [FlareController::class, 'show']); // Get a single flare
    Route::delete('/flares/{id}', [FlareController::class, 'destroy']); // Delete a flare
});



