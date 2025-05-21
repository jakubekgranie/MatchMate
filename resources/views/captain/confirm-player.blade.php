<x-layout page-title="Wniosek użytkownika {{ $user->name }} {{ $user->surname }}" :flex-center="true">
    <x-update-form-container>
        <h1 class="text-center mb-4.5 text-xl font-semibold">Wniosek użytkownika {{ $user->name }} {{ $user->surname }}</h1>
        <x-profile-card :$user :captain-mode="true"/>
    </x-update-form-container>
</x-layout>
