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
    <!--
    <meta http-equiv="Content-Security-Policy" content="
        default-src 'self' http://[::1]:5173;
        font-src https://fonts.gstatic.com"
    >

    <meta name="author" content="">
    <link rel="canonical" href="">
    <link rel="icon" type="image/png" href="">
    -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>MatchMate</title>
</head>
<body>
    <x-nav-bar/>
    {{ $slot }}
</body>
</html>
