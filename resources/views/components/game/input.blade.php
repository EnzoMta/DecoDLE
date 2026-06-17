@props(['placeholder' => 'Tape un prénom...'])

<div class="relative w-full max-w-md">
    <input
        {{ $attributes->merge(['class' =>
            'w-full rounded-1xl bg-white text-zinc-900 placeholder-zinc-400
             px-6 py-4 pr-14 text-lg shadow-lg
             focus:outline-none focus:ring-4 focus:ring-decode-violet/50']) }}
        placeholder="{{ $placeholder }}"
        autocomplete="off" />

    <button type="submit"
        class="absolute right-2 top-1/2 -translate-y-1/2 grid place-items-center
               size-10 rounded-full text-decode-violet hover:bg-decode-violet/10 transition">
        <flux:icon name="chevron-right" class="size-6" />
    </button>
</div>