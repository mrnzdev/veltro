@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Equipos')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Equipos ⚽</h2>
                    <p class="text-gray-400">Gestiona tus equipos y descubre nuevos para unirte.</p>
                </div>
                <a href="{{ route('teams.create') }}" 
                   class="mt-4 lg:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Equipo
                </a>
            </div>

            <!-- Search and Filters -->
            <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a]">
                <form method="GET" action="{{ route('teams.index') }}" class="space-y-4">
                    <!-- Search Bar -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Buscar equipos por nombre o descripción..."
                                       class="w-full pl-10 pr-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        <button type="submit" 
                                class="px-6 py-3 bg-[#01FF87] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                            Buscar
                        </button>
                    </div>

                    <!-- Filters -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Availability Filter -->
                        <div>
                            <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Disponibilidad</label>
                            <select name="availability" 
                                    class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                                <option value="">Todas</option>
                                <option value="open" {{ request('availability') === 'open' ? 'selected' : '' }}>Con espacio</option>
                                <option value="full" {{ request('availability') === 'full' ? 'selected' : '' }}>Completos</option>
                            </select>
                        </div>

                        <!-- Size Filter -->
                        <div>
                            <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Tamaño</label>
                            <select name="size" 
                                    class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                                <option value="">Todos</option>
                                <option value="small" {{ request('size') === 'small' ? 'selected' : '' }}>Pequeños (≤7)</option>
                                <option value="medium" {{ request('size') === 'medium' ? 'selected' : '' }}>Medianos (8-15)</option>
                                <option value="large" {{ request('size') === 'large' ? 'selected' : '' }}>Grandes (>15)</option>
                            </select>
                        </div>

                        <!-- Sort Filter -->
                        <div>
                            <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Ordenar por</label>
                            <select name="sort" 
                                    class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                                <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Más recientes</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                                <option value="members" {{ request('sort') === 'members' ? 'selected' : '' }}>Más miembros</option>
                            </select>
                        </div>

                        <!-- Clear Filters -->
                        <div class="flex items-end">
                            <a href="{{ route('teams.index') }}" 
                               class="w-full px-4 py-2 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200 text-center">
                                Limpiar filtros
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- My Teams Section -->
        @if($ownedTeams->count() > 0 || $memberTeams->count() > 0)
        <div class="mb-12">
            <h3 class="text-xl font-semibold text-[#CDFDE6] mb-6">Mis Equipos</h3>
            
            <!-- Owned Teams -->
            @if($ownedTeams->count() > 0)
            <div class="mb-8">
                <h4 class="text-lg font-medium text-[#01FF87] mb-4">Equipos que Dirijo</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($ownedTeams as $team)
                    <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-lg font-semibold text-[#CDFDE6]">{{ $team->name }}</h5>
                            <span class="px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] text-xs font-medium rounded-full">Propietario</span>
                        </div>
                        @if($team->description)
                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($team->description, 100) }}</p>
                        @endif
                        <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                            <span>{{ $team->getCurrentMembersCount() }}/{{ $team->max_members }} miembros</span>
                            <span>{{ $team->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('teams.show', $team) }}" 
                               class="flex-1 text-center px-3 py-2 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                                Ver
                            </a>
                            <a href="{{ route('teams.edit', $team) }}" 
                               class="flex-1 text-center px-3 py-2 bg-[#01FF87]/20 text-[#01FF87] rounded-lg hover:bg-[#01FF87]/30 transition-colors duration-200">
                                Editar
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Member Teams -->
            @if($memberTeams->count() > 0)
            <div>
                <h4 class="text-lg font-medium text-[#01FF87] mb-4">Equipos en los que Participo</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($memberTeams as $team)
                    <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-lg font-semibold text-[#CDFDE6]">{{ $team->name }}</h5>
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs font-medium rounded-full">
                                {{ $team->pivot->role === 'captain' ? 'Capitán' : 'Miembro' }}
                            </span>
                        </div>
                        @if($team->description)
                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($team->description, 100) }}</p>
                        @endif
                        <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                            <span>Propietario: {{ $team->owner->name }}</span>
                            <span>Se unió {{ $team->pivot->joined_at ? $team->pivot->joined_at->diffForHumans() : 'Recientemente' }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('teams.show', $team) }}" 
                               class="flex-1 text-center px-3 py-2 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                                Ver
                            </a>
                            <form method="POST" action="{{ route('teams.leave', $team) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-3 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                                        onclick="return confirm('¿Estás seguro de que quieres salir de este equipo?')">
                                    Salir
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- All Teams Section -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h3 class="text-xl font-semibold text-[#CDFDE6] mb-2 sm:mb-0">Todos los Equipos</h3>
                @if($allTeams->total() > 0)
                <div class="text-sm text-gray-400">
                    Mostrando {{ $allTeams->firstItem() ?? 0 }} - {{ $allTeams->lastItem() ?? 0 }} de {{ $allTeams->total() }} equipos
                    @if(request()->hasAny(['search', 'availability', 'size', 'sort']))
                        <span class="text-[#01FF87]">(filtrados)</span>
                    @endif
                </div>
                @endif
            </div>
            
            @if($allTeams->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allTeams as $team)
                <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-colors duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-[#CDFDE6]">{{ $team->name }}</h5>
                        @if($team->hasMember(Auth::user()))
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">Miembro</span>
                        @elseif($team->hasSpaceForMembers())
                        <span class="px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] text-xs font-medium rounded-full">Abierto</span>
                        @else
                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 text-xs font-medium rounded-full">Completo</span>
                        @endif
                    </div>
                    @if($team->description)
                    <p class="text-gray-400 text-sm mb-4">{{ Str::limit($team->description, 100) }}</p>
                    @endif
                    <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                        <span>{{ $team->getCurrentMembersCount() }}/{{ $team->max_members }} miembros</span>
                        <span>Propietario: {{ $team->owner->name }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('teams.show', $team) }}" 
                           class="flex-1 text-center px-3 py-2 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                            Ver
                        </a>
                        @if(!$team->hasMember(Auth::user()) && $team->hasSpaceForMembers())
                        <form method="POST" action="{{ route('teams.join', $team) }}" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-3 py-2 bg-[#01FF87]/20 text-[#01FF87] rounded-lg hover:bg-[#01FF87]/30 transition-colors duration-200">
                                Unirse
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                @if($allTeams->hasPages())
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-400">
                        Página {{ $allTeams->currentPage() }} de {{ $allTeams->lastPage() }}
                    </div>
                    <div class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($allTeams->onFirstPage())
                            <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $allTeams->previousPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($allTeams->getUrlRange(1, $allTeams->lastPage()) as $page => $url)
                            @if ($page == $allTeams->currentPage())
                                <span class="px-3 py-2 text-[#1f1f1f] bg-[#01FF87] rounded-lg font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($allTeams->hasMorePages())
                            <a href="{{ $allTeams->nextPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-12">
                @if(request()->hasAny(['search', 'availability', 'size', 'sort']))
                    {{-- No results for filtered search --}}
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-[#CDFDE6]">No se encontraron equipos</h3>
                    <p class="mt-1 text-sm text-gray-400">Intenta ajustar tus filtros de búsqueda.</p>
                    <div class="mt-6">
                        <a href="{{ route('teams.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Limpiar filtros
                        </a>
                    </div>
                @else
                    {{-- No teams exist at all --}}
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-[#CDFDE6]">No hay equipos disponibles</h3>
                    <p class="mt-1 text-sm text-gray-400">Sé el primero en crear un equipo.</p>
                    <div class="mt-6">
                        <a href="{{ route('teams.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Equipo
                        </a>
                    </div>
                @endif
            </div>
            @endif
        </div>
</div>
@endsection
