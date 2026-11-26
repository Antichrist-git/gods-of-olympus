<?php

use App\Http\Controllers\ArchivedGodController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\GodController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/gods');
// Проверка уникальности имени прямо во время заполнения формы.
Route::get('gods/check-name', [GodController::class, 'checkName'])
    ->name('gods.check-name')
    ->middleware('throttle:60,1');
Route::resource('gods', GodController::class)
    ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'auth')
    ->middlewareFor(['edit', 'update', 'destroy'], 'can:manage-god,god');

Route::middleware('auth')->group(function () {
    Route::post('gods/{god}/comments', [CommentController::class, 'store'])->name('gods.comments.store');
    Route::post('users/{user:username}/friendship', [FriendshipController::class, 'store'])->name('friends.store');
    Route::delete('users/{user:username}/friendship', [FriendshipController::class, 'destroy'])->name('friends.destroy');
    Route::get('users', [CollectionController::class, 'index'])->name('collections.index');
    Route::middleware('can:manage-archive')->prefix('archive')->name('archive.')->group(function () {
        Route::get('/', [ArchivedGodController::class, 'index'])->name('index');
        Route::patch('{id}', [ArchivedGodController::class, 'restore'])->whereNumber('id')->name('restore');
        Route::delete('{id}', [ArchivedGodController::class, 'destroy'])->whereNumber('id')->name('destroy');
    });

    Route::get('users/{id}/gods', [CollectionController::class, 'legacy'])->whereNumber('id');
    Route::get('users/{user:username}/gods', [CollectionController::class, 'show'])->name('collections.show');
});

require __DIR__.'/auth.php';