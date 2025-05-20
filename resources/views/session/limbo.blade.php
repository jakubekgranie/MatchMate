<x-layout page-title="Profil" :flex-center="true" class="mb-23">
    <x-update-form-container class="max-w-[33%]">
        <h1 class="font-bold text-2xl">Oczekiwanie na weryfikację</h1><br>
        <p class="text-lg">Cześć <b>{{ $user->name }}</b>,<br>
            Twoje konto oczekuje na weryfikację przez kapitana drużyny <strong>{{ $user->team->name }}</strong>. Do czasu wydania werdyktu, jego funkcjonalność jest ograniczona. Zostaniesz powiadomiony mailowo w momencie zmian.<br>
            <br>
            Pozdrawiamy,<br>
            <strong>Zespół MatchMate</strong>
        </p>
    </x-update-form-container>
</x-layout>
