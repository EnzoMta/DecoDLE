<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Enums\GameType;
use App\Models\DailyPerson;
use App\Models\Person;
use Livewire\Attributes\Computed;


class Classic extends Component
{
    public ?Person $target = null;
    public string $input = '';

    public function mount(): void
    {
        $this->target = DailyPerson::forToday(GameType::CLASSIC)
            ->first()
            ?->person;
    }
    #[Computed]
    public function suggestions()
    {
        if (strlen($this->input) < 1) {
            return collect();
        }

        return Person::where('first_name', 'ilike', $this->input . '%')
            ->orderBy('first_name')
            ->limit(5)
            ->get();
    }

    public function pickSuggestion(int $personId): void
    {
        $person = Person::find($personId);
        if (! $person) return;

        $this->input = $person->first_name;
        $this->submitGuess();
    }

    public function submitGuess(): void
    {
        dd($this->input);
    }

    public function render()
    {

        return view('livewire.game.classic');
    }
}
