<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\GodController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('comments', [CommentController::class, 'index']);
    Route::get('comments/{id}', [CommentController::class, 'show'])->whereNumber('id');
    Route::get('gods', [GodController::class, 'index']);
    Route::get('gods/{id}', [GodController::class, 'show'])->whereNumber('id');
});
