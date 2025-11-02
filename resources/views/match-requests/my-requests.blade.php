@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Mis Solicitudes de Partido')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('match-requests.index') }}" class="inline-flex items-center text-[#01FF87] hover:text-[#00e676] transition-colors duration-200">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a solicitudes
        </a>
    </div>

    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Mis Solicitudes de Partido</h2>
                <p class="text-gray-400">Gestiona las solicitudes de partido creadas por tus equipos.</p>
            </div>
            <div class="mt-4 lg:mt-0 flex gap-3">
                <a href="{{ route('match-requests.my-applications') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-[#2a2a2a] border border-[#3a3a3a] text-[#CDFDE6] font-semibold rounded-xl hover:bg-[#3a3a3a] hover:border-[#01FF87]/50 transition-all duration-200">
                    Mis Aplicaciones
                </a>
                @can('create', \App\Models\MatchRequest::class)
                <a href="{{ route('match-requests.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Solicitud
                </a>
                @endcan
            </div>
        </div>
    </div>

    @if($matchRequests->count() > 0)
    <div class="space-y-6">
        @foreach($matchRequests as $matchRequest)
        <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/30 transition-all duration-300">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-[#CDFDE6]">{{ $matchRequest->team->name }}</h3>
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                    @if($matchRequest->status === 'open') bg-[#01FF87]/20 text-[#01FF87] border border-[#01FF87]/30
                                    @elseif($matchRequest->status === 'matched') bg-green-500/20 text-green-400 border border-green-500/30
                                    @elseif($matchRequest->status === 'cancelled') bg-red-500/20 text-red-400 border border-red-500/30
                                    @else bg-gray-500/20 text-gray-400 border border-gray-500/30 @endif">
                                    {{ ucfirst($matchRequest->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400 mb-3">Creado {{ $matchRequest->created_at->diffForHumans() }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="h-5 w-5 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-300">{{ $matchRequest->match_datetime->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="h-5 w-5 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-gray-300">{{ $matchRequest->location }}</span>
                                </div>
                            </div>

                            @if($matchRequest->status === 'open')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#3a3a3a] rounded-lg">
                                <svg class="h-4 w-4 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-300">{{ $matchRequest->applications->count() }} {{ $matchRequest->applications->count() === 1 ? 'aplicación' : 'aplicaciones' }}</span>
                            </div>
                            @elseif($matchRequest->status === 'matched' && $matchRequest->match)
                            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3">
                                <p class="text-green-400 text-sm">
                                    Partido confirmado con <span class="font-bold">{{ $matchRequest->match->opponentTeam->name }}</span>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-row lg:flex-col gap-2 lg:w-48">
                    <a href="{{ route('match-requests.show', $matchRequest) }}"
                        class="flex-1 text-center px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200">
                        Ver Detalles
                    </a>
                    @if($matchRequest->status === 'open')
                    <form method="POST" action="{{ route('match-requests.cancel', $matchRequest) }}" class="flex-1" onsubmit="return confirm('¿Estás seguro?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 font-medium transition-all duration-200 border border-red-500/30">
                            Cancelar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($matchRequests->hasPages())
    <div class="mt-8">
        <div class="flex items-center justify-center space-x-2">
            @if ($matchRequests->onFirstPage())
            <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </span>
            @else
            <a href="{{ $matchRequests->previousPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            @endif

            @foreach ($matchRequests->getUrlRange(1, $matchRequests->lastPage()) as $page => $url)
            @if ($page == $matchRequests->currentPage())
            <span class="px-3 py-2 text-[#1f1f1f] bg-[#01FF87] rounded-lg font-medium">{{ $page }}</span>
            @else
            <a href="{{ $url }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a]">{{ $page }}</a>
            @endif
            @endforeach

            @if ($matchRequests->hasMorePages())
            <a href="{{ $matchRequests->nextPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a]">
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
    @else
    <!-- Empty State -->
    <div class="text-center py-16 px-4">
        <div class="max-w-md mx-auto">
            <div class="w-20 h-20 mx-auto mb-6 bg-[#2a2a2a] rounded-full flex items-center justify-center">
                <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">No tienes solicitudes de partido</h3>
            <p class="text-gray-400 mb-6">Crea tu primera solicitud para encontrar equipos con los que jugar.</p>
            @can('create', \App\Models\MatchRequest::class)
            <a href="{{ route('match-requests.create') }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Solicitud
            </a>
            @endcan
        </div>
    </div>
    @endif
</div>
@endsection

