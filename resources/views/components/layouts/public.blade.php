@props(['profile', 'title' => null, 'description' => null])

@php
    $sections = ['inicio' => 'Inicio', 'sobre-mi' => 'Sobre mí', 'experiencia' => 'Experiencia', 'proyectos' => 'Proyectos', 'contacto' => 'Contacto'];
    $firstName = \Illuminate\Support\Str::before($profile->name, ' ');
@endphp

<!DOCTYPE html>
<html lang="es" class="bg-ink-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $profile->name.' — '.$profile->role }}</title>
    <meta name="description" content="{{ $description ?? $profile->tagline }}">
    <meta property="og:title" content="{{ $title ?? $profile->name }}">
    <meta property="og:description" content="{{ $description ?? $profile->tagline }}">
    <x-fonts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-clip bg-ink-900 font-sans leading-relaxed text-mist-400 antialiased">
    <div class="spotlight pointer-events-none fixed inset-0 z-0 hidden lg:block"></div>
    <div class="noise pointer-events-none fixed inset-0 z-[60]"></div>

    <a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[70] focus:rounded focus:bg-accent focus:px-4 focus:py-2 focus:text-ink-950">
        Saltar al contenido
    </a>

    {{-- Barra de navegación --}}
    <header class="navbar fixed inset-x-0 top-0 z-50 border-b border-transparent transition-all duration-300" data-navbar>
        <nav class="mx-auto flex h-18 max-w-7xl items-center justify-between px-6 lg:px-10" aria-label="Principal">
            <a href="{{ route('home') }}#inicio" class="font-display text-2xl font-extrabold tracking-tight text-mist-100">
                {{ $firstName }}<span class="text-accent">.</span>
            </a>

            <ul class="hidden items-center gap-8 md:flex">
                @foreach ($sections as $id => $label)
                    <li>
                        <a href="{{ route('home') }}#{{ $id }}" data-section="{{ $id }}"
                           class="nav-link relative py-2 text-sm font-medium text-mist-400 transition hover:text-mist-100 after:absolute after:inset-x-0 after:-bottom-0.5 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-accent after:transition-transform after:content-['']">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="flex items-center gap-3">
                @if ($profile->email)
                    <a href="mailto:{{ $profile->email }}" class="pill-primary hidden !px-5 !py-2.5 sm:inline-flex">Contrátame</a>
                @endif
                <button type="button" class="grid size-10 place-items-center rounded-full border border-ink-600 text-mist-100 md:hidden" data-menu-toggle aria-expanded="false" aria-controls="menu-movil">
                    <span class="sr-only">Abrir menú</span>
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </nav>

        <div id="menu-movil" class="hidden border-t border-ink-700 bg-ink-900/95 px-6 py-6 backdrop-blur md:hidden" data-menu>
            <ul class="space-y-1">
                @foreach ($sections as $id => $label)
                    <li><a href="{{ route('home') }}#{{ $id }}" class="block py-2 font-display text-2xl font-bold text-mist-100 hover:text-accent">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>
    </header>

    @auth
        <a href="{{ route('admin.dashboard') }}" class="fixed right-4 bottom-4 z-50 rounded-full border border-accent/30 bg-ink-800/90 px-4 py-2 font-mono text-xs text-accent shadow-lg backdrop-blur transition hover:bg-ink-700">
            Panel de administración →
        </a>
    @endauth

    <div class="relative z-10">
        {{ $slot }}
    </div>

    {{-- Pie de página --}}
    <footer class="relative z-10 border-t border-ink-700">
        <div class="mx-auto flex max-w-7xl flex-col gap-8 px-6 py-12 md:flex-row md:items-center md:justify-between lg:px-10">
            <div>
                <a href="{{ route('home') }}#inicio" class="font-display text-2xl font-extrabold text-mist-100">{{ $firstName }}<span class="text-accent">.</span></a>
                <p class="mt-2 text-sm">Construyendo experiencias digitales que marcan la diferencia.</p>
            </div>
            <x-social-links :profile="$profile" />
        </div>
        <div class="mx-auto flex max-w-7xl flex-col gap-2 border-t border-ink-700/60 px-6 py-6 text-xs sm:flex-row sm:justify-between lg:px-10">
            <p>© {{ now()->year }} {{ $profile->name }}. Todos los derechos reservados.</p>
            <p>Hecho con <span class="text-mist-300">Laravel</span> y <span class="text-mist-300">Tailwind CSS</span>.</p>
        </div>
    </footer>
</body>
</html>
