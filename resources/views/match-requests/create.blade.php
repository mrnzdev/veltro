@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Crear Solicitud de Partido')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('match-requests.index') }}" class="inline-flex items-center text-[#01FF87] hover:text-[#00e676] transition-colors duration-200">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a solicitudes
        </a>
    </div>

    <div class="bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-8 border border-[#3a3a3a] shadow-xl">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Crear Solicitud de Partido</h2>
            <p class="text-gray-400">Publica una solicitud para que otros equipos puedan aplicar a jugar contra ti.</p>
        </div>

        <form method="POST" action="{{ route('match-requests.store') }}" class="space-y-6">
            @csrf

            <!-- Team Selection -->
            <div>
                <label for="team_id" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                    Equipo <span class="text-red-400">*</span>
                </label>
                <select id="team_id"
                    name="team_id"
                    required
                    class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200 @error('team_id') border-red-500 @enderror">
                    <option value="">Selecciona un equipo</option>
                    @foreach($userTeams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                        {{ $team->name }} - {{ $team->getFootballTypeName() }}
                    </option>
                    @endforeach
                </select>
                @error('team_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">Solo puedes crear solicitudes para equipos donde eres dueño o capitán.</p>
            </div>

            <!-- Match Date and Time -->
            <div>
                <label for="match_datetime" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                    Fecha y Hora del Partido <span class="text-red-400">*</span>
                </label>
                <input type="datetime-local"
                    id="match_datetime"
                    name="match_datetime"
                    value="{{ old('match_datetime') }}"
                    required
                    min="{{ now()->format('Y-m-d\TH:i') }}"
                    class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200 @error('match_datetime') border-red-500 @enderror">
                @error('match_datetime')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                    Ubicación <span class="text-red-400">*</span>
                </label>
                <input type="text"
                    id="location"
                    name="location"
                    value="{{ old('location') }}"
                    required
                    maxlength="255"
                    placeholder="Ej: Estadio Central, Calle Principal 123"
                    class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200 @error('location') border-red-500 @enderror">
                @error('location')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 pt-4">
                <button type="submit"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                    Crear Solicitud
                </button>
                <a href="{{ route('match-requests.index') }}"
                    class="flex-1 text-center px-6 py-3 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-xl hover:bg-[#4a4a4a] border border-[#4a4a4a] transition-all duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

