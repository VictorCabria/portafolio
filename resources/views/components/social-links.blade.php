@props(['profile'])

@php
    $links = array_filter([
        'GitHub' => $profile->github_url,
        'LinkedIn' => $profile->linkedin_url,
        'X / Twitter' => $profile->twitter_url,
        'Sitio web' => $profile->website_url,
        'Email' => $profile->email ? 'mailto:'.$profile->email : null,
    ]);
@endphp

<ul {{ $attributes->merge(['class' => 'flex items-center gap-5']) }} aria-label="Redes sociales">
    @foreach ($links as $label => $url)
        <li>
            <a href="{{ $url }}" target="_blank" rel="noreferrer noopener" title="{{ $label }}"
               class="block text-mist-400 transition hover:-translate-y-0.5 hover:text-mist-100">
                <span class="sr-only">{{ $label }}</span>
                <x-icon :name="$label" class="size-6" />
            </a>
        </li>
    @endforeach
</ul>
