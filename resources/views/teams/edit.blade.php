<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Editar {{ $team->name }}</title>

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
                    <a href="{{ route('teams.show', $team) }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        ← Volver al Equipo
                    </a>
                    <a href="{{ route('teams.index') }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        Todos los Equipos
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Editar Equipo ⚽</h2>
            <p class="text-gray-400">Actualiza la información y configuración de tu equipo.</p>
        </div>

        <!-- Form -->
        <div class="bg-[#2a2a2a] rounded-xl p-8 border border-[#3a3a3a]">
            <form method="POST" action="{{ route('teams.update', $team) }}">
                @csrf
                @method('PUT')

                <!-- Team Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Nombre del Equipo *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $team->name) }}"
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
                              placeholder="Cuéntanos sobre tu equipo...">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Members -->
                <div class="mb-6">
                    <label for="max_members" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Máximo de Miembros *
                    </label>
                    <select id="max_members" 
                            name="max_members"
                            class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                            required>
                        <option value="">Selecciona el máximo de miembros</option>
                        @for($i = 2; $i <= 50; $i++)
                        <option value="{{ $i }}" {{ old('max_members', $team->max_members) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i === 11 ? '(Equipo de Fútbol Estándar)' : '' }}
                        </option>
                        @endfor
                    </select>
                    @error('max_members')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Team Status (Owner only) -->
                @if($team->isOwnedBy(Auth::user()))
                <div class="mb-8">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $team->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#01FF87] focus:ring-[#01FF87] border-[#4a4a4a] rounded bg-[#3a3a3a]">
                        <span class="ml-2 text-sm text-[#CDFDE6]">El equipo está activo</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-400">Los equipos inactivos no serán visibles para otros usuarios.</p>
                    @error('is_active')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium py-3 px-6 rounded-lg hover:opacity-90 transition-opacity duration-200">
                        Actualizar Equipo
                    </button>
                    <a href="{{ route('teams.show', $team) }}" 
                       class="flex-1 text-center bg-[#3a3a3a] text-[#CDFDE6] font-medium py-3 px-6 rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Team Stats -->
        <div class="mt-8 bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a]">
            <h3 class="text-lg font-semibold text-[#CDFDE6] mb-4">Estadísticas del Equipo</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-[#01FF87]">{{ $team->getCurrentMembersCount() }}</div>
                    <div class="text-sm text-gray-400">Miembros Actuales</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-[#01FF87]">{{ $team->max_members }}</div>
                    <div class="text-sm text-gray-400">Máximo de Miembros</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-[#01FF87]">{{ $team->created_at->diffInDays(now()) }}</div>
                    <div class="text-sm text-gray-400">Días Activo</div>
                </div>
            </div>
        </div>

        <!-- Danger Zone (Owner only) -->
        @if($team->isOwnedBy(Auth::user()))
        <div class="mt-8 bg-red-900/20 border border-red-500/30 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-red-400 mb-4">Zona de Peligro</h3>
            <p class="text-sm text-gray-400 mb-4">Una vez que elimines un equipo, no hay vuelta atrás. Por favor, asegúrate.</p>
            <form method="POST" action="{{ route('teams.destroy', $team) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                        onclick="return confirm('¿Estás completamente seguro de que quieres eliminar este equipo? Esta acción no se puede deshacer y eliminará todos los datos del equipo, incluyendo las relaciones de los miembros.')">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Eliminar Equipo
                </button>
            </form>
        </div>
        @endif
    </main>
</body>

</html>
