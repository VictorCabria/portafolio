@props(['name', 'label', 'checked' => false, 'hint' => null])

<label class="flex cursor-pointer items-start gap-3">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked))
           class="peer sr-only">
    <span class="relative mt-0.5 h-5 w-9 shrink-0 rounded-full bg-ink-600 transition peer-checked:bg-accent peer-focus-visible:ring-2 peer-focus-visible:ring-accent/40 after:absolute after:top-0.5 after:left-0.5 after:size-4 after:rounded-full after:bg-mist-100 after:transition peer-checked:after:translate-x-4"></span>
    <span>
        <span class="block text-sm font-medium text-mist-100">{{ $label }}</span>
        @if ($hint)
            <span class="block text-xs text-mist-400">{{ $hint }}</span>
        @endif
    </span>
</label>
