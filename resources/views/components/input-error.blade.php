<p id="{{ $name }}_error" class="text-red-500 text-sm pl-1 font-semibold hidden">
    @error($name) {{ $message }} @enderror
</p>
@error($name)
{{-- Allows the profile.js to work with validation --}}
    <script type="module">document.getElementById("{{ $name }}_error").classList.remove("hidden")</script>
@enderror
