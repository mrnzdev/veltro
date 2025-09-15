<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Crear Equipo</title>

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
                        <h1 class="text-xl font-bold text-[#CDFDE6]">{{ config('app.name', 'Veltro') }}</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('teams.index') }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        ← Volver a Equipos
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        Panel
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Crear Nuevo Equipo ⚽</h2>
            <p class="text-gray-400">Crea tu propio equipo e invita a jugadores a unirse.</p>
        </div>

        <!-- Form -->
        <div class="bg-[#2a2a2a] rounded-xl p-8 border border-[#3a3a3a]">
            <form method="POST" action="{{ route('teams.store') }}">
                @csrf

                <!-- Team Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Nombre del Equipo *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                           placeholder="Ingresa el nombre de tu equipo"
                           required>
                    @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Descripción
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                              placeholder="Cuéntanos sobre tu equipo...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Members -->
                <div class="mb-8">
                    <label for="max_members" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Máximo de Miembros *
                    </label>
                    <select id="max_members" 
                            name="max_members"
                            class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                            required>
                        <option value="">Selecciona el máximo de miembros</option>
                        @for($i = 2; $i <= 50; $i++)
                        <option value="{{ $i }}" {{ old('max_members', 11) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i === 11 ? '(Equipo de Fútbol Estándar)' : '' }}
                        </option>
                        @endfor
                    </select>
                    @error('max_members')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium py-3 px-6 rounded-lg hover:opacity-90 transition-opacity duration-200">
                        Crear Equipo
                    </button>
                    <a href="{{ route('teams.index') }}" 
                       class="flex-1 text-center bg-[#3a3a3a] text-[#CDFDE6] font-medium py-3 px-6 rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-900/20 border border-blue-500/30 rounded-xl p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-[#CDFDE6]">Consejos para Crear Equipos</h3>
                    <div class="mt-2 text-sm text-gray-400">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Te convertirás automáticamente en el propietario del equipo</li>
                            <li>Los equipos de fútbol estándar tienen 11 jugadores</li>
                            <li>Puedes invitar jugadores a unirse a tu equipo más tarde</li>
                            <li>Los nombres de los equipos deben ser únicos en la plataforma</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
