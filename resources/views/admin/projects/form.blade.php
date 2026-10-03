@php($editing = $project->exists)

<x-layouts.admin :title="$editing ? 'Editar proyecto' : 'Nuevo proyecto'">
    <form method="POST" enctype="multipart/form-data"
          action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
          class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <section class="card space-y-5">
                <x-field name="title" label="Título" :value="$project->title" required />
                <x-field name="summary" label="Resumen" type="textarea" rows="2" :value="$project->summary" required maxlength="300"
                         hint="Máximo 300 caracteres. Se muestra en las tarjetas del portafolio." />
                <x-field name="description" label="Descripción detallada" type="textarea" rows="14" :value="$project->description"
                         class="font-mono" hint="Admite Markdown: ## títulos, **negrita**, listas con -, enlaces [texto](url)." />
            </section>

            <section class="card grid gap-5 sm:grid-cols-2">
                <x-field name="technologies" label="Tecnologías" :value="App\Support\Tags::join($project->technologies)" class="sm:col-span-2"
                         hint="Separadas por comas." />
                <x-field name="demo_url" label="URL de la demo" type="url" :value="$project->demo_url" placeholder="https://" />
                <x-field name="repo_url" label="URL del repositorio" type="url" :value="$project->repo_url" placeholder="https://github.com/…" />
                <x-field name="client" label="Cliente / empresa" :value="$project->client" />
                <x-field name="year" label="Año" type="number" :value="$project->year" min="1990" max="2100" />
            </section>
        </div>

        <div class="space-y-6">
            <section class="card space-y-5">
                <x-toggle name="is_published" label="Publicado" :checked="$project->is_published"
                          hint="Si lo desactivas queda como borrador." />
                <x-toggle name="is_featured" label="Destacado" :checked="$project->is_featured"
                          hint="Aparece en la página principal." />
                <x-field name="sort_order" label="Orden" type="number" :value="$project->sort_order ?? 0" min="0" />
                <x-field name="slug" label="URL amigable (slug)" :value="$project->slug"
                         hint="Opcional. Se genera a partir del título." />
            </section>

            <section class="card">
                <p class="form-label">Imagen de portada</p>
                <img id="image-preview" src="{{ $project->imageUrl() }}" alt=""
                     @class(['mb-3 aspect-video w-full rounded-md border border-ink-700 object-cover', 'hidden' => ! $project->imageUrl()])>
                <input type="file" name="image" accept="image/*" data-preview="image-preview"
                       class="w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-ink-700 file:px-3 file:py-1.5 file:text-mist-100 hover:file:bg-ink-600">
                <p class="form-hint">JPG, PNG o WebP de hasta 4 MB. Ideal 1600×900.</p>
                @error('image') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                @if ($project->image)
                    <label class="mt-3 flex items-center gap-2 text-xs"><input type="checkbox" name="remove_image" value="1"> Quitar imagen</label>
                @endif
            </section>

            <div class="flex gap-3">
                <button class="btn-primary flex-1">{{ $editing ? 'Guardar cambios' : 'Crear proyecto' }}</button>
                <a href="{{ route('admin.projects.index') }}" class="btn-ghost">Cancelar</a>
            </div>
        </div>
    </form>
</x-layouts.admin>
