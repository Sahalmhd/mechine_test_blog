<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

// Login Route
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('posts', PostController::class);
