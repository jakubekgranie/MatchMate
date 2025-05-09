<x-layout :flex-center="true">
    <x-account-form method="POST" action="/register" heading="Zarejestruj się">
        <x-form-input name="name" placeholder="Jan" required>Imię</x-form-input>
        <x-form-input name="surname" placeholder="Kowalski" required>Nazwisko</x-form-input>
        <x-form-input name="email" type="email" autocomplete="email" placeholder="jan.kowalski@poczta.pl" required>Adres email</x-form-input>
        <x-form-input name="password" type="password" required>Hasło</x-form-input>
        <x-form-input name="password_confirmation" type="password" required>Powtórz hasło</x-form-input>
    </x-account-form>
</x-layout>
