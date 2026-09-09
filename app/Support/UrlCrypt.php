<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Throwable;

class UrlCrypt
{
    /**
     * Enkripsi ID integer / numerik menjadi string token URL-Safe.
     */
    public static function encode(int|string $id): string
    {
        $encrypted = Crypt::encryptString((string)$id);
        return strtr($encrypted, ['+' => '-', '/' => '_', '=' => '~']);
    }

    /**
     * Dekripsi string token URL-Safe kembali ke nilai string aslinya.
     * Mengembalikan null jika token tidak valid, telah dimanipulasi, atau kedaluwarsa.
     */
    public static function decode(?string $token): ?string
    {
        if (! $token || ! is_string($token)) {
            return null;
        }

        try {
            $original = strtr($token, ['-' => '+', '_' => '/', '~' => '=']);
            return Crypt::decryptString($original);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Dekripsi token dan kembalikan sebagai integer ID.
     */
    public static function decodeId(?string $token): ?int
    {
        $decoded = static::decode($token);
        return is_numeric($decoded) ? (int)$decoded : null;
    }
}
