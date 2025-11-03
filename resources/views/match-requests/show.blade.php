@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Solicitud de Partido')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Match Request Details -->
            <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#CDFDE6] mb-2">{{ $matchRequest->team->name }}</h2>
                        <p class="text-sm text-gray-400">Publicado {{ $matchRequest->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-4 py-2 bg-[#01FF87]/20 text-[#01FF87] text-sm font-bold rounded-full border border-[#01FF87]/30 self-start">
                        {{ ucfirst($matchRequest->status) }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-4 bg-[#3a3a3a] rounded-lg">
                        <svg class="h-6 w-6 text-[#01FF87] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 mb-1">Fecha y Hora</p>
                            <p class="text-[#CDFDE6] font-semibold text-sm sm:text-base break-words">{{ $matchRequest->match_datetime->format('l, d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-[#3a3a3a] rounded-lg">
                        <svg class="h-6 w-6 text-[#01FF87] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 mb-1">Ubicación</p>
                            <p class="text-[#CDFDE6] font-semibold text-sm sm:text-base break-words">{{ $matchRequest->location }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-[#3a3a3a] rounded-lg">
                        <span class="text-2xl flex-shrink-0">{{ $matchRequest->team->getFootballTypeIcon() }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 mb-1">Tipo de Fútbol</p>
                            <p class="text-[#CDFDE6] font-semibold text-sm sm:text-base">{{ $matchRequest->team->getFootballTypeName() }}</p>
                        </div>
                    </div>
                </div>

                @if($isOwnerOrCaptain && $matchRequest->status === 'open')
                <div class="mt-6 pt-6 border-t border-[#3a3a3a]">
                    <form method="POST" action="{{ route('match-requests.cancel', $matchRequest) }}" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta solicitud?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 font-medium transition-all duration-200 border border-red-500/30 hover:scale-105">
                            Cancelar Solicitud
                        </button>
                    </form>
                </div>
                @endif

                @if($matchRequest->status === 'matched' && $matchRequest->match)
                <div class="mt-6 pt-6 border-t border-[#3a3a3a]">
                    <div class="bg-[#01FF87]/10 border border-[#01FF87]/30 rounded-lg p-4">
                        <p class="text-[#01FF87] font-semibold mb-2 text-sm sm:text-base">¡Partido Confirmado!</p>
                        <p class="text-gray-300 text-sm mb-4">El partido se jugará contra <span class="font-bold text-[#CDFDE6]">{{ $matchRequest->match->opponentTeam->name }}</span></p>

                        @if($matchRequest->match->status === 'scheduled')
                        @if($matchRequest->match->canRecordResults(Auth::user()))
                        <!-- Show result recording link -->
                        @if(!$matchRequest->match->areResultsConfirmed())
                        <a href="{{ route('matches.results.show', $matchRequest->match) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Registrar Resultados
                        </a>
                        @endif
                        @endif
                        @elseif($matchRequest->match->status === 'completed')
                        <!-- Match completed, show results link -->
                        <a href="{{ route('matches.results.show', $matchRequest->match) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-lg hover:bg-[#4a4a4a] border border-[#4a4a4a] hover:scale-105 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-sm">Ver Resultados ({{ $matchRequest->match->team_score }} - {{ $matchRequest->match->opponent_score }})</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Applications Section (Owner/Captain View) -->
            @if($isOwnerOrCaptain && $matchRequest->status === 'open')
            <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] shadow-xl">
                <h3 class="text-xl font-bold text-[#CDFDE6] mb-4">
                    Aplicaciones ({{ $matchRequest->applications->count() }})
                </h3>

                @if($matchRequest->applications->count() > 0)
                <div x-data="{ showAll: false }">
                    <div class="space-y-4">
                        @foreach($matchRequest->applications as $index => $application)
                        <div class="bg-[#3a3a3a] rounded-lg p-4 border border-[#4a4a4a] transition-all duration-300"
                            x-show="showAll || {{ $index }} < 3"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-bold text-[#CDFDE6] mb-1">{{ $application->applicantTeam->name }}</h4>
                                    <p class="text-sm text-gray-400">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full flex-shrink-0 self-start
                                    @if($application->status === 'pending') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                    @elseif($application->status === 'accepted') bg-green-500/20 text-green-400 border border-green-500/30
                                    @else bg-red-500/20 text-red-400 border border-red-500/30 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>

                            @if($application->message)
                            <p class="text-gray-300 text-sm mb-3 p-3 bg-[#2a2a2a] rounded">{{ $application->message }}</p>
                            @endif

                            @if($application->isPending())
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('match-requests.applications.accept', [$matchRequest, $application]) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2.5 bg-[#01FF87] text-[#1f1f1f] font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 transition-all duration-200">
                                        Aceptar
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    @if($matchRequest->applications->count() > 3)
                    <div class="mt-4">
                        <button @click="showAll = !showAll"
                            class="w-full px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200 flex items-center justify-center gap-2">
                            <span x-text="showAll ? 'Mostrar menos' : 'Mostrar todas ({{ $matchRequest->applications->count() - 3 }} más)'"></span>
                            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': showAll }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-center text-gray-400 py-8">Aún no hay aplicaciones para esta solicitud.</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Team Info -->
            <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] shadow-xl">
                <h3 class="text-lg font-bold text-[#CDFDE6] mb-4">Información del Equipo</h3>
                <div class="space-y-4">
                    <div class="p-3 bg-[#3a3a3a] rounded-lg">
                        <p class="text-xs text-gray-400 mb-1">Nombre</p>
                        <p class="text-[#CDFDE6] font-semibold text-sm break-words">{{ $matchRequest->team->name }}</p>
                    </div>
                    <div class="p-3 bg-[#3a3a3a] rounded-lg">
                        <p class="text-xs text-gray-400 mb-1">Miembros</p>
                        <p class="text-[#CDFDE6] font-semibold text-sm">{{ $matchRequest->team->getCurrentMembersCount() }}/{{ $matchRequest->team->max_members }}</p>
                    </div>
                    <div class="p-3 bg-[#3a3a3a] rounded-lg">
                        <p class="text-xs text-gray-400 mb-1">Creador</p>
                        <p class="text-[#CDFDE6] font-semibold text-sm break-words">{{ $matchRequest->creator->name }}</p>
                    </div>
                </div>
                <a href="{{ route('teams.show', $matchRequest->team) }}" class="mt-4 block text-center px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200 hover:scale-105">
                    Ver Equipo
                </a>
            </div>

            <!-- Apply Section (Non-Owner View) -->
            @if(!$isOwnerOrCaptain && $matchRequest->canAcceptApplications())
            @if($userTeamsCanApply->count() > 0)
            <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] shadow-xl">
                <h3 class="text-lg font-bold text-[#CDFDE6] mb-4">Aplicar con tu Equipo</h3>
                <form method="POST" action="{{ route('match-requests.apply', $matchRequest) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="team_id" class="block text-sm font-medium text-[#CDFDE6] mb-2">Selecciona tu equipo</label>
                            <select id="team_id" name="team_id" required class="w-full px-3 py-2.5 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] transition-all duration-200">
                                @foreach($userTeamsCanApply as $team)
                                <option value="{{ $team->id }}">{{ $team->name }} ({{ $team->getFootballTypeName() }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-[#CDFDE6] mb-2">Mensaje (opcional)</label>
                            <textarea id="message" name="message" rows="3" maxlength="500" class="w-full px-3 py-2.5 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] resize-none transition-all duration-200" placeholder="Mensaje para el equipo..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                            Aplicar
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] shadow-xl">
                <h3 class="text-lg font-bold text-[#CDFDE6] mb-4">No puedes aplicar</h3>
                <div class="flex items-start gap-3 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                    <svg class="h-5 w-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="text-sm">
                        <p class="text-yellow-300 font-medium mb-1">Tipo de fútbol incompatible</p>
                        <p class="text-gray-400">Esta solicitud es para equipos de <strong class="text-[#01FF87]">{{ $matchRequest->team->getFootballTypeName() }}</strong>. Tus equipos juegan otros tipos de fútbol.</p>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection