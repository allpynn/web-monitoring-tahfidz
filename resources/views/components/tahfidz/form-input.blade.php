@props(['disabled' => false, 'label' => '', 'name' => '', 'type' => 'text', 'placeholder' => '', 'required' => false, 'value' => ''])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
    @endif
    <input {{ $disabled ? 'disabled' : '' }} 
        id="{{ $name }}" 
        name="{{ $name }}" 
        type="{{ $type }}" 
        placeholder="{{ $placeholder }}" 
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {!! $attributes->merge(['class' => 'block w-full px-4 py-3 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm dark:text-white transition-all shadow-sm placeholder-gray-400']) !!}>
    @error($name)
        <p class="mt-1 text-xs text-red-600 font-bold italic">{{ $message }}</p>
    @enderror
</div>
