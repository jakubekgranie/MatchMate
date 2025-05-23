@props(['title' => 'Ramka'])
<fieldset class="p-7.5 mt-10 border-2 border-stone-500 rounded-md">
    <legend class="text-3xl font-semibold text-center mx-3">&nbsp;&nbsp;{{ $title }}&nbsp;&nbsp;</legend>
    <div class="grid grid-cols-3 gap-4 w-300 overflow-hidden">
        {{ $slot }}
    </div>
</fieldset>
