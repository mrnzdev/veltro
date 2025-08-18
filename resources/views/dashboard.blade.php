<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen" style="background-color: #1f1f1f; color: #CDFDE6;">
    <!-- Navigation -->
    <nav class="bg-[#2a2a2a] shadow-sm border-b border-[#3a3a3a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-[#CDFDE6]">{{ config('app.name', 'Laravel') }}</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                        <div class="h-8 w-8 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center hover:scale-110 transition-transform duration-200">
                            <span class="text-sm font-medium text-[#1f1f1f]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 hover:text-[#01FF87] transition-colors duration-200">{{ Auth::user()->email }}</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-[#3a3a3a] shadow-sm text-sm leading-4 font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-8 bg-green-900/20 border border-green-500/30 rounded-xl p-4">
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
        @endif

        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">¡Bienvenido de vuelta, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-gray-400">Aquí está lo que está pasando con tu cuenta hoy.</p>
        </div>
    </main>
</body>

</html>