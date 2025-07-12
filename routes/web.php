<?php

use App\Http\Controllers\MemoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('menus', MenuController::class);
    Route::post('menus/{menu}/favorite', [MenuController::class, 'favorite'])->name('menus.favorite');

    Route::post('menus/{menu}/memos', [MemoController::class, 'store'])->name('memos.store');
    Route::patch('menus/{menu}/memos/{memo}', [MemoController::class, 'update'])->name('memos.update');
    Route::delete('menus/{menu}/memos/{memo}', [MemoController::class, 'destroy'])->name('memos.destroy');
});

require __DIR__.'/auth.php';
