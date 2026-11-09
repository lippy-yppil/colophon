<?php
use App\Http\Controllers\ShelfController;

Route::get('/shelf', [ShelfController::class, 'index'])->name('shelf.index');
Route::get('/shelf/{user}', [ShelfController::class, 'userShelf'])->name('shelf.user');

use App\Http\Controllers\SearchController;
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('shelf.index');
    }
    $featuredBooks = \App\Models\Book::where('status', 'available')
        ->with('genres')
        ->take(10)
        ->get();

    return view('welcome', compact('featuredBooks'));
});