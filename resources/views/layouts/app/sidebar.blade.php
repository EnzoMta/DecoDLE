<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen text-white bg-decode-bg
             bg-[radial-gradient(ellipse_at_top,_var(--color-decode-violet-deep)_0%,_var(--color-decode-bg)_55%)]">
    <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Modes de jeu')" class="grid">
                <flux:sidebar.item icon="user" :href="route('game.classic')" :current="request()->routeIs('game.classic')" wire:navigate>
                    {{ __('Classique') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="face-smile" :href="route('game.emoji')" :current="request()->routeIs('game.emoji')" wire:navigate>
                    {{ __('Emoji') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="photo" :href="route('game.photo')" :current="request()->routeIs('game.photo')" wire:navigate>
                    {{ __('Photo') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            @can('admin')
            <flux:sidebar.group :heading="__('Administration')" class="grid">
                <flux:sidebar.item icon="cog-6-tooth" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Back-office') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            @endcan

        </flux:sidebar.nav>

        <flux:spacer />


    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>