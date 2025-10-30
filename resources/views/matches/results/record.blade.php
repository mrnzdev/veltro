@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Registrar Resultados')

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
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Registrar Resultados del Partido</h2>
            <p class="text-gray-400">{{ $userTeam->name }} vs {{ $opponentTeam->name }}</p>
            <p class="text-sm text-gray-500">{{ $match->match_datetime->format('d/m/Y H:i') }} - {{ $match->location }}</p>
        </div>

        <form method="POST" action="{{ route('matches.results.store', $match) }}" id="resultsForm">
            @csrf

            <!-- Participants Section -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-[#CDFDE6] mb-4">Participantes de {{ $userTeam->name }}</h3>
                <p class="text-sm text-gray-400 mb-4">Selecciona los jugadores que participaron en el partido:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($userTeam->members as $member)
                    <label class="flex items-center p-3 bg-[#3a3a3a] rounded-lg cursor-pointer hover:bg-[#4a4a4a] transition-colors border border-[#4a4a4a]">
                        <input type="checkbox" name="participants[]" value="{{ $member->id }}" class="participant-checkbox w-4 h-4 text-[#01FF87] bg-gray-700 border-gray-600 rounded focus:ring-[#01FF87] focus:ring-2">
                        <span class="ml-3 text-[#CDFDE6]">{{ $member->name }}</span>
                    </label>
                    @endforeach
                </div>

                @error('participants')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Goals Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-[#CDFDE6]">Goles de {{ $userTeam->name }}</h3>
                        <p class="text-sm text-gray-400">Agrega los goles anotados (opcional)</p>
                    </div>
                    <button type="button" onclick="addGoal()" class="px-4 py-2 bg-[#01FF87] text-[#1f1f1f] font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 transition-all duration-200">
                        + Agregar Gol
                    </button>
                </div>

                <div id="goalsContainer" class="space-y-3">
                    <!-- Goals will be added here dynamically -->
                </div>

                <div id="noGoalsMessage" class="text-center py-8 text-gray-500">
                    <p>No se han agregado goles. Haz clic en "Agregar Gol" para registrar un gol.</p>
                </div>
            </div>

            <!-- Score Display -->
            <div class="bg-[#3a3a3a] rounded-lg p-6 mb-8 border border-[#4a4a4a]">
                <h3 class="text-lg font-bold text-[#CDFDE6] mb-3">Resultado Final</h3>
                <div class="flex items-center justify-center gap-6">
                    <div class="text-center">
                        <p class="text-sm text-gray-400 mb-1">{{ $userTeam->name }}</p>
                        <p id="userTeamScore" class="text-4xl font-bold text-[#01FF87]">0</p>
                    </div>
                    <span class="text-3xl text-gray-500">-</span>
                    <div class="text-center">
                        <p class="text-sm text-gray-400 mb-1">{{ $opponentTeam->name }}</p>
                        <p class="text-4xl font-bold text-gray-400">?</p>
                    </div>
                </div>
                <p class="text-xs text-center text-gray-500 mt-3">El equipo contrario registrará su resultado por separado</p>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                    Guardar Resultados
                </button>
                <a href="{{ route('match-requests.show', $match->matchRequest) }}" class="flex-1 text-center px-6 py-3 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-xl hover:bg-[#4a4a4a] border border-[#4a4a4a] transition-all duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let goalCount = 0;

function getSelectedParticipants() {
    const checkboxes = document.querySelectorAll('.participant-checkbox:checked');
    return Array.from(checkboxes).map(cb => ({
        id: cb.value,
        name: cb.nextElementSibling.textContent.trim()
    }));
}

function addGoal() {
    const participants = getSelectedParticipants();
    
    if (participants.length === 0) {
        alert('Primero debes seleccionar al menos un participante.');
        return;
    }

    goalCount++;
    const container = document.getElementById('goalsContainer');
    const noGoalsMessage = document.getElementById('noGoalsMessage');
    
    noGoalsMessage.style.display = 'none';

    const goalDiv = document.createElement('div');
    goalDiv.className = 'flex items-center gap-3 p-4 bg-[#3a3a3a] rounded-lg border border-[#4a4a4a]';
    goalDiv.id = `goal-${goalCount}`;
    
    goalDiv.innerHTML = `
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-[#01FF87]/20 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"></path>
                </svg>
            </div>
        </div>
        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
            <select name="goals[${goalCount}][scorer_id]" required class="px-3 py-2 bg-[#2a2a2a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87]">
                <option value="">Seleccionar goleador...</option>
                ${participants.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
            </select>
            <input type="number" name="goals[${goalCount}][minute]" min="1" max="120" placeholder="Minuto" required class="px-3 py-2 bg-[#2a2a2a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#01FF87]">
        </div>
        <button type="button" onclick="removeGoal(${goalCount})" class="flex-shrink-0 p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    
    container.appendChild(goalDiv);
    updateScore();
}

function removeGoal(id) {
    const goalDiv = document.getElementById(`goal-${id}`);
    if (goalDiv) {
        goalDiv.remove();
        updateScore();
        
        const container = document.getElementById('goalsContainer');
        const noGoalsMessage = document.getElementById('noGoalsMessage');
        if (container.children.length === 0) {
            noGoalsMessage.style.display = 'block';
        }
    }
}

function updateScore() {
    const goalsContainer = document.getElementById('goalsContainer');
    const goalCount = goalsContainer.children.length;
    document.getElementById('userTeamScore').textContent = goalCount;
}

// Update participant dropdowns when checkboxes change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('participant-checkbox')) {
        updateParticipantDropdowns();
    }
});

function updateParticipantDropdowns() {
    const participants = getSelectedParticipants();
    const selects = document.querySelectorAll('select[name^="goals"]');
    
    selects.forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Seleccionar goleador...</option>' +
            participants.map(p => `<option value="${p.id}" ${p.id == currentValue ? 'selected' : ''}>${p.name}</option>`).join('');
    });
}
</script>
@endsection

