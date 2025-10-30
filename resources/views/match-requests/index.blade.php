@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Solicitudes de Partido')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Solicitudes de Partido</h2>
                <p class="text-gray-400">Encuentra equipos buscando partidos o publica tu propia solicitud.</p>
            </div>
            <div class="mt-4 lg:mt-0 flex gap-3">
                <a href="{{ route('match-requests.my-requests') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-[#2a2a2a] border border-[#3a3a3a] text-[#CDFDE6] font-semibold rounded-xl hover:bg-[#3a3a3a] hover:border-[#01FF87]/50 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Mis Solicitudes
                </a>
                @can('create', \App\Models\MatchRequest::class)
                <a href="{{ route('match-requests.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Solicitud
                </a>
                @endcan
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] mb-6">
            <form method="GET" action="{{ route('match-requests.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Tipo de Fútbol</label>
                        <select name="football_type"
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                            <option value="">Todos</option>
                            <option value="football_5" {{ request('football_type') === 'football_5' ? 'selected' : '' }}>Fútbol 5</option>
                            <option value="football_7" {{ request('football_type') === 'football_7' ? 'selected' : '' }}>Fútbol 7</option>
                            <option value="football_11" {{ request('football_type') === 'football_11' ? 'selected' : '' }}>Fútbol 11</option>
                            <option value="futsal" {{ request('football_type') === 'futsal' ? 'selected' : '' }}>Futsal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Ubicación</label>
                        <input type="text"
                            name="location"
                            value="{{ request('location') }}"
                            placeholder="Buscar por ubicación..."
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Desde</label>
                        <input type="date"
                            name="start_date"
                            value="{{ request('start_date') }}"
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Hasta</label>
                        <input type="date"
                            name="end_date"
                            value="{{ request('end_date') }}"
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#01FF87] text-[#1f1f1f] font-semibold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                        Filtrar
                    </button>
                    <a href="{{ route('match-requests.index') }}"
                        class="px-6 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Match Requests Grid -->
    @if($matchRequests->count() > 0)
    <div class="mb-4">
        <p class="text-sm text-gray-400">
            Mostrando {{ $matchRequests->count() }} {{ $matchRequests->count() === 1 ? 'solicitud' : 'solicitudes' }}
            @if(request()->hasAny(['football_type', 'location', 'start_date', 'end_date']))
            <span class="text-[#01FF87]">(filtradas)</span>
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($matchRequests as $matchRequest)
        <div class="group bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 hover:shadow-xl hover:shadow-[#01FF87]/10 transition-all duration-300 hover:-translate-y-1">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1 min-w-0">
                    <h5 class="text-lg font-bold text-[#CDFDE6] mb-1 group-hover:text-[#01FF87] transition-colors">
                        {{ $matchRequest->team->name }}
                    </h5>
                    <p class="text-xs text-gray-500">{{ $matchRequest->created_at->diffForHumans() }}</p>
                </div>
                <span class="ml-2 px-3 py-1.5 bg-[#01FF87]/20 text-[#01FF87] text-xs font-bold rounded-full border border-[#01FF87]/30 flex items-center gap-1 flex-shrink-0">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="3"></circle>
                    </svg>
                    Abierto
                </span>
            </div>

            <!-- Match Info -->
            <div class="space-y-3 mb-4">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5 text-[#01FF87] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-300">{{ $matchRequest->match_datetime->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5 text-[#01FF87] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-gray-300 truncate">{{ $matchRequest->location }}</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#3a3a3a] text-[#01FF87] font-medium text-sm border border-[#01FF87]/30">
                    <span class="mr-1.5">{{ $matchRequest->team->getFootballTypeIcon() }}</span>
                    {{ $matchRequest->team->getFootballTypeName() }}
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 pt-4 border-t border-[#3a3a3a]">
                <a href="{{ route('match-requests.show', $matchRequest) }}"
                    class="flex-1 text-center px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200 hover:scale-105">
                    Ver Detalles
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($matchRequests->hasPages())
    <div class="mt-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-400">
                Página {{ $matchRequests->currentPage() }} de {{ $matchRequests->lastPage() }}
            </div>
            <div class="flex items-center space-x-2">
                @if ($matchRequests->onFirstPage())
                <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </span>
                @else
                <a href="{{ $matchRequests->previousPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                @endif

                @foreach ($matchRequests->getUrlRange(1, $matchRequests->lastPage()) as $page => $url)
                @if ($page == $matchRequests->currentPage())
                <span class="px-3 py-2 text-[#1f1f1f] bg-[#01FF87] rounded-lg font-medium">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">{{ $page }}</a>
                @endif
                @endforeach

                @if ($matchRequests->hasMorePages())
                <a href="{{ $matchRequests->nextPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
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
    </div>
    @endif
    @else
    <!-- Empty State -->
    <div class="text-center py-16 px-4">
        <div class="max-w-md mx-auto">
            <div class="w-20 h-20 mx-auto mb-6 bg-[#2a2a2a] rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            @if(request()->hasAny(['football_type', 'location', 'start_date', 'end_date']))
            <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">No se encontraron solicitudes</h3>
            <p class="text-gray-400 mb-6">Intenta ajustar tus filtros para encontrar solicitudes de partido.</p>
            <a href="{{ route('match-requests.index') }}"
                class="inline-flex items-center px-6 py-3 bg-[#2a2a2a] text-[#CDFDE6] font-semibold rounded-xl hover:bg-[#3a3a3a] border border-[#3a3a3a] transition-all duration-200">
                Limpiar filtros
            </a>
            @else
            <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">No hay solicitudes disponibles</h3>
            <p class="text-gray-400 mb-6">Sé el primero en publicar una solicitud de partido.</p>
            @can('create', \App\Models\MatchRequest::class)
            <a href="{{ route('match-requests.create') }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Solicitud
            </a>
            @endcan
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

