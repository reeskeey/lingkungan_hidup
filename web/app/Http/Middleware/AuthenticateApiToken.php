<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\UrlCrypt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token autentikasi tidak ditemukan. Harap login terlebih dahulu.',
            ], 401);
        }

        $decodedJson = UrlCrypt::decode($token);
        if (! $decodedJson) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau telah kedaluwarsa.',
            ], 401);
        }

        $payload = json_decode($decodedJson, true);
        if (! isset($payload['user_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payload token tidak valid.',
            ], 401);
        }

        $user = User::find($payload['user_id']);
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
