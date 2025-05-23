@php
    use App\Models\User;
    use App\Models\PendingUserChanges;
    use Illuminate\Support\Facades\Auth;
    $role = Auth::user()->role_id;
    $all = User::with("team")->whereHas("team", function ($query) { $query->where(["id" => Auth::user()->team->id]); })->get();
    $base = $all->filter(fn($teamMember) => !$teamMember->awaiting_review);
    $unconfirmed = $all->filter(fn($teamMember) => $teamMember->awaiting_review);
    $unconfirmedIds = $unconfirmed->pluck('id')->map(fn($id) => (int)$id)->all();

    $playing = $base->filter(fn($teamMember) => !$teamMember->is_reserve);
    $reserve = $base->filter(fn($teamMember) => $teamMember->is_reserve);
    $uuids = PendingUserChanges::where(["user_change_statuses_id" => 1, "user_change_types_id" => 4])
        ->get()
        ->filter(fn($record) => in_array(intval($record->desired_value), $unconfirmedIds))
        ->pluck("url_key");
    $index = 0;
@endphp
<x-layout page-title="Profil">
    <x-slot:scripts>
        @vite(["resources/js/profile.js", "resources/js/clickableCard.js", "resources/js/cardCustomMenu.js"])
    </x-slot:scripts>
    <div id="cardContextMenu" class="rounded-md bg-stone-100 px-2 py-3.5 shadow-md shadow-stone-600/65 hidden w-fit absolute z-60">
        <form action="{{ route("change-status") }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" id="playerId" name="playerId">
            <button class="text-lg">Zmień na gracza <span id="contextMenuFeed"></span></button>
        </form>
    </div>
    <div class="w-fit m-auto my-11">
        <div>
            <div class="flex {{ $role == 2 ? "justify-between min-w-[40dvw]" : "justify-center" }} gap-5">
                <x-team-card class="shrink-0" :team="Auth::user()->team"/>
                @if($role === 2)
                    <div class="flex gap-3">
                        <x-update-form-container>
                            <form class="grid grid-cols-1 gap-3.5" id="profileForm" action="/my-team/text" method="POST">
                                @csrf
                                @method('PATCH')
                                <x-form-input name="handle" maxlength="25" :label="false" placeholder="Nazwa drużyny" :override-grid="true"/>
                                <x-form-input name="motto" maxlength="60" :label="false" placeholder="Motto drużyny" :override-grid="true"/>
                                <x-form-input name="color" maxlength="7" :label="false" placeholder="Kolor drużyny (hex)" :override-grid="true"/>
                                <x-save-button class="h-9" id="profileFormSubmission" type="submit" svg-icon-id="svgIconText"/>
                            </form>
                        </x-update-form-container>
                        <x-update-form-container>
                            <form class="grid grid-cols-2 gap-3.5" id="imageForm" action="/my-team/images" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PATCH')
                                <x-form-image-input name="pfp" class="p-6">
                                    <g>
                                        <path style="opacity:0.965"
                                              d="M 247.5,-0.5 C 251.5,-0.5 255.5,-0.5 259.5,-0.5C 318.795,35.2513 383.128,55.7513 452.5,61C 457.428,62.9268 460.594,66.4268 462,71.5C 465.06,142.137 466.06,212.803 465,283.5C 464.147,322.727 452.147,358.061 429,389.5C 410.42,414.081 388.587,435.247 363.5,453C 331.022,475.578 296.688,495.078 260.5,511.5C 256.5,511.5 252.5,511.5 248.5,511.5C 212.977,495.41 179.31,476.244 147.5,454C 108.657,426.828 78.8234,391.994 58,349.5C 49.6014,331.447 45.6014,312.447 46,292.5C 45.1422,218.82 45.8089,145.153 48,71.5C 49.4269,65.0747 53.2602,60.9081 59.5,59C 110.886,53.4033 160.553,41.07 208.5,22C 222.217,15.4837 235.217,7.98367 247.5,-0.5 Z M 252.5,35.5 C 266.77,42.3001 281.103,49.1334 295.5,56C 338.46,74.3746 383.126,86.5412 429.5,92.5C 432.177,155.441 433.01,218.441 432,281.5C 431.247,317.312 419.581,348.978 397,376.5C 370.95,406.892 340.45,431.725 305.5,451C 289.815,459.843 273.815,468.177 257.5,476C 255.5,476.667 253.5,476.667 251.5,476C 217.678,460.27 186.012,441.27 156.5,419C 135.176,401.681 116.676,381.847 101,359.5C 92.2108,345.474 85.5441,330.474 81,314.5C 80.0023,307.533 79.3357,300.533 79,293.5C 78.2292,225.49 78.7292,157.49 80.5,89.5C 128.738,83.274 175.405,71.1073 220.5,53C 231.709,47.9004 242.376,42.0671 252.5,35.5 Z"/>
                                    </g>
                                </x-form-image-input>
                                <x-form-image-input name="banner" accept="image/png, image/jpg">
                                    <g><path style="opacity:0.986" d="M 511.5,80.5 C 511.5,197.167 511.5,313.833 511.5,430.5C 506.36,445.132 496.026,453.298 480.5,455C 330.5,455.667 180.5,455.667 30.5,455C 14.9738,453.298 4.6405,445.132 -0.5,430.5C -0.5,313.833 -0.5,197.167 -0.5,80.5C 4.50997,66.3315 14.51,58.1649 29.5,56C 180.167,55.3333 330.833,55.3333 481.5,56C 496.49,58.1649 506.49,66.3315 511.5,80.5 Z M 32.5,89.5 C 181.167,89.5 329.833,89.5 478.5,89.5C 478.667,167.501 478.5,245.501 478,323.5C 456.5,304 435,284.5 413.5,265C 403.5,259 393.5,259 383.5,265C 361.32,287.347 338.986,309.514 316.5,331.5C 272.688,279.187 228.688,227.02 184.5,175C 169.701,167.494 157.201,170.327 147,183.5C 109.237,228.02 71.2368,272.353 33,316.5C 32.5,240.834 32.3333,165.167 32.5,89.5 Z"/></g>
                                    <g><path style="opacity:0.974" d="M 375.5,129.5 C 401.814,126.365 419.648,137.032 429,161.5C 434.166,187.67 424.666,206.17 400.5,217C 372.003,223.837 352.503,213.67 342,186.5C 337.275,157.926 348.442,138.926 375.5,129.5 Z"/></g>
                                </x-form-image-input>
                                <x-save-button class="h-9 col-span-2" id="imageFormSubmission" type="submit" svg-icon-id="svgIconText"/>
                                <x-input-error class="col-span-2 text-center" name="pfp"/>
                                <x-input-error class="col-span-2 text-center" name="banner"/>
                            </form>
                        </x-update-form-container>
                    </div>
                @endif
            </div>
            <div class="flex flex-col gap-8">
                <x-player-frame title="Gracze">
                        @foreach($playing as $player)
                            <x-profile-card :user="$player" class="player"/>
                        @endforeach
                </x-player-frame>
                <x-player-frame title="Rezerwowi">
                        @foreach($reserve as $benched)
                            <x-profile-card :user="$benched" class="reserve"/>
                        @endforeach
                </x-player-frame>
                @if($role === 2 && sizeof($unconfirmed))
                    <x-player-frame title="Oczekujący">
                            @foreach($unconfirmed as $awaiting)
                                <x-profile-card :user="$awaiting" :captain-mode="true" :uuid="$uuids[$index]" class="notClickable"/>
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                    </x-player-frame>
                @endif
            </div>
        </div>
    </div>
</x-layout>
