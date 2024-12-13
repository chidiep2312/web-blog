<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MessageController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Các route cần authentication
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/save-post', [PostController::class, 'save']);
    Route::post('/update-post/{id}', [PostController::class, 'update']);
    Route::post('/like-post/{id}', [PostController::class, 'likePost'])->name('like-post');
    Route::delete('/delete-post/{id}', [PostController::class, 'deletePost'])->name('delete-post');
    Route::post('/follow-user', [UserController::class, 'follow']);
    Route::post('/follow-back/{id}', [HomeController::class, 'followBack']);
    Route::post('/update-avatar/{id}', [UserController::class, 'updateAvatar']);
    Route::post('/get-tag-posts/{id}', [PostController::class, 'getTagPost']);
    Route::post('/create-comment/{userId}/{postId}',[PostController::class,'createComment']);
    Route::post('/change-password/{userId}',[AuthController::class,'changePass']);
    Route::get('/load-avatar/{id}', [HomeController::class, 'homeLoad']);
   
});

Route::get('/follow-notifications/{id}',[HomeController::class,'followNotification']);
Route::post('/send-message/{senderId}/{receiverId}',[MessageController::class,'sendMess']);
// Tìm kiếm người dùng
Route::get('/search-user-page/{id}/{authId}', [UserController::class, 'searchUser'])->name('search');
//post
Route::get('/personal-tag-post/{id}', [PostController::class, 'getTagPost']);
// Các route khác
Route::get('/create-post', [PostController::class, 'createPost'])->name('create-post');
Route::get('/personal-page/{userId}', [UserController::class, 'show'])->name('personal');
Route::get('/friend-page/{authUser}/{userId}', [UserController::class, 'showFriendPage'])->name('friend-page');
Route::get('/detail-post/{id}', [PostController::class, 'detail'])->name('post-detail');


// Route tìm kiếm người dùng
Route::post('/search-user', [UserController::class, 'search'])->name('search-user');
