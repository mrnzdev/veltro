@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Resultados del Partido')

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

    <!-- Match Header -->
    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-8 border border-[#3a3a3a] shadow-xl mb-6">
        <div class="text-center mb-6">
            <div class="inline-flex items-center px-4 py-2 bg-green-500/20 text-green-400 rounded-full border border-green-500/30 mb-4">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Partido Finalizado
            </div>
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Resultados Oficiales</h2>
            <p class="text-gray-400">{{ $match->match_datetime->format('d/m/Y H:i') }} - {{ $match->location }}</p>
            <p class="text-xs text-gray-500 mt-2">Confirmado el {{ $match->result_confirmed_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Final Score -->
        <div class="bg-[#2a2a2a] rounded-xl p-8 mb-6">
            <div class="flex items-center justify-center gap-12">
                <div class="text-center">
                    <p class="text-lg text-gray-400 mb-3">{{ $match->team->name }}</p>
                    <p class="text-6xl font-bold text-[#01FF87]">{{ $match->team_score }}</p>
                </div>
                <span class="text-5xl text-gray-500 font-bold">-</span>
                <div class="text-center">
                    <p class="text-lg text-gray-400 mb-3">{{ $match->opponentTeam->name }}</p>
                    <p class="text-6xl font-bold text-[#01FF87]">{{ $match->opponent_score }}</p>
                </div>
            </div>
        </div>

        <!-- Winner Badge -->
        @if($match->team_score !== $match->opponent_score)
        <div class="text-center mb-6">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] rounded-xl font-bold shadow-lg">
                <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011 1v1.42l1.246.712a1 1 0 11-.992 1.736l-1.75-1A1 1 0 012 14v-2a1 1 0 011-1zm14 0a1 1 0 011 1v2a1 1 0 01-.504.868l-1.75 1a1 1 0 11-.992-1.736L16 13.42V12a1 1 0 011-1zm-9.618 5.504a1 1 0 011.364-.372l.254.145V16a1 1 0 112 0v.277l.254-.145a1 1 0 11.992 1.736l-1.735.992a.995.995 0 01-1.022 0l-1.735-.992a1 1 0 01-.372-1.364z" clip-rule="evenodd"></path>
                </svg>
                Ganador: {{ $match->team_score > $match->opponent_score ? $match->team->name : $match->opponentTeam->name }}
            </div>
        </div>
        @else
        <div class="text-center mb-6">
            <div class="inline-flex items-center px-6 py-3 bg-gray-500/20 text-gray-400 rounded-xl font-bold border border-gray-500/30">
                Empate
            </div>
        </div>
        @endif
    </div>

    <!-- Match Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Team 1 Participants -->
        <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a]">
            <h3 class="text-xl font-bold text-[#CDFDE6] mb-4 flex items-center gap-2">
                <span class="text-2xl">{{ $match->team->getFootballTypeIcon() }}</span>
                {{ $match->team->name }}
            </h3>
            <p class="text-sm text-gray-400 mb-4">Participantes del partido:</p>
            <div class="space-y-2">
                @foreach($match->participants()->where('team_id', $match->team_id)->with('user')->get() as $participant)
                <div class="flex items-center gap-2 p-2 bg-[#3a3a3a] rounded-lg">
                    <svg class="h-4 w-4 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-300">{{ $participant->user->name }}</span>
                    @if($match->goals()->where('scorer_id', $participant->user_id)->count() > 0)
                    <span class="ml-auto text-xs px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] rounded-full border border-[#01FF87]/30">
                        {{ $match->goals()->where('scorer_id', $participant->user_id)->count() }} ⚽
                    </span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Team 2 Participants -->
        <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a]">
            <h3 class="text-xl font-bold text-[#CDFDE6] mb-4 flex items-center gap-2">
                <span class="text-2xl">{{ $match->opponentTeam->getFootballTypeIcon() }}</span>
                {{ $match->opponentTeam->name }}
            </h3>
            <p class="text-sm text-gray-400 mb-4">Participantes del partido:</p>
            <div class="space-y-2">
                @foreach($match->participants()->where('team_id', $match->opponent_team_id)->with('user')->get() as $participant)
                <div class="flex items-center gap-2 p-2 bg-[#3a3a3a] rounded-lg">
                    <svg class="h-4 w-4 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-300">{{ $participant->user->name }}</span>
                    @if($match->goals()->where('scorer_id', $participant->user_id)->count() > 0)
                    <span class="ml-auto text-xs px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] rounded-full border border-[#01FF87]/30">
                        {{ $match->goals()->where('scorer_id', $participant->user_id)->count() }} ⚽
                    </span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Goals Timeline -->
    @if($match->goals()->count() > 0)
    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a]">
        <h3 class="text-xl font-bold text-[#CDFDE6] mb-4">Cronología de Goles</h3>
        <div class="space-y-3">
            @foreach($match->goals()->with(['scorer', 'team'])->orderBy('minute')->get() as $goal)
            <div class="flex items-center gap-4 p-4 bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors">
                <div class="flex-shrink-0 w-16 text-center">
                    <span class="text-2xl font-bold text-[#01FF87]">{{ $goal->minute }}'</span>
                </div>
                <div class="flex-1">
                    <p class="text-[#CDFDE6] font-semibold">{{ $goal->scorer->name }}</p>
                    <p class="text-sm text-gray-400">{{ $goal->team->name }}</p>
                </div>
                <div class="flex-shrink-0">
                    <span class="text-3xl">⚽</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] text-center">
        <p class="text-gray-400">Partido sin goles (0-0)</p>
    </div>
    @endif
</div>
@endsection

