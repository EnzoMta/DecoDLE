@props(['glow' => false])

<div {{ $attributes->class([
    'w-full max-w-md rounded-3xl border p-6 backdrop-blur-md shadow-2xl shadow-black/40
     bg-gradient-to-br from-decode-surface-2/70 to-decode-surface/40',
    'border-white/10'                                      => ! $glow,
    'border-decode-violet/50 ring-2 ring-decode-violet/40' => $glow,
]) }}>
    {{ $slot }}
</div>