<?php

namespace App\Support;

final class PhoneNumber
{
    public static function normalize(string $phoneNumber): ?string
    {
        $trimmed = trim($phoneNumber);
        if (! str_starts_with($trimmed, '+')) {
            return null;
        }

        $normalized = '+'.preg_replace('/\D/', '', substr($trimmed, 1));

        return preg_match('/^\+[1-9]\d{6,14}$/', $normalized) ? $normalized : null;
    }
}
