@props(['type' => false, 'label' => true, 'gridOverride' => ''])
@php
    $name = $attributes->get('name');
@endphp
<div class="{{ $gridOverride != '' ? $gridOverride : "sm:col-span-6 sm:col-start-2" }}">
    @if($label)
        <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900">{{ $slot }}</label>
    @endif
    <div class="{{ $label ?? 'mt-2' }}">
        <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-400/75 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
            <input type="{{ !$type ? 'text' : $type }}" id="{{ $name }}" {{$attributes->merge(['class' => "block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none sm:text-sm/6"])}}>
        </div>

        <p id="{{ $name }}_error" class="text-red-500 text-sm pl-3 font-semibold hidden">
            @error($name) {{ $message }} @enderror
        </p>
        @error($name)
            {{-- Allows the profile.js to work with validation --}}
            <script>document.getElementById("{{ $name }}_error").classList.remove("hidden")</script>
        @enderror
    </div>
</div>
