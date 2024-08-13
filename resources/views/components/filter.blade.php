<div {{ $attributes }}>
    <div class="inline-flex w-full items-center">
        @if (isset($slot))
            <div class="flex">
                {{-- {{$slot}} --}}
                @isset($select)
                    {{ $select }}
                @endisset
            </div>
        @endif
        <div class="flex items-center bg-white border-[#E2E8F0] hover:border-colorAccent border rounded-lg w-full max-w-full shadow-sm">
            <div class="pl-4 text-colorBlack/70">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                    <path d="M11 8C10.606 8 10.2159 8.0776 9.85195 8.22836C9.48797 8.37913 9.15726 8.6001 8.87868 8.87868C8.6001 9.15726 8.37913 9.48797 8.22836 9.85195C8.0776 10.2159 8 10.606 8 11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M20 20L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <input wire:model.live="{{ $model }}" class="w-full h-10 border-0 bg-white text-black leading-tight rounded-lg focus:outline-none focus:ring-0 py-3 px-4" type="search" placeholder="Búsqueda">
        </div>

        @isset($button)
            {{ $button }}
        @endisset
    </div>
</div>
