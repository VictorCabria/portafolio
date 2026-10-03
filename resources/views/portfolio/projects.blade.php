<x-layouts.public :profile="$profile" :title="'Proyectos — '.$profile->name">
    <div class="mx-auto min-h-screen max-w-screen-xl px-6 pt-32 pb-24 md:px-12 lg:px-24">
        <a href="{{ route('home') }}" class="group mb-2 inline-flex items-center font-semibold leading-tight text-accent">
            <x-icon name="back" class="mr-1 size-4 transition-transform group-hover:-translate-x-2" />
            {{ $profile->name }}
        </a>
        <h1 class="font-display text-4xl font-extrabold tracking-tight text-mist-100 sm:text-5xl">Todos los proyectos</h1>

        <table id="contenido" class="mt-12 w-full border-collapse text-left">
            <thead class="sticky top-18 z-10 border-b border-ink-600 bg-ink-900/75 px-6 py-5 backdrop-blur">
                <tr>
                    <th class="py-4 pr-8 text-sm font-semibold text-mist-100">Año</th>
                    <th class="py-4 pr-8 text-sm font-semibold text-mist-100">Proyecto</th>
                    <th class="hidden py-4 pr-8 text-sm font-semibold text-mist-100 lg:table-cell">Cliente</th>
                    <th class="hidden py-4 pr-8 text-sm font-semibold text-mist-100 lg:table-cell">Tecnologías</th>
                    <th class="hidden py-4 pr-8 text-sm font-semibold text-mist-100 sm:table-cell">Enlaces</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr class="border-b border-ink-700/60 last:border-none">
                        <td class="py-4 pr-4 align-top text-sm">
                            <div class="translate-y-px">{{ $project->year }}</div>
                        </td>
                        <td class="py-4 pr-4 align-top font-semibold leading-snug text-mist-100">
                            <a href="{{ route('projects.show', $project) }}" class="group inline-flex items-baseline hover:text-accent">
                                {{ $project->title }}
                                <x-icon name="arrow" class="ml-1 size-3.5 transition-transform group-hover:-translate-y-1 group-hover:translate-x-1" />
                            </a>
                        </td>
                        <td class="hidden py-4 pr-4 align-top text-sm lg:table-cell">{{ $project->client ?? '—' }}</td>
                        <td class="hidden py-4 pr-4 align-top lg:table-cell">
                            <x-tags :items="$project->technologies" class="-translate-y-1.5" />
                        </td>
                        <td class="hidden py-4 align-top sm:table-cell">
                            <ul class="space-y-1 text-sm">
                                @if ($project->demo_url)
                                    <li><a href="{{ $project->demo_url }}" target="_blank" rel="noreferrer noopener" class="hover:text-accent">{{ parse_url($project->demo_url, PHP_URL_HOST) }}</a></li>
                                @endif
                                @if ($project->repo_url)
                                    <li><a href="{{ $project->repo_url }}" target="_blank" rel="noreferrer noopener" class="hover:text-accent">Código fuente</a></li>
                                @endif
                            </ul>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-sm">Todavía no hay proyectos publicados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.public>
