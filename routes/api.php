<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\FollowController;
use App\Http\Controllers\v1\LikeController;
use App\Http\Controllers\v1\PostController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\SearchController;
use App\Http\Middleware\IsAdminUser;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
	return response('noting to test');
});

Route::group(['prefix' => 'register', 'middleware' => ['auth:sanctum', IsAdminUser::class]], function () {
	Route::post('/student', [AuthController::class, 'register_student']);
	Route::post('/staff', [AuthController::class, 'register_staff']);
	Route::delete('/{user_id}', [AuthController::class, 'delete_user']);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'user/profile', 'middleware' => 'auth:sanctum'], function () {
	Route::get('/', [ProfileController::class, 'getCurrentUserProfile']);
	Route::get('/{user_id}', [ProfileController::class, 'getUserProfile']);
	Route::get('/{user_id}/followers', [ProfileController::class, 'followers']);
	Route::get('/{user_id}/followings', [ProfileController::class, 'followings']);
});


Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::apiResource('posts.comments', CommentController::class)->except('show')->middleware('auth:sanctum');

Route::put('/like/{post_id}', [LikeController::class, 'like'])->middleware('auth:sanctum');
Route::delete('/unlike/{post_id}', [LikeController::class, 'unlike'])->middleware('auth:sanctum');

Route::put('/follow/{followed_id}', [FollowController::class, 'follow'])->middleware('auth:sanctum');
Route::delete('/follow/{followed_id}', [FollowController::class, 'unfollow'])->middleware('auth:sanctum');

Route::get('/search', [SearchController::class, 'search'])->middleware('auth:sanctum');
