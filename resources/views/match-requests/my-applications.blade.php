@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Mis Aplicaciones')

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
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Mis Aplicaciones</h2>
                <p class="text-gray-400">Revisa el estado de las aplicaciones que has enviado.</p>
            </div>
            <div class="mt-4 lg:mt-0">
                <a href="{{ route('match-requests.my-requests') }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-5 py-2.5 bg-[#2a2a2a] border border-[#3a3a3a] text-[#CDFDE6] font-semibold rounded-xl hover:bg-[#3a3a3a] hover:border-[#01FF87]/50 transition-all duration-200">
                    Mis Solicitudes
                </a>
            </div>
        </div>
    </div>

    @if($applications->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($applications as $application)
        <div class="group bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 hover:shadow-xl hover:shadow-[#01FF87]/10 transition-all duration-300 hover:-translate-y-1 flex flex-col">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-[#CDFDE6] mb-1 group-hover:text-[#01FF87] transition-colors">
                        {{ $application->matchRequest->team->name }}
                    </h3>
                    <p class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</p>
                </div>
                <span class="ml-2 px-3 py-1.5 text-xs font-bold rounded-full flex-shrink-0
                    @if($application->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                    @elseif($application->status === 'accepted') bg-green-500/20 text-green-400 border border-green-500/30
                    @else bg-red-500/20 text-red-400 border border-red-500/30 @endif">
                    {{ ucfirst($application->status) }}
                </span>
            </div>

            <!-- Team Info -->
            <div class="mb-3">
                <p class="text-xs text-gray-400">Aplicaste con</p>
                <p class="text-sm text-[#01FF87] font-semibold">{{ $application->applicantTeam->name }}</p>
            </div>

            <!-- Match Info -->
            <div class="space-y-3 mb-4 flex-grow">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5 text-[#01FF87] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-300">{{ $application->matchRequest->match_datetime->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5 text-[#01FF87] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-gray-300 truncate">{{ $application->matchRequest->location }}</span>
                </div>

                @if($application->message)
                <div class="bg-[#3a3a3a] rounded-lg p-3">
                    <p class="text-xs text-gray-400 mb-1">Tu mensaje:</p>
                    <p class="text-xs text-gray-300 line-clamp-3">{{ $application->message }}</p>
                </div>
                @endif

                @if($application->isAccepted())
                <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-3">
                    <p class="text-green-400 text-xs font-semibold">
                        ¡Aplicación aceptada!
                    </p>
                </div>
                @elseif($application->isRejected())
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3">
                    <p class="text-red-400 text-xs">
                        Aplicación rechazada
                    </p>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-2 pt-4 border-t border-[#3a3a3a]">
                <a href="{{ route('match-requests.show', $application->matchRequest) }}"
                    class="text-center px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200 hover:scale-105">
                    Ver Solicitud
                </a>
                @if($application->isPending())
                <form method="POST" action="{{ route('match-requests.applications.cancel', [$application->matchRequest, $application]) }}" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta aplicación?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 font-medium transition-all duration-200 border border-red-500/30">
                        Cancelar Aplicación
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
    <div class="mt-8">
        <div class="flex items-center justify-center space-x-2">
            @if ($applications->onFirstPage())
            <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </span>
            @else
            <a href="{{ $applications->previousPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            @endif

            @foreach ($applications->getUrlRange(1, $applications->lastPage()) as $page => $url)
            @if ($page == $applications->currentPage())
            <span class="px-3 py-2 text-[#1f1f1f] bg-[#01FF87] rounded-lg font-medium">{{ $page }}</span>
            @else
            <a href="{{ $url }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a]">{{ $page }}</a>
            @endif
            @endforeach

            @if ($applications->hasMorePages())
            <a href="{{ $applications->nextPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @else
            <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">No tienes aplicaciones</h3>
            <p class="text-gray-400 mb-6">Explora las solicitudes de partido disponibles y aplica con tu equipo.</p>
            <a href="{{ route('match-requests.index') }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Explorar Solicitudes
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

