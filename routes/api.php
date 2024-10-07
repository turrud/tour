<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\PostTagsController;
use App\Http\Controllers\Api\TagPostsController;
use App\Http\Controllers\Api\post_tagController;
use App\Http\Controllers\Api\UserPostsController;
use App\Http\Controllers\Api\PostImagesController;
use App\Http\Controllers\Api\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('users', UserController::class);

        // User Posts
        Route::get('/users/{user}/posts', [
            UserPostsController::class,
            'index',
        ])->name('users.posts.index');
        Route::post('/users/{user}/posts', [
            UserPostsController::class,
            'store',
        ])->name('users.posts.store');

        Route::apiResource('posts', PostController::class);

        // Post Images
        Route::get('/posts/{post}/images', [
            PostImagesController::class,
            'index',
        ])->name('posts.images.index');
        Route::post('/posts/{post}/images', [
            PostImagesController::class,
            'store',
        ])->name('posts.images.store');

        // Post Tags
        Route::get('/posts/{post}/tags', [
            PostTagsController::class,
            'index',
        ])->name('posts.tags.index');
        Route::post('/posts/{post}/tags/{tag}', [
            PostTagsController::class,
            'store',
        ])->name('posts.tags.store');
        Route::delete('/posts/{post}/tags/{tag}', [
            PostTagsController::class,
            'destroy',
        ])->name('posts.tags.destroy');

        Route::apiResource('images', ImageController::class);

        Route::apiResource('tags', TagController::class);

        // Tag Posts
        Route::get('/tags/{tag}/posts', [
            TagPostsController::class,
            'index',
        ])->name('tags.posts.index');
        Route::post('/tags/{tag}/posts/{post}', [
            TagPostsController::class,
            'store',
        ])->name('tags.posts.store');
        Route::delete('/tags/{tag}/posts/{post}', [
            TagPostsController::class,
            'destroy',
        ])->name('tags.posts.destroy');
    });
