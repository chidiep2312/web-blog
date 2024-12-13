<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\HomeController;
Route::get('/', [WebController::class, 'welcome'])->name('welcome');

// Đăng ký và đăng nhập
Route::get('/register', [WebController::class, 'registerFrm'])->name('register');
Route::get('/login', [WebController::class, 'loginFrm'])->name('login');
Route::get('/set-password', [HomeController::class, 'setPass']);
// Trang chính và các trang khác
Route::get('/home/{userId}', [HomeController::class, 'home'])->name('home');
Route::get('/friend/{userId}', [HomeController::class, 'friendList'])->name('friend');
Route::get('/chat/{senderId}/{receiverId}', [MessageController::class, 'chat'])->name('chat');
Route::get('/my-posts/{userId}', [HomeController::class, 'myPosts'])->name('my-posts');
Route::get('/edit-post/{id}', [PostController::class, 'edit'])->name('edit-post');

