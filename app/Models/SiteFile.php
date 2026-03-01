<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteFile extends Model
{
    protected $fillable = [
        'token',
        'disk',
        'path',
        'original_name',
        'mime',
        'size',
        'extension',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public static function generateToken(): string
    {
        // 64 chars, URL-safe
        return Str::random(64);
    }

    public function storageExists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    public function isInlineFriendly(): bool
    {
        $mime = (string) ($this->mime ?? '');
        if (Str::startsWith($mime, 'image/')) return true;
        if ($mime === 'application/pdf') return true;
        return false;
    }

    /**
     * Signed URL helpers (safer)
     * Format:
     *   /files/{token}?exp=UNIX_TS&sig=HMAC
     */
    public function signedQuery(int $ttlSeconds = 60 * 60 * 24 * 7): array
    {
        $exp = time() + max(60, $ttlSeconds);
        $sig = self::signature($this->token, $exp);
        return ['exp' => $exp, 'sig' => $sig];
    }

    public static function signature(string $token, int $exp): string
    {
        // Use APP_KEY as HMAC secret. Laravel APP_KEY usually begins with base64:
        $key = config('app.key');
        if (is_string($key) && str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        return hash_hmac('sha256', $token . '|' . $exp, (string) $key);
    }

    public static function validateSignature(string $token, ?int $exp, ?string $sig): bool
    {
        if (!$exp || !$sig) return false;
        if ($exp < time()) return false;

        $expected = self::signature($token, $exp);
        return hash_equals($expected, $sig);
    }
}
