<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-black antialiased text-gray-300 selection:bg-teal-500/30">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">

            <!-- Left Panel (Desktop Only) -->
            <div class="bg-black relative hidden h-full flex-col p-10 text-white lg:flex border-r border-gray-900">
                <!-- Pure black background with the subtle teal glow from the welcome page -->
                <div class="absolute inset-0 bg-black"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-teal-500/5 rounded-full blur-[120px] pointer-events-none"></div>

                <!-- Logo and Name -->
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-900 border border-gray-800">
                        <x-app-logo-icon class="h-6 w-6 fill-current text-teal-400" />
                    </span>
                    <span class="ms-2 text-white">Hour<span class="text-teal-400">-Tracker</span></span>
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="lg" class="text-white">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading size="sm" class="text-gray-500">{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>

            <!-- Right Panel (Form Area) -->
            <div class="w-full lg:p-8 bg-black">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">

                    <!-- Mobile Logo -->
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-900 border border-gray-800">
                            <x-app-logo-icon class="size-6 fill-current text-teal-400" />
                        </span>
                        <span class="sr-only">{{ config('app.name', 'Hour-Tracker') }}</span>
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
