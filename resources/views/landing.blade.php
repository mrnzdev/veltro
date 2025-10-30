<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Veltro - Plataforma para jugadores de fútbol amateur. Encuentra jugadores, crea equipos, organiza partidos y construye tu red de fútbol.">
    <meta name="keywords" content="fútbol amateur, equipos de fútbol, jugadores, partidos, comunidad deportiva">

    <title>Veltro - Plataforma de Fútbol Amateur</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Subtle Float Animation */
        @keyframes float-subtle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float-subtle {
            animation: float-subtle 8s ease-in-out infinite;
        }
        
        /* Fade In Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Stagger delays for cards */
        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }
        .stagger-5 { animation-delay: 0.5s; opacity: 0; }
        .stagger-6 { animation-delay: 0.6s; opacity: 0; }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body class="bg-[#1f1f1f] text-[#CDFDE6] antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#1a1a1a]/95 backdrop-blur-md border-b border-[#2a2a2a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="h-10 w-10 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform duration-300">
                        <svg class="h-5 w-5 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-[#CDFDE6] tracking-tight group-hover:text-[#01FF87] transition-colors duration-300">
                        VELTRO
                    </span>
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
                        class="hidden sm:inline-block px-4 py-2 text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        Iniciar sesión
                    </a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-sm font-semibold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 transition-all duration-200">
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
    <section class="relative min-h-screen flex items-center justify-center pt-20 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#1a1a1a] via-[#1f1f1f] to-[#1f1f1f]"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#01FF87]/3 rounded-full blur-3xl animate-float-subtle"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#00e676]/3 rounded-full blur-3xl animate-float-subtle" style="animation-delay: -4s;"></div>
        </div>

        <div class="relative max-w-6xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#01FF87]/5 border border-[#01FF87]/10 rounded-full mb-8 fade-in-up">
                <span class="inline-flex h-2 w-2 rounded-full bg-[#01FF87]"></span>
                <span class="text-sm font-medium text-[#01FF87]">Plataforma de Fútbol Amateur</span>
            </div>

            <!-- Main Headline -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-extrabold mb-6 leading-[1.1] tracking-tight fade-in-up stagger-1">
                <span class="text-[#CDFDE6]">Conecta, Juega y</span>
                <br>
                <span class="bg-gradient-to-r from-[#01FF87] via-[#00e676] to-[#01FF87] text-transparent bg-clip-text">Crece en el Fútbol</span>
            </h1>

            <!-- Subheadline -->
            <p class="text-lg sm:text-xl lg:text-2xl text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed fade-in-up stagger-2">
                La plataforma que conecta a jugadores de fútbol amateur. Encuentra compañeros, organiza tu equipo y gestiona partidos en un solo lugar.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16 fade-in-up stagger-3">
                @guest
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-base font-semibold rounded-lg shadow-lg shadow-[#01FF87]/25 hover:shadow-xl hover:shadow-[#01FF87]/30 transition-all duration-300 hover:-translate-y-0.5">
                    Comenzar gratis
                    <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="#features"
                    class="w-full sm:w-auto px-8 py-4 bg-transparent text-[#CDFDE6] text-base font-medium rounded-lg border border-[#3a3a3a] hover:border-[#01FF87]/50 hover:bg-[#2a2a2a]/30 transition-all duration-300">
                    Ver características
                </a>
                @else
                <a href="{{ url('/dashboard') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-base font-semibold rounded-lg shadow-lg shadow-[#01FF87]/25 hover:shadow-xl hover:shadow-[#01FF87]/30 transition-all duration-300 hover:-translate-y-0.5">
                    Ir al Dashboard
                    <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                @endguest
            </div>

            <!-- Hero Visual -->
            <div class="relative max-w-5xl mx-auto fade-in-up stagger-4">
                <div class="relative rounded-xl overflow-hidden border border-[#2a2a2a] shadow-2xl">
                    <div class="aspect-[16/9] bg-gradient-to-br from-[#2a2a2a] via-[#1a1a1a] to-[#2a2a2a] flex items-center justify-center relative">
                        <!-- Abstract Gradient Mesh -->
                        <div class="absolute inset-0">
                            <div class="absolute top-0 left-1/4 w-1/2 h-1/2 bg-[#01FF87]/10 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 right-1/4 w-1/2 h-1/2 bg-[#00e676]/10 rounded-full blur-3xl"></div>
                        </div>
                        
                        <!-- Football Field Grid -->
                        <svg class="w-full h-full opacity-10" viewBox="0 0 800 450" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="50" y="50" width="700" height="350" rx="4" stroke="#01FF87" stroke-width="1.5"/>
                            <line x1="400" y1="50" x2="400" y2="400" stroke="#01FF87" stroke-width="1.5"/>
                            <circle cx="400" cy="225" r="50" stroke="#01FF87" stroke-width="1.5"/>
                            <circle cx="400" cy="225" r="3" fill="#01FF87"/>
                            <rect x="50" y="137.5" width="80" height="175" stroke="#01FF87" stroke-width="1.5"/>
                            <rect x="670" y="137.5" width="80" height="175" stroke="#01FF87" stroke-width="1.5"/>
                        </svg>
                        
                        <!-- Center Icon -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-20 w-20 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center shadow-lg">
                                <svg class="h-10 w-10 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 lg:py-32 px-4 sm:px-6 lg:px-8 bg-[#1f1f1f]">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20 reveal">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight tracking-tight">
                    <span class="text-[#CDFDE6]">Todo lo que necesitas</span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
                    Herramientas completas para gestionar tu experiencia futbolística
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-12 w-12 bg-[#01FF87]/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-[#01FF87]/50">01</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#CDFDE6] mb-3">Encuentra Jugadores</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Conecta con jugadores de tu zona que compartan tu pasión. Busca por posición, nivel y disponibilidad.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-12 w-12 bg-[#01FF87]/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-[#01FF87]/50">02</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#CDFDE6] mb-3">Crea Equipos</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Organiza tu propio equipo desde cero. Gestiona miembros, define roles y coordina a todos fácilmente.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-12 w-12 bg-[#01FF87]/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-[#01FF87]/50">03</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#CDFDE6] mb-3">Gestiona Partidos</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Programa encuentros, coordina horarios y mantén el registro de tus partidos en un calendario intuitivo.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-12 w-12 bg-[#01FF87]/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-[#01FF87]/50">04</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#CDFDE6] mb-3">Construye tu Perfil</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Muestra tus habilidades, posiciones favoritas y experiencia para que otros te descubran fácilmente.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-12 w-12 bg-[#01FF87]/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-[#01FF87]/50">05</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#CDFDE6] mb-3">Únete a Equipos</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Explora equipos existentes y solicita unirte. Encuentra el grupo perfecto que se ajuste a tu estilo.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-12 w-12 bg-[#01FF87]/10 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-[#01FF87]/50">06</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#CDFDE6] mb-3">Analiza tus Stats</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Monitorea tu rendimiento y progreso. Visualiza tu evolución como jugador con estadísticas detalladas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 lg:py-32 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1f1f1f] to-[#1a1a1a]">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20 reveal">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight tracking-tight">
                    <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text">¿Por qué Veltro?</span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
                    La plataforma diseñada para la comunidad de fútbol amateur
                </p>
            </div>

            <!-- Benefits Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <!-- Benefit 1 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/20 transition-all duration-300">
                    <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mb-6">
                        <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Conecta Localmente</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Encuentra jugadores de tu zona con habilidades complementarias. Amplía tu red y descubre nuevas oportunidades de juego.
                    </p>
                </div>

                <!-- Benefit 2 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/20 transition-all duration-300">
                    <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mb-6">
                        <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Organiza Fácilmente</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Programa partidos, coordina ubicaciones y confirma asistencias con herramientas intuitivas y eficientes.
                    </p>
                </div>

                <!-- Benefit 3 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/20 transition-all duration-300">
                    <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mb-6">
                        <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Construye tu Red</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Forma parte de una comunidad activa. Crea relaciones duraderas y expande tu círculo futbolístico.
                    </p>
                </div>

                <!-- Benefit 4 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8 hover:border-[#01FF87]/20 transition-all duration-300">
                    <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mb-6">
                        <svg class="h-6 w-6 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#CDFDE6] mb-3">Mide tu Progreso</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Visualiza tu evolución con estadísticas detalladas. Establece metas y celebra tus logros deportivos.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats & Social Proof Section -->
    <section class="py-20 lg:py-32 px-4 sm:px-6 lg:px-8 bg-[#1a1a1a]">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16 lg:mb-20 reveal">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight tracking-tight">
                    <span class="text-[#CDFDE6]">Únete a la</span>
                    <span class="bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text"> Comunidad</span>
                </h2>
                <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
                    Jugadores de todo el país ya disfrutan del fútbol amateur de manera organizada
                </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20 reveal">
                <div class="text-center bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8">
                    <div class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text mb-2">500+</div>
                    <div class="text-lg sm:text-xl text-[#CDFDE6] font-semibold mb-1">Jugadores Activos</div>
                    <div class="text-sm text-gray-500">Conectados cada semana</div>
                </div>

                <div class="text-center bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8">
                    <div class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text mb-2">150+</div>
                    <div class="text-lg sm:text-xl text-[#CDFDE6] font-semibold mb-1">Equipos Creados</div>
                    <div class="text-sm text-gray-500">En toda la plataforma</div>
                </div>

                <div class="text-center bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-8">
                    <div class="text-5xl sm:text-6xl font-bold bg-gradient-to-r from-[#01FF87] to-[#00e676] text-transparent bg-clip-text mb-2">1,200+</div>
                    <div class="text-lg sm:text-xl text-[#CDFDE6] font-semibold mb-1">Partidos Jugados</div>
                    <div class="text-sm text-gray-500">Este mes</div>
                </div>
            </div>

            <!-- Testimonials -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mr-3">
                            <span class="text-base font-bold text-[#1f1f1f]">JS</span>
                        </div>
                        <div>
                            <div class="font-semibold text-[#CDFDE6]">Juan Silva</div>
                            <div class="text-sm text-gray-400">Delantero</div>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        "Encontré el equipo perfecto en mi zona. Ahora juego todos los fines de semana y he hecho grandes amigos."
                    </p>
                </div>

                <!-- Testimonial 2 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mr-3">
                            <span class="text-base font-bold text-[#1f1f1f]">MR</span>
                        </div>
                        <div>
                            <div class="font-semibold text-[#CDFDE6]">María Rodríguez</div>
                            <div class="text-sm text-gray-400">Mediocampista</div>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        "La plataforma es súper intuitiva. Organizar partidos nunca fue tan simple. Totalmente recomendado."
                    </p>
                </div>

                <!-- Testimonial 3 -->
                <div class="reveal bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center mr-3">
                            <span class="text-base font-bold text-[#1f1f1f]">CG</span>
                        </div>
                        <div>
                            <div class="font-semibold text-[#CDFDE6]">Carlos Gómez</div>
                            <div class="text-sm text-gray-400">Defensa</div>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        "Formé mi equipo desde cero y ahora competimos regularmente. Veltro hace todo muy profesional."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 lg:py-32 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#1a1a1a] to-[#1f1f1f] relative overflow-hidden">
        <!-- Subtle Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#01FF87]/[0.02] via-transparent to-[#00e676]/[0.02]"></div>

        <div class="max-w-4xl mx-auto text-center relative reveal">
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight tracking-tight">
                <span class="text-[#CDFDE6]">¿Listo para jugar?</span>
            </h2>
            
            <p class="text-lg sm:text-xl text-gray-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                Únete a cientos de jugadores que organizan su fútbol amateur de manera más eficiente.
            </p>

            @guest
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
                <a href="{{ route('register') }}"
                    class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-base font-semibold rounded-lg shadow-lg shadow-[#01FF87]/25 hover:shadow-xl hover:shadow-[#01FF87]/30 transition-all duration-300 hover:-translate-y-0.5">
                    Crear cuenta gratis
                    <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto px-10 py-4 bg-transparent text-[#CDFDE6] text-base font-medium rounded-lg border border-[#3a3a3a] hover:border-[#01FF87]/50 hover:bg-[#2a2a2a]/30 transition-all duration-300">
                    Iniciar sesión
                </a>
            </div>

            <p class="text-sm text-gray-500">
                Gratis para siempre · Sin tarjeta de crédito · Listo en 2 minutos
            </p>
            @else
            <a href="{{ url('/dashboard') }}"
                class="inline-block px-10 py-4 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-base font-semibold rounded-lg shadow-lg shadow-[#01FF87]/25 hover:shadow-xl hover:shadow-[#01FF87]/30 transition-all duration-300 hover:-translate-y-0.5">
                Ir al Dashboard
                <svg class="inline-block ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1a1a1a] border-t border-[#2a2a2a] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-[#CDFDE6] tracking-tight">VELTRO</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md leading-relaxed">
                        La plataforma definitiva para jugadores de fútbol amateur. Conecta, organiza y crece en el deporte que amas.
                    </p>
                    <!-- Social Media -->
                    <div class="flex space-x-3">
                        <a href="#" class="h-10 w-10 bg-[#2a2a2a] hover:bg-[#01FF87] border border-[#3a3a3a] hover:border-[#01FF87] rounded-lg flex items-center justify-center transition-all duration-200 group">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="h-10 w-10 bg-[#2a2a2a] hover:bg-[#01FF87] border border-[#3a3a3a] hover:border-[#01FF87] rounded-lg flex items-center justify-center transition-all duration-200 group">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                        <a href="#" class="h-10 w-10 bg-[#2a2a2a] hover:bg-[#01FF87] border border-[#3a3a3a] hover:border-[#01FF87] rounded-lg flex items-center justify-center transition-all duration-200 group">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-[#1f1f1f]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Platform -->
                <div>
                    <h4 class="text-[#CDFDE6] font-semibold mb-4">Plataforma</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Características</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Equipos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Jugadores</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Partidos</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-[#CDFDE6] font-semibold mb-4">Empresa</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Acerca de</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Contacto</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Privacidad</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-[#01FF87] transition-colors text-sm">Términos</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-[#2a2a2a] pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Veltro. Todos los derechos reservados.
                </p>
                <p class="text-gray-500 text-sm">
                    Hecho para la comunidad de fútbol amateur
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll Reveal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for scroll reveals
            const revealElements = document.querySelectorAll('.reveal');
            
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            revealElements.forEach(element => {
                revealObserver.observe(element);
            });

            // Trigger animations for stagger elements
            const staggerElements = document.querySelectorAll('[class*="stagger-"]');
            staggerElements.forEach(element => {
                element.classList.add('fade-in-up');
            });
        });
    </script>
</body>

</html>
