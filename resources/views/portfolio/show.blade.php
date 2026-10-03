<x-layouts.public :profile="$profile" :title="$project->title.' — '.$profile->name" :description="$project->summary">
    <div class="mx-auto min-h-screen max-w-3xl px-6 pt-32 pb-24 md:px-12">
        <a href="{{ route('projects') }}" class="group mb-8 inline-flex items-center font-semibold leading-tight text-accent">
            <x-icon name="back" class="mr-1 size-4 transition-transform group-hover:-translate-x-2" />
            Todos los proyectos
        </a>

        @unless ($project->is_published)
            <p class="mb-6 rounded border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-sm text-amber-300">
                Borrador: solo tú puedes ver este proyecto porque has iniciado sesión.
            </p>
        @endunless

        <article id="contenido">
            <p class="font-mono text-sm text-accent">
                {{ collect([$project->year, $project->client])->filter()->implode(' · ') }}
            </p>
            <h1 class="mt-2 font-display text-4xl font-extrabold tracking-tight text-mist-100 sm:text-5xl">{{ $project->title }}</h1>
            <p class="mt-4 text-lg text-mist-300">{{ $project->summary }}</p>

            <x-tags :items="$project->technologies" class="mt-6" />

            @if ($project->demo_url || $project->repo_url)
                <div class="mt-8 flex flex-wrap gap-4">
                    @if ($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" rel="noreferrer noopener"
                           class="inline-flex items-center gap-2 rounded-full bg-accent-strong px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-accent">
                            Ver proyecto en vivo <x-icon name="arrow" class="size-4" />
                        </a>
                    @endif
                    @if ($project->repo_url)
                        <a href="{{ $project->repo_url }}" target="_blank" rel="noreferrer noopener"
                           class="inline-flex items-center gap-2 rounded border border-accent px-5 py-2.5 text-sm font-semibold text-accent transition hover:bg-accent-soft">
                            <x-icon name="GitHub" class="size-4" /> Código fuente
                        </a>
                    @endif
                </div>
            @endif

            @if ($project->imageUrl())
                <img src="{{ $project->imageUrl() }}" alt="Captura de {{ $project->title }}"
                     class="mt-12 w-full rounded-lg border border-ink-700 shadow-2xl shadow-black/40">
            @endif

            @if ($project->description)
                <div class="prose prose-invert mt-12 max-w-none prose-headings:text-mist-100 prose-a:text-accent prose-strong:text-mist-100 prose-p:text-mist-400 prose-li:text-mist-400 prose-li:marker:text-accent">
                    {!! Str::markdown($project->description, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                </div>
            @endif
        </article>

        @if ($others->isNotEmpty())
            <aside class="mt-24 border-t border-ink-700 pt-12">
                <h2 class="mb-6 text-sm font-bold uppercase tracking-widest text-mist-100">Otros proyectos</h2>
                <ul class="grid gap-4 sm:grid-cols-3">
                    @foreach ($others as $other)
                        <li>
                            <a href="{{ route('projects.show', $other) }}" class="block h-full rounded-md border border-ink-700 bg-ink-800/40 p-5 transition hover:-translate-y-1 hover:border-accent/40">
                                <h3 class="font-medium text-mist-100">{{ $other->title }}</h3>
                                <p class="mt-2 line-clamp-3 text-sm">{{ $other->summary }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </div>
</x-layouts.public>
