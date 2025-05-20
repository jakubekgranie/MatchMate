@props(['flexCenter' => false, 'pageTitle' => false, 'scripts' => '', 'navless' => false])

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Jakub Namyślak">
    <meta name="keywords" content="[!] FILL LATER">
    <meta name="description" content="[!] FILL LATER">
    <meta name="owner" content="[!] FILL LATER">
    <meta name="coverage" content="Worldwide">
    <meta name="rating" content="General">
    <meta name="HandheldFriendly" content="True">
    <meta name="copyright" content="[!] FILL LATER">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&family=Outfit:wght@100..900&family=Praise&display=swap" rel="stylesheet">
    <!--
    <meta http-equiv="Content-Security-Policy" content="
        default-src 'self' http://[::1]:5173;
        font-src https://fonts.gstatic.com"
    >

    <meta name="author" content="">
    <link rel="canonical" href="">
    <link rel="icon" type="image/png" href="">
    -->
    @vite(['resources/js/app.js', 'resources/css/app.css', "resources/js/disableFakeNavBars.js", "resources/js/notificationHandler.js"])
    {{ $scripts }}
    <title>{{ "MatchMate".($pageTitle ? " - $pageTitle" : "") }}</title>
</head>
<body class="{{ request()->is("/") ? "bg-white h-[100vh] overflow-hidden" : "bg-stone-200" }}{{ $flexCenter ? " min-h-[100vh]" : "" }}">
    @if(!$navless)
        <x-nav-bar/>
    @endif
    @if($flexCenter)
        <div class="flex flex-col items-center justify-center min-h-[calc(100vh-5.75rem)]">
    @endif
    <x-notification/>
    <main>
        {{ $slot }}
    </main>
    @if($flexCenter)
        </div>
    @endif
</body>
</html>
