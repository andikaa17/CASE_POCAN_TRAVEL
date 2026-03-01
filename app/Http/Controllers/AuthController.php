<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Register user baru
     */
    public function register(Request $request)
    {
         \Log::info('Register request:', $request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'required|string|max:15'
        ]);

        if ($validator->fails()) {
            \Log::error('Register validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'customer'
        ]);

        // Kirim email verifikasi
        event(new Registered($user));

        // ===== PENTING! JANGAN KASIH TOKEN DULU =====
        // User HARUS verifikasi email dulu sebelum bisa login
        // Makanya token TIDAK dibuat di sini!

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan cek email untuk verifikasi.',
            'data' => [
                'user' => $user,
                // 'token' => $token,  // <-- COMMENT / HAPUS!
                // 'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
                'data' => null
            ], 401);
        }

        // ===== CEK APAKAH EMAIL SUDAH VERIFIED =====
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email belum diverifikasi. Silakan cek email Anda.',
                'data' => [
                    'email' => $user->email,
                    'need_verification' => true
                ]
            ], 403); // 403 Forbidden
        }

        // Buat token hanya jika email sudah terverifikasi
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    /**
     * Logout user (hapus token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
            'data' => null
        ], 200);
    }

    /**
     * Get current user profile
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profil user',
            'data' => $request->user()
        ], 200);
    }
}