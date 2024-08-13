@props(['disabled' => false, 'placeholder' => ''])

<input {{ $disabled ? 'disabled' : '' }} placeholder="{{ $placeholder }}" {!! $attributes->merge(['class' => 'border-customGray focus:border-1 hover:border-customPrimary focus:border-colorAccent focus:ring-0 rounded-lg shadow-sm bg-customGray placeholder:text-[#A9A9A9] disabled:cursor-not-allowed  disabled:bg-customBlack/10 ' . (($errors->has($attributes->wire('model')->value)) ? 'border-error' : 'border-customGray')]) !!}>
