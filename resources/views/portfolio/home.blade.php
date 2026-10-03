@php
    $nameParts = explode(' ', $profile->name, 2);
    $skills = $profile->skills ?? [];
    $currentJob = $experiences->first(fn ($experience) => $experience->isCurrent());
    $stats = array_filter([
        ['value' => $projectsCount, 'label' => 'Proyectos'],
        ['value' => $experiences->count(), 'label' => 'Empresas'],
        ['value' => count($skills), 'label' => 'Tecnologías'],
    ], fn ($stat) => $stat['value'] > 0);
@endphp

<x-layouts.public :profile="$profile">
    <main id="contenido">

        {{-- ============ HERO ============ --}}
        <section id="inicio" class="relative flex min-h-screen items-center overflow-hidden pt-28 pb-20" aria-label="Presentación">
            <div class="pointer-events-none absolute -top-40 right-[-10%] size-[42rem] rounded-full bg-accent-strong/20 blur-[140px]"></div>
            <div class="pointer-events-none absolute bottom-0 left-[-15%] size-[30rem] rounded-full bg-sky-500/10 blur-[120px]"></div>
            <div class="hero-grid pointer-events-none absolute inset-0"></div>

            <div class="relative mx-auto grid w-full max-w-7xl items-center gap-16 px-6 lg:grid-cols-[1.15fr_1fr] lg:px-10">
                <div>
                    {{-- Pastilla: rol · empresa actual · ubicación --}}
                    <p class="reveal inline-flex flex-wrap items-center gap-1 rounded-full border border-ink-600 bg-ink-800/70 p-1 text-sm backdrop-blur">
                        <span class="inline-flex items-center gap-2 rounded-full border border-accent/30 bg-accent-soft px-3 py-1 font-semibold text-accent">
                            <span class="relative flex size-2">
                                @if ($profile->available_for_work)
                                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-accent opacity-75"></span>
                                @endif
                                <span class="relative inline-flex size-2 rounded-full bg-accent"></span>
                            </span>
                            {{ $profile->role }}
                        </span>
                        @if ($currentJob)
                            <span class="px-2.5 font-semibold text-mist-100">@ {{ $currentJob->company }}</span>
                        @endif
                        @if ($profile->location)
                            <span class="inline-flex items-center gap-1.5 border-l border-ink-600 px-3 font-mono text-xs text-mist-300">
                                <x-icon name="pin" class="size-3.5 text-accent" /> {{ $profile->location }}
                            </span>
                        @endif
                    </p>

                    <h1 class="mt-8 font-display font-extrabold tracking-tight text-mist-100">
                        <span class="reveal block text-2xl font-semibold text-mist-300 sm:text-3xl" style="--delay: 100ms">Hola, soy</span>
                        <span class="reveal mt-2 block text-5xl leading-[0.95] sm:text-7xl xl:text-8xl" style="--delay: 200ms">
                            {{ $nameParts[0] }}
                            @isset($nameParts[1])
                                <span class="text-gradient">{{ $nameParts[1] }}</span>
                            @endisset
                        </span>
                    </h1>

                    {{-- Línea de terminal que escribe sola las tecnologías --}}
                    <p class="reveal mt-7 inline-flex items-center gap-2 rounded-lg border border-ink-600 bg-ink-950/60 px-4 py-2 font-mono text-sm text-mist-100 sm:text-base" style="--delay: 300ms">
                        <span class="text-accent">&gt;</span> Enfoque:
                        <span class="font-semibold text-accent" data-typing='@json($skills ?: [$profile->role])'>{{ $skills[0] ?? $profile->role }}</span><span class="-ml-1 inline-block h-[1.1em] w-2.5 animate-pulse bg-accent" aria-hidden="true"></span>
                    </p>

                    @if ($profile->tagline)
                        <p class="reveal mt-6 max-w-xl text-base text-mist-300 sm:text-lg" style="--delay: 400ms">
                            {{ $profile->tagline }}
                            @if ($currentJob)
                                Actualmente en <strong class="font-semibold text-mist-100">{{ $currentJob->company }}</strong>.
                            @endif
                        </p>
                    @endif

                    @if ($skills)
                        <ul class="reveal mt-6 flex max-w-xl flex-wrap gap-2" style="--delay: 450ms" aria-label="Tecnologías principales">
                            @foreach (array_slice($skills, 0, 7) as $skill)
                                <li class="rounded-lg border border-ink-600 bg-ink-800/60 px-3 py-1 font-mono text-xs text-mist-300">{{ $skill }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="reveal mt-10 flex flex-wrap gap-4" style="--delay: 500ms">
                        <a href="#proyectos" class="pill-primary">
                            <x-icon name="code" class="size-4" />
                            Ver proyectos
                        </a>
                        <a href="#contacto" class="pill-outline">
                            Ponte en contacto
                            <x-icon name="next" class="size-4 rotate-90" />
                        </a>
                        @if ($profile->cv_url)
                            <a href="{{ $profile->cv_url }}" target="_blank" rel="noreferrer noopener" class="pill-outline">
                                <x-icon name="document" class="size-4" />
                                CV en PDF
                            </a>
                        @endif
                    </div>

                    @if ($stats)
                        <dl class="reveal mt-14 flex flex-wrap items-center gap-8" style="--delay: 600ms">
                            @foreach ($stats as $stat)
                                @unless ($loop->first)
                                    <div class="h-10 w-px bg-ink-600" aria-hidden="true"></div>
                                @endunless
                                <div class="flex flex-col-reverse">
                                    <dt class="text-xs uppercase tracking-widest text-mist-400">{{ $stat['label'] }}</dt>
                                    <dd class="font-display text-3xl font-bold text-mist-100">{{ $stat['value'] }}<span class="text-accent">+</span></dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>

                {{-- Foto con etiquetas flotantes --}}
                <div class="reveal relative mx-auto w-full max-w-md" style="--delay: 300ms">
                    <div class="animate-float relative">
                        <div class="absolute -inset-px rounded-[2.5rem] bg-gradient-to-br from-accent via-accent-strong/40 to-transparent"></div>
                        <div class="relative aspect-[4/5] overflow-hidden rounded-[2.5rem] bg-ink-800">
                            @if ($profile->avatarUrl())
                                <img src="{{ $profile->avatarUrl() }}" alt="Foto de {{ $profile->name }}" class="size-full object-cover">
                            @else
                                <div class="grid size-full place-items-center bg-gradient-to-br from-ink-700 via-ink-800 to-ink-950">
                                    <span class="font-display text-8xl font-extrabold text-gradient">{{ $profile->initials() }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-ink-900/80 to-transparent"></div>
                        </div>

                        @isset($skills[0])
                            <div class="absolute top-10 -left-6 flex items-center gap-2.5 rounded-2xl border border-ink-600 bg-ink-800/80 px-4 py-3 text-sm font-semibold text-mist-100 shadow-xl backdrop-blur-md sm:-left-12">
                                <span class="grid size-8 place-items-center rounded-lg bg-accent-soft text-accent"><x-icon name="code" class="size-4" /></span>
                                {{ $skills[0] }}
                            </div>
                        @endisset
                        @isset($skills[1])
                            <div class="absolute -right-4 bottom-16 flex items-center gap-2.5 rounded-2xl border border-ink-600 bg-ink-800/80 px-4 py-3 text-sm font-semibold text-mist-100 shadow-xl backdrop-blur-md sm:-right-10">
                                <span class="grid size-8 place-items-center rounded-lg bg-accent-soft text-accent">✦</span>
                                {{ $skills[1] }}
                            </div>
                        @endisset
                    </div>
                </div>
            </div>

            <a href="#sobre-mi" class="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-[0.65rem] uppercase tracking-[0.3em] text-mist-400 transition hover:text-accent lg:flex">
                Scroll
                <span class="flex h-10 w-6 justify-center rounded-full border border-ink-600 pt-2">
                    <span class="size-1.5 animate-bounce rounded-full bg-accent"></span>
                </span>
            </a>
        </section>

        {{-- ============ MARQUESINA DE TECNOLOGÍAS ============ --}}
        @if ($skills)
            <div class="relative -rotate-1 overflow-hidden border-y border-accent/20 bg-accent-strong py-5" aria-hidden="true">
                <div class="animate-marquee flex w-max gap-10 whitespace-nowrap">
                    @foreach (array_merge($skills, $skills, $skills, $skills) as $skill)
                        <span class="flex items-center gap-10 font-display text-xl font-bold uppercase tracking-wide text-white">
                            {{ $skill }} <span class="text-sky-200">✦</span>
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ============ SOBRE MÍ ============ --}}
        <section id="sobre-mi" class="py-28 lg:py-36" aria-labelledby="titulo-sobre-mi">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <span class="reveal section-label">Sobre mí</span>
                <h2 id="titulo-sobre-mi" class="reveal section-title">Pasión por el código,<br><span class="text-gradient">enfoque en resultados</span></h2>

                <div class="mt-14 grid gap-14 lg:grid-cols-2 lg:gap-20">
                    <div class="space-y-5 text-base sm:text-lg">
                        @foreach (preg_split('/\R{2,}/', trim((string) $profile->about)) as $paragraph)
                            @if ($paragraph !== '')
                                <p class="reveal" style="--delay: {{ $loop->index * 100 }}ms">{{ $paragraph }}</p>
                            @endif
                        @endforeach

                        <div class="reveal flex flex-wrap gap-4 pt-4">
                            @if ($profile->cv_url)
                                <a href="{{ $profile->cv_url }}" target="_blank" rel="noreferrer noopener" class="pill-primary">
                                    Descargar CV <x-icon name="arrow" class="size-4" />
                                </a>
                            @endif
                            <x-social-links :profile="$profile" class="self-center" />
                        </div>
                    </div>

                    @if ($skills)
                        <div class="reveal rounded-3xl border border-ink-700 bg-ink-800/40 p-8 lg:p-10">
                            <h3 class="font-display text-xl font-bold text-mist-100">Stack tecnológico</h3>
                            <p class="mt-1 text-sm">Herramientas con las que trabajo a diario.</p>
                            <ul class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @foreach ($skills as $skill)
                                    <li class="group flex items-center gap-2.5 rounded-xl border border-ink-700 bg-ink-900/60 px-4 py-3 text-sm font-medium text-mist-300 transition hover:-translate-y-0.5 hover:border-accent/50 hover:text-mist-100">
                                        <span class="size-1.5 shrink-0 rounded-full bg-accent transition group-hover:shadow-[0_0_12px_var(--color-accent)]"></span>
                                        {{ $skill }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ============ EXPERIENCIA ============ --}}
        @if ($experiences->isNotEmpty())
            @php
                // Cada tarjeta lleva una barra de color distinta dentro de la gama azul
                $accents = [
                    'from-accent via-sky-400 to-cyan-400',
                    'from-accent-strong via-indigo-500 to-violet-500',
                    'from-sky-500 via-cyan-400 to-teal-400',
                ];
                $columns = match (min($experiences->count(), 3)) {
                    1 => 'max-w-xl',
                    2 => 'max-w-5xl md:grid-cols-2',
                    default => 'md:grid-cols-2 lg:grid-cols-3',
                };
            @endphp
            <section id="experiencia" class="relative overflow-hidden border-t border-ink-700/60 py-28 lg:py-36" aria-labelledby="titulo-experiencia">
                <div class="pointer-events-none absolute top-1/4 left-1/3 size-96 rounded-full bg-accent-strong/10 blur-[140px]"></div>
                <div class="pointer-events-none absolute right-1/4 bottom-1/4 size-96 rounded-full bg-cyan-500/10 blur-[140px]"></div>

                <div class="relative mx-auto max-w-7xl px-6 lg:px-10">
                    <div class="mx-auto max-w-3xl text-center">
                        <span class="reveal inline-flex items-center gap-2 rounded-full border border-ink-600 bg-ink-800/80 px-3 py-1 font-mono text-xs text-mist-100">
                            <span class="text-accent">&gt;</span> mi_trayectoria_profesional
                        </span>
                        <h2 id="titulo-experiencia" class="reveal section-title mt-5">Experiencia <span class="text-gradient">laboral</span></h2>
                        <p class="reveal mx-auto mt-5 max-w-2xl text-base sm:text-lg">
                            Los equipos y empresas donde he construido productos con impacto real.
                        </p>
                    </div>

                    <ol class="mx-auto mt-16 grid gap-6 sm:gap-8 {{ $columns }}">
                        @foreach ($experiences as $experience)
                            @php $current = $experience->isCurrent(); @endphp
                            <li class="reveal" style="--delay: {{ $loop->index * 100 }}ms">
                                <article class="group relative flex h-full flex-col justify-between overflow-hidden rounded-3xl border border-ink-700 bg-ink-800/50 p-6 shadow-xl backdrop-blur-xl transition duration-500 hover:-translate-y-1.5 hover:border-accent/40 hover:shadow-2xl hover:shadow-accent-strong/15 sm:p-8">
                                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r opacity-90 transition group-hover:opacity-100 {{ $accents[$loop->index % count($accents)] }}"></div>

                                    <div>
                                        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                                            <span class="rounded-full border border-accent/30 bg-accent-soft px-3 py-1 font-mono text-[0.7rem] font-semibold uppercase tracking-wider text-accent">
                                                {{ $experience->period }}
                                            </span>
                                            @if ($current)
                                                <span class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-400">
                                                    <span class="relative flex size-2">
                                                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span class="relative inline-flex size-2 rounded-full bg-emerald-400"></span>
                                                    </span>
                                                    Puesto actual
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-mist-400">
                                                    <x-icon name="check" class="size-4 text-accent" /> Finalizado
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mb-5 flex items-start gap-4">
                                            <div class="shrink-0 rounded-2xl border border-accent/20 bg-accent-soft p-3 text-accent transition duration-300 group-hover:scale-110">
                                                <x-icon name="briefcase" class="size-6" />
                                            </div>
                                            <div>
                                                <h3 class="font-display text-xl leading-snug font-bold text-mist-100 transition group-hover:text-accent">{{ $experience->role }}</h3>
                                                <p class="mt-1 text-sm font-medium text-mist-300">@ {{ $experience->company }}</p>
                                            </div>
                                        </div>

                                        @if ($experience->description)
                                            <p class="mb-6 text-sm leading-relaxed whitespace-pre-line">{{ $experience->description }}</p>
                                        @endif

                                        @if (! empty($experience->technologies))
                                            <ul class="mb-6 flex flex-wrap gap-2" aria-label="Tecnologías">
                                                @foreach ($experience->technologies as $tech)
                                                    <li class="rounded-lg border border-ink-600 bg-ink-900/60 px-2.5 py-1 font-mono text-xs text-mist-300 transition group-hover:border-accent/25">{{ $tech }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3 border-t border-ink-700/70 pt-5">
                                        @if ($experience->company_url)
                                            <a href="{{ $experience->company_url }}" target="_blank" rel="noreferrer noopener"
                                               class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-accent-strong to-accent px-4 py-2.5 text-xs font-semibold text-white shadow-md transition hover:shadow-lg hover:shadow-accent-strong/30 hover:brightness-110 active:scale-[0.98]">
                                                Visitar empresa <x-icon name="arrow" class="size-3.5" />
                                            </a>
                                        @else
                                            <span class="flex-1 font-mono text-xs text-mist-400">
                                                <span class="text-accent">{{ count($experience->technologies ?? []) }}</span> tecnologías utilizadas
                                            </span>
                                        @endif
                                        <span class="grid size-9 shrink-0 place-items-center rounded-xl border border-ink-600 font-mono text-xs text-mist-400">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </section>
        @endif

        {{-- ============ PROYECTOS ============ --}}
        <section id="proyectos" class="border-t border-ink-700/60 py-28 lg:py-36" aria-labelledby="titulo-proyectos">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <div class="grid gap-6 lg:grid-cols-[1fr_1.4fr] lg:items-end">
                    <div>
                        <span class="reveal section-label">Casos de estudio</span>
                        <h2 id="titulo-proyectos" class="reveal section-title">Proyectos <span class="text-gradient">destacados</span></h2>
                    </div>
                    <p class="reveal max-w-lg text-base sm:text-lg lg:justify-self-end">
                        Una selección de trabajos que muestran mi forma de resolver problemas y construir producto.
                    </p>
                </div>

                @if ($projects->isEmpty())
                    <p class="mt-16 rounded-2xl border border-dashed border-ink-600 p-8 text-center text-sm">
                        Aún no hay proyectos destacados. Márcalos como «destacado» desde el panel.
                    </p>
                @else
                    <div class="mt-20 space-y-28">
                        @foreach ($projects as $project)
                            <article class="reveal grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                                {{-- Visual --}}
                                <a href="{{ route('projects.show', $project) }}" class="group relative block {{ $loop->odd ? '' : 'lg:order-2' }}" tabindex="-1" aria-hidden="true">
                                    <div class="pointer-events-none absolute inset-8 rounded-full bg-accent-strong/30 blur-3xl transition duration-500 group-hover:bg-accent/40"></div>
                                    <div class="relative overflow-hidden rounded-2xl border border-ink-600 bg-ink-800 shadow-2xl transition duration-500 group-hover:-translate-y-2 group-hover:border-accent/50">
                                        <div class="flex items-center gap-1.5 border-b border-ink-700 bg-ink-900/80 px-4 py-3">
                                            <span class="size-2.5 rounded-full bg-ink-600"></span>
                                            <span class="size-2.5 rounded-full bg-ink-600"></span>
                                            <span class="size-2.5 rounded-full bg-accent/70"></span>
                                            <span class="ml-3 h-4 flex-1 rounded bg-ink-700/60"></span>
                                        </div>
                                        @if ($project->imageUrl())
                                            <img src="{{ $project->imageUrl() }}" alt="" loading="lazy" class="aspect-[16/10] w-full object-cover object-top transition duration-700 group-hover:scale-[1.03]">
                                        @else
                                            <div class="grid aspect-[16/10] w-full place-items-center bg-gradient-to-br from-ink-700 via-ink-800 to-ink-950">
                                                <x-icon name="code" class="size-14 text-accent/60" />
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                {{-- Contenido --}}
                                <div class="{{ $loop->odd ? '' : 'lg:order-1' }}">
                                    <p class="flex items-center gap-3 font-mono text-sm text-accent">
                                        <span class="text-mist-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="h-px w-8 bg-ink-600"></span>
                                        {{ collect([$project->year, $project->client])->filter()->implode(' · ') ?: 'Proyecto' }}
                                    </p>
                                    <h3 class="mt-4 font-display text-3xl font-bold text-mist-100 sm:text-4xl">
                                        <a href="{{ route('projects.show', $project) }}" class="transition hover:text-accent">{{ $project->title }}</a>
                                    </h3>
                                    <p class="mt-4 text-base sm:text-lg">{{ $project->summary }}</p>
                                    <x-tags :items="$project->technologies" class="mt-6" />

                                    <div class="mt-8 flex flex-wrap items-center gap-4">
                                        <a href="{{ route('projects.show', $project) }}" class="pill-outline !py-3">
                                            Ver proyecto <x-icon name="arrow" class="size-4" />
                                        </a>
                                        @if ($project->demo_url)
                                            <a href="{{ $project->demo_url }}" target="_blank" rel="noreferrer noopener" class="text-sm font-semibold text-mist-300 transition hover:text-accent">Demo ↗</a>
                                        @endif
                                        @if ($project->repo_url)
                                            <a href="{{ $project->repo_url }}" target="_blank" rel="noreferrer noopener" class="text-sm font-semibold text-mist-300 transition hover:text-accent">Código ↗</a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                <div class="reveal mt-24 flex flex-col items-center gap-4 text-center">
                    <p class="text-mist-300">¿Quieres ver más?</p>
                    <a href="{{ route('projects') }}" class="pill-outline">
                        Ver todos los proyectos <x-icon name="next" class="size-4" />
                    </a>
                </div>
            </div>
        </section>

        {{-- ============ CONTACTO ============ --}}
        <section id="contacto" class="px-6 pb-28 lg:px-10" aria-labelledby="titulo-contacto">
            <div class="reveal relative mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-gradient-to-br from-accent-strong via-blue-700 to-ink-800 px-8 py-16 sm:px-14 lg:py-24">
                <div class="hero-grid pointer-events-none absolute inset-0 opacity-60"></div>
                <div class="pointer-events-none absolute -top-32 -right-20 size-96 rounded-full bg-sky-400/30 blur-3xl"></div>

                <div class="relative grid gap-10 lg:grid-cols-[1.3fr_1fr] lg:items-end">
                    <div>
                        <span class="mb-5 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-200 before:block before:h-0.5 before:w-5 before:rounded-full before:bg-sky-200">Contacto</span>
                        <h2 id="titulo-contacto" class="font-display text-4xl font-extrabold leading-[1.05] text-white sm:text-6xl">
                            ¡Hola! Cuéntame<br>sobre tu idea
                        </h2>
                        <p class="mt-5 max-w-lg text-blue-100/80 sm:text-lg">
                            Ya sea un proyecto, una consultoría o simplemente saludar: mi bandeja de entrada siempre está abierta.
                        </p>
                    </div>

                    @if ($profile->email)
                        <div class="lg:justify-self-end">
                            <a href="mailto:{{ $profile->email }}" class="group inline-flex items-center gap-3 border-b-2 border-white/30 pb-2 font-display text-lg font-bold break-all text-white transition hover:border-white sm:text-2xl">
                                {{ $profile->email }}
                                <x-icon name="arrow" class="size-6 shrink-0 transition-transform group-hover:-translate-y-1 group-hover:translate-x-1" />
                            </a>
                            <x-social-links :profile="$profile" class="mt-8 [&_a]:text-blue-100 [&_a:hover]:text-white" />
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-layouts.public>
