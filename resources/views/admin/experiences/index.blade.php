<x-layouts.admin title="Experiencia">
    <x-slot:actions>
        <a href="{{ route('admin.experiences.create') }}" class="btn-primary">+ Añadir experiencia</a>
    </x-slot:actions>

    <ul class="space-y-3">
        @forelse ($experiences as $experience)
            <li class="card flex flex-wrap items-start justify-between gap-4 p-5">
                <div class="min-w-0">
                    <p class="font-mono text-xs uppercase tracking-wide">{{ $experience->period }}</p>
                    <p class="mt-1 font-medium text-mist-100">{{ $experience->role }} · {{ $experience->company }}</p>
                    <x-tags :items="$experience->technologies" class="mt-2" />
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('admin.experiences.edit', $experience) }}" class="btn-ghost px-2.5 py-1 text-xs">Editar</a>
                    <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}"
                          onsubmit="return confirm('¿Eliminar esta experiencia?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger px-2.5 py-1 text-xs">Eliminar</button>
                    </form>
                </div>
            </li>
        @empty
            <li class="card text-center text-sm">Aún no has añadido experiencia laboral.</li>
        @endforelse
    </ul>
</x-layouts.admin>
