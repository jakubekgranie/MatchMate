@props(['type' => false, 'label' => true, 'overrideGrid' => false, 'containerClass' => false])
@php
    $name = $attributes->get('name');
@endphp
@if(!$overrideGrid)
    <div class="sm:col-span-6 sm:col-start-2 {{ $containerClass ? $containerClass : '' }}">
@endif
    @if($label)
        <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900">{{ $slot }}</label>
    @endif
        <div @if($label) class="mt-2" @endif>
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-400/75 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                <input type="{{ !$type ? 'text' : $type }}" id="{{ $name }}" {{$attributes->merge(['class' => "block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none sm:text-sm/6"])}}>
            </div>
            <x-input-error :$name/>
        </div>
@if(!$overrideGrid)
    </div>
@endif
