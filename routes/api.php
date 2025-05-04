<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['api', 'auth:sanctum']);


Route::get('/auth/google/redirect', [App\Http\Controllers\GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleController::class, 'callback']);

Route::group(['middleware' => ['api', 'auth:sanctum']], function () {
    Route::apiResource('projects', App\Http\Controllers\ProjectController::class);
    //
});
