@props(['active' => false])
<div class="relative">
    {{ $slot }}
    <div class="indicator-dot-active absolute top-[-1px] right-[-5px] {{ $active ? 'text-customPrimaryLight' : 'text-error' }}">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="16" height="16" rx="8" fill="currentColor"/>
        </svg>
    </div>
</div>
