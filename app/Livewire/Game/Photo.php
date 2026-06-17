<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Enums\GameType;
use App\Models\DailyPerson;
use App\Models\Person;
use Livewire\Attributes\Computed;
use App\Models\Score;

class Photo extends Component
{
    public ?Person $target = null;
    public string $input = '';
    public bool $won = false;
    public array $guesses = [];
    public int $restartCount = 0;

    private const BLUR_LEVELS = [
        'blur(20px) grayscale(100%)',
        'blur(14px) grayscale(100%)',
        'blur(9px) grayscale(80%)',
        'blur(5px) grayscale(60%)',
        'blur(2px) grayscale(30%)',
        'blur(0px) grayscale(0%)',
    ];

    private function sessionKey(): string
    {
        return 'game.photo.current';
    }

    public function mount(): void
    {
        if ($saved = session($this->sessionKey())) {
            $this->target  = Person::find($saved['target_id']);
            $this->guesses = $saved['guesses'];
            $this->won     = $saved['won'];
        }


        if (! $this->target) {
            $this->restart();
        }
    }

    private function persist(): void
    {
        session([$this->sessionKey() => [
            'target_id' => $this->target?->id,
            'guesses'   => $this->guesses,
            'won'       => $this->won,
        ]]);
    }

    #[Computed]
    public function blurFilter(): string
    {
        if ($this->won) {
            return 'blur(0px) grayscale(0%)';
        }

        $index = min(count($this->guesses), count(self::BLUR_LEVELS) - 1);

        return self::BLUR_LEVELS[$index];
    }

    #[Computed]
    public function blurStep(): int
    {
        return min(count($this->guesses), count(self::BLUR_LEVELS) - 1);
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

    public function submitGuess(): void
    {
        if ($this->won) return;

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
            'correct'    => $guess->id === $this->target->id,
        ];

        $this->input = '';

        if ($guess->id === $this->target->id) {
            $this->won = true;
        }

        $this->persist();
    }

    public function restart(): void
    {
        $this->target       = Person::inRandomOrder()->first();
        $this->guesses      = [];
        $this->won          = false;
        $this->input        = '';
        $this->restartCount++;

        $this->persist();
    }

    #[Computed]
    public function yesterdayPerson(): ?Person
    {
        return DailyPerson::where('game_type', GameType::PHOTO->value)
            ->where('date', now()->subDay()->toDateString())
            ->first()
            ?->person;
    }

    #[Computed]
    public function winnersToday(): int
    {
        return Score::where('game_type', GameType::PHOTO->value)
            ->where('date', now()->toDateString())
            ->where('won', true)
            ->count();
    }

    public function render()
    {
        return view('livewire.game.photo');
    }
}
