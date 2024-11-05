<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

 // route for get single post
 Route::get('/post/{id}', [PostController::class, 'show']);
 // route for get all posts
 Route::get('/posts', [PostController::class, 'index']);
 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

   
    // route for store post 

    Route::post('/post/store', [PostController::class, 'store']);

    // route for update post
    Route::patch('/post/update/{id}', [PostController::class, 'update']);

    // route for delete post
    Route::delete('/post/delete/{id}', [PostController::class, 'destroy']);
});

