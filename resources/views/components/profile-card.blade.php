@props(["user", "previewMode" => false, "captainMode" => false, "uuid" => false])
@php
    use App\Helpers\ControllerHelper;
    use App\Models\PendingUserChanges;
    $team = $user->team;
    $baseAddress = !(request()->is("my-team")) ? ControllerHelper::getParentUrl(request()->getRequestUri()) : "/profile/action/$uuid/";
@endphp

<div {{ $attributes->merge(["class" => "flex items-center flex-col profileCard mb-[2px]".(!$captainMode ? " cursor-pointer " : "")]) }}>
    <div @if(!is_null($team)) style="{{ "border-color: {$team->color}"}}" @endif class="border-2{{ $captainMode ? " rounded-bl-none rounded-br-none" : "" }}{{ is_null($team) ? ' border-gray-500/65 ' : ' ' }}rounded-xl shadow-sm shadow-gray-500 w-96">
        <div @if($previewMode) id="user_banner" @endif style="background-image: url('{{ asset(is_null($user->banner_name) ? Vite::asset("resources/images/defaults/banner.png") : asset("storage/images/banners/".$user->banner_name)) }}')" class="h-18 border-b-3 border-gray-500/45 bg-cover bg-position-[center_top] rounded-t-[calc(1rem-5px)]"></div>
        <div style="background: oklch(98.5% 0.002 247.839) url('{{ Vite::asset("resources/images/defaults/cardbg.png") }}')" class="flex items-center px-7 gap-4 bg-gray-50 rounded-b-[calc(1rem-5px)] !bg-no-repeat !bg-contain !bg-position-[left_1rem_center]">
            <div class="max-w-23 h-23 mr-2 flex content-center">
                <img @if($previewMode) id="user_pfp" @endif src="{{ asset(is_null($user->pfp_name) ? Vite::asset("resources/images/defaults/pfp.png") : asset("storage/images/pfps/{$user->pfp_name}")) }}" class="h-full min-w-12.5 drop-shadow-sm drop-shadow-gray-600 select-none" alt="Your profile picture">
            </div>
            <div class="flex justify-between flex-col gap-0.5">
                <div class="flex justify-center items-center w-fit">
                    <img src="{{ asset(is_null($team) ? Vite::asset("resources/images/defaults/team_icon.png") : asset("storage/images/icons/{$team->icon_name}")) }}" class="h-5 mr-1.5 select-none" alt="Ikona klubu {{ !is_null($team) ? $team->handle : "(nie należy)" }}">
                    <h2 @if(!is_null($team)) style="{{ "color: {$team->color}" }}" @endif class="uppercase italic font-cal-sans {{ is_null($team) ? 'text-stone-500/85' : ""}}">{{ is_null($team) ? "Oczekuje" : $team->handle }}</h2>
                </div>
                <h1 class="font-outfit font-semibold tracking-wide text-lg text-gray-950 text-shadow-sm text-shadow-stone-400/55 text-nowrap whitespace-nowrap overflow-hidden overflow-ellipsis max-w-50"><span @if($previewMode) id="user_name" @endif>{{ $user->name }}</span> <span @if($previewMode) id="user_surname" @endif>{{ $user->surname }}</span></h1>
            </div>
        </div>
    </div>
    @if($captainMode)
        <div @if(!is_null($team)) style="border-color: {{ $team->color }}" @endif class="border-2 {{ is_null($team) ? 'border-gray-500/65' : ""}} border-t-0 w-[calc(100%-4px)] rounded-b-xl shadow-sm shadow-gray-500 bg-gray-50 px-5 py-2.5 mt-[1px] grid grid-cols-2 gap-4">
            <a class="h-9" href="{{ $baseAddress }}accept">
                <x-save-button text="Akceptuj" class="w-full h-full" :activated="true">
                    <g><path style="opacity:0.985" d="M 511.5,136.5 C 511.5,137.5 511.5,138.5 511.5,139.5C 400.492,242.342 289.325,345.008 178,447.5C 174.714,445.218 171.714,442.551 169,439.5C 112.634,378.969 56.1336,318.636 -0.5,258.5C -0.5,257.5 -0.5,256.5 -0.5,255.5C 23.991,233.407 48.3243,211.074 72.5,188.5C 109.324,227.458 146.158,266.458 183,305.5C 269.199,224.966 355.699,144.799 442.5,65C 443.635,64.2506 444.635,64.4173 445.5,65.5C 467.55,89.213 489.55,112.88 511.5,136.5 Z"/></g>
                </x-save-button>
            </a>
            <a class="h-9" href="{{ $baseAddress }}reject">
                <x-save-button text="Odrzuć" class="w-full h-full bg-red-700 hover:bg-red-600 !text-gray-100 focus-visible:outline-red-500" fill="oklch(87.2% 0.01 258.338)" :silently-activated="true">
                    <g><path style="opacity:0.987" d="M 234.5,31.5 C 295.234,27.6884 348.401,45.6884 394,85.5C 406.5,73 419,60.5 431.5,48C 444.16,38.5213 455.66,39.6879 466,51.5C 467.562,53.9573 468.562,56.624 469,59.5C 469.667,101.167 469.667,142.833 469,184.5C 467.846,192.317 463.679,197.817 456.5,201C 412.559,202.651 368.559,202.984 324.5,202C 310.731,196.627 305.897,186.794 310,172.5C 311.08,170.004 312.414,167.67 314,165.5C 325.167,154.333 336.333,143.167 347.5,132C 300.46,93.5655 248.794,85.8988 192.5,109C 131.059,138.346 99.0589,187.18 96.5,255.5C 98.0577,315.474 124.058,361.307 174.5,393C 232.069,423.713 288.735,422.046 344.5,388C 375.041,366.461 396.541,337.961 409,302.5C 415.491,294.002 423.991,290.836 434.5,293C 442.932,294.619 451.265,296.619 459.5,299C 468.298,304.431 471.798,312.264 470,322.5C 443.738,398.429 392.238,448.262 315.5,472C 226.283,492.83 150.116,470.33 87,404.5C 34.3683,340.689 19.035,268.689 41,188.5C 66.1667,115.333 115.333,66.1667 188.5,41C 203.746,36.7184 219.079,33.5517 234.5,31.5 Z"/></g>
                </x-save-button>
            </a>
        </div>
    @else
        <div @if(!is_null($team)) style="border-color: {{ $team->color }}" @endif class="border-2 {{ is_null($team) ? 'border-gray-500/65' : ""}} border-t-0 w-fit rounded-b-xl shadow-sm shadow-gray-500 bg-gray-50 px-5 py-2.5 mt-[1px] hidden">
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
    @endif
</div>
