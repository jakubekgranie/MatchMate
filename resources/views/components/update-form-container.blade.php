@props(["title" => "???"])
<div {{ $attributes->merge(["class" => "m-auto max-w-[calc(26.25rem+4px)] w-full border border-gray-500/45 rounded-2xl px-4 pt-4.5 pb-7 bg-gray-100/60 shadow-sm shadow-gray-400"]) }}>
    <h2 class="text-left mb-4.5 text-lg font-semibold">{{ $title }}</h2>
    {{ $slot }}
</div>
