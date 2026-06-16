<div class="max-w-3xl mx-auto p-6">

    @if ($errors->any())
    <div class="text-red-400 text-sm mb-4">
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    @if (session('status'))
    <div class="text-green-400 text-sm mb-4">{{ session('status') }}</div>
    @endif

    <form wire:submit="save" class="space-y-8">

        {{-- ===== Section Classique ===== --}}
        <fieldset class="space-y-3">
            <legend class="font-bold">Classique</legend>

            <flux:input wire:model="first_name" label="Prénom *" />
            <flux:input wire:model="last_name" label="Nom *" />

            <flux:select wire:model="gender" label="Genre *">
                <option value="">— choisir —</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
                <option value="Autre">Autre</option>
            </flux:select>

            <flux:input wire:model="age" type="number" label="Âge *" />
            <flux:input wire:model="height" type="number" label="Taille (cm) *" />
            <flux:input wire:model="hair_color" label="Couleur de cheveux *" />
            <flux:input wire:model="city" label="Ville *" />
            <flux:input wire:model="hobby" label="Hobby *" />
            <flux:input wire:model="specialization" label="Spécialisation *" />

            {{-- optionnels mais utiles pour les jeux --}}
            <flux:input wire:model="class" label="Classe" />
            <flux:input wire:model="origin" label="Origine" />
            <flux:textarea wire:model="description" label="Description (mode Description)" />
        </fieldset>

        {{-- ===== Section Emoji ===== --}}
        <fieldset class="space-y-3">
            <legend class="font-bold">Emojis (mode Emoji)</legend>
            <div class="grid grid-cols-4 gap-3">
                <flux:input wire:model="emoji_1" placeholder="🎮" />
                <flux:input wire:model="emoji_2" placeholder="🎸" />
                <flux:input wire:model="emoji_3" placeholder="⚽" />
                <flux:input wire:model="emoji_4" placeholder="📚" />
            </div>
        </fieldset>

        {{-- ===== Section Photo ===== --}}
        <fieldset class="space-y-3">
            <legend class="font-bold">Photo (mode Photo)</legend>
            @if ($photo)
            <img src="{{ $photo->temporaryUrl() }}" class="w-32 rounded">
            @elseif ($photo_path)
            <img src="{{ route('photos.show', basename($photo_path)) }}" class="w-32 rounded">
            @endif
            <input type="file" wire:model="photo" accept="image/*">
            @error('photo') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
        </fieldset>

        <flux:button type="submit" variant="primary">Enregistrer</flux:button>
    </form>

    {{-- ===== Liste des fiches ===== --}}
    <ul class="mt-10 divide-y divide-zinc-700">
        @foreach ($people as $person)
        <li class="flex justify-between py-2">
            <span>{{ $person->full_name }}</span>
            <span class="flex gap-2">
                <button wire:click="edit({{ $person->id }})" class="text-blue-400">Éditer</button>
                <button wire:click="delete({{ $person->id }})" wire:confirm="Supprimer ?" class="text-red-400">Suppr.</button>
            </span>
        </li>
        @endforeach
    </ul>
</div>