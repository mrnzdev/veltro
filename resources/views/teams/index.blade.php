@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Equipos')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Equipos ⚽</h2>
                <p class="text-gray-400">Gestiona tus equipos y descubre nuevos para unirte.</p>
            </div>
            <a href="{{ route('teams.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Equipo
            </a>
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
            <h3 class="text-xl font-semibold text-[#CDFDE6] mb-6">Todos los Equipos</h3>
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
                {{ $allTeams->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-[#CDFDE6]">No se encontraron equipos</h3>
                <p class="mt-1 text-sm text-gray-400">Comienza creando un nuevo equipo.</p>
                <div class="mt-6">
                    <a href="{{ route('teams.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Equipo
                    </a>
                </div>
            </div>
            @endif
        </div>
</div>
@endsection
