<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:api']);

if (env('APP_ENV') == 'local') {
    Route::get('/auth/google/redirect', [App\Http\Controllers\GoogleController::class, 'fakeRedirect']);
    Route::get('/auth/google/callback', [App\Http\Controllers\GoogleController::class, 'fakeCallback'])->name('fakeCallback');
    Route::get('login', [App\Http\Controllers\GoogleController::class, 'login'])->name('login');
} else {
    Route::get('/auth/google/redirect', [App\Http\Controllers\GoogleController::class, 'redirect']);
    Route::get('/auth/google/callback', [App\Http\Controllers\GoogleController::class, 'callback']);
}


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [App\Http\Controllers\GoogleController::class, 'logout']);
    Route::apiResource('projects', App\Http\Controllers\ProjectController::class);
    Route::apiResource('posts', App\Http\Controllers\PostController::class);
    Route::apiResource('tags', App\Http\Controllers\TagController::class);
});


Route::get('p/{slug}', [App\Http\Controllers\ProjectController::class, 'showPublic'])->name('projects.public');
