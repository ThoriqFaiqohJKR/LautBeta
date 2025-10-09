<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>@yield('title', 'Dashboard')</title>
  <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @vite('resources/css/app.css')
  @livewireStyles

</head>

<body class="flex flex-col min-h-screen bg-white text-slate-800 overflow-x-hidden">




  <main class="flex-1">
    @yield('content')
  </main>
  @livewireScripts
</body>

</html>