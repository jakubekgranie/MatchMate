<x-layout :flex-center="true">
    <x-account-form method="POST" action="/register" heading="Zarejestruj się">
        <x-form-input name="name" maxlength="13" class="w-44" container-class="w-44" placeholder="Jan" required>Imię</x-form-input>
        <x-form-input name="surname" maxlength="35" class="w-44" container-class="w-44" placeholder="Kowalski" required>Nazwisko</x-form-input>
        <x-form-input name="email" maxlength="254" class="w-44" container-class="w-44" type="email" autocomplete="email" placeholder="jan.kowalski@poczta.pl" required>Adres email</x-form-input>
        <x-form-input name="password" maxlength="64" class="w-44" container-class="w-44" type="password" required>Hasło</x-form-input>
        <x-form-input name="password_confirmation" container-class="w-44" type="password" required>Powtórz hasło</x-form-input>
    </x-account-form>
</x-layout>
