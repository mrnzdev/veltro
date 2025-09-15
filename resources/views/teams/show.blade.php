@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - ' . $team->name)

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Team Header -->
        <div class="bg-[#2a2a2a] rounded-xl p-8 border border-[#3a3a3a] mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                        <h1 class="text-3xl font-bold text-[#CDFDE6]">{{ $team->name }}</h1>
                        @if($isOwner)
                        <span class="px-3 py-1 bg-[#01FF87]/20 text-[#01FF87] text-sm font-medium rounded-full">Propietario</span>
                        @elseif($isCaptain)
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-sm font-medium rounded-full">Capitán</span>
                        @elseif($isMember)
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 text-sm font-medium rounded-full">Miembro</span>
                        @endif
                    </div>
                    
                    @if($team->description)
                    <p class="text-gray-400 mb-4">{{ $team->description }}</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $team->getCurrentMembersCount() }}/{{ $team->max_members }} miembros
                        </div>
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Creado {{ $team->created_at->diffForHumans() }}
                        </div>
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Propietario: {{ $team->owner->name }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 lg:mt-0 flex flex-wrap gap-3">
                    @if($isOwner || $isCaptain)
                    <a href="{{ route('teams.edit', $team) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#01FF87]/20 text-[#01FF87] font-medium rounded-lg hover:bg-[#01FF87]/30 transition-colors duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Equipo
                    </a>
                    @endif

                    @if(!$isMember && $team->hasSpaceForMembers())
                    <form method="POST" action="{{ route('teams.join', $team) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                                Unirse al Equipo
                        </button>
                    </form>
                    @elseif($isMember && !$isOwner)
                    <form method="POST" action="{{ route('teams.leave', $team) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                                onclick="return confirm('¿Estás seguro de que quieres salir de este equipo?')">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                                    Salir del Equipo
                        </button>
                    </form>
                    @endif

                    @if($isOwner)
                    <form method="POST" action="{{ route('teams.destroy', $team) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este equipo? Esta acción no se puede deshacer.')">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                                    Eliminar Equipo
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-[#2a2a2a] rounded-xl p-8 border border-[#3a3a3a]">
            <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-[#CDFDE6]">Miembros del Equipo</h2>
            <span class="text-sm text-gray-400">{{ $team->getCurrentMembersCount() }} de {{ $team->max_members }} miembros</span>
            </div>

            @if($team->members->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($team->members as $member)
                <div class="bg-[#3a3a3a] rounded-lg p-4 border border-[#4a4a4a]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-[#1f1f1f]">{{ substr($member->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-[#CDFDE6]">{{ $member->name }}</p>
                                <p class="text-xs text-gray-400">{{ $member->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($member->pivot->role === 'owner')
                            <span class="px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] text-xs font-medium rounded-full">Propietario</span>
                            @elseif($member->pivot->role === 'captain')
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs font-medium rounded-full">Capitán</span>
                            @else
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-400 text-xs font-medium rounded-full">Miembro</span>
                            @endif

                            @if(($isOwner || $isCaptain) && $member->pivot->role !== 'owner' && $member->id !== Auth::id())
                            <div class="relative group">
                                <button class="p-1 text-gray-400 hover:text-[#CDFDE6] transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                                    <div class="py-1">
                                        @if($member->pivot->role !== 'captain')
                                        <form method="POST" action="{{ route('teams.promote', [$team, $member]) }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#CDFDE6] hover:bg-[#4a4a4a] transition-colors duration-200">
                                                Promover a Capitán
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('teams.remove-member', [$team, $member]) }}" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-500/20 transition-colors duration-200"
                                                    onclick="return confirm('¿Estás seguro de que quieres remover a este miembro?')">
                                                Remover del Equipo
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-400">
                        Se unió {{ $member->pivot->joined_at ? $member->pivot->joined_at->diffForHumans() : 'Recientemente' }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-[#CDFDE6]">Aún no hay miembros</h3>
                <p class="mt-1 text-sm text-gray-400">Invita a jugadores a unirse a tu equipo.</p>
            </div>
            @endif
        </div>
</div>
@endsection
