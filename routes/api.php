<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController; // TAMBAHKAN INI

Route::get('/ping', function() {
    return response()->json(['success' => true, 'message' => 'API JALAN!']);
});

// Public routes (pake API Key)
Route::middleware(['api.key'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/buses', [BusController::class, 'index']);
});

// Protected routes (Bearer Token)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/bookings', [BookingController::class, 'bookTicket']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking']);
    Route::get('/check-availability', [BookingController::class, 'checkAvailability']);
    Route::get('/bookings/{id}/payment-status', [BookingController::class, 'checkPaymentStatus']);
    
    // Routes Payment
    Route::post('/payments/{booking_id}/process', [PaymentController::class, 'process']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::get('/payments/check/{booking_id}', [PaymentController::class, 'checkStatus']);
    
    // Routes payment tambahan
    Route::get('/payments/history', [PaymentController::class, 'history']);
    Route::post('/payments/{id}/refund', [PaymentController::class, 'refund']);
    Route::get('/payments/stats', [PaymentController::class, 'statistics']);

    // Routes invoice
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'generate']);
    Route::get('/invoice/{id}/preview', [InvoiceController::class, 'preview']);
});