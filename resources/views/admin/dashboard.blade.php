<x-layouts.admin :title="'Hola, '.Str::before($profile->name, ' ')">
    <x-slot:actions>
        <a href="{{ route('admin.projects.create') }}" class="btn-primary">+ Nuevo proyecto</a>
    </x-slot:actions>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as $label => $value)
            <div class="card">
                <p class="text-xs font-medium uppercase tracking-wider">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-mist-100">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <section class="card lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold text-mist-100">Últimos proyectos</h2>
                <a href="{{ route('admin.projects.index') }}" class="text-sm text-accent hover:underline">Ver todos</a>
            </div>
            <ul class="divide-y divide-ink-700">
                @forelse ($latest as $project)
                    <li class="flex items-center justify-between gap-4 py-3">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-mist-100">{{ $project->title }}</p>
                            <p class="truncate text-xs">{{ $project->summary }}</p>
                        </div>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn-ghost shrink-0 px-3 py-1 text-xs">Editar</a>
                    </li>
                @empty
                    <li class="py-3 text-sm">Aún no has creado proyectos.</li>
                @endforelse
            </ul>
        </section>

        <section class="card">
            <h2 class="mb-4 font-semibold text-mist-100">Accesos rápidos</h2>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('admin.profile.edit') }}" class="hover:text-accent">→ Editar mi presentación</a></li>
                <li><a href="{{ route('admin.experiences.create') }}" class="hover:text-accent">→ Añadir experiencia</a></li>
                <li><a href="{{ route('home') }}" target="_blank" class="hover:text-accent">→ Ver el portafolio público</a></li>
            </ul>
        </section>
    </div>
</x-layouts.admin>
