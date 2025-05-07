<header class="inset-x-0 top-0 z-50 fixed">
    <nav class="flex items-center justify-between p-6 lg:px-8 {{ !request()->is("/") ? "backdrop-blur-sm shadow-sm" : "" }}" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="/" class="-m-1.5 p-1.5">
                <span class="sr-only">Your Company</span>
                <img class="h-8 w-auto" src="{{ Vite::asset('resources/images/matchmate_icon.png') }}" alt="">
            </a>
        </div>
        <div class="flex lg:hidden">
            <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-10">
            <x-section-anchor class="hover:backdrop-blur-md bg-white/16 hover:bg-stone-100/60 py-2 px-3 border-2 hover:border-black/40 border-gray-700/20 rounded-xl transition duration-100" href="https://www.google.com">Terminarz</x-section-anchor>
            <x-section-anchor class="hover:backdrop-blur-md bg-white/16 hover:bg-stone-100/60 py-2 px-3 border-2 hover:border-black/40 border-gray-700/20 rounded-xl transition duration-100" href="https://www.google.com">Moja drużyna</x-section-anchor>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end gap-2">
            @guest
                <x-section-anchor href="/register" class="px-2.5 py-2 border-2 border-black/35 rounded-xl bg-violet-500/40 text-shadow-2xs/12.5 hover:bg-violet-500/57.5 duration-100">Zarejestruj się</x-section-anchor>
                <x-section-anchor href="/login" class="px-2.5 py-2 border-2 border-black/35 rounded-xl bg-green-400/40 text-shadow-2xs/12.5 hover:bg-green-400/60 duration-100">Zaloguj się&nbsp;<span aria-hidden="true">&rarr;</span></x-section-anchor>
            @endguest
            @auth
                <x-section-anchor href="https://www.google.com" class="px-2.5 py-2 border-2 border-black/20 rounded-xl">Wyloguj się</x-section-anchor>
                <x-section-anchor href="https://www.google.com" class="px-2.5 py-2 border-2 border-black/20 rounded-xl bg-purple-700/35 text-shadow-2xs/12.5">Profil&nbsp;<span aria-hidden="true">&rarr;</span></x-section-anchor>
            @endauth
        </div>
    </nav>
    <!-- Mobile menu, show/hide based on menu open state. -->
    <div class="lg:hidden" role="dialog" aria-modal="true">
        <!-- Background backdrop, show/hide based on slide-over state. -->
        <div class="fixed inset-0 z-50"></div>
        <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
            <div class="flex items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <img class="h-8 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="">
                </a>
                <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">
                        <x-section-anchor href="https://www.google.com" :mobile="true">Terminarz</x-section-anchor>
                        <x-section-anchor href="https://www.google.com" :mobile="true">Moja drużyna</x-section-anchor>
                    </div>
                    <div class="py-6">
                        <x-section-anchor href="/register" :mobile="true">Zarejestruj się</x-section-anchor>
                        <x-section-anchor href="/login" :mobile="true">Zaloguj się</x-section-anchor>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
