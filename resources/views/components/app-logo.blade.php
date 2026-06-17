@props([
'sidebar' => false,
])

@if($sidebar)
<flux:sidebar.brand name="DecoDLE" {{ $attributes }}>
    <x-slot name="logo">
        <x-app-logo-icon class="h-7 w-auto" />
    </x-slot>
</flux:sidebar.brand>
@else
<flux:brand name="DecoDLE" {{ $attributes }}>
    <x-slot name="logo">
        <x-app-logo-icon class="h-7 w-auto" />
    </x-slot>
</flux:brand>
@endif