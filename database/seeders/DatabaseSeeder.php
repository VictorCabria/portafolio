<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Usuario administrador y contenido inicial del portafolio.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@portafolio.test')],
            ['name' => 'Administrador', 'password' => env('ADMIN_PASSWORD', 'password')],
        );

        $this->call(ContentSeeder::class);
    }
}
