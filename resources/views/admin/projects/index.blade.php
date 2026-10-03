<x-layouts.admin title="Proyectos">
    <x-slot:actions>
        <a href="{{ route('admin.projects.create') }}" class="btn-primary">+ Nuevo proyecto</a>
    </x-slot:actions>

    @if ($projects->isEmpty())
        <div class="card text-center">
            <p class="text-mist-100">Todavía no tienes proyectos.</p>
            <p class="mt-1 text-sm">Crea el primero para mostrarlo en tu portafolio.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-ink-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-900 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3">Proyecto</th>
                        <th class="hidden px-4 py-3 md:table-cell">Año</th>
                        <th class="hidden px-4 py-3 sm:table-cell">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-700 bg-ink-800/30">
                    @foreach ($projects as $project)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($project->imageUrl())
                                        <img src="{{ $project->imageUrl() }}" alt="" class="hidden h-10 w-16 rounded object-cover sm:block">
                                    @else
                                        <div class="hidden h-10 w-16 rounded bg-ink-700 sm:block"></div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-mist-100">{{ $project->title }}</p>
                                        <p class="max-w-md truncate text-xs">{{ $project->summary }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden px-4 py-3 md:table-cell">{{ $project->year }}</td>
                            <td class="hidden px-4 py-3 sm:table-cell">
                                <div class="flex flex-wrap gap-1.5">
                                    @if ($project->is_published)
                                        <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-xs text-emerald-300">Publicado</span>
                                    @else
                                        <span class="rounded-full bg-amber-500/10 px-2 py-0.5 text-xs text-amber-300">Borrador</span>
                                    @endif
                                    @if ($project->is_featured)
                                        <span class="rounded-full bg-accent-soft px-2 py-0.5 text-xs text-accent">Destacado</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn-ghost px-2.5 py-1 text-xs">Ver</a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn-ghost px-2.5 py-1 text-xs">Editar</a>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                          onsubmit="return confirm('¿Eliminar «{{ addslashes($project->title) }}»? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger px-2.5 py-1 text-xs">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="form-hint mt-3">Los proyectos se ordenan por el campo «Orden» (menor primero) y luego por año.</p>
    @endif
</x-layouts.admin>
