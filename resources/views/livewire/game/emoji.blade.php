<div class="min-h-screen flex flex-col items-center px-4 py-12">
    <h1 class="text-2xl font-bold mb-2">DecoDLE - Mode Emoji</h1>

    @if (! $target)
    <p class="text-red-500 text-center mt-4">
        ⚠️ Aucune personne n'a été tirée pour aujourd'hui.
    </p>
    @endif

    @unless ($won)
    <div class="max-w-xl mx-auto text-center mb-6">
        <p class="text-zinc-400 text-sm mb-2">
            Devine l'élève caché derrière ces emojis ! Un nouvel indice se dévoile à chaque mauvaise tentative.
        </p>
        @if ($this->yesterdayPerson)
        <p class="mt-2 text-zinc-500 text-sm">
            L'élève d'hier était
            <span class="text-red-400 font-bold">{{ $this->yesterdayPerson->full_name }}</span>
        </p>
        @endif
    </div>
    @endunless

    {{-- Emoji display --}}
    @if ($target)
    <div class="flex gap-4 my-8">
        @foreach ([1, 2, 3, 4] as $i)
        @if ($i <= $this->revealedCount)
            <div
                class="cell-reveal w-20 h-20 flex items-center justify-center text-5xl rounded-2xl bg-zinc-700 shadow-lg border-2 border-zinc-500">
                {{ $target->{'emoji_' . $i} ?? '?' }}
            </div>
            @else
            <div class="w-20 h-20 flex items-center justify-center text-3xl rounded-2xl bg-zinc-800 border-2 border-dashed border-zinc-600 text-zinc-500 select-none">
                ?
            </div>
            @endif
            @endforeach
    </div>
    <p class="text-xs text-zinc-500 mb-6 tracking-wide uppercase">
        {{ $this->revealedCount }} / 4 indice(s) révélé(s)
    </p>
    @endif

    {{-- Win callout --}}
    @if ($won)
    <img src="{{ asset('gifs/Winner.gif') }}"
        alt="Victoire !"
        class="w-64 mx-auto mt-4 rounded-xl shadow-lg" />
    <flux:callout variant="success" icon="check-circle" class="w-full max-w-md mb-4">
        <flux:callout.heading>Félicitations !!!</flux:callout.heading>
        <flux:callout.text>
            Tu as trouvé {{ $target->full_name }} en {{ count($guesses) }} tentative(s).
        </flux:callout.text>
    </flux:callout>
    @endif

    {{-- Input --}}
    @unless ($won)
    <form wire:submit="submitGuess" class="relative w-full max-w-md">
        <flux:input
            wire:model.live.debounce.150ms="input"
            placeholder="Entre un prénom..."
            autocomplete="off"
            autofocus />

        @error('input')
        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
        @enderror

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

    {{-- Wrong guesses list --}}
    @if (count($guesses) > 0)
    <div class="w-full max-w-md mt-8">
        <h3 class="text-xs font-bold uppercase text-zinc-400 mb-3 tracking-wider">Tentatives :</h3>
        <div class="flex flex-col gap-2">
            @foreach (array_reverse($guesses) as $guess)
            <div @class([ 'flex items-center justify-between rounded-lg px-4 py-3' , 'bg-green-700 border border-green-500'=> $guess['correct'],
                'bg-zinc-800 border border-zinc-700' => ! $guess['correct'],
                ])>
                <span class="text-white font-medium">{{ $guess['first_name'] }} {{ $guess['last_name'] }}</span>
                @if ($guess['correct'])
                <span class="text-green-300 text-sm">✓</span>
                @else
                <span class="text-red-400 text-sm">✗</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-10">
        <x-game.restart-button />
    </div>
</div>