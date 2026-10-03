<!DOCTYPE html>
<html lang="es" class="bg-ink-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Iniciar sesión · Portafolio</title>
    <x-fonts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-ink-950 px-6 font-sans text-mist-400 antialiased">
    <div class="spotlight pointer-events-none fixed inset-0"></div>

    <main class="relative w-full max-w-sm">
        <p class="mb-8 text-center font-mono text-sm text-accent">&lt;portafolio /&gt;</p>

        <form method="POST" action="{{ route('login') }}" class="card space-y-5 bg-ink-900/80 backdrop-blur">
            @csrf
            <div>
                <h1 class="text-xl font-bold text-mist-100">Bienvenido de nuevo</h1>
                <p class="mt-1 text-sm">Accede para administrar tu portafolio.</p>
            </div>

            <x-field name="email" label="Correo electrónico" type="email" required autofocus autocomplete="username" />
            <x-field name="password" label="Contraseña" type="password" required autocomplete="current-password" />

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" class="rounded border-ink-600 bg-ink-950 text-accent focus:ring-accent/30">
                Recordarme
            </label>

            <button class="btn-primary w-full">Entrar</button>
        </form>

        <a href="{{ route('home') }}" class="mt-6 block text-center text-sm hover:text-accent">← Volver al portafolio</a>
    </main>
</body>
</html>
