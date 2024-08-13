@props(['show'])

<div {{ $attributes->merge(['class' => 'absolute bg-black flex items-center p-2 rounded-lg z-10']) }} x-show="{{ $show }}" style="display:none">
    <svg width="11" height="14" class="absolute -left-2.5" viewBox="0 0 11 14" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
            d="M0.499999 7.86604C-0.166668 7.48114 -0.166667 6.51889 0.5 6.13399L11 0.071815L11 13.9282L0.499999 7.86604Z"
            fill="currentColor" fill-opacity="1" />
    </svg>

    <div class="flex flex-col items-start relative manropeRegular text-white text-[12px] max-w-60">
        {{ $slot }}
    </div>
</div>
