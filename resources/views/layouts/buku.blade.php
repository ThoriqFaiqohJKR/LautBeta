<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- DearFlip -->
    <link rel="stylesheet" href="/dflip/css/dflip.min.css">
    <script src="/dflip/js/libs/jquery.min.js"></script>
    <script src="/dflip/js/dflip.js"></script>
</head>

<body class="flex flex-col min-h-screen bg-white text-slate-800 overflow-x-hidden">




    <main class="flex-1">
        @yield('content')
    </main>
    @livewireScripts
</body>

</html>
