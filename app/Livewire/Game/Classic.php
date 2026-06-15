<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Enums\GameType;
use App\Models\DailyPerson;
use App\Models\Person;
use Livewire\Attributes\Computed;
use App\Models\Score;



class Classic extends Component
{
    public ?Person $target = null;
    public string $input = '';
    public bool $won = false;
    public array $guesses = [];


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
        $alreadyGuessed = collect($this->guesses)
            ->pluck('first_name')
            ->toArray();

        return Person::where('first_name', 'ilike', $this->input . '%')
            ->whereNotIn('first_name', $alreadyGuessed)
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
    private function compareGuess(Person $guess, Person $target): array
    {
        $guessAttrs  = $guess->getComparableAttributes();
        $targetAttrs = $target->getComparableAttributes();

        $numeric = ['age', 'height'];
        $result  = [];

        foreach ($guessAttrs as $key => $value) {
            $targetValue = $targetAttrs[$key];

            if ($value === $targetValue) {
                $status = 'exact';
            } elseif (in_array($key, $numeric, true)) {
                $status = $value < $targetValue ? 'higher' : 'lower';
            } else {
                $status = 'wrong';
            }

            $result[$key] = [
                'value'  => $value,
                'status' => $status,
            ];
        }

        return $result;
    }
    public function submitGuess(): void
    {
        if (! $this->target) {
            $this->addError('input', "Aucune personne du jour, contacte un admin.");
            return;
        }
        $alreadyGuessed = collect($this->guesses)->pluck('first_name')->toArray();
        if (in_array($this->input, $alreadyGuessed, true)) {
            $this->addError('input', "Tu as déjà tenté ce prénom.");
            return;
        }

        $guess = Person::where('first_name', $this->input)->first();

        if (! $guess) {
            $this->addError('input', "Aucun élève avec ce prénom.");
            return;
        }

        $this->guesses[] = [
            'first_name' => $guess->first_name,
            'last_name'  => $guess->last_name,
            'comparison' => $this->compareGuess($guess, $this->target),
        ];

        $this->input = '';

        if ($guess->id === $this->target->id) {
            $this->won = true;
        }
    }
    #[Computed]
    public function yesterdayPerson(): ?Person
    {
        return DailyPerson::where('game_type', GameType::CLASSIC->value)
            ->where('date', now()->subDay()->toDateString())
            ->first()
            ?->person;
    }

    #[Computed]
    public function winnersToday(): int
    {
        return Score::where('game_type', GameType::CLASSIC->value)
            ->where('date', now()->toDateString())
            ->where('won', true)
            ->count();
    }
    public function render()
    {

        return view('livewire.game.classic');
    }
}
