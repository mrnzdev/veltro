<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Veltro - Plataforma para jugadores de fútbol amateur. Encuentra jugadores, crea equipos, organiza partidos y construye tu red de fútbol.">
    <meta name="keywords" content="fútbol amateur, equipos de fútbol, jugadores, partidos, comunidad deportiva">

    <title>Veltro - Tu Plataforma de Fútbol Amateur</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(1, 255, 135, 0.3); }
            50% { box-shadow: 0 0 40px rgba(1, 255, 135, 0.6); }
        }
        
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-[#1f1f1f] text-[#CDFDE6] antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#1a1a1a]/95 backdrop-blur-md border-b border-[#2a2a2a]/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="h-10 w-10 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform duration-300 shadow-lg shadow-[#01FF87]/20">
                        <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-[#CDFDE6] group-hover:text-[#01FF87] transition-colors duration-300">
                        VELTRO
                    </h1>
                </a>

                <!-- Auth Links -->
                @if (Route::has('login'))
                <div class="flex items-center gap-3">
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-4 py-2 text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200">
                        Iniciar sesión
                    </a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-sm font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/30 transition-all duration-200 hover:scale-105">
                        Crear cuenta
                    </a>
                    @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#1a1a1a] via-[#1f1f1f] to-[#1f1f1f]"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#01FF87]/5 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#00e676]/5 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto text-center">
            <div class="mb-8">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#01FF87]/10 border border-[#01FF87]/20 rounded-full mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#01FF87] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#01FF87]"></span>
                    </span>
                    <span class="text-sm font-medium text-[#01FF87]">La plataforma de fútbol amateur</span>
                </div>
            </div>

            <!-- Main Headline -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                <span class="text-[#CDFDE6]">Conecta, Juega,</span>
                <br>
                <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text">Crece en el Fútbol</span>
            </h1>

            <!-- Subheadline -->
            <p class="text-xl sm:text-2xl text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed">
                Únete a la comunidad de jugadores de fútbol amateur. Encuentra compañeros, crea tu equipo y gestiona tus partidos en un solo lugar.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                @guest
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-lg font-bold rounded-xl hover:shadow-2xl hover:shadow-[#01FF87]/40 transition-all duration-300 hover:scale-105 animate-pulse-glow">
                    Comenzar Gratis
                    <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#features"
                    class="w-full sm:w-auto px-8 py-4 bg-[#2a2a2a] text-[#CDFDE6] text-lg font-semibold rounded-xl border border-[#3a3a3a] hover:border-[#01FF87] hover:bg-[#2a2a2a]/80 transition-all duration-300 hover:scale-105">
                    Conocer más
                </a>
                @else
                <a href="{{ url('/dashboard') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-lg font-bold rounded-xl hover:shadow-2xl hover:shadow-[#01FF87]/40 transition-all duration-300 hover:scale-105">
                    Ir al Dashboard
                    <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                @endguest
            </div>

            <!-- Hero Visual Placeholder -->
            <div class="relative max-w-5xl mx-auto">
                <div class="bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] rounded-2xl border border-[#3a3a3a] p-8 shadow-2xl">
                    <div class="aspect-video bg-[#1a1a1a] rounded-xl border border-[#2a2a2a] flex items-center justify-center relative overflow-hidden">
                        <!-- Football Field Illustration -->
                        <svg class="w-full h-full opacity-20" viewBox="0 0 800 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Field -->
                            <rect x="50" y="50" width="700" height="300" rx="10" stroke="#01FF87" stroke-width="2"/>
                            <!-- Center line -->
                            <line x1="400" y1="50" x2="400" y2="350" stroke="#01FF87" stroke-width="2"/>
                            <!-- Center circle -->
                            <circle cx="400" cy="200" r="50" stroke="#01FF87" stroke-width="2"/>
                            <circle cx="400" cy="200" r="5" fill="#01FF87"/>
                            <!-- Left penalty area -->
                            <rect x="50" y="125" width="100" height="150" stroke="#01FF87" stroke-width="2"/>
                            <rect x="50" y="162.5" width="40" height="75" stroke="#01FF87" stroke-width="2"/>
                            <!-- Right penalty area -->
                            <rect x="650" y="125" width="100" height="150" stroke="#01FF87" stroke-width="2"/>
                            <rect x="710" y="162.5" width="40" height="75" stroke="#01FF87" stroke-width="2"/>
                        </svg>
                        
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="h-20 w-20 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                                    <svg class="h-10 w-10 text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1" fill="none"/>
                                        <path d="M12 2 L12 6 M12 18 L12 22 M2 12 L6 12 M18 12 L22 12"/>
                                        <path d="M5 5 L9 9 M15 15 L19 19 M19 5 L15 9 M9 15 L5 19"/>
                                    </svg>
                                </div>
                                <p class="text-[#01FF87] font-semibold text-lg">Vista Previa de la Plataforma</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="mt-16">
                <a href="#features" class="inline-block animate-bounce">
                    <svg class="h-8 w-8 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1f1f1f] to-[#1a1a1a]">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold mb-4">
                    <span class="text-[#CDFDE6]">Todo lo que necesitas</span>
                    <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text"> en un solo lugar</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Herramientas completas para gestionar tu experiencia futbolística
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Find Players -->
                <div class="group bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87] transition-all duration-300 hover:shadow-xl hover:shadow-[#01FF87]/20 hover:-translate-y-2">
                    <div class="h-14 w-14 bg-gradient-to-br from-[#01FF87]/20 to-[#00e676]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3 group-hover:text-[#01FF87] transition-colors">Encuentra Jugadores</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Descubre jugadores cerca de ti con intereses similares. Busca por posición, nivel y disponibilidad para formar el equipo perfecto.
                    </p>
                </div>

                <!-- Feature 2: Create Teams -->
                <div class="group bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87] transition-all duration-300 hover:shadow-xl hover:shadow-[#01FF87]/20 hover:-translate-y-2">
                    <div class="h-14 w-14 bg-gradient-to-br from-[#01FF87]/20 to-[#00e676]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3 group-hover:text-[#01FF87] transition-colors">Crea Equipos</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Organiza tu propio equipo desde cero. Gestiona miembros, define roles y mantén a todos coordinados con facilidad.
                    </p>
                </div>

                <!-- Feature 3: Manage Matches -->
                <div class="group bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87] transition-all duration-300 hover:shadow-xl hover:shadow-[#01FF87]/20 hover:-translate-y-2">
                    <div class="h-14 w-14 bg-gradient-to-br from-[#01FF87]/20 to-[#00e676]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3 group-hover:text-[#01FF87] transition-colors">Gestiona Partidos</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Programa encuentros, coordina horarios y lleva el registro de todos tus partidos en un calendario intuitivo.
                    </p>
                </div>

                <!-- Feature 4: Build Profile -->
                <div class="group bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87] transition-all duration-300 hover:shadow-xl hover:shadow-[#01FF87]/20 hover:-translate-y-2">
                    <div class="h-14 w-14 bg-gradient-to-br from-[#01FF87]/20 to-[#00e676]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3 group-hover:text-[#01FF87] transition-colors">Construye tu Perfil</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Muestra tus habilidades, posiciones favoritas y experiencia. Haz que otros jugadores te descubran fácilmente.
                    </p>
                </div>

                <!-- Feature 5: Join Teams -->
                <div class="group bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87] transition-all duration-300 hover:shadow-xl hover:shadow-[#01FF87]/20 hover:-translate-y-2">
                    <div class="h-14 w-14 bg-gradient-to-br from-[#01FF87]/20 to-[#00e676]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3 group-hover:text-[#01FF87] transition-colors">Únete a Equipos</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Explora equipos existentes y solicita unirte. Encuentra el grupo perfecto que se ajuste a tu estilo de juego.
                    </p>
                </div>

                <!-- Feature 6: Track Stats -->
                <div class="group bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87] transition-all duration-300 hover:shadow-xl hover:shadow-[#01FF87]/20 hover:-translate-y-2">
                    <div class="h-14 w-14 bg-gradient-to-br from-[#01FF87]/20 to-[#00e676]/20 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3 group-hover:text-[#01FF87] transition-colors">Analiza tus Stats</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Monitorea tu rendimiento, partidos jugados y progreso. Visualiza tu evolución como jugador con estadísticas detalladas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1a1a1a] to-[#1f1f1f]">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold mb-4">
                    <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text">¿Por qué elegir Veltro?</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    La plataforma diseñada específicamente para la comunidad de fútbol amateur
                </p>
            </div>

            <!-- Benefits Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Benefit 1 -->
                <div class="relative bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border-2 border-[#3a3a3a] rounded-2xl p-8 overflow-hidden group hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#01FF87]/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Conecta con Jugadores Locales</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Encuentra compañeros de tu zona con habilidades complementarias. Amplía tu red y descubre nuevas oportunidades de juego cada semana.
                        </p>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="relative bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border-2 border-[#3a3a3a] rounded-2xl p-8 overflow-hidden group hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#01FF87]/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Organiza Partidos Fácilmente</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Olvídate de las complicaciones. Programa partidos, coordina ubicaciones y confirma asistencias con herramientas intuitivas.
                        </p>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="relative bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border-2 border-[#3a3a3a] rounded-2xl p-8 overflow-hidden group hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#01FF87]/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Construye tu Red de Fútbol</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Forma parte de una comunidad activa. Crea relaciones duraderas y expande tu círculo futbolístico más allá del campo.
                        </p>
                    </div>
                </div>

                <!-- Benefit 4 -->
                <div class="relative bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border-2 border-[#3a3a3a] rounded-2xl p-8 overflow-hidden group hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#01FF87]/10 to-transparent rounded-bl-full"></div>
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Monitorea tu Progreso</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Visualiza tu evolución con estadísticas detalladas. Establece metas y celebra tus logros mientras mejoras como jugador.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Community/Social Proof Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1f1f1f] to-[#1a1a1a]">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold mb-4">
                    <span class="text-[#CDFDE6]">Únete a</span>
                    <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text"> Nuestra Comunidad</span>
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Miles de jugadores ya están disfrutando del fútbol amateur de una manera nueva
                </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <div class="text-center">
                    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87]/50 transition-all duration-300">
                        <div class="text-5xl font-bold bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text mb-3">500+</div>
                        <div class="text-xl text-[#CDFDE6] font-semibold mb-2">Jugadores Activos</div>
                        <div class="text-gray-400">En crecimiento cada día</div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87]/50 transition-all duration-300">
                        <div class="text-5xl font-bold bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text mb-3">150+</div>
                        <div class="text-xl text-[#CDFDE6] font-semibold mb-2">Equipos Formados</div>
                        <div class="text-gray-400">Y sumando más</div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#1a1a1a] border border-[#3a3a3a] rounded-2xl p-8 hover:border-[#01FF87]/50 transition-all duration-300">
                        <div class="text-5xl font-bold bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text mb-3">1,200+</div>
                        <div class="text-xl text-[#CDFDE6] font-semibold mb-2">Partidos Organizados</div>
                        <div class="text-gray-400">Cada mes</div>
                    </div>
                </div>
            </div>

            <!-- Testimonials -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-6 hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-[#1f1f1f]">JS</span>
                        </div>
                        <div>
                            <div class="font-semibold text-[#CDFDE6]">Juan Silva</div>
                            <div class="text-sm text-gray-400">Delantero</div>
                        </div>
                    </div>
                    <p class="text-gray-400 italic leading-relaxed">
                        "Encontré el equipo perfecto en mi zona. Ahora juego todos los fines de semana y he hecho grandes amigos."
                    </p>
                    <div class="flex mt-4 text-[#01FF87]">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-6 hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-[#1f1f1f]">MR</span>
                        </div>
                        <div>
                            <div class="font-semibold text-[#CDFDE6]">María Rodríguez</div>
                            <div class="text-sm text-gray-400">Mediocampista</div>
                        </div>
                    </div>
                    <p class="text-gray-400 italic leading-relaxed">
                        "La plataforma es súper fácil de usar. Organizar partidos nunca fue tan simple. ¡Totalmente recomendado!"
                    </p>
                    <div class="flex mt-4 text-[#01FF87]">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-[#2a2a2a] border border-[#3a3a3a] rounded-2xl p-6 hover:border-[#01FF87]/50 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-[#1f1f1f]">CG</span>
                        </div>
                        <div>
                            <div class="font-semibold text-[#CDFDE6]">Carlos Gómez</div>
                            <div class="text-sm text-gray-400">Defensa</div>
                        </div>
                    </div>
                    <p class="text-gray-400 italic leading-relaxed">
                        "Formé mi equipo desde cero y ahora competimos regularmente. Veltro hace que todo sea muy profesional."
                    </p>
                    <div class="flex mt-4 text-[#01FF87]">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1a1a1a] to-[#1f1f1f] relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full bg-gradient-to-r from-[#01FF87]/5 via-transparent to-[#00e676]/5 blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto text-center relative">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
                <span class="text-[#CDFDE6]">¿Listo para</span>
                <br>
                <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text">Comenzar a Jugar?</span>
            </h2>
            
            <p class="text-xl text-gray-400 mb-12 max-w-2xl mx-auto">
                Únete a cientos de jugadores que ya están disfrutando del fútbol amateur de una manera más organizada y divertida.
            </p>

            @guest
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-lg font-bold rounded-xl hover:shadow-2xl hover:shadow-[#01FF87]/40 transition-all duration-300 hover:scale-105">
                    Crear cuenta gratis
                    <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto px-10 py-5 bg-[#2a2a2a] text-[#CDFDE6] text-lg font-semibold rounded-xl border border-[#3a3a3a] hover:border-[#01FF87] hover:bg-[#2a2a2a]/80 transition-all duration-300 hover:scale-105">
                    Ya tengo cuenta
                </a>
            </div>

            <p class="mt-8 text-sm text-gray-500">
                Sin tarjeta de crédito requerida • Gratis para siempre • Comienza en 2 minutos
            </p>
            @else
            <a href="{{ url('/dashboard') }}"
                class="inline-block px-10 py-5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-lg font-bold rounded-xl hover:shadow-2xl hover:shadow-[#01FF87]/40 transition-all duration-300 hover:scale-105">
                Ir a mi Dashboard
                <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1a1a1a] border-t border-[#2a2a2a] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mr-3">
                            <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#CDFDE6]">VELTRO</h3>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        La plataforma definitiva para jugadores de fútbol amateur. Conecta, organiza y crece en el deporte que amas.
                    </p>
                    <!-- Social Media -->
                    <div class="flex space-x-4">
                        <a href="#" class="h-10 w-10 bg-[#2a2a2a] hover:bg-[#01FF87] border border-[#3a3a3a] hover:border-[#01FF87] rounded-lg flex items-center justify-center transition-all duration-300 group">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="h-10 w-10 bg-[#2a2a2a] hover:bg-[#01FF87] border border-[#3a3a3a] hover:border-[#01FF87] rounded-lg flex items-center justify-center transition-all duration-300 group">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="h-10 w-10 bg-[#2a2a2a] hover:bg-[#01FF87] border border-[#3a3a3a] hover:border-[#01FF87] rounded-lg flex items-center justify-center transition-all duration-300 group">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Platform -->
                <div>
                    <h4 class="text-[#CDFDE6] font-semibold mb-4">Plataforma</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-[#01FF87] transition-colors">Características</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Precios</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Seguridad</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Actualizaciones</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="text-[#CDFDE6] font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Privacidad</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Términos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Contacto</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors">Soporte</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-[#2a2a2a] pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; {{ date('Y') }} Veltro. Todos los derechos reservados.
                </p>
                <p class="text-gray-500 text-sm">
                    Hecho con <span class="text-[#01FF87]">⚡</span> para la comunidad de fútbol amateur
                </p>
            </div>
        </div>
    </footer>

    <!-- Success Message (if exists from session) -->
    @if(session('success'))
    <div class="fixed bottom-4 right-4 z-50 max-w-sm animate-bounce">
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
</body>

</html>
