@props(["accept" => "image/png"])
@php
    $name = $attributes->get("name");
@endphp
<input type="file" id="{{ $name }}" name="{{ $name }}" accept="{{ $accept }}" hidden/>
<div id="dnd_{{ $name }}" {{ $attributes->merge(["class" => "bg-gray-800 hover:bg-gray-700 cursor-pointer rounded-md duration-100 outline-2 outline-gray-500 outline-dashed -outline-offset-8 hover:outline-gray-300 focus-visible:outline-solid"]) }}>
    <div class="aspect-square flex justify-center items-center">
        <svg id="svg{{ ucfirst($name) }}" style="fill: oklch(70.7% 0.022 261.325);" viewBox="0 0 512 512" class="svg w-6 h-6 pointer-events-none duration-100">
            {{ $slot }}
        </svg>
    </div>
</div>
