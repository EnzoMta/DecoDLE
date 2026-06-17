<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Enums\GameType;
use App\Models\DailyPerson;
use App\Models\Person;
use Livewire\Attributes\Computed;
use App\Models\Score;

class Emoji extends Component
{
    public ?Person $target = null;
    public string $input = '';
    public bool $won = false;
    public array $guesses = [];

    private function sessionKey(): string
    {
        return 'game.emoji.current';
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
    public function revealedCount(): int
    {
        if ($this->won) {
            return 4;
        }

        return min(count($this->guesses) + 1, 4);
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
        $this->target  = Person::inRandomOrder()->first();
        $this->guesses = [];
        $this->won     = false;
        $this->input   = '';
        unset($this->revealedCount);

        $this->persist();
    }

    #[Computed]
    public function yesterdayPerson(): ?Person
    {
        return DailyPerson::where('game_type', GameType::EMOJI->value)
            ->where('date', now()->subDay()->toDateString())
            ->first()
            ?->person;
    }

    #[Computed]
    public function winnersToday(): int
    {
        return Score::where('game_type', GameType::EMOJI->value)
            ->where('date', now()->toDateString())
            ->where('won', true)
            ->count();
    }

    public function render()
    {
        return view('livewire.game.emoji');
    }
}
