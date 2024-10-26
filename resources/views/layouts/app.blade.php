<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="nunito antialiased bg-customGray">
        <x-banner />

        <div class="min-h-screen">
            @livewire('navigation-menu')
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <x-footer />
        </div>

        @persist('loading-screen-indicator')
            <div x-data="{
                open: true,
                init() {
                    let self = this;
                    document.addEventListener('livewire:navigate', (event) => {
                        self.open = true;
                        let context = event.detail
                        console.log(context);
                    });
                    document.addEventListener('livewire:navigated', () => {
                        self.open = false;
                    })
                }
            }" x-show="open"
                x-transition:enter="transition ease-in-out duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in-out duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                <div class="flex justify-center items-center fixed z-[9999] w-full h-full top-0 left-0">
                    <x-app.logo width="150" :color="true" class="absolute z-[999999] animate-pulse" />
                    <div class="absolute top-0 left-0 w-full h-full bg-customPrimary/50 backdrop-blur-sm"></div>
                </div>
            </div>
        @endpersist

        @stack('modals')

        @livewireScripts
        <x-toast />
    </body>
</html>
