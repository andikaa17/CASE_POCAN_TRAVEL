<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /**
     * Kirim ulang email verifikasi (BUTUH LOGIN)
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terverifikasi.'
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Link verifikasi telah dikirim ke email Anda.'
        ]);
    }

    /**
     * Kirim ulang email verifikasi - PUBLIC (tanpa login)
     */
    public function resendPublic(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terverifikasi.'
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Link verifikasi telah dikirim ulang ke email Anda.'
        ]);
    }

    /**
     * Verifikasi email dari link
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'success' => false,
                'message' => 'Link verifikasi tidak valid.'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            // Redirect ke halaman frontend dengan pesan
            return redirect(env('FRONTEND_URL') . '/login?verified=already');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Redirect ke halaman login dengan pesan sukses
        return redirect(env('FRONTEND_URL') . '/login?verified=success');
    }

    /**
     * Cek status verifikasi user yang login
     */
    public function checkVerificationStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'verified' => $user->hasVerifiedEmail(),
            'email' => $user->email
        ]);
    }
}