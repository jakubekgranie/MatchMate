@props(["user", "previewMode" => false])
<div class="flex items-center flex-col profileCard cursor-pointer max-w-96">
    <div class="border-2 {{ is_null($user->teams) ? 'border-gray-500/45' : "border-[$user->teams->teamColor]"}} rounded-xl shadow-sm shadow-gray-500">
        <div @if($previewMode) id="user_banner" @endif style="background-image: url('{{ asset(is_null($user->banner_name) ? "images/defaults/banner.png" : "storage/images/banners/".$user->banner_name) }}')" class="h-18 border-b-3 border-gray-500/45 bg-cover bg-position-[center_top] rounded-t-[calc(1rem-5px)]"></div>
        <div class="flex items-center px-7 gap-4 bg-gray-50 rounded-b-[calc(1rem-5px)] bg-[url(/public/images/cardbg.png)] bg-no-repeat bg-contain bg-position-[left_1rem_center]">
            <div class="max-w-23 h-23 mr-2 flex content-center">
                <img @if($previewMode) id="user_pfp" @endif src="{{ asset(is_null($user->pfp_name) ? "images/defaults/pfp.png" : "storage/images/pfps/{$user->pfp_name}") }}" class="h-full min-w-12.5 drop-shadow-sm drop-shadow-gray-600 select-none" alt="Your profile picture">
            </div>
            <div class="flex justify-between flex-col">
                <div class="flex justify-center w-fit">
                    <img src="{{ asset(is_null($user->teams) ? "images/defaults/team_icon.png" : "storage/images/defaults/$user->teams->icon_name") }}" class="h-5 mr-1.5 select-none" alt="Ikona klubu {{ !is_null($user->teams) ? $user->teams->name : "(nie należy)" }}">
                    <h2 class="uppercase italic font-cal-sans {{ is_null($user->teams) ? 'text-stone-500/85' : "text-[$user->teams->teamColor]/75"}}">{{ is_null($user->teams) ? "Oczekuje" : $user->teams->name }}</h2>
                </div>
                <h1 class="font-outfit font-semibold tracking-wide text-lg text-gray-950 text-shadow-sm text-shadow-stone-400/55 text-nowrap whitespace-nowrap overflow-hidden overflow-ellipsis max-w-50"><span @if($previewMode) id="user_name" @endif>{{ $user->name }}</span> <span @if($previewMode) id="user_surname" @endif>{{ $user->surname }}</span></h1>
            </div>
        </div>
    </div>
    <div class="border-2 {{ is_null($user->teams) ? 'border-gray-500/45' : "border-[$user->teams->teamColor]"}} border-t-0 w-fit rounded-b-xl shadow-sm shadow-gray-500 bg-gray-50 px-5 py-2.5 mt-[1px] hidden">
        <div class="m-auto flex gap-4">
            <div class="font-cal-sans">
                <p class="uppercase text-sm">Wzrost</p>
                <p class="text-xs" @if($previewMode)id="user_height" @endif>{{ !is_null($user->height) ? "$user->height cm" : '-'}}</p>
            </div>
            <div class="font-cal-sans">
                <p class="uppercase text-sm">Wiek</p>
                @php
                    $suffix;
                    $truncated = $user->age % 10;
                    if($user->age % 100 > 20 && $truncated > 1 && $truncated < 5)
                        $suffix = "lata";
                    else
                        $suffix = "lat";
                @endphp
                <p class="text-xs" @if($previewMode)id="user_age" @endif>{{ !is_null($user->height) ? "$user->age $suffix" : '-'}}</p>
            </div>
            <div class="font-cal-sans">
                <p class="uppercase text-sm">Waga</p>
                <p class="text-xs" @if($previewMode)id="user_weight" @endif>{{ !is_null($user->height) ? "$user->weight kg" : '-'}}</p>
            </div>
        </div>
    </div>
</div>
