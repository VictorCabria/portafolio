<x-layouts.admin title="Mi perfil">
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="card">
            <h2 class="mb-1 font-semibold text-mist-100">Presentación</h2>
            <p class="mb-6 text-sm">Lo primero que verán los visitantes en la columna izquierda.</p>

            <div class="mb-6 flex items-center gap-5">
                @if ($profile->avatarUrl())
                    <img id="avatar-preview" src="{{ $profile->avatarUrl() }}" alt="" class="size-20 rounded-full object-cover">
                @else
                    <img id="avatar-preview" alt="" class="hidden size-20 rounded-full object-cover">
                    <div class="grid size-20 place-items-center rounded-full bg-ink-700 font-mono text-accent">{{ $profile->initials() }}</div>
                @endif
                <div class="space-y-2">
                    <input type="file" name="avatar" accept="image/*" data-preview="avatar-preview"
                           class="text-sm file:mr-3 file:rounded-md file:border-0 file:bg-ink-700 file:px-3 file:py-1.5 file:text-mist-100 hover:file:bg-ink-600">
                    @if ($profile->avatar)
                        <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="remove_avatar" value="1"> Quitar foto</label>
                    @endif
                    @error('avatar') <p class="text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-field name="name" label="Nombre completo" :value="$profile->name" required />
                <x-field name="role" label="Cargo / especialidad" :value="$profile->role" required />
                <x-field name="tagline" label="Frase corta" :value="$profile->tagline" class="sm:col-span-2"
                         hint="Una línea que resuma lo que haces." />
                <x-field name="about" label="Sobre mí" type="textarea" rows="8" :value="$profile->about" class="sm:col-span-2"
                         hint="Separa los párrafos con una línea en blanco." />
                <x-field name="skills" label="Tecnologías" :value="App\Support\Tags::join($profile->skills)" class="sm:col-span-2"
                         hint="Separadas por comas: PHP, Laravel, Vue…" />
                <x-field name="location" label="Ubicación" :value="$profile->location" />
                <x-field name="email" label="Email de contacto" type="email" :value="$profile->email" />
            </div>

            <div class="mt-6">
                <x-toggle name="available_for_work" label="Disponible para nuevos proyectos" :checked="$profile->available_for_work"
                          hint="Muestra una insignia verde bajo tu nombre." />
            </div>
        </section>

        <section class="card">
            <h2 class="mb-6 font-semibold text-mist-100">Enlaces</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-field name="github_url" label="GitHub" type="url" :value="$profile->github_url" placeholder="https://github.com/usuario" />
                <x-field name="linkedin_url" label="LinkedIn" type="url" :value="$profile->linkedin_url" placeholder="https://linkedin.com/in/usuario" />
                <x-field name="twitter_url" label="X / Twitter" type="url" :value="$profile->twitter_url" />
                <x-field name="website_url" label="Sitio web" type="url" :value="$profile->website_url" />
                <x-field name="cv_url" label="Enlace a tu CV" type="url" :value="$profile->cv_url" class="sm:col-span-2"
                         hint="Por ejemplo, un PDF en Google Drive. Aparece como botón «Descargar CV»." />
            </div>
        </section>

        <div class="flex justify-end">
            <button class="btn-primary">Guardar cambios</button>
        </div>
    </form>
</x-layouts.admin>
