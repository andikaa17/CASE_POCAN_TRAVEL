<?php
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;

// ========== PUBLIC ROUTES ==========
Route::get('/', function () {
    return view('welcome');
});

// Test Firebase (opsional)
Route::get('/test-firebase', function () {
    try {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-auth.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        
        $database = $factory->createDatabase();
        
        $testRef = $database->getReference('test/connection')
            ->set([
                'message' => 'Hello from Laravel!',
                'time' => now()->toDateTimeString()
            ]);
        
        $data = $database->getReference('test/connection')->getValue();
        
        return response()->json([
            'success' => true,
            'message' => 'Firebase connected!',
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});



// ========== ROUTES YANG BUTUH LOGIN & VERIFIKASI ==========
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// ========== ROUTES KHUSUS ADMIN ==========
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});