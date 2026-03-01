<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // CEK DARI HEADER ATAU QUERY PARAMETER
        $apiKey = $request->header('X-API-Key') ?? $request->query('X-API-Key');
        $envKey = env('API_KEY');
        
        // ===== LOG DEBUG =====
        Log::info('========== API KEY DEBUG ==========');
        Log::info('Method: ' . $request->method());
        Log::info('Request X-API-Key: ' . ($apiKey ?? 'NULL'));
        Log::info('Environment API_KEY: ' . ($envKey ?? 'NULL'));
        Log::info('Length request key: ' . strlen($apiKey ?? ''));
        Log::info('Length env key: ' . strlen($envKey ?? ''));
        Log::info('First char request: ' . substr($apiKey ?? '', 0, 1));
        Log::info('First char env: ' . substr($envKey ?? '', 0, 1));
        Log::info('Are they equal? ' . ($apiKey === $envKey ? 'YES' : 'NO'));
        
        // Coba trim dulu (hapus spasi)
        if (trim($apiKey ?? '') !== trim($envKey ?? '')) {
            Log::info('❌ INVALID API KEY - Returning 401');
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key',
                'data' => null,
                'debug' => [
                    'received' => $apiKey,
                    'expected' => $envKey
                ]
            ], 401);
        }
        
        Log::info('✅ API KEY VALID - Proceeding');
        return $next($request);
    }
}