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
// Trang chính 
Route::get('/home/{userId}', [HomeController::class, 'home'])->name('home');
//trang nguoi ban theo doi
Route::get('/friend/{userId}', [HomeController::class, 'friendList'])->name('friend');
//man hinh gui tin nhan
Route::get('/chat/{senderId}/{receiverId}', [MessageController::class, 'chat'])->name('chat');
//danh sach post nguoi dung
Route::get('/my-posts/{userId}', [HomeController::class, 'myPosts'])->name('my-posts');
//man hinh chinh sua bai dang
Route::get('/edit-post/{id}', [PostController::class, 'edit'])->name('edit-post');

