<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\PertunjukanController;
use App\Http\Controllers\Api\ArtistGroupController;
use App\Http\Controllers\Api\TalentController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TalentBookingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\Admin\PertunjukanController as AdminPertunjukanController;
use App\Http\Controllers\Api\Admin\ArtistGroupController as AdminArtistGroupController;
use App\Http\Controllers\Api\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Api\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Api\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication routes (public)
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string|max:20',
        'location' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'location' => $validated['location'],
        'password' => bcrypt($validated['password']),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'user' => $user,
        'token' => $token,
    ], 201);
});

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($validated)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'user' => $user,
        'token' => $token,
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Logged out successfully',
    ]);
});


// Public routes WITHOUT prefix - for frontend compatibility
Route::get('/pertunjukan', [PertunjukanController::class, 'index']);
Route::get('/pertunjukan/{id}', [PertunjukanController::class, 'show']);

Route::get('/artist-groups', [ArtistGroupController::class, 'index']);
Route::get('/artist-groups/{id}', [ArtistGroupController::class, 'show']);

Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);

// Banners (public)
Route::get('/banners', [BannerController::class, 'index']);

// Talents routes (using Talent model with English field names)
Route::get('/talents', [TalentController::class, 'index']);
Route::get('/talents/{id}', [TalentController::class, 'show']);

// Talent bookings
Route::post('/talent-bookings', [TalentBookingController::class, 'store'])->middleware('auth:sanctum');
Route::get('/talent-bookings', [TalentBookingController::class, 'index'])->middleware('auth:sanctum');
Route::get('/talent-bookings/{id}', [TalentBookingController::class, 'show'])->middleware('auth:sanctum');

// Event ticket orders  
Route::post('/event-ticket-orders', [BookingController::class, 'store'])->middleware('auth:sanctum');
Route::get('/user/event-ticket-orders', [BookingController::class, 'index'])->middleware('auth:sanctum');
Route::get('/event-ticket-orders/{id}', [BookingController::class, 'show'])->middleware('auth:sanctum');

// Wishlist routes (require auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy']);
    Route::get('/wishlists/check', [WishlistController::class, 'check']);
});

// User profile routes (require auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserController::class, 'getProfile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
});

// Payment proof upload (require auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload-payment-proof', [PaymentController::class, 'uploadProof']);
    Route::get('/payment-proof/{orderType}/{orderId}', [PaymentController::class, 'getProof']);
});

// Forgot password (public)
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $status = Password::sendResetLink($request->only('email'));
    
    return $status === Password::RESET_LINK_SENT
        ? response()->json(['success' => true, 'message' => 'Reset link sent to your email'])
        : response()->json(['success' => false, 'message' => 'Unable to send reset link'], 400);
});

// Public routes WITH v1 prefix - for versioned API
Route::prefix('v1')->group(function () {
    // Pertunjukan routes
    Route::get('/pertunjukans', [PertunjukanController::class, 'index']);
    Route::get('/pertunjukans/{id}', [PertunjukanController::class, 'show']);
    
    // Artist Group routes
    Route::get('/artist-groups', [ArtistGroupController::class, 'index']);
    Route::get('/artist-groups/{id}', [ArtistGroupController::class, 'show']);
    
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
    
    // Artist Group management
    Route::apiResource('artist-groups', AdminArtistGroupController::class);
    
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
