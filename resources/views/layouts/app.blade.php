<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen" style="background-color: #1f1f1f; color: #CDFDE6;">
    <!-- Header -->
    @include('layouts.header')

    <!-- Page Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="fixed top-20 right-4 z-50 max-w-sm">
        <div class="bg-green-900/20 border border-green-500/30 rounded-xl p-4 backdrop-blur-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-[#CDFDE6]">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-20 right-4 z-50 max-w-sm">
        <div class="bg-red-900/20 border border-red-500/30 rounded-xl p-4 backdrop-blur-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-[#CDFDE6]">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Auto-hide flash messages -->
    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const messages = document.querySelectorAll('.fixed.top-20.right-4');
            messages.forEach(function(message) {
                message.style.opacity = '0';
                message.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    message.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>

</html>
