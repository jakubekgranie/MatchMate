<x-layout :flex-center="true">
    <form class="m-auto w-fit border border-gray-500/45 rounded-2xl px-7 py-11 bg-gray-100/60 mt-10" method="POST" action="/register">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h1 class="text-4xl font-bold text-gray-900 text-center mb-7.5">Zarejestruj się</h1>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
                    <div class="sm:col-span-6 sm:col-start-2">
                        <label for="name" class="block text-sm/6 font-medium text-gray-900">Imię</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-400/75 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="name" id="name" class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="Jan">
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-6 sm:col-start-2">
                        <label for="surname" class="block text-sm/6 font-medium text-gray-900">Nazwisko</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-400/75 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="surname" id="surname" class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="Kowalski">
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-6 sm:col-start-2">
                        <label for="email" class="block text-sm/6 font-medium text-gray-900">Adres email</label>
                        <div class="mt-2">
                            <x-form-input id="email" name="email" type="email" autocomplete="email" placeholder="jan.kowalski@poczta.pl"/>
                        </div>
                    </div>

                    <div class="sm:col-span-6 sm:col-start-2">
                        <label for="password" class="block text-sm/6 font-medium text-gray-900">Hasło</label>
                        <div class="mt-2">
                            <x-form-input id="password" name="password" type="password"/>
                        </div>
                    </div>

                    <div class="sm:col-span-6 sm:col-start-2">
                        <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900">Powtórz hasło</label>
                        <div class="mt-2">
                            <x-form-input id="password_confirmation" name="password_confirmation" type="password"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-center gap-x-6 text-shadow-lg/12.5">
            <a href="{{ url()->previous() }}">
                <button type="button" class="cursor-pointer text-sm/6 font-semibold text-gray-900">Anuluj</button>
            </a>
            <button type="submit" class="cursor-pointer rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Kontynuuj</button>
        </div>
    </form>
</x-layout>
