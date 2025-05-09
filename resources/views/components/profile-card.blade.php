@props(['user'])
<div class="border-2 {{ is_null($user->teams) ? 'border-gray-500/45' : "border-[$user->teams->teamColor]"}} rounded-xl">
    <div style="background-image: url('{{ asset(is_null($user->banner_name) ? "images/defaults/banner.png" : "images/banners/".$user->banner_name) }}')" class="h-22 border-b-2 border-gray-500/45 bg-cover bg-position-[center_top] rounded-t-[calc(1rem-5px)]"></div>
    <div class="flex items-center px-7 py-3.5 gap-4 bg-white/75 rounded-b-[calc(1rem-5px)]">
        <img src="{{ asset(is_null($user->pfp_name) ? "images/defaults/pfp.png" : "images/pfps/$user->pfp_name") }}" class="w-17.5 h-17.5 rounded-full mr-2 drop-shadow-sm drop-shadow-gray-700" alt="Your profile picture">
        <div class="flex justify-between h-17.5 flex-col">
            <div class="flex px-[0.65rem] py-[0.2rem] border-2 {{ is_null($user->teams) ? "border-stone-700/85" : "border-[$user->teams->team_color]/85" }} justify-center rounded-lg w-fit">
                <img src="{{ asset(is_null($user->teams) ? "images/defaults/team_icon.png" : "images/defaults/$user->teams->icon_name") }}" class="h-5 mr-1.5" alt="Ikona klubu {{ !is_null($user->teams) ? $user->teams->name : "(nie należy)" }}">
                <h2 class="uppercase font-cal-sans {{ is_null($user->teams) ? 'text-stone-500/85' : "text-[$user->teams->teamColor]"}}">{{ is_null($user->teams) ? "Oczekuje" : $user->teams->name }}</h2>
            </div>
            <h1 class="font-playwrite font-bold text-lg text-shadow-red-900 text-shadow-sm/60">{{ $user->name." ".$user->surname }}</h1>
        </div>
    </div>
</div>
