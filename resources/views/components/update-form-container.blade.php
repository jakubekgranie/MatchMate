@props(["title" => null, "mailMode" => false])
<div {{ $attributes->merge(["class" => "h-fit max-w-[calc(26.25rem+4px)] w-full border border-gray-500/45 rounded-2xl px-4 ".($title ? "pb-7" : "pb-4.5")." pt-4.5 bg-gray-100/60 shadow-sm shadow-gray-400"]) }}>
    @if($title)
        <h2 class="text-left mb-4.5 text-lg font-semibold">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
