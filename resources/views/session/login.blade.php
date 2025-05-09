<x-layout :flex-center="true" page-title="login">
    <x-account-form method="POST" action="/login" heading="Zaloguj się">
        <x-form-input name="email" type="email" autocomplete="email" placeholder="jan.kowalski@poczta.pl" required>Adres email</x-form-input>
        <x-form-input name="password" type="password" required>Hasło</x-form-input>
    </x-account-form>
</x-layout>
