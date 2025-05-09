<x-layout page-title="Profil" :flex-center="true">
    <div class="border-2 border-gray-500/65 rounded-2xl flex items-center py-7 px-9.5">
        <div>
            <h1 class="text-2xl font-bold mb-6">Edytuj swój profil</h1>
            <x-profile-card :user="\App\Models\User::with('teams')->find(Auth::id())"/>
        </div>
        <div class="h-70 w-0.5 bg-stone-400 mx-8"></div>
        <form class="m-auto w-fit border border-gray-500/45 rounded-2xl px-4 py-7 bg-gray-100/60" method="POST" >
            @csrf
            @method('PATCH')
            <x-form-input :label="false" placeholder="Imię" />
        </form>
    </div>
</x-layout>
