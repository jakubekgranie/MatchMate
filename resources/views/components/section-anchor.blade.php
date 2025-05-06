@props(['mobile' => false])

<a {{ $attributes->merge([
    'class' => 'font-semibold text-gray-900 '.
        (!$mobile
            ? 'text-sm/6'
            : '-mx-3 block rounded-lg px-3 py-2 text-base/7'
        )
]) }}>
    {{ $slot }}
</a>
