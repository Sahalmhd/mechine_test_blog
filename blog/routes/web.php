<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAuth;




// Authentication routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('showlogin');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




// Resource route
Route::resource('posts', PostController::class)->middleware(CheckAuth::class);


// ONLY BECAUSE ERORR WITH RESOURCES UPDATE

Route::POST('posts/{id}/update', [PostController::class, 'update'])->name('posts.update');


//search route
Route::get('/posts/search', [PostController::class, 'search']);