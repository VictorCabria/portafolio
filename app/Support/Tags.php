<?php

namespace App\Support;

class Tags
{
    /**
     * Convierte "Laravel, Vue,  Tailwind" en ['Laravel', 'Vue', 'Tailwind'].
     */
    public static function parse(?string $value): array
    {
        return collect(explode(',', (string) $value))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public static function join(?array $tags): string
    {
        return implode(', ', $tags ?? []);
    }
}
