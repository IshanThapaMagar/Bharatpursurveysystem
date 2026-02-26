@props(['label', 'value'])

<div class="flex flex-col gap-0.5">
    <span class="text-xs text-gray-400 font-medium leading-tight">{{ $label }}</span>
    <span class="text-sm text-gray-800 font-semibold">{{ $value ?: '—' }}</span>
</div>
