<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\FollowController;
use App\Http\Controllers\v1\LikeController;
use App\Http\Controllers\v1\PostController;
use Illuminate\Support\Facades\Route;


// TODO: add admin level tokens for use the register auth routes
Route::post('/register/student', [AuthController::class, 'register_student']);
Route::post('/register/staff', [AuthController::class, 'register_staff']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/user/profile', [ProfileController::class, 'getCurrentUserProfile'])->middleware('auth:sanctum');
Route::get('/user/profile/{user_id}', [ProfileController::class, 'getUserProfile'])->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::apiResource('posts.comments', CommentController::class)->except('show')->middleware('auth:sanctum');

Route::put('/like/{post_id}', [LikeController::class, 'like'])->middleware('auth:sanctum');
Route::delete('/unlike/{post_id}', [LikeController::class, 'unlike'])->middleware('auth:sanctum');

Route::put('/follow/{followed_id}', [FollowController::class, 'follow'])->middleware('auth:sanctum');
Route::delete('/follow/{followed_id}', [FollowController::class, 'unfollow'])->middleware('auth:sanctum');

Route::get('/search', [SearchController::class, 'search'])->middleware('auth:sanctum');
