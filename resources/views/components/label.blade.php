@props(['value','hasError' => '','for'=>''])

<div class="flex items-center mb-1" x-data="{
    isOpenTooltip:false
}">
    <label for="{{$for}}" {{ $attributes->merge(['class' => 'block text-[14px] text-[#242424] mr-1']) }}>
        {{ $value ?? $slot }}
    </label>

    @error($hasError)
        <div class="flex items-center">
            <svg @mouseenter="isOpenTooltip = !isOpenTooltip" @mouseleave="isOpenTooltip = !isOpenTooltip"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-error">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>

            <x-tooltip show="isOpenTooltip" class="ml-10 !bg-[#FFDEDE] text-[#FFDEDE]">
                <span class="text-error break-words line-clamp-2 text-ellipsis text-left">
                    {{ $message }}
                </span>
            </x-tooltip>
        </div>
    @enderror
</div>
