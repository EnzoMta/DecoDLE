<div class="min-h-screen flex flex-col items-center px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">DecoDLE - Mode Classique</h1>
    @if (! $target)
    <p class="text-red-500 text-center mt-4">
        ⚠️ Aucune personne n'a été tirée pour aujourd'hui.
    </p>
    @endif
    @php
    $labels = [
    'class' => 'Classe',
    'gender' => 'Genre',
    'age' => 'Âge',
    'height' => 'Taille',
    'hair_color' => 'Cheveux',
    'city' => 'Ville',
    'hobby' => 'Hobby',
    'specialization' => 'Spé',
    ];
    @endphp
    @if (count($guesses) > 0)
    <div class="w-full max-w-5xl mt-6 overflow-x-auto">
        {{-- Header --}}
        <div class="grid grid-cols-9 gap-2 mb-2 text-xs font-bold uppercase text-zinc-400">
            <div class="text-center">Prénom</div>
            @foreach ($labels as $label)
            <div class="text-center">{{ $label }}</div>
            @endforeach
        </div>

        {{-- Tentatives --}}
        @foreach ($guesses as $index => $guess)
        <div class="grid grid-cols-9 gap-2 mb-2" wire:key="guess-{{ $loop->index}}">
            {{-- Cellule prénom --}}
            <div class="rounded-lg bg-zinc-800 text-white p-3 text-center text-sm font-semibold flex items-center justify-center">
                {{ $guess['first_name'] }}
            </div>

            {{-- Cellules attributs --}}
            @foreach ($guess['comparison'] as $key => $cell)
            <div
                @class([ 'cell-reveal rounded-lg p-3 text-center text-sm font-semibold text-white flex items-center justify-center shadow-md' , 'bg-green-600'=> $cell['status'] === 'exact',
                'bg-yellow-500' => in_array($cell['status'], ['higher', 'lower']),
                'bg-red-600' => $cell['status'] === 'wrong',
                ])
                style="animation-delay: {{ $loop->index * 300 }}ms;"
                >
                {{ $cell['value'] }}
                @if ($cell['status'] === 'higher') ↑ @endif
                @if ($cell['status'] === 'lower') ↓ @endif
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif

    @if ($won)
    <img src="{{ asset('gifs/Winner.gif') }}"
        alt="Victoire !"
        class="w-64 mx-auto mt-4 rounded-xl shadow-lg" />
    <flux:callout variant="success" icon="check-circle" class="w-full max-w-md mt-6">
        <flux:callout.heading>Félicitations !!!</flux:callout.heading>
        <flux:callout.text>
            Tu as trouvé {{ $target?->full_name ?? 'cette personne' }} en {{ count($guesses) }} tentative(s).
        </flux:callout.text>
    </flux:callout>
    @endif
    @unless ($won)
    <div class="max-w-3xl mx-auto text-center mb-8">
        <h1 class="text-3xl font-bold mb-3">
            DEVINE L'ÉLÈVE DE DECODE D'AUJOURD'HUI !
        </h1>
        <p class="text-zinc-400 mb-4">
            Tape n'importe quel prénom pour commencer.
        </p>

        @if (! $won)
        <p class="text-sm text-red-400 font-medium">
            {{ $this->winnersToday }} {{ $this->winnersToday > 1 ? 'personnes ont' : 'personne a' }} déjà trouvé !
        </p>
        @endif

        @if ($this->yesterdayPerson)
        <p class="mt-4 text-zinc-500">
            L'élève d'hier était
            <span class="text-red-400 font-bold">{{ $this->yesterdayPerson->full_name }}</span>
        </p>
        @endif
    </div>
    <form wire:submit="submitGuess" class="relative w-full max-w-md mt-6">
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
    @endunless

    <div class="mt-10">
        <x-game.restart-button />
    </div>
</div>