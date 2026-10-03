<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Datos iniciales de ejemplo: edítalos desde /admin.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@portafolio.test')],
            ['name' => 'Administrador', 'password' => env('ADMIN_PASSWORD', 'password')],
        );

        Profile::query()->delete();
        Profile::create([
            'name' => 'Víctor Cabria',
            'role' => 'Desarrollador Full Stack',
            'tagline' => 'Construyo aplicaciones web robustas, rápidas y fáciles de usar.',
            'about' => "Soy desarrollador web especializado en Laravel y en el ecosistema PHP. Me gusta convertir problemas de negocio en software claro, mantenible y bien diseñado.\n\nHe trabajado en sistemas de trámites, plataformas multi-tenant y paneles administrativos, cuidando tanto la arquitectura del backend como la experiencia del usuario final.\n\nFuera del código, disfruto aprendiendo nuevas tecnologías y compartiendo lo que aprendo con otros desarrolladores.",
            'location' => 'Colombia',
            'email' => 'victor.cabria@bexsoluciones.com',
            'github_url' => 'https://github.com/',
            'linkedin_url' => 'https://www.linkedin.com/',
            'skills' => ['PHP', 'Laravel', 'MySQL', 'JavaScript', 'Vue', 'Tailwind CSS', 'Git', 'Docker'],
            'available_for_work' => true,
        ]);

        Experience::query()->delete();
        Experience::insert([
            [
                'role' => 'Desarrollador Full Stack',
                'company' => 'Bex Soluciones',
                'company_url' => null,
                'period' => '2023 — Actualidad',
                'description' => 'Desarrollo y mantenimiento de plataformas web en Laravel, incluyendo un sistema de trámites multi-tenant, integraciones con APIs externas y paneles administrativos.',
                'technologies' => json_encode(['Laravel', 'MySQL', 'Livewire', 'Tailwind CSS']),
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'Desarrollador Web',
                'company' => 'Empresa anterior',
                'company_url' => null,
                'period' => '2021 — 2023',
                'description' => 'Creación de sitios y aplicaciones a medida para clientes, desde la maqueta hasta el despliegue en producción.',
                'technologies' => json_encode(['PHP', 'JavaScript', 'Bootstrap', 'MySQL']),
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Project::query()->delete();
        $projects = [
            [
                'title' => 'Bex Trámites',
                'summary' => 'Plataforma multi-tenant para gestionar trámites en línea, con flujos configurables, notificaciones y panel de seguimiento.',
                'technologies' => ['Laravel', 'MySQL', 'Tenancy', 'Tailwind CSS'],
                'client' => 'Bex Soluciones',
                'year' => 2026,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Portafolio personal',
                'summary' => 'Este mismo sitio: portafolio administrable con panel propio para gestionar perfil, experiencia y proyectos.',
                'technologies' => ['Laravel 13', 'Blade', 'Tailwind CSS 4', 'Vite'],
                'year' => 2026,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Dashboard de ventas',
                'summary' => 'Panel analítico con métricas en tiempo real, filtros avanzados y exportación de reportes a Excel y PDF.',
                'technologies' => ['Laravel', 'Vue', 'Chart.js'],
                'year' => 2025,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'API de inventario',
                'summary' => 'API REST documentada para control de inventario multi-bodega con autenticación por tokens.',
                'technologies' => ['Laravel', 'Sanctum', 'MySQL'],
                'year' => 2024,
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project + [
                'description' => "## El reto\n\nDescribe aquí el problema que resolvía el proyecto y para quién.\n\n## La solución\n\nExplica las decisiones técnicas más importantes, la arquitectura y tu papel en el equipo.\n\n## Resultados\n\n- Un resultado medible\n- Otro logro destacable",
                'is_published' => true,
            ]);
        }
    }
}
