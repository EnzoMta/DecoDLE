<div class="p-8">
    <h1 class="text-2xl font-bold mb-4">DecoDLE - Mode Classique</h1>

    @if ($target)
    {{-- TODO debug : à enlever après --}}
    <p class="text-sm text-gray-500">
        🎯 (debug) Personne du jour : {{ $target->full_name }}
    </p>
    @else
    <p class="text-red-500">Aucune personne n'a été tirée pour aujourd'hui.</p>
    @endif
    <form wire:submit="submitGuess" class="relative mt-6 max-w-md">
        <flux:input
            wire:model.live.debounce.150ms="input"
            placeholder="Entre un prénom..."
            autocomplete="off"
            autofocus />

        @if (strlen($input) > 0 && $this->suggestions->isNotEmpty())
        <div class="absolute z-10 mt-1 w-full bg-zinc-900 border border-zinc-700 rounded shadow-lg overflow-hidden">
            @foreach ($this->suggestions as $person)
            <button
                type="button"
                wire:click="pickSuggestion({{ $person->id }})"
                class="block w-full text-left px-3 py-2 hover:bg-zinc-700 transition">
                {{ $person->first_name }} {{ $person->last_name }}
            </button>
            @endforeach
        </div>
        @endif
    </form>
</div>