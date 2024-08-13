@props(['target' => ''])
<div class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-[9999999999999]" wire:loading wire:target="{{ $target }}">
    <div class="fixed inset-0 transform transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-customPrimary/50 backdrop-blur-sm">
            <div class="w-full h-full animate-pulse flex justify-center items-center flex-col">
                <x-app.logo width="150" />
                <p class="mt-4 text-white text-2xl font-semibold">Cargando...</p>
            </div>
        </div>
    </div>
</div>
