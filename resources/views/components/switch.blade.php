@props(['id', 'disabled' => false])
<div x-data="{
    model: @entangle($attributes->wire('model')).live,
}">
    <div {{ $attributes->except('wire:model')->merge(['class' => 'btn-status']) }}>
        <input {{ $attributes->wire('model') }} type="checkbox" name="checkbox" id="chk-{{ $id }}" class="hidden" {{ $disabled ? 'disabled' : '' }} />
        <label
            for="chk-{{ $id }}"
            id="{{ $id }}"
            :class="'btn-change flex items-center p-1 rounded-full w-12 h-6 cursor-pointer before:transition-all before:duration-300 before:rounded-full before:h-[17px] before:w-[17px] before:block before:content-[\'\'] '
            + (model ? 'bg-[#ECEDF2] before:bg-customPrimary before:translate-x-[23px]' : 'bg-[#ECEDF2] before:bg-[#D5DEF4]' )"
        >
        </label>
    </div>
</div>
