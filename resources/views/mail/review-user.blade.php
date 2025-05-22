<x-layout :navless="true">
    <x-slot:scripts>
        <style rel="stylesheet">
            @media (min-width: 640px){.sm\:pl-4{padding-left:1rem}}@media (min-width: 768px){.md\:text-3xl{font-size:1.875rem;line-height:2.25rem}.md\:text-lg{font-size:1.125rem;line-height:1.75rem}}@media (min-width: 1024px){.lg\:text-center{text-align:center}}
        </style>
    </x-slot:scripts>
    <header
        class="flex justify-center items-center gap-3 bg-lime-700 py-3"
        style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; --tw-bg-opacity: 1; background-color: rgb(77 124 15); padding-top: 0.75rem; padding-bottom: 0.75rem;">
        <img src="{{ Vite::asset("resources/images/matchmate_title.png") }}" style="height: 80%;" alt="MatchMate logo"/>
    </header>
    <main
        class="px-5 py-7 bg-stone-200 text-gray-950 sm:pl-4"
        style="font-family: system-ui; background-color: rgb(231 229 228); padding: 1.75rem 10%;">
        <div class="mx-12" style="margin-left: 3rem; margin-right: 3rem;">
            <h1
                class="md:text-3xl font-bold lg:text-center mb-6"
                style="font-size: inherit; font-weight: 700; margin: 0 0 1.5rem;">
                Wniosek użytkownika {{ "$user->name $user->surname" }}
            </h1>
            <div class="md:text-lg max-w-140 mb-2" style="margin: 0 auto 0.5rem auto; text-align: center">
                <p class="mb-3" style="margin: 0 0 0.75rem;">
                    Cześć
                    <strong style="font-weight: bolder;">{{ $recipient }}</strong>,
                </p>
                <p style="margin: 0;">Użytkownik <strong>{{ "$user->name $user->surname" }} ({{ $user->email }})</strong> złożył wniosek o dodanie do drużyny.</p>
            </div>
            <div style="margin: 2rem 0">
                <a href="https://jn.plesk.netk.pl/profile/action/{{ $uuid }}/dashboard">
                    <p style="text-decoration: underline; color: darkblue; text-align: center; margin: 6px;">Kliknij tutaj, by rozważyć prośbę.</p>
                </a>
                <a href="https://jn.plesk.netk.pl/profile/action/{{ $uuid }}/accept">
                    <p style="text-decoration: underline; color: darkblue; text-align: center; margin: 6px;">Kliknij tutaj, by bezpośrednio zaakceptować prośbę.</p>
                </a>
                <a href="https://jn.plesk.netk.pl/profile/action/{{ $uuid }}/reject">
                    <p style="text-decoration: underline; color: darkblue; text-align: center; margin: 6px;">Kliknij tutaj, by bezpośrednio odrzucić prośbę.</p>
                </a>
            </div>
            <div class="md:text-lg max-w-140 mb-2 text-center" style="text-align: center; margin: 0 auto 0.5rem auto;">
                Z poważaniem,
                <p style="margin: 0;"><strong style="font-weight: bolder;">Zespół MatchMate</strong></p>
            </div>
        </div>
        <div
            class="w-full my-5 bg-stone-400 rounded-sm h-1 m-auto"
            style="margin: 1.25rem auto; height: 0.25rem; width: 100%; border-radius: 0.125rem; --tw-bg-opacity: 1; background-color: rgb(168 162 158);"></div>
        <p
            class="text-center text-gray-800 font-bold"
            style="margin: 0; text-align: center; font-weight: 700; --tw-text-opacity: 1; color: rgb(31 41 55);">
            © 2025 MatchMate. Wszystkie prawa zastrzeżone.
        </p>
    </main>
</x-layout>
