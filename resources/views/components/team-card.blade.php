@props(["team"])
<div {{ $attributes->merge(["class" => "w-120 h-fit border border-gray-500/45 rounded-2xl shadow-sm border-2"]) }} style="border-color: {{ $team->color }}">
    <div style="background-image: url('storage/images/banners/{{ $team->banner_name }}');" @if(request()->is("my-team")) id="user_banner" @endif class="flex rounded-2xl bg-cover bg-center pt-20">
        <img src="storage/images/icons/{{ $team->icon_name }}" class="w-30 h-30 bg-gray-50 p-4 rounded-2xl rounded-br-none" @if(request()->is("my-team")) id="user_pfp" @endif alt="Ikona drużyny">
        <div class="min-h-15 w-90 max-h-30 mt-auto bg-gray-50 rounded-br-2xl px-3 flex items-center gap-2">
            <h2 @if(request()->is("my-team")) id="user_handle" @endif class="font-bold text-lg" style="color: {{ $team->color }}">{{ $team->handle }}</h2>
                <div class="h-5 bg-gray-500/45 rounded-sm w-0.5"></div>
            <q @if(request()->is("my-team")) id="user_motto" @endif class="font-semibold text-nowrap whitespace-nowrap overflow-hidden overflow-ellipsis italic">{{ $team->motto }}</q>
        </div>
    </div>
</div>
