@props(['guesses'])

<div class="w-full max-w-md">
    <h3 class="text-xs font-bold uppercase text-white/50 mb-3 tracking-widest">Tentatives</h3>
    <div class="flex flex-col gap-2">
        @foreach (array_reverse($guesses) as $guess)
        <div @class([ 'flex items-center justify-between rounded-1xl px-4 py-3 border' , 'bg-green-600/20 border-green-500/50'=> $guess['correct'],
            'bg-white/5 border-white/10' => ! $guess['correct'],
            ])>
            <span class="text-white font-medium">{{ $guess['first_name'] }} {{ $guess['last_name'] }}</span>
            <span class="{{ $guess['correct'] ? 'text-green-300' : 'text-red-300' }} text-sm">
                {{ $guess['correct'] ? '✓' : '✗' }}
            </span>
        </div>
        @endforeach
    </div>
</div>