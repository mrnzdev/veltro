@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Resultados Pendientes')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('match-requests.show', $match->matchRequest) }}" class="inline-flex items-center text-[#01FF87] hover:text-[#00e676] transition-colors duration-200">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a solicitud
        </a>
    </div>

    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-8 border border-[#3a3a3a] shadow-xl">
        <!-- Match Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Resultados del Partido</h2>
            <p class="text-gray-400">{{ $match->team->name }} vs {{ $match->opponentTeam->name }}</p>
            <p class="text-sm text-gray-500">{{ $match->match_datetime->format('d/m/Y H:i') }} - {{ $match->location }}</p>
        </div>

        @if($hasOpponentSubmitted)
        <!-- Opponent has submitted, user can confirm -->
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-yellow-400 mb-2">{{ $opponentTeam->name }} ha registrado los resultados</h3>
                    <p class="text-gray-300 mb-4">Revisa los resultados enviados por el equipo contrario y confírmalos si están correctos.</p>
                </div>
            </div>
        </div>

        <!-- Show opponent's submitted results -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Team Participants -->
            <div class="bg-[#3a3a3a] rounded-lg p-6 border border-[#4a4a4a]">
                <h3 class="text-lg font-bold text-[#CDFDE6] mb-4">Participantes - {{ $match->team->name }}</h3>
                <div class="space-y-2">
                    @foreach($match->participants()->where('team_id', $match->team_id)->with('user')->get() as $participant)
                    <div class="flex items-center gap-2 text-gray-300">
                        <svg class="h-4 w-4 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $participant->user->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Opponent Participants -->
            <div class="bg-[#3a3a3a] rounded-lg p-6 border border-[#4a4a4a]">
                <h3 class="text-lg font-bold text-[#CDFDE6] mb-4">Participantes - {{ $match->opponentTeam->name }}</h3>
                <div class="space-y-2">
                    @foreach($match->participants()->where('team_id', $match->opponent_team_id)->with('user')->get() as $participant)
                    <div class="flex items-center gap-2 text-gray-300">
                        <svg class="h-4 w-4 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $participant->user->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Goals -->
        @if($match->goals()->count() > 0)
        <div class="bg-[#3a3a3a] rounded-lg p-6 border border-[#4a4a4a] mb-8">
            <h3 class="text-lg font-bold text-[#CDFDE6] mb-4">Goles</h3>
            <div class="space-y-3">
                @foreach($match->goals()->with(['scorer', 'team'])->orderBy('minute')->get() as $goal)
                <div class="flex items-center gap-3 p-3 bg-[#2a2a2a] rounded-lg">
                    <span class="text-[#01FF87] font-bold">{{ $goal->formatMinute() }}</span>
                    <span class="text-gray-300">{{ $goal->scorer->name }}</span>
                    <span class="text-gray-500">({{ $goal->team->name }})</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Score -->
        <div class="bg-[#2a2a2a] rounded-lg p-8 mb-8 border border-[#3a3a3a]">
            <h3 class="text-lg font-bold text-[#CDFDE6] mb-4 text-center">Resultado</h3>
            <div class="flex items-center justify-center gap-8">
                <div class="text-center">
                    <p class="text-sm text-gray-400 mb-2">{{ $match->team->name }}</p>
                    <p class="text-5xl font-bold text-[#01FF87]">{{ $match->team_score }}</p>
                </div>
                <span class="text-4xl text-gray-500">-</span>
                <div class="text-center">
                    <p class="text-sm text-gray-400 mb-2">{{ $match->opponentTeam->name }}</p>
                    <p class="text-5xl font-bold text-[#01FF87]">{{ $match->opponent_score }}</p>
                </div>
            </div>
        </div>

        <!-- Confirm Button -->
        <form method="POST" action="{{ route('matches.results.confirm', $match) }}">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                Confirmar Resultados
            </button>
        </form>

        @else
        <!-- User submitted, waiting for opponent -->
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-blue-400 mb-2">Resultados Enviados</h3>
                <p class="text-gray-300 mb-6">Esperando a que {{ $opponentTeam->name }} registre sus resultados.</p>
                <p class="text-sm text-gray-400">Una vez que ambos equipos hayan registrado los resultados, se confirmarán automáticamente o requerirán confirmación manual.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

