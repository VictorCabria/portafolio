@php($editing = $experience->exists)

<x-layouts.admin :title="$editing ? 'Editar experiencia' : 'Nueva experiencia'">
    <form method="POST"
          action="{{ $editing ? route('admin.experiences.update', $experience) : route('admin.experiences.store') }}"
          class="card grid max-w-3xl gap-5 sm:grid-cols-2">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-field name="role" label="Cargo" :value="$experience->role" required />
        <x-field name="company" label="Empresa" :value="$experience->company" required />
        <x-field name="period" label="Periodo" :value="$experience->period" required placeholder="2023 — Actualidad" />
        <x-field name="company_url" label="Web de la empresa" type="url" :value="$experience->company_url" />
        <x-field name="description" label="Qué hiciste" type="textarea" rows="4" :value="$experience->description" class="sm:col-span-2" />
        <x-field name="technologies" label="Tecnologías" :value="App\Support\Tags::join($experience->technologies)"
                 hint="Separadas por comas." />
        <x-field name="sort_order" label="Orden" type="number" :value="$experience->sort_order ?? 0" min="0"
                 hint="Menor número = aparece antes." />

        <div class="flex gap-3 sm:col-span-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Añadir' }}</button>
            <a href="{{ route('admin.experiences.index') }}" class="btn-ghost">Cancelar</a>
        </div>
    </form>
</x-layouts.admin>
