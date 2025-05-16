@if(session("theme"))
    <div id="notification" style="transform: translateX(-50%)" class="bg-green-600 fixed flex -top-10 left-[50%] items-center rounded-md px-7 py-2.5 shadow-md gap-2 duration-300 ease-out animate-popup"> <!-- A notification -->
        <img id="notification_icon" src="{{ Vite::asset("resources/images/check_mark.png") }}" class="shrink-0 w-8 aspect-square" alt="Ikona powodzenia">
        <p id="notification_feed" class="text-gray-50 font-semibold">Sukces!</p>
    </div>
    <script type="module" src="{{ Vite::asset("resources/js/notificationHandler.js") }}"></script>
    <script type="module">notify({{ session("title") ? session("title") : "Sukces!" }}, {{ session("theme") }})</script>
@endif
