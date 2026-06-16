<div class="min-h-screen flex flex-col items-center px-4 py-12 gap-8">

    <h1 class="text-3xl font-bold tracking-wide uppercase text-decode-pop drop-shadow">
        DecoDLE <span class="text-white/50 text-lg normal-case">— Emoji</span>
    </h1>

    {{-- Win --}}
    @if ($won)
    <x-game.card glow class="max-w-md text-center space-y-4">
        <img src="{{ asset('gifs/Winner.gif') }}" alt="Victoire !" class="w-48 mx-auto rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-decode-pop">Bien joué !</h2>
        <p class="text-white/80">
            Tu as trouvé <span class="font-bold text-white">{{ $target?->full_name ?? 'cette personne' }}</span><br>
            en <span class="text-decode-pop font-bold">{{ count($guesses) }}</span> tentative(s).
        </p>
    </x-game.card>
    @endif

    {{-- Intro --}}
    @if (! $won && count($guesses) === 0)
    <x-game.card class="max-w-lg text-center space-y-3">
        <p class="text-sm uppercase tracking-widest text-decode-pop/70">Mode Emoji</p>
        <h2 class="text-2xl font-bold">Devine l'élève caché derrière ces emojis !</h2>
        <p class="text-white/60">Un nouvel indice se dévoile à chaque mauvaise tentative.</p>
        @if ($this->yesterdayPerson)
        <p class="text-sm text-white/50">
            Hier c'était <span class="text-decode-pop font-semibold">{{ $this->yesterdayPerson->full_name }}</span>
        </p>
        @endif
    </x-game.card>
    @endif

    {{-- Emojis --}}
    @if ($target)
    <div class="flex flex-col items-center gap-4">
        <div class="flex gap-4">
            @foreach ([1, 2, 3, 4] as $i)
            @if ($i <= $this->revealedCount)
                <div class="cell-reveal w-20 h-20 flex items-center justify-center text-5xl rounded-2xl bg-decode-surface border border-white/15 shadow-lg">
                    {{ $target->{'emoji_' . $i} ?? '❓' }}
                </div>
                @else
                <div class="w-20 h-20 flex items-center justify-center text-3xl rounded-2xl bg-white/5 border-2 border-dashed border-white/15 text-white/30 select-none">
                    ?
                </div>
                @endif
                @endforeach
        </div>
        <p class="text-xs text-white/50 tracking-widest uppercase">
            {{ $this->revealedCount }} / 4 indice(s) révélé(s)
        </p>
    </div>
    @endif

    {{-- Input --}}
    @unless ($won)
    <form wire:submit="submitGuess" class="relative w-full max-w-md">
        <x-game.input wire:model.live.debounce.150ms="input" placeholder="Tape un prénom..." autofocus />

        @error('input')
        <p class="text-red-300 text-sm mt-2 text-center">{{ $message }}</p>
        @enderror

        @if (strlen($input) > 0 && $this->suggestions->isNotEmpty())
        <div class="absolute z-20 mt-2 w-full rounded-2xl border border-white/10 bg-decode-surface/95 backdrop-blur shadow-xl overflow-hidden">
            @foreach ($this->suggestions as $person)
            <button type="button" wire:click="pickSuggestion({{ $person->id }})"
                class="block w-full text-left px-4 py-2 text-white/90 hover:bg-decode-violet/30 transition">
                {{ $person->first_name }} {{ $person->last_name }}
            </button>
            @endforeach
        </div>
        @endif
    </form>
    @endunless

    {{-- Tentatives --}}
    @if (count($guesses) > 0)
    <x-game.guess-list :guesses="$guesses" />
    @endif

    <x-game.restart-button />
</div>