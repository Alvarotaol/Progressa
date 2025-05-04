<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['api']);


Route::get('/auth/google/redirect', [App\Http\Controllers\GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleController::class, 'callback']);

Route::group(['middleware' => ['api']], function () {
    Route::apiResource('projects', App\Http\Controllers\ProjectController::class);
    //
});
