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

                <div class="mb-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-lg bg-[#01FF87]/20 text-[#01FF87] font-semibold text-base border-2 border-[#01FF87]/40">
                        <span class="text-2xl mr-2">{{ $team->getFootballTypeIcon() }}</span>
                        {{ $team->getFootballTypeName() }}
                    </span>
                </div>

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
                @if($userPendingRequest)
                <div class="inline-flex items-center px-4 py-2 bg-yellow-500/20 text-yellow-400 font-medium rounded-lg border border-yellow-500/30">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Solicitud Pendiente
                </div>
                <form method="POST" action="{{ route('teams.join-requests.cancel', [$team, $userPendingRequest]) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                        onclick="return confirm('¿Estás seguro de que quieres cancelar tu solicitud?')">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar Solicitud
                    </button>
                </form>
                @else
                <button data-team-id="{{ $team->id }}" data-team-name="{{ $team->name }}" class="join-request-btn inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Solicitar Unirse
                </button>
                @endif
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

    <!-- Pending Join Requests (for owners/captains only) -->
    @if(($isOwner || $isCaptain) && $pendingRequests->count() > 0)
    <div class="bg-[#2a2a2a] rounded-xl p-8 border border-[#3a3a3a] mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-[#CDFDE6]">Solicitudes Pendientes</h2>
            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-sm font-medium rounded-full border border-yellow-500/30">
                {{ $pendingRequests->count() }} {{ $pendingRequests->count() === 1 ? 'solicitud' : 'solicitudes' }}
            </span>
        </div>

        <div class="space-y-4">
            @foreach($pendingRequests as $request)
            <div class="bg-[#3a3a3a] rounded-lg p-6 border border-[#4a4a4a] hover:border-[#01FF87]/30 transition-colors duration-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- User Info -->
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="h-12 w-12 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-[#1f1f1f]">{{ substr($request->user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-semibold text-[#CDFDE6] truncate">{{ $request->user->name }}</p>
                            <p class="text-sm text-gray-400 truncate">{{ $request->user->email }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $request->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <!-- Message -->
                    @if($request->message)
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-400 italic bg-[#2a2a2a] p-3 rounded-lg border-l-2 border-[#01FF87]/50">
                            "{{ $request->message }}"
                        </p>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('teams.join-requests.approve', [$team, $request]) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 transition-all duration-200"
                                title="Aprobar solicitud">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('teams.join-requests.reject', [$team, $request]) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 border border-red-500/30 transition-colors duration-200"
                                title="Rechazar solicitud">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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
</script>
@endsection