<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PertunjukanController;
use App\Http\Controllers\Api\SenimanController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\Admin\PertunjukanController as AdminPertunjukanController;
use App\Http\Controllers\Api\Admin\SenimanController as AdminSenimanController;
use App\Http\Controllers\Api\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Api\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Api\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes WITHOUT prefix - for frontend compatibility
Route::get('/pertunjukan', [PertunjukanController::class, 'index']);
Route::get('/pertunjukan/{id}', [PertunjukanController::class, 'show']);

Route::get('/seniman', [SenimanController::class, 'index']);
Route::get('/seniman/{id}', [SenimanController::class, 'show']);

Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);

// Talents routes (alias for seniman with talent-specific logic)
Route::get('/talents', [SenimanController::class, 'index']);
Route::get('/talents/{id}', [SenimanController::class, 'show']);

// Talent bookings
Route::post('/talent-bookings', [BookingController::class, 'store']);

// Event ticket orders  
Route::post('/event-ticket-orders', [BookingController::class, 'store']);

// Wishlist routes (require auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy']);
    Route::get('/wishlists/check', [WishlistController::class, 'check']);
});

// Public routes WITH v1 prefix - for versioned API
Route::prefix('v1')->group(function () {
    // Pertunjukan routes
    Route::get('/pertunjukans', [PertunjukanController::class, 'index']);
    Route::get('/pertunjukans/{id}', [PertunjukanController::class, 'show']);
    
    // Seniman routes
    Route::get('/senimans', [SenimanController::class, 'index']);
    Route::get('/senimans/{id}', [SenimanController::class, 'show']);
    
    // Berita routes
    Route::get('/beritas', [BeritaController::class, 'index']);
    Route::get('/beritas/{id}', [BeritaController::class, 'show']);
});

// Protected routes - authentication required
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Booking routes
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    
    // Transaction routes
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    
    // Wishlist routes
    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy']);
});

// Admin routes - authentication + admin role required
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Pertunjukan management
    Route::apiResource('pertunjukans', AdminPertunjukanController::class);
    
    // Seniman management
    Route::apiResource('senimans', AdminSenimanController::class);
    
    // Berita management
    Route::apiResource('beritas', AdminBeritaController::class);
    
    // Booking management
    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show']);
    Route::patch('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus']);
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy']);
    
    // Transaction management
    Route::get('/transactions', [AdminTransactionController::class, 'index']);
    Route::get('/transactions/{id}', [AdminTransactionController::class, 'show']);
    Route::patch('/transactions/{id}/status', [AdminTransactionController::class, 'updateStatus']);
});
