<x-layout :flex-center="true" page-title="login">
    <x-account-form method="post" action="/login" heading="Zaloguj się">
        <x-form-input name="email" maxlength="254" container-class="w-44" type="email" autocomplete="email" placeholder="jan.kowalski@poczta.pl" required>Adres email</x-form-input>
        <x-form-input name="password" maxlength="64" container-class="w-44" type="password" required>Hasło</x-form-input>
    </x-account-form>
</x-layout>
