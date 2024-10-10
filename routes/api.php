<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');


Route::post('/register/student', [AuthController::class, 'register_student']);

Route::post('/register/staff', [AuthController::class, 'register_staff']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::apiResource('posts', PostController::class);
