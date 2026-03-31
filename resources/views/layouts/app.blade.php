<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- Chart.js & SweetAlert2 loaded globally so AJAX-navigated chart pages always find them -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@stack('styles')

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100" x-data="{ miniSidebar: false }"
        @sidebar-toggled.window="miniSidebar = $event.detail.miniSidebar">
        @include('layouts.navigation')
        <x-sidebar />

        <main class="transition-all duration-300" :class="miniSidebar ? 'ml-16' : 'ml-72'">
            {{ $slot }}
        </main>
    </div>
    @stack('scripts')
</body>

</html>
