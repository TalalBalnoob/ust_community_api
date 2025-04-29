<?php

use App\Http\Controllers\v1\ActivityController;
use App\Http\Controllers\v1\AdminController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\BookmarkController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\FollowController;
use App\Http\Controllers\v1\LikeController;
use App\Http\Controllers\v1\PostController;
use App\Http\Controllers\v1\ProfileController;
use App\Http\Controllers\v1\SearchController;
use App\Http\Middleware\IsAdminUser;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Route;

Route::get(
    '/test',
    function () {
        return response('noting to test');
    }
);

Route::get('/token', [AuthController::class, 'checkToken'])
    ->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::group(
    ['prefix' => 'user/profile', 'middleware' => 'auth:sanctum'],
    function () {
        Route::get('/', [ProfileController::class, 'getUserProfile']);
        Route::post('/', [ProfileController::class, 'editUserProfile']);
        Route::get('/{user_id}', [ProfileController::class, 'getUserProfile']);
        Route::get('/{user_id}/followers', [ProfileController::class, 'followers']);
        Route::get('/{user_id}/followings', [ProfileController::class, 'followings']);
    }
);


Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::apiResource('posts.comments', CommentController::class)
    ->except('show')->middleware('auth:sanctum');

Route::put('/like/{post_id}', [LikeController::class, 'like'])
    ->middleware('auth:sanctum');
Route::delete('/unlike/{post_id}', [LikeController::class, 'unlike'])
    ->middleware('auth:sanctum');


Route::put('/bookmark/{post_id}', [BookmarkController::class, 'book'])
    ->middleware('auth:sanctum');
Route::delete('/bookmark/{post_id}', [BookmarkController::class, 'unbook'])
    ->middleware('auth:sanctum');
Route::get('/bookmarks', [BookmarkController::class, 'getUserBookmarks'])
    ->middleware('auth:sanctum');

Route::put('/follow/{followed_id}', [FollowController::class, 'follow'])
    ->middleware('auth:sanctum');
Route::delete('/follow/{followed_id}', [FollowController::class, 'unfollow'])
    ->middleware('auth:sanctum');

Route::get('/search', [SearchController::class, 'search'])
    ->middleware('auth:sanctum');

Route::get('/activity', [ActivityController::class, 'getAllUserActivitis'])->middleware('auth:sanctum');
Route::get('/unreadActivity', [ActivityController::class, 'getUnreadUserActivitis'])->middleware('auth:sanctum');
Route::post('/readActivity', [ActivityController::class, 'readActivity'])->middleware('auth:sanctum');

// ############################# Admin Routes #############################
Route::group(
    ['prefix' => 'admin', 'middleware' => ['auth:sanctum', IsAdminUser::class]],
    function () {
        Route::get('users', [AdminController::class, 'getUserList']);
        Route::get('user/{user_id}', [AdminController::class, 'showUser']);
        Route::put('user/{user_id}', [AdminController::class, 'updateUser']);
        Route::delete('users/{user_id}', [AdminController::class, 'deleteUser']);

        Route::get('search/users', [AdminController::class, 'searchUsers']);

        Route::post('register/student', [AuthController::class, 'register_student']);
        Route::post('register/staff', [AuthController::class, 'register_staff']);
    }
);
