<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Support\UrlCrypt;

class AuthApiController extends Controller
{
    /**
     * Login petugas melalui REST API mobile.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email atau password salah.',
            ], 422);
        }

        $tokenPayload = json_encode([
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => time(),
        ]);

        $token = UrlCrypt::encode($tokenPayload);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->encrypted_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'province_id' => $user->province_id ? UrlCrypt::encode($user->province_id) : null,
                'is_superadmin' => $user->isSuperadmin(),
            ],
        ]);
    }

    /**
     * Mengambil profil pengguna yang sedang login.
     */
    public function user(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->encrypted_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'province_id' => $user->province_id ? UrlCrypt::encode($user->province_id) : null,
                'is_superadmin' => $user->isSuperadmin(),
            ],
        ]);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil diakhiri.',
        ]);
    }
}
