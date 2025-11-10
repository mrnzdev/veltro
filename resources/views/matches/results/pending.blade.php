@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Resultados Pendientes')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
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

        <!-- Dispute History -->
        @if($match->disputes()->count() > 0)
        <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-6 mb-8">
            <div class="flex items-center gap-3 mb-4">
                <svg class="h-6 w-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-lg font-bold text-orange-400">Historial de Disputas</h3>
            </div>
            <div class="space-y-3">
                @foreach($match->disputes()->with('disputedBy')->latest()->get() as $dispute)
                <div class="bg-[#2a2a2a]/50 rounded-lg p-4 border-l-4 border-orange-500/50">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-sm text-gray-300">
                            <span class="font-semibold text-orange-300">{{ $dispute->disputedBy->name }}</span>
                            <span class="text-gray-400">disputó los resultados</span>
                        </p>
                        <span class="text-xs text-gray-500">{{ $dispute->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-400 italic bg-[#1a1a1a] p-3 rounded border-l-2 border-orange-500/30">
                        "{{ $dispute->dispute_reason }}"
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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
                @php
                $goalsWithMinutes = $match->goals()->with(['scorer', 'team'])->whereNotNull('minute')->orderBy('minute')->get();
                $goalsWithoutMinutes = $match->goals()->with(['scorer', 'team'])->whereNull('minute')->get();
                $allGoals = $goalsWithMinutes->concat($goalsWithoutMinutes);
                @endphp
                @foreach($allGoals as $goal)
                <div class="flex items-center gap-3 p-3 bg-[#2a2a2a] rounded-lg">
                    <span class="text-[#01FF87] font-bold">{{ $goal->formatMinute() }}</span>
                    <span class="text-gray-300">{{ $goal->getScorerName() }}</span>
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

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('matches.results.confirm', $match) }}">
                @csrf
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                    ✓ Confirmar Resultados
                </button>
            </form>
            <button type="button" onclick="showDisputeModal()" class="w-full px-6 py-3 bg-red-500/20 text-red-400 font-bold rounded-xl border border-red-500/30 hover:bg-red-500/30 transition-all duration-200">
                ✗ Disputar Resultados
            </button>
        </div>

        @else
        <!-- User submitted, waiting for opponent -->
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-blue-400 mb-2">Resultados Enviados</h3>
                    <p class="text-gray-300 mb-4">Los resultados han sido registrados y están esperando confirmación de {{ $opponentTeam->name }}.</p>
                </div>
            </div>
        </div>

        <!-- Show submitted results for review -->
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
                @php
                $goalsWithMinutes = $match->goals()->with(['scorer', 'team'])->whereNotNull('minute')->orderBy('minute')->get();
                $goalsWithoutMinutes = $match->goals()->with(['scorer', 'team'])->whereNull('minute')->get();
                $allGoals = $goalsWithMinutes->concat($goalsWithoutMinutes);
                @endphp
                @foreach($allGoals as $goal)
                <div class="flex items-center gap-3 p-3 bg-[#2a2a2a] rounded-lg">
                    <span class="text-[#01FF87] font-bold">{{ $goal->formatMinute() }}</span>
                    <span class="text-gray-300">{{ $goal->getScorerName() }}</span>
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

        <!-- Edit Button for submitter -->
        <form method="POST" action="{{ route('matches.results.edit', $match) }}" onsubmit="return confirm('¿Estás seguro de que deseas editar los resultados? Esto eliminará los datos actuales y tendrás que volver a registrarlos.');">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-gray-500/20 text-gray-300 font-medium rounded-xl border border-gray-500/30 hover:bg-gray-500/30 transition-all duration-200">
                ✏️ Editar Resultados
            </button>
        </form>
        @endif
    </div>
</div>

<!-- Dispute Modal -->
<div id="disputeModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-8 border border-[#3a3a3a] shadow-2xl max-w-md w-full">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex-shrink-0 w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-red-400">Disputar Resultados</h3>
        </div>

        <p class="text-gray-300 mb-6">
            Si los resultados no son correctos, puedes disputarlos. Esto eliminará los datos actuales y permitirá que el equipo contrario vuelva a registrarlos.
        </p>

        <form method="POST" action="{{ route('matches.results.dispute', $match) }}" id="disputeForm">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Motivo de la disputa <span class="text-red-400">*</span>
                </label>
                <textarea
                    name="reason"
                    rows="4"
                    required
                    minlength="10"
                    maxlength="500"
                    placeholder="Explica por qué los resultados no son correctos (mínimo 10 caracteres)..."
                    class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres, máximo 500</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="hideDisputeModal()" class="flex-1 px-6 py-3 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-xl hover:bg-[#4a4a4a] transition-all duration-200">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all duration-200">
                    Disputar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showDisputeModal() {
        document.getElementById('disputeModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function hideDisputeModal() {
        document.getElementById('disputeModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on outside click
    document.getElementById('disputeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideDisputeModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideDisputeModal();
        }
    });
</script>
@endsection