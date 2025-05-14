<x-layout page-title="Profil" :flex-center="true">
    <div class="outline-lime-400 hidden"></div> <!-- A class present only in js -->
    <x-slot:scripts>
        <script type="module" src="{{Vite::asset("resources/js/profile.js")}}"></script>
        <script type="module" src="{{Vite::asset("resources/js/clickableCard.js")}}"></script>
    </x-slot:scripts>
    <div class="border-2 border-gray-500/65 bg-white/30 rounded-2xl flex items-center py-7 px-9.5 shadow-sm shadow-gray-300">
        <div>
            <h1 class="text-2xl text-gray-950 font-bold mb-6">Edytuj swój profil</h1>
            <x-profile-card :user="\App\Models\User::with('teams')->find(Auth::id())" :preview-mode="true"/>
        </div>
        <div class="h-90 w-0.5 bg-stone-400 mx-8"></div>
        <div class="grid gap-7">
            <x-update-form-container title="Dane podstawowe">
                <form class="grid grid-cols-2 gap-3.5" id="profileForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <x-form-input name="name" maxlength="13" :label="false" placeholder="Imię" grid-override=" " />
                    <x-form-input name="surname" maxlength="35" :label="false" placeholder="Nazwisko" grid-override=" " />
                    <x-form-input name="age" type="number" maxlength="3" min="18" max="120" :label="false" placeholder="Wiek" grid-override=" " />
                    <x-form-input name="height" type="number" maxlength="3" min="55" max="272" :label="false" placeholder="Wzrost (cm)" grid-override=" " />
                    <x-form-input name="weight" type="number" maxlength="3" min="20" max="300" :label="false" placeholder="Waga (kg)" grid-override=" " />
                    <x-save-button id="submission" type="submit" disabled svg-icon-id="svgIconText"/>
                </form>
            </x-update-form-container>
            <x-update-form-container title="Obrazy">
                <form id="imageForm" method="POST" action="/profile/images" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-2 gap-3.5">
                        <input type="file" accept="image/png, image/webp" id="pfp" name="pfp"/>
                        <div id="dnd_pfp" class=" bg-gray-800 hover:bg-gray-700 cursor-pointer rounded-md duration-100 outline-2 outline-gray-500 outline-dashed -outline-offset-8 hover:outline-gray-300 focus-visible:outline-solid">
                            <div class="aspect-square flex justify-center items-center">
                                <svg id="svgPfp" style="fill: oklch(70.7% 0.022 261.325);" viewBox="0 0 512 512" class="svg w-6 h-6 pointer-events-none duration-100">
                                    <g><path style="opacity:0.989" d="M 233.5,-0.5 C 248.167,-0.5 262.833,-0.5 277.5,-0.5C 377.807,11.9842 449.307,63.9842 492,155.5C 502.297,180.687 508.797,206.687 511.5,233.5C 511.5,248.167 511.5,262.833 511.5,277.5C 499.018,377.805 447.018,449.305 355.5,492C 330.316,502.296 304.316,508.796 277.5,511.5C 262.833,511.5 248.167,511.5 233.5,511.5C 133.193,499.016 61.693,447.016 19,355.5C 8.70332,330.313 2.20332,304.313 -0.5,277.5C -0.5,262.833 -0.5,248.167 -0.5,233.5C 11.9821,133.195 63.9821,61.6947 155.5,19C 180.684,8.70393 206.684,2.20393 233.5,-0.5 Z M 247.5,76.5 C 291.252,75.7896 320.752,95.7896 336,136.5C 345.891,183.548 329.391,217.715 286.5,239C 243.142,252.438 208.642,240.604 183,203.5C 163.095,163.093 169.262,127.26 201.5,96C 215.164,85.3285 230.497,78.8285 247.5,76.5 Z M 195.5,287.5 C 235.501,287.333 275.501,287.5 315.5,288C 353.683,294.848 377.849,317.015 388,354.5C 393.909,377.123 387.076,394.623 367.5,407C 312.402,445.304 253.068,453.638 189.5,432C 165.507,423.105 144.674,409.605 127,391.5C 120.406,379.412 119.073,366.745 123,353.5C 133.764,316.561 157.931,294.561 195.5,287.5 Z"/></g>
                                </svg>
                            </div>
                        </div>
                        <input type="file" accept="image/png, image/webp" id="banner" name="banner"/>
                        <div id="dnd_banner" class="bg-gray-800 hover:bg-gray-700 cursor-pointer rounded-md duration-100 outline-2 outline-gray-500 outline-dashed -outline-offset-8 hover:outline-gray-300 focus-visible:outline-solid">
                            <div class="aspect-square flex justify-center items-center">
                                <svg id="svgBanner" style="fill: oklch(70.7% 0.022 261.325);" viewBox="0 0 512 512" class="svg w-6.5 h-6.5 pointer-events-none duration-100">
                                    <g><path style="opacity:0.986" d="M 511.5,80.5 C 511.5,197.167 511.5,313.833 511.5,430.5C 506.36,445.132 496.026,453.298 480.5,455C 330.5,455.667 180.5,455.667 30.5,455C 14.9738,453.298 4.6405,445.132 -0.5,430.5C -0.5,313.833 -0.5,197.167 -0.5,80.5C 4.50997,66.3315 14.51,58.1649 29.5,56C 180.167,55.3333 330.833,55.3333 481.5,56C 496.49,58.1649 506.49,66.3315 511.5,80.5 Z M 32.5,89.5 C 181.167,89.5 329.833,89.5 478.5,89.5C 478.667,167.501 478.5,245.501 478,323.5C 456.5,304 435,284.5 413.5,265C 403.5,259 393.5,259 383.5,265C 361.32,287.347 338.986,309.514 316.5,331.5C 272.688,279.187 228.688,227.02 184.5,175C 169.701,167.494 157.201,170.327 147,183.5C 109.237,228.02 71.2368,272.353 33,316.5C 32.5,240.834 32.3333,165.167 32.5,89.5 Z"/></g>
                                    <g><path style="opacity:0.974" d="M 375.5,129.5 C 401.814,126.365 419.648,137.032 429,161.5C 434.166,187.67 424.666,206.17 400.5,217C 372.003,223.837 352.503,213.67 342,186.5C 337.275,157.926 348.442,138.926 375.5,129.5 Z"/></g>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3.5 mt-3.5 h-9">
                        <x-save-button id="imageSubmission" type="submit" disabled svg-icon-id="svgIconImages"/>
                        <x-save-button id="imageReset" type="button" svg-icon-id="svgIconImages" class="bg-red-700 hover:bg-red-600 text-gray-100" fill="oklch(87.2% 0.01 258.338)" text="Przywróć">
                            <g><path style="opacity:0.987" d="M 234.5,31.5 C 295.234,27.6884 348.401,45.6884 394,85.5C 406.5,73 419,60.5 431.5,48C 444.16,38.5213 455.66,39.6879 466,51.5C 467.562,53.9573 468.562,56.624 469,59.5C 469.667,101.167 469.667,142.833 469,184.5C 467.846,192.317 463.679,197.817 456.5,201C 412.559,202.651 368.559,202.984 324.5,202C 310.731,196.627 305.897,186.794 310,172.5C 311.08,170.004 312.414,167.67 314,165.5C 325.167,154.333 336.333,143.167 347.5,132C 300.46,93.5655 248.794,85.8988 192.5,109C 131.059,138.346 99.0589,187.18 96.5,255.5C 98.0577,315.474 124.058,361.307 174.5,393C 232.069,423.713 288.735,422.046 344.5,388C 375.041,366.461 396.541,337.961 409,302.5C 415.491,294.002 423.991,290.836 434.5,293C 442.932,294.619 451.265,296.619 459.5,299C 468.298,304.431 471.798,312.264 470,322.5C 443.738,398.429 392.238,448.262 315.5,472C 226.283,492.83 150.116,470.33 87,404.5C 34.3683,340.689 19.035,268.689 41,188.5C 66.1667,115.333 115.333,66.1667 188.5,41C 203.746,36.7184 219.079,33.5517 234.5,31.5 Z"/></g>
                        </x-save-button>
                    </div>
                </form>
            </x-update-form-container>
        </div>
    </div>
</x-layout>
