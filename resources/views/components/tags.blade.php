@props(['items' => []])

@if (! empty($items))
    <ul {{ $attributes->merge(['class' => 'flex flex-wrap gap-2']) }} aria-label="Tecnologías">
        @foreach ($items as $item)
            <li class="rounded-full bg-accent-soft px-3 py-1 text-xs font-medium leading-5 text-accent">{{ $item }}</li>
        @endforeach
    </ul>
@endif
