<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, $next)
{
    if (! $request->user()->hasVerifiedEmail()) {
        return response()->json([
            'success' => false,
            'message' => 'Email belum diverifikasi'
        ], 403);
    }

    return $next($request);
}
}
