<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Database\Seeders\ContentSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SyncContentController extends Controller
{
    /**
     * Aplica las migraciones pendientes y reemplaza perfil, experiencia y
     * proyectos por los de ContentSeeder.
     * Sirve para publicar en producción (Vercel) el contenido versionado en el repo.
     */
    public function __invoke()
    {
        Artisan::call('migrate', ['--force' => true]);

        DB::transaction(fn () => app(ContentSeeder::class)->run());

        return redirect()->route('admin.dashboard')->with('status', 'Contenido sincronizado con el repositorio.');
    }
}
