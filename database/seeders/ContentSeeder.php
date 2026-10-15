<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Contenido del portafolio (perfil, experiencia y proyectos). Reemplaza el
     * existente; también se puede ejecutar desde /admin con "Sincronizar contenido".
     */
    public function run(): void
    {
        Profile::query()->delete();
        Profile::create([
            'name' => 'Víctor Cabria',
            'role' => 'Desarrollador Móvil y Full Stack',
            'tagline' => 'Desarrollo aplicaciones móviles que funcionan con y sin conexión a internet, con backends sólidos en Laravel.',
            'about' => "Soy desarrollador web especializado en Laravel y en el ecosistema PHP. Me gusta convertir problemas de negocio en software claro, mantenible y bien diseñado.\n\nHe trabajado en sistemas de trámites, plataformas multi-tenant y paneles administrativos, cuidando tanto la arquitectura del backend como la experiencia del usuario final.\n\nFuera del código, disfruto aprendiendo nuevas tecnologías y compartiendo lo que aprendo con otros desarrolladores.",
            'location' => 'Colombia',
            'avatar' => 'images/perfil.jpg',
            'email' => 'victorjosecabria11@gmail.com',
            'github_url' => 'https://github.com/',
            'linkedin_url' => 'https://www.linkedin.com/',
            'skills' => ['Flutter', 'Dart', 'Apps offline-first', 'Laravel', 'APIs REST', 'Desarrollo con IA', 'Firebase', 'MySQL', 'PostgreSQL', 'React Native (básico)'],
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
                'role' => 'Frontend Developer',
                'company' => 'BOP Solutions LLC',
                'company_url' => null,
                'period' => 'feb. 2024 — sept. 2024',
                'description' => 'Desarrollo front end de aplicaciones móviles con Flutter y Dart siguiendo la arquitectura MVVM, implementando especificaciones de diseño y trabajando en equipo con Git y Bitbucket. Contrato temporal · Miami, Florida (híbrido).',
                'technologies' => json_encode(['Flutter', 'Dart', 'MVVM', 'Desarrollo Android', 'Desarrollo front end', 'Especificaciones de diseño', 'Git', 'Bitbucket', 'Trabajo en equipo']),
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'Pasante de TI',
                'company' => 'Hotel Capilla del Mar',
                'company_url' => null,
                'period' => 'abr. 2023 — oct. 2023',
                'description' => 'Prácticas en el área de tecnología: mantenimiento de equipos y soporte al servicio al huésped. Contrato de prácticas · Cartagena de Indias (presencial).',
                'technologies' => json_encode(['Mantenimiento de equipos', 'Servicio al huésped']),
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role' => 'Full Stack Developer',
                'company' => 'Synergia',
                'company_url' => null,
                'period' => 'ene. 2022 — feb. 2023',
                'description' => 'Desarrollo de aplicaciones Android con Flutter, Dart y Firebase, trabajando con bases de datos, resolución de incidencias y control de calidad del producto bajo metodología Scrum. Jornada completa · Cartagena de Indias (híbrido).',
                'technologies' => json_encode(['Flutter', 'Dart', 'Firebase', 'Desarrollo Android', 'Bases de datos', 'Scrum', 'Git', 'Calidad de producto', 'Resolución de incidencias', 'Especificaciones de diseño']),
                'sort_order' => 4,
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
                'title' => 'TODOS – App de telemedicina',
                'summary' => 'App móvil multi-país para agendar consultas médicas, entrar a la sala virtual y contratar planes de salud.',
                'technologies' => ['Flutter', 'Dart', 'GetX', 'Firebase', 'MVVM'],
                'client' => 'BOP Solutions LLC',
                'year' => 2024,
                'image' => 'images/proyectos/todos-telemedicina.jpg',
                'description' => "## El reto\n\nTODOS necesitaba una app de telemedicina para varios países (Chile y Colombia) donde los afiliados pudieran agendar consultas en línea o presenciales, entrar a la sala virtual y contratar planes de salud, todo desde una sola base de código para iOS, Android y web.\n\n## La solución\n\nTrabajé en el front end de la app con Flutter y Dart bajo arquitectura MVVM, usando GetX para navegación y estado e inyección de dependencias con get_it. Implementé las pantallas a partir de las especificaciones de diseño: onboarding, login por documento, registro, recuperación de contraseña, panel de consultas (marcadas, realizadas y canceladas), sala de espera, mapa de clínicas y tienda de planes.\n\nLa app es configurable por país e idioma (español y portugués) desde un archivo de configuración, y se integra con Firebase para analítica y reporte de errores.\n\n## Resultados\n\n- Una sola base de código publicada en iOS, Android y web\n- Flujos de agendamiento y acceso a la consulta virtual en pocos pasos\n- Marca y contenido adaptables por país sin cambiar el código",
                'gallery' => [
                    'images/proyectos/todos/01-bienvenida.jpg',
                    'images/proyectos/todos/02-login.jpg',
                    'images/proyectos/todos/03-home-consultas.jpg',
                    'images/proyectos/todos/04-home-sin-citas.jpg',
                    'images/proyectos/todos/05-tienda-planes.jpg',
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Portafolio personal',
                'summary' => 'Este mismo sitio: portafolio administrable con panel propio para gestionar perfil, experiencia y proyectos.',
                'technologies' => ['Laravel 13', 'Blade', 'Tailwind CSS 4', 'Vite'],
                'year' => 2026,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Dashboard de ventas',
                'summary' => 'Panel analítico con métricas en tiempo real, filtros avanzados y exportación de reportes a Excel y PDF.',
                'technologies' => ['Laravel', 'Vue', 'Chart.js'],
                'year' => 2025,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'API de inventario',
                'summary' => 'API REST documentada para control de inventario multi-bodega con autenticación por tokens.',
                'technologies' => ['Laravel', 'Sanctum', 'MySQL'],
                'year' => 2024,
                'is_featured' => false,
                'sort_order' => 5,
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
