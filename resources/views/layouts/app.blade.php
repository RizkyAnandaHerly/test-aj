<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WarehouseTrack') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>

        @vite(['resources/js/app.js'])
    </head>
    <body class="bg-light antialiased">
        <div class="min-vh-100 d-flex flex-column">
            
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow-sm border-bottom">
                    <div class="container py-3">
                        <h4 class="mb-0 fw-bold text-dark">{{ $header }}</h4>
                    </div>
                </header>
            @endisset

            <main class="flex-grow-1 py-4">
                <div class="container">
                    {{ $slot }}
                </div>
            </main>
            
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>