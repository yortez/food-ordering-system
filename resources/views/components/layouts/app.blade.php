<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Food Ordering System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/filament/filament/sidebar/style.css') }}">
    @livewireStyles

</head>
<body class="bg-gray-100">
      {{-- @livewire('partials.sidebar') --}}
    <main>
        {{ $slot }}

    </main>
    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sidebar/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sidebar/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sidebar/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sidebar/popper.js') }}"></script>
</body>
</html>

