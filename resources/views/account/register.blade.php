<x-layout :flex-center="true">
    <x-slot:scripts>
        @vite(['resources/js/profile.js'])
    </x-slot:scripts>
    <x-account-form method="POST" id="profileForm" action="/register" heading="Zarejestruj się">
        <x-form-input name="name" maxlength="13" class="w-44" container-class="w-44" placeholder="Jan" required>Imię</x-form-input>
        <x-form-input name="surname" maxlength="35" class="w-44" container-class="w-44" placeholder="Kowalski" required>Nazwisko</x-form-input>
        <x-form-input name="email" maxlength="254" class="w-44" container-class="w-44" type="email" autocomplete="email" placeholder="jan.kowalski@poczta.pl" required>Adres email</x-form-input>
        <div class="sm:col-span-6 sm:col-start-2">
            <label for="team" class="block text-sm/6 font-medium text-gray-900">Drużyna</label>
            <div class="mt-2 w-44">
                <div class="w-44 flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-400/75 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                    <select required name="team" id="team" class="block cursor-pointer min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-500 focus:outline-none sm:text-sm/6">
                        <option selected disabled>-</option>
                        @php
                            foreach (\App\Models\Team::all() as $team)
                                echo "<option>{$team->name}</option>"
                        @endphp
                    </select>
                </div>
                <x-input-error name="team"/>
            </div>
        </div>
        <x-form-input name="password" maxlength="64" class="w-44" container-class="w-44" type="password" required>Hasło</x-form-input>
        <x-form-input name="password_confirmation" container-class="w-44" type="password" required>Powtórz hasło</x-form-input>
    </x-account-form>
</x-layout>
