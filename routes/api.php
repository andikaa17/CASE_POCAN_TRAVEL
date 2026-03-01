<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;


Route::get('/ping', function() {
    return response()->json(['success' => true, 'message' => 'API JALAN!']);
});

// ===== ROUTES VERIFIKASI EMAIL (TANPA API KEY) =====
// Ini harus bisa diakses publik tanpa API Key karena link dikirim via email
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify')
    ->withoutMiddleware(['api.key']); // <-- PENTING: BEBAS DARI API KEY!

// ===== RESEND VERIFIKASI (PUBLIC) =====
Route::post('/email/resend', [VerificationController::class, 'resendPublic'])
    ->middleware(['api.key', 'throttle:10,60']); // <-- PAKAI API KEY

// ========== PUBLIC ROUTES (GA BUTUH LOGIN) ==========
Route::middleware(['api.key'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/buses', [BusController::class, 'index']);
});

// ========== ROUTES YANG BUTUH LOGIN (TAPI BELUM VERIFIED) ==========
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Profile routes (bisa diakses walau belum verifikasi)
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    
    // ===== VERIFICATION ROUTES (untuk user yang sudah login) =====
    Route::post('/email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])
        ->middleware('throttle:10,60');
    Route::get('/email/verify-status', [VerificationController::class, 'checkVerificationStatus']);
});

// ========== ROUTES YANG BUTUH LOGIN + VERIFIED ==========
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/bookings', [BookingController::class, 'bookTicket']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking']);
    
    // Routes Payment
    Route::post('/payments/{booking_id}/process', [PaymentController::class, 'process']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::get('/payments/check/{booking_id}', [PaymentController::class, 'checkStatus']);
    Route::get('/payments/history', [PaymentController::class, 'history']);
    Route::post('/payments/{id}/refund', [PaymentController::class, 'refund']);
    Route::get('/payments/stats', [PaymentController::class, 'statistics']);

    // Routes invoice
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'generate']);
    Route::get('/invoice/{id}/preview', [InvoiceController::class, 'preview']);
    
    // route validasi 
    Route::get('/check-availability', [ScheduleController::class, 'checkAvailability']);
    
    // QRIS Payment Routes
    Route::post('/payments/{booking_id}/qris', [PaymentController::class, 'processQris']);
    Route::post('/payments/{booking_id}/simulate-success', [PaymentController::class, 'simulatePaymentSuccess']);
    Route::post('/payments/{booking_id}/regenerate-qris', [PaymentController::class, 'regenerateQris']);
});