<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PertunjukanController as AdminPertunjukanController;
use App\Http\Controllers\Admin\SenimanController as AdminSenimanController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('pertunjukan', AdminPertunjukanController::class);
    Route::resource('seniman', AdminSenimanController::class);
    Route::resource('berita', AdminBeritaController::class);
    
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [AdminBookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('booking.updateStatus');
    Route::patch('/booking/{booking}/payment', [AdminBookingController::class, 'updatePayment'])->name('booking.update-payment');
    Route::delete('/booking/{booking}', [AdminBookingController::class, 'destroy'])->name('booking.destroy');
    Route::post('/booking/bulk-update-payment', [AdminBookingController::class, 'bulkUpdatePayment'])->name('booking.bulk-update-payment');
    Route::post('/booking/bulk-delete', [AdminBookingController::class, 'bulkDelete'])->name('booking.bulk-delete');
    
    Route::get('/transaction', [AdminTransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/{transaction}', [AdminTransactionController::class, 'show'])->name('transaction.show');
    Route::patch('/transaction/{transaction}/status', [AdminTransactionController::class, 'updateStatus'])->name('transaction.updateStatus');
    
    // Category-specific routes
    Route::get('/kategori/{kategori}', [\App\Http\Controllers\Admin\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/{kategori}/create', [\App\Http\Controllers\Admin\KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/{kategori}', [\App\Http\Controllers\Admin\KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/{id}/edit', [\App\Http\Controllers\Admin\KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}/{id}', [\App\Http\Controllers\Admin\KategoriController::class, 'destroy'])->name('kategori.destroy');
    
    // Talent Management
    Route::resource('talent', \App\Http\Controllers\Admin\TalentController::class);
    
    // Talent Booking Management
    Route::prefix('talent-booking')->name('talent-booking.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TalentBookingController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\TalentBookingController::class, 'show'])->name('show');
        Route::post('/{id}/update-status', [\App\Http\Controllers\Admin\TalentBookingController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\TalentBookingController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
