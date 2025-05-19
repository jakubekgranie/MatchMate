@props(['heading' => "Formularz"])
<form {{ $attributes->merge(['class' => "m-auto w-fit border border-gray-500/45 rounded-2xl px-7 py-11 bg-gray-100/60 my-13"]) }}>
    @csrf
    <div class="space-y-12">
        <div class="border-b border-gray-900/10 pb-12">
            <h1 class="text-4xl font-bold text-gray-900 text-center mb-7.5">{{ $heading }}</h1>
            <div class="mt-10 grid grid-cols-1 gap-x-5 gap-y-6 sm:grid-cols-8">
                {{ $slot }}
                <div class="sm:col-span-6 sm:col-start-2 font-medium flex items-center -mt-4 -mb-5 cursor-pointer">
                    <input type="checkbox" name="remember" id="remember" class="cursor-pointer accent-indigo-600">
                    <label for="remember" class="sm:text-sm/6 cursor-pointer">&nbsp;&nbsp;Zapamiętaj mnie</label>
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
