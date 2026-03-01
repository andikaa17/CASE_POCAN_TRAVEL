<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

class FirebaseService
{
    protected $database;
    
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-auth.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        
        $this->database = $factory->createDatabase();
    }
    
    public function updatePaymentStatus($bookingId, $status, $qrData = null)
    {
        $reference = $this->database->getReference("payments/{$bookingId}");
        
        $data = [
            'status' => $status,
            'updated_at' => now()->toIso8601String()
        ];
        
        if ($qrData) {
            $data['qr_url'] = $qrData['qr_url'] ?? null;
            $data['amount'] = $qrData['amount'] ?? null;
            $data['booking_code'] = $qrData['booking_code'] ?? null;
            $data['payment_id'] = $qrData['payment_id'] ?? null;
            $data['expires_at'] = $qrData['expires_at'] ?? null;
        }
        
        $reference->update($data);
    }
    
    public function getPaymentStatus($bookingId)
    {
        $reference = $this->database->getReference("payments/{$bookingId}");
        return $reference->getValue();
    }
}