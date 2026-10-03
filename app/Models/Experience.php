<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['role', 'company', 'company_url', 'period', 'description', 'technologies', 'sort_order'])]
class Experience extends Model
{
    protected function casts(): array
    {
        return [
            'technologies' => 'array',
        ];
    }

    /**
     * Un puesto es actual si su periodo termina en «Presente», «Actualidad», etc.
     */
    public function isCurrent(): bool
    {
        return Str::contains(Str::lower($this->period), ['presente', 'actual', 'hoy', 'present']);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderByDesc('id');
    }
}
