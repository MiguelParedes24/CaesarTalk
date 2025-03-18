<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        .swal2-popup {
            width: 400px !important;
            height: 400px !important;
            font-size: 14px !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased bg-gray-500 ">
    <div class="flex flex-col min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main
            class="flex flex-col mt-20 p-8 sm:ml-64 min-h-[calc(100vh-5rem)] overflow-x-hidden overflow-y-auto box-border">
            @yield('content')
        </main>
    </div>
    @auth
        <script src="{{ asset('js/general.js') }}"></script>
        @if (request()->routeIs('messages.*') || request()->routeIs('search.users'))
            {{-- Si no esta en estas rutas, no hace uso del script --}}
            <script src="{{ asset('js/messages.js') }}"></script>
        @endif
        @if (request()->routeis('users.*'))
            <script src="{{ asset('js/users.js') }}"></script>
        @endif
    @endauth
    <script src="{{ asset('js/validate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>


</html>
