<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name', 'role', 'tagline', 'about', 'location', 'email', 'phone', 'avatar', 'cv_url',
    'github_url', 'linkedin_url', 'twitter_url', 'website_url', 'skills', 'available_for_work',
])]
class Profile extends Model
{
    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'available_for_work' => 'boolean',
        ];
    }

    /**
     * El portafolio tiene un único perfil; se crea vacío si aún no existe.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'name' => 'Tu Nombre',
            'role' => 'Desarrollador Web',
        ]);
    }

    /**
     * Enlace de WhatsApp al teléfono; asume Colombia (+57) si no trae indicativo.
     */
    public function whatsappUrl(): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $this->phone);

        if ($digits === '') {
            return null;
        }

        return 'https://wa.me/'.(strlen($digits) === 10 ? '57'.$digits : $digits);
    }

    public function avatarUrl(): ?string
    {
        if (blank($this->avatar)) {
            return null;
        }

        // Las imágenes versionadas en public/images se sirven como archivos estáticos.
        return str_starts_with($this->avatar, 'images/')
            ? asset($this->avatar)
            : Storage::url($this->avatar);
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }
}
