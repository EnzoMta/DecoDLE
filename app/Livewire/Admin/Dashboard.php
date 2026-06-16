<?php

namespace App\Livewire\Admin;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class Dashboard extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $first_name = '';
    public string $last_name = '';
    public ?string $class = null;
    public ?string $gender = null;
    public ?int $age = null;
    public ?int $height = null;
    public ?string $hair_color = null;
    public ?string $city = null;
    public ?string $origin = null;
    public ?string $hobby = null;
    public ?string $specialization = null;
    public ?string $description = null;
    public ?string $emoji_1 = null;
    public ?string $emoji_2 = null;
    public ?string $emoji_3 = null;
    public ?string $emoji_4 = null;

    public $photo;
    public ?string $photo_path = null;

    protected function rules(): array
    {
        return [
            // --- obligatoires (NOT NULL en base) ---
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'gender'         => ['required', 'string', 'max:255'],
            'age'            => ['required', 'integer', 'min:0'],
            'height'         => ['required', 'integer', 'min:1'],
            'hair_color'     => ['required', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:255'],
            'hobby'          => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'class'          => ['nullable', 'string', 'max:255'],
            'origin'         => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'emoji_1'        => ['nullable', 'string', 'max:8'],
            'emoji_2'        => ['nullable', 'string', 'max:8'],
            'emoji_3'        => ['nullable', 'string', 'max:8'],
            'emoji_4'        => ['nullable', 'string', 'max:8'],
            'photo'          => ['nullable', 'image', 'max:4096'],
        ];
    }


    public function edit(int $id): void
    {
        $person = Person::findOrFail($id);
        $this->editingId = $person->id;
        $this->fill($person->only([
            'first_name',
            'last_name',
            'class',
            'gender',
            'age',
            'height',
            'hair_color',
            'city',
            'origin',
            'hobby',
            'specialization',
            'description',
            'emoji_1',
            'emoji_2',
            'emoji_3',
            'emoji_4',
            'photo_path',
        ]));
        $this->photo = null;
    }

    public function save(): void
    {
        $data = $this->validate();
        unset($data['photo']);

        if ($this->photo) {
            $extension = $this->photo->getClientOriginalExtension();
            $filename  = Str::slug($this->first_name . '-' . $this->last_name) . '.' . $extension;

            File::ensureDirectoryExists(database_path('photos'));
            File::copy($this->photo->getRealPath(), database_path('photos/' . $filename));

            $data['photo_path'] = $filename;
        }

        Person::updateOrCreate(['id' => $this->editingId], $data);

        $this->reset();
        session()->flash('status', 'Fiche enregistrée.');
    }

    public function delete(int $id): void
    {
        Person::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'people' => Person::orderBy('first_name')->get(),
        ]);
    }
}
