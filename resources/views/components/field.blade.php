{{-- Campo de formulario con etiqueta, ayuda y error --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null, 'rows' => null])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>

    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows ?? 4 }}"
                  {{ $attributes->except('class')->merge(['class' => 'form-input']) }}>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
               {{ $attributes->except('class')->merge(['class' => 'form-input']) }}>
    @endif

    @if ($hint)
        <p class="form-hint">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
    @enderror
</div>
