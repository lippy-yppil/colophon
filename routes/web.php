<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Profil (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rak saya (minggu 2)
    Route::resource('books', BookController::class)->except(['show']);
    Route::patch('books/{book}/visibility', [BookController::class, 'toggleVisibility'])
        ->name('books.visibility');

    // Peminjaman (minggu 4)
    Route::post('/books/{book}/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');

    Route::patch('/loans/{loan}/cancel', [LoanController::class, 'cancel'])->name('loans.cancel');
    Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::patch('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::patch('/loans/{loan}/handover', [LoanController::class, 'handover'])->name('loans.handover');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
});

require __DIR__.'/auth.php';