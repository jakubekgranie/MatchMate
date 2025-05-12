<x-layout page-title="Profil" :flex-center="true">
    <x-slot:scripts>
        <script type="module" src="{{Vite::asset("resources/js/profile.js")}}"></script>
        <script type="module" src="{{Vite::asset("resources/js/clickableCard.js")}}"></script>
    </x-slot:scripts>
    <div class="border-2 border-gray-500/65 bg-white/30 rounded-2xl flex items-center py-7 px-9.5 shadow-sm shadow-gray-300">
        <div>
            <h1 class="text-2xl text-gray-950 font-bold mb-6">Edytuj swój profil</h1>
            <x-profile-card :user="\App\Models\User::with('teams')->find(Auth::id())" :preview-mode="true"/>
        </div>
        <div class="h-70 w-0.5 bg-stone-400 mx-8"></div>
        <div class="m-auto w-fit border border-gray-500/45 rounded-2xl px-4 pt-4.5 pb-7 bg-gray-100/60 shadow-sm shadow-gray-400">
            <h2 class="text-left mb-4.5 text-lg font-semibold">Dane podstawowe</h2>
            <form class="grid grid-cols-2 gap-3.5" id="profileForm" method="POST" >
                @csrf
                @method('PATCH')
                <x-form-input name="name" :label="false" placeholder="Imię" grid-override=" " />
                <x-form-input name="surname" :label="false" placeholder="Nazwisko" grid-override=" " />
                <x-form-input name="age" type="number" maxlength="2" :label="false" placeholder="Wiek" grid-override=" " />
                <x-form-input name="height" type="number" maxlength="3" :label="false" placeholder="Wzrost (cm)" grid-override=" " />
                <x-form-input name="weight" type="number" maxlength="3" :label="false" placeholder="Waga (kg)" grid-override=" " />
                <button type="submit" disabled id="submission" class="rounded-md text-sm font-semibold shadow-xs bg-gray-800 text-gray-300 hover:bg-gray-700 cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 duration-100 font-system flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" id="svgIcon" class="w-4 h-4 mr-2 duration-100" viewBox="0 0 495 495" style="fill: oklch(70.7% 0.022 261.325); shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd">
                        <g><path style="opacity:0.997" d="M 53.5,15.5 C 88.8333,15.5 124.167,15.5 159.5,15.5C 159.333,58.168 159.5,100.835 160,143.5C 160.93,159.546 169.097,169.713 184.5,174C 208.136,175.13 231.803,175.63 255.5,175.5C 279.197,175.63 302.864,175.13 326.5,174C 341.903,169.713 350.07,159.546 351,143.5C 351.5,100.835 351.667,58.168 351.5,15.5C 364.171,15.3334 376.838,15.5001 389.5,16C 390.833,16.6667 392.167,17.3333 393.5,18C 426.667,51.1667 459.833,84.3333 493,117.5C 493.667,118.833 494.333,120.167 495,121.5C 495.667,233.5 495.667,345.5 495,457.5C 489.833,477.333 477.333,489.833 457.5,495C 443.504,495.5 429.504,495.667 415.5,495.5C 415.667,447.499 415.5,399.499 415,351.5C 413.214,327.379 400.714,311.879 377.5,305C 336.854,303.863 296.187,303.363 255.5,303.5C 215.144,303.36 174.811,303.86 134.5,305C 110.729,311.438 97.8956,326.938 96,351.5C 95.5,399.499 95.3333,447.499 95.5,495.5C 81.496,495.667 67.496,495.5 53.5,495C 33.6667,489.833 21.1667,477.333 16,457.5C 15.3333,322.833 15.3333,188.167 16,53.5C 21.3488,33.6518 33.8488,20.9852 53.5,15.5 Z"/></g>
                        <g><path style="opacity:1" d="M 191.5,15.5 C 234.167,15.5 276.833,15.5 319.5,15.5C 319.5,58.1667 319.5,100.833 319.5,143.5C 276.833,143.5 234.167,143.5 191.5,143.5C 191.5,100.833 191.5,58.1667 191.5,15.5 Z"/></g>
                        <g><path style="opacity:0.999" d="M 137.5,335.5 C 216.167,335.333 294.834,335.5 373.5,336C 378,337.833 381.167,341 383,345.5C 383.5,395.499 383.667,445.499 383.5,495.5C 298.167,495.5 212.833,495.5 127.5,495.5C 127.333,445.499 127.5,395.499 128,345.5C 130.022,340.98 133.189,337.647 137.5,335.5 Z"/></g>
                    </svg>
                    Zatwierdź
                </button>
            </form>
        </div>
    </div>
</x-layout>
