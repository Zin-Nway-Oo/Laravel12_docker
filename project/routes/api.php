<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::apiResource('users', UserController::class);
