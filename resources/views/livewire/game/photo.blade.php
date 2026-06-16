<div class="min-h-screen flex flex-col items-center px-4 py-12">
    <h1 class="text-2xl font-bold mb-2">DecoDLE - Mode Photo</h1>

    @unless ($won)
    <div class="max-w-xl mx-auto text-center mb-6">
        <p class="text-zinc-400 text-sm mb-2">
            Devine l'élève caché dans la photo ! Elle devient de moins en moins floue à chaque mauvaise tentative.
        </p>
        @if ($this->yesterdayPerson)
        <p class="mt-2 text-zinc-500 text-sm">
            L'élève d'hier était
            <span class="text-red-400 font-bold">{{ $this->yesterdayPerson->full_name }}</span>
        </p>
        @endif
    </div>
    @endunless

    {{-- Photo --}}
    @if ($target && $target->photo_path)
    <div class="relative mb-4">
        <img
            wire:key="photo-{{ $restartCount }}"
            src="{{ route('photos.show', basename($target->photo_path)) }}"
            alt="Qui est-ce ?"
            class="w-64 h-64 object-cover rounded-2xl shadow-2xl border-2 border-zinc-600 transition-all duration-700"
            style="filter: {{ $this->blurFilter }}" />
        @unless ($won)
        <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-white/10 pointer-events-none"></div>
        @endunless
    </div>

    {{-- Blur level indicator --}}
    @unless ($won)
    <div class="flex gap-2 mb-6">
        @for ($i = 0; $i < 5; $i++)
            <div @class([ "w-3 h-3 rounded-full transition-all duration-500" , 'bg-red-500'=> $i < $this->blurStep,
                'bg-zinc-600' => $i >= $this->blurStep,
                ])></div>
    @endfor
</div>
<p class="text-xs text-zinc-500 mb-6 tracking-wide uppercase">
    {{ $this->blurStep }} / 5 indice(s) révélé(s)
</p>
@endunless
@elseif ($target && ! $target->photo_path)
<div class="w-64 h-64 flex items-center justify-center bg-zinc-800 rounded-2xl border-2 border-dashed border-zinc-600 mb-6 text-zinc-500 text-sm">
    Aucune photo disponible
</div>
@endif

@if (! $target)
<p class="text-red-500 text-center mt-4">
    ⚠️ Aucune personne n'a été tirée pour aujourd'hui.
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

{{-- Guesses list --}}
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
