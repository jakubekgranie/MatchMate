@if(session("title"))
    <div id="notification" style="transform: translateX(-50%)" class="hidden bg-green-600 fixed -top-10 left-[50%] items-center justify-between max-w-90 rounded-md px-7 py-2.5 shadow-md gap-2 duration-300 ease-out animate-popup"> <!-- A notification -->
        <img id="notification_icon" src="{{ Vite::asset("resources/images/check_mark.png") }}" class="shrink-0 w-8 aspect-square mr-3" alt="Ikona powodzenia">
        <p id="notification_feed" class="text-gray-50 font-semibold">Sukces!</p>
    </div>
    <div class="hidden bg-red-600 flex"></div> <!-- Class renderer, flex added for ubiquity -->
    <script>
        window.addEventListener('load', () => notify({{ Js::from(session('title')) }}, {{ Js::from(session('theme') ? session('theme') : 0) }}));
    </script>
@endif
