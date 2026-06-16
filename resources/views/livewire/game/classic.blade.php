<div class="min-h-screen flex flex-col items-center px-4 py-12 gap-8">

    {{-- Titre --}}
    <h1 class="text-3xl font-bold tracking-wide uppercase text-decode-pop drop-shadow">
        DecoDLE <span class="text-white/50 text-lg normal-case">— Classique</span>
    </h1>

    @php
    $labels = [
    'class' => 'Classe', 'gender' => 'Genre', 'age' => 'Âge', 'height' => 'Taille',
    'hair_color' => 'Cheveux', 'city' => 'Ville', 'hobby' => 'Hobby', 'specialization' => 'Spé',
    ];
    @endphp

    {{-- Aucune personne du jour --}}

    {{-- Win card --}}
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

    {{-- Intro (tant qu'on n'a pas commencé et pas gagné) --}}
    @if (! $won && count($guesses) === 0)
    <x-game.card class="max-w-lg text-center space-y-3">
        <p class="text-sm uppercase tracking-widest text-decode-pop/70">Mode Classique</p>
        <h2 class="text-2xl font-bold">Devine l'élève de Decode !</h2>
        <p class="text-white/60">Tape n'importe quel prénom pour commencer.</p>
        @if ($this->yesterdayPerson)
        <p class="text-sm text-white/50">
            Hier c'était <span class="text-decode-pop font-semibold">{{ $this->yesterdayPerson->full_name }}</span>
        </p>
        @endif
    </x-game.card>
    @endif

    {{-- Tentatives --}}
    @if (count($guesses) > 0)
    <div class="w-full max-w-5xl overflow-x-auto">
        {{-- Header --}}
        <div class="grid grid-cols-10 gap-2 mb-2 text-xs font-bold uppercase tracking-wider text-white/50">
            <div class="text-center">Prénom</div>
            @foreach ($labels as $label)
            <div class="text-center">{{ $label }}</div>
            @endforeach
        </div>

        @foreach ($guesses as $guess)
        <div class="grid grid-cols-10 gap-2 mb-2" wire:key="guess-{{ $loop->index }}">
            {{-- Photo --}}
            <div class="rounded-xl bg-decode-surface border border-white/10 overflow-hidden aspect-square flex items-center justify-center">
                @if (! empty($guess['photo_path']))
                <img src="{{ route('photos.show', basename($guess['photo_path'])) }}"
                    alt="" class="w-full h-full object-cover">
                @else
                <span class="text-white/30 text-xs">—</span>
                @endif
            </div>
            {{-- Prénom --}}
            <div class="rounded-xl bg-decode-surface border border-white/10 text-white p-3 text-center text-sm font-semibold flex items-center justify-center">
                {{ $guess['first_name'] }}
            </div>

            {{-- Attributs --}}
            @foreach ($guess['comparison'] as $key => $cell)
            <div @class([ 'cell-reveal rounded-xl p-3 text-center text-sm font-semibold text-white flex items-center justify-center gap-1 shadow-md' , 'bg-green-600'=> $cell['status'] === 'exact',
                'bg-amber-500' => in_array($cell['status'], ['higher', 'lower']),
                'bg-red-600/80' => $cell['status'] === 'wrong',
                ])
                style="animation-delay: {{ $loop->index * 120 }}ms;">
                @if ($key === 'height')
                {{ intdiv($cell['value'], 100) }}m{{ str_pad($cell['value'] % 100, 2, '0', STR_PAD_LEFT) }}
                @else
                {{ $cell['value'] }}
                @endif
                @if ($cell['status'] === 'higher') ↑ @endif
                @if ($cell['status'] === 'lower') ↓ @endif
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif

    {{-- Input + suggestions --}}
    @unless ($won)
    <form wire:submit="submitGuess" class="relative w-full max-w-md">
        <x-game.input
            wire:model.live.debounce.150ms="input"
            placeholder="Tape un prénom..."
            autofocus />

        @if (strlen($input) > 0 && $this->suggestions->isNotEmpty())
        <div class="absolute z-20 mt-2 w-full rounded-2xl border border-white/10 bg-decode-surface/95 backdrop-blur shadow-xl overflow-hidden">
            @foreach ($this->suggestions as $person)
            <button type="button"
                wire:click="pickSuggestion({{ $person->id }})"
                class="block w-full text-left px-4 py-2 text-white/90 hover:bg-decode-violet/30 transition">
                {{ $person->first_name }} {{ $person->last_name }}
            </button>
            @endforeach
        </div>
        @endif
    </form>
    @endunless

    {{-- Rejouer --}}
    <x-game.restart-button />
</div>