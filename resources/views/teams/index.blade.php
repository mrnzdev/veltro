@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Equipos')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Equipos</h2>
                <p class="text-gray-400">Gestiona tus equipos y descubre nuevos para unirte.</p>
            </div>
            <a href="{{ route('teams.create') }}"
                class="mt-4 lg:mt-0 inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Equipo
            </a>
        </div>

        <!-- Tab Navigation -->
        <div class="flex items-center gap-2 p-1 bg-[#2a2a2a] rounded-xl border border-[#3a3a3a] mb-6">
            <button onclick="switchTab('my-teams')" id="tab-my-teams"
                class="tab-button flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200 bg-[#01FF87] text-[#1f1f1f] shadow-lg shadow-[#01FF87]/20">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Mis Equipos</span>
                @php
                // Calculate unique teams count (owned teams + member teams minus duplicates)
                $ownedTeamIds = $ownedTeams->pluck('id');
                $uniqueMemberTeamsCount = $memberTeams->filter(function($team) use ($ownedTeamIds) {
                return !$ownedTeamIds->contains($team->id);
                })->count();
                $totalUniqueTeams = $ownedTeams->count() + $uniqueMemberTeamsCount;
                @endphp
                <span class="px-2 py-0.5 bg-[#1f1f1f]/20 text-[#1f1f1f] text-xs font-bold rounded-full">{{ $totalUniqueTeams }}</span>
            </button>
            <button onclick="switchTab('discover')" id="tab-discover"
                class="tab-button flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200 text-gray-400 hover:text-[#CDFDE6] hover:bg-[#3a3a3a]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>Descubrir Equipos</span>
            </button>
        </div>
    </div>

    <!-- My Teams Tab -->
    <div id="content-my-teams" class="tab-content">
        @if($ownedTeams->count() > 0 || $memberTeams->count() > 0)
        @php
        // Merge owned teams and member teams, but exclude owned teams from member teams to avoid duplicates
        $ownedTeamIds = $ownedTeams->pluck('id');
        $filteredMemberTeams = $memberTeams->filter(function($team) use ($ownedTeamIds) {
        return !$ownedTeamIds->contains($team->id);
        });
        $myTeams = collect($ownedTeams)->merge($filteredMemberTeams)->sortByDesc('created_at');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($myTeams as $team)
            @php
            $isOwner = $team->isOwnedBy(Auth::user());
            $role = $isOwner ? 'owner' : ($team->pivot->role ?? 'member');
            @endphp
            <div class="group bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 hover:shadow-xl hover:shadow-[#01FF87]/10 transition-all duration-300 hover:-translate-y-1">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h5 class="text-lg font-bold text-[#CDFDE6] mb-1 truncate group-hover:text-[#01FF87] transition-colors">
                            {{ $team->name }}
                        </h5>
                        <p class="text-xs text-gray-500">{{ $team->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="ml-2 flex-shrink-0">
                        @if($isOwner)
                        <div class="px-3 py-1.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-xs font-bold rounded-full shadow-md flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011 1v1.42l1.246.712a1 1 0 11-.992 1.736l-1.75-1A1 1 0 012 14v-2a1 1 0 011-1zm14 0a1 1 0 011 1v2a1 1 0 01-.504.868l-1.75 1a1 1 0 11-.992-1.736L16 13.42V12a1 1 0 011-1zm-9.618 5.504a1 1 0 011.364-.372l.254.145V16a1 1 0 112 0v.277l.254-.145a1 1 0 11.992 1.736l-1.735.992a.995.995 0 01-1.022 0l-1.735-.992a1 1 0 01-.372-1.364z" clip-rule="evenodd"></path>
                            </svg>
                            Dueño
                        </div>
                        @elseif($role === 'captain')
                        <span class="px-3 py-1.5 bg-blue-500/20 text-blue-400 text-xs font-bold rounded-full border border-blue-500/30 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Capitán
                        </span>
                        @else
                        <span class="px-3 py-1.5 bg-purple-500/20 text-purple-400 text-xs font-bold rounded-full border border-purple-500/30">
                            Miembro
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($team->description)
                <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $team->description }}</p>
                @else
                <p class="text-gray-500 text-sm mb-4 italic">Sin descripción</p>
                @endif

                <!-- Stats -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#3a3a3a] text-[#01FF87] font-medium text-sm border border-[#01FF87]/30">
                            <span class="mr-1.5">{{ $team->getFootballTypeIcon() }}</span>
                            {{ $team->getFootballTypeName() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 pb-4 border-b border-[#3a3a3a]">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-[#3a3a3a] rounded-lg">
                                <svg class="h-4 w-4 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Miembros</p>
                                <p class="text-sm font-bold text-[#CDFDE6]">{{ $team->getCurrentMembersCount() }}/{{ $team->max_members }}</p>
                            </div>
                        </div>
                        @if(!$isOwner && $team->owner)
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <div class="p-2 bg-[#3a3a3a] rounded-lg">
                                <svg class="h-4 w-4 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-500">Dueño</p>
                                <p class="text-sm font-semibold text-[#CDFDE6] truncate">{{ $team->owner->name }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('teams.show', $team) }}"
                        class="flex-1 text-center px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200 hover:scale-105">
                        Ver Detalles
                    </a>
                    @if($isOwner)
                    <a href="{{ route('teams.edit', $team) }}"
                        class="flex-1 text-center px-4 py-2.5 bg-[#01FF87]/20 text-[#01FF87] rounded-lg hover:bg-[#01FF87]/30 font-medium transition-all duration-200 hover:scale-105 border border-[#01FF87]/30">
                        Editar
                    </a>
                    @else
                    <form method="POST" action="{{ route('teams.leave', $team) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 font-medium transition-all duration-200 hover:scale-105 border border-red-500/30"
                            onclick="return confirm('¿Estás seguro de que quieres salir de este equipo?')">
                            Salir
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16 px-4">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 mx-auto mb-6 bg-[#2a2a2a] rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">Aún no eres parte de ningún equipo</h3>
                <p class="text-gray-400 mb-6">Crea tu primer equipo o descubre equipos existentes para unirte.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('teams.create') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Equipo
                    </a>
                    <button onclick="switchTab('discover')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-[#2a2a2a] text-[#CDFDE6] font-semibold rounded-xl hover:bg-[#3a3a3a] border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Explorar Equipos
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Discover Teams Tab -->
    <div id="content-discover" class="tab-content hidden">
        <!-- Search and Filters -->
        <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] mb-6">
            <form method="GET" action="{{ route('teams.index') }}" class="space-y-4">
                <input type="hidden" name="tab" value="discover">

                <!-- Search Bar -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Buscar equipos por nombre o descripción..."
                                class="w-full pl-10 pr-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-[#01FF87] text-[#1f1f1f] font-semibold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                        Buscar
                    </button>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Disponibilidad</label>
                        <select name="availability"
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                            <option value="">Todas</option>
                            <option value="open" {{ request('availability') === 'open' ? 'selected' : '' }}>Con espacio</option>
                            <option value="full" {{ request('availability') === 'full' ? 'selected' : '' }}>Completos</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Tamaño</label>
                        <select name="size"
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                            <option value="">Todos</option>
                            <option value="small" {{ request('size') === 'small' ? 'selected' : '' }}>Pequeños (≤7)</option>
                            <option value="medium" {{ request('size') === 'medium' ? 'selected' : '' }}>Medianos (8-15)</option>
                            <option value="large" {{ request('size') === 'large' ? 'selected' : '' }}>Grandes (>15)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#CDFDE6] mb-2">Ordenar por</label>
                        <select name="sort"
                            class="w-full px-3 py-2 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200">
                            <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Más recientes</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                            <option value="members" {{ request('sort') === 'members' ? 'selected' : '' }}>Más miembros</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('teams.index') }}?tab=discover"
                            class="w-full px-4 py-2 bg-[#3a3a3a] text-[#CDFDE6] font-medium rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200 text-center">
                            Limpiar filtros
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Teams Grid -->
        @php
        $user = Auth::user();
        $discoverTeams = $allTeams->filter(function($team) use ($user) {
        return !$team->hasMember($user);
        });
        @endphp

        @if($discoverTeams->count() > 0)
        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-gray-400">
                Mostrando {{ $discoverTeams->count() }} {{ $discoverTeams->count() === 1 ? 'equipo' : 'equipos' }} disponibles
                @if(request()->hasAny(['search', 'availability', 'size', 'sort']))
                <span class="text-[#01FF87]">(filtrados)</span>
                @endif
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($discoverTeams as $team)
            <div class="group bg-gradient-to-br from-[#2a2a2a] to-[#252525] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 hover:shadow-xl hover:shadow-[#01FF87]/10 transition-all duration-300 hover:-translate-y-1">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h5 class="text-lg font-bold text-[#CDFDE6] mb-1 truncate group-hover:text-[#01FF87] transition-colors">
                            {{ $team->name }}
                        </h5>
                        <p class="text-xs text-gray-500">{{ $team->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="ml-2 flex-shrink-0">
                        @if($team->hasSpaceForMembers())
                        <span class="px-3 py-1.5 bg-[#01FF87]/20 text-[#01FF87] text-xs font-bold rounded-full border border-[#01FF87]/30 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="3"></circle>
                            </svg>
                            Abierto
                        </span>
                        @else
                        <span class="px-3 py-1.5 bg-gray-500/20 text-gray-400 text-xs font-bold rounded-full border border-gray-500/30">
                            Completo
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($team->description)
                <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $team->description }}</p>
                @else
                <p class="text-gray-500 text-sm mb-4 italic">Sin descripción</p>
                @endif

                <!-- Stats -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#3a3a3a] text-[#01FF87] font-medium text-sm border border-[#01FF87]/30">
                            <span class="mr-1.5">{{ $team->getFootballTypeIcon() }}</span>
                            {{ $team->getFootballTypeName() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 pb-4 border-b border-[#3a3a3a]">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-[#3a3a3a] rounded-lg">
                                <svg class="h-4 w-4 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Miembros</p>
                                <p class="text-sm font-bold text-[#CDFDE6]">{{ $team->getCurrentMembersCount() }}/{{ $team->max_members }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <div class="p-2 bg-[#3a3a3a] rounded-lg">
                                <svg class="h-4 w-4 text-[#01FF87]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-500">Dueño</p>
                                <p class="text-sm font-semibold text-[#CDFDE6] truncate">{{ $team->owner->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('teams.show', $team) }}"
                        class="flex-1 text-center px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-all duration-200 hover:scale-105">
                        Ver Detalles
                    </a>
                    @if($team->hasSpaceForMembers())
                    <button data-team-id="{{ $team->id }}" data-team-name="{{ $team->name }}" class="join-request-btn flex-1 px-4 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 font-bold transition-all duration-200 hover:scale-105">
                        Solicitar Unirse
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($allTeams->hasPages())
        <div class="mt-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-400">
                    Página {{ $allTeams->currentPage() }} de {{ $allTeams->lastPage() }}
                </div>
                <div class="flex items-center space-x-2">
                    @if ($allTeams->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-[#3a3a3a] rounded-lg cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </span>
                    @else
                    <a href="{{ $allTeams->appends(['tab' => 'discover'])->previousPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    @endif

                    @foreach ($allTeams->getUrlRange(1, $allTeams->lastPage()) as $page => $url)
                    @if ($page == $allTeams->currentPage())
                    <span class="px-3 py-2 text-[#1f1f1f] bg-[#01FF87] rounded-lg font-medium">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}&tab=discover" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">{{ $page }}</a>
                    @endif
                    @endforeach

                    @if ($allTeams->hasMorePages())
                    <a href="{{ $allTeams->appends(['tab' => 'discover'])->nextPageUrl() }}" class="px-3 py-2 text-[#CDFDE6] bg-[#3a3a3a] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
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
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="text-center py-16 px-4">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 mx-auto mb-6 bg-[#2a2a2a] rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                @if(request()->hasAny(['search', 'availability', 'size', 'sort']))
                <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">No se encontraron equipos</h3>
                <p class="text-gray-400 mb-6">Intenta ajustar tus filtros de búsqueda para encontrar equipos disponibles.</p>
                <a href="{{ route('teams.index') }}?tab=discover"
                    class="inline-flex items-center px-6 py-3 bg-[#2a2a2a] text-[#CDFDE6] font-semibold rounded-xl hover:bg-[#3a3a3a] border border-[#3a3a3a] transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Limpiar filtros
                </a>
                @else
                <h3 class="text-xl font-bold text-[#CDFDE6] mb-2">No hay equipos disponibles</h3>
                <p class="text-gray-400 mb-6">Parece que ya eres parte de todos los equipos existentes. ¿Por qué no creas uno nuevo?</p>
                <a href="{{ route('teams.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-semibold rounded-xl hover:shadow-lg hover:shadow-[#01FF87]/20 hover:scale-105 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Equipo
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Join Request Modal -->
<div id="joinRequestModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-[#2a2a2a] rounded-xl border border-[#3a3a3a] max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-[#CDFDE6]">Solicitar Unirse</h3>
            <button onclick="closeJoinRequestModal()" class="text-gray-400 hover:text-[#CDFDE6] transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <p class="text-gray-400 mb-4">
            Envía una solicitud para unirte a <span id="modalTeamName" class="text-[#01FF87] font-semibold"></span>
        </p>

        <form id="joinRequestForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                    Mensaje (opcional)
                </label>
                <textarea id="message"
                    name="message"
                    rows="4"
                    maxlength="500"
                    class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                    placeholder="¿Por qué quieres unirte a este equipo? (opcional)"></textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
            </div>

            <div class="flex gap-3">
                <button type="button"
                    onclick="closeJoinRequestModal()"
                    class="flex-1 px-4 py-2.5 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] font-medium transition-colors duration-200">
                    Cancelar
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 font-bold transition-all duration-200">
                    Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Switching Script -->
<script>
    // Join Request Modal Functions
    function openJoinRequestModal(teamId, teamName) {
        const modal = document.getElementById('joinRequestModal');
        const form = document.getElementById('joinRequestForm');
        const teamNameSpan = document.getElementById('modalTeamName');

        form.action = `/teams/${teamId}/join`;
        teamNameSpan.textContent = teamName;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeJoinRequestModal() {
        const modal = document.getElementById('joinRequestModal');
        const form = document.getElementById('joinRequestForm');
        const messageField = document.getElementById('message');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        form.reset();
        messageField.value = '';
    }

    // Event delegation for join request buttons
    document.addEventListener('click', function(event) {
        if (event.target.closest('.join-request-btn')) {
            const button = event.target.closest('.join-request-btn');
            const teamId = button.dataset.teamId;
            const teamName = button.dataset.teamName;
            openJoinRequestModal(teamId, teamName);
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeJoinRequestModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('joinRequestModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeJoinRequestModal();
        }
    });

    function switchTab(tabName) {
        // Update tab buttons
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            const isActive = tab.id === `tab-${tabName}`;
            if (isActive) {
                tab.className = 'tab-button flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200 bg-[#01FF87] text-[#1f1f1f] shadow-lg shadow-[#01FF87]/20';
            } else {
                tab.className = 'tab-button flex-1 flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200 text-gray-400 hover:text-[#CDFDE6] hover:bg-[#3a3a3a]';
            }
        });

        // Update content
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            const isActive = content.id === `content-${tabName}`;
            if (isActive) {
                content.classList.remove('hidden');
                content.classList.add('animate-fadeIn');
            } else {
                content.classList.add('hidden');
                content.classList.remove('animate-fadeIn');
            }
        });

        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

    // Initialize tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'my-teams';
        switchTab(tab);
    });

    // Add fade-in animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
`;
    document.head.appendChild(style);
</script>
@endsection