<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name', 'role', 'tagline', 'about', 'location', 'email', 'avatar', 'cv_url',
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

    public function avatarUrl(): ?string
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
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
