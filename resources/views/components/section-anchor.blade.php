@props(['mobile' => false, 'formMode' => false])
@php
    $sharedClasses = ['font-semibold text-gray-900 ', 'text-sm/6', '-mx-3 block rounded-lg px-3 py-2 text-base/7'];
@endphp

@if($formMode)
    <button type="submit" {{ $attributes->merge([
        'class' => $sharedClasses[0].'cursor-pointer '.
            (!$mobile
                ? $sharedClasses[1]
                : $sharedClasses[2]
            )
    ]) }}>
        {{ $slot }}
    </button>
@else
    <a {{ $attributes->merge([
        'class' => $sharedClasses[0].
            (!$mobile
                ? $sharedClasses[1]
                : $sharedClasses[2]
            )
    ]) }}>
        {{ $slot }}
    </a>
@endif
