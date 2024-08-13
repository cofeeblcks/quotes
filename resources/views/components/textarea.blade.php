@props(['disabled' => false, 'required' => false, 'name', 'value' => null, 'rows'])

<textarea {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }} id="{{ $name }}" name="{{ $name }}" rows="{{ $rows ?? 3 }}"
    {{ $attributes->merge(['class' => 'w-full rounded-lg border-1 focus:border-customPrimary focus:ring-0 shadow-sm hover:border-customPrimary '. (($errors->has($attributes->wire('model')->value))? 'border-[#FD4A4A]' : 'border-[#E2E8F0]')]) }}>{{ $value }}</textarea>
