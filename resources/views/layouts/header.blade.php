<!-- Modern Navigation Header -->
<nav class="bg-[#1a1a1a]/95 backdrop-blur-md border-b border-[#2a2a2a]/50 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center group">
                    <div class="h-9 w-9 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-xl flex items-center justify-center mr-3 group-hover:scale-105 transition-transform duration-300 shadow-lg shadow-[#01FF87]/20">
                        <svg class="h-5 w-5 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-[#CDFDE6] group-hover:text-[#01FF87] transition-colors duration-300">
                        {{ config('app.name', 'Veltro') }}
                    </h1>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-[#01FF87] bg-[#2a2a2a]/30' : '' }}">
                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Panel
                </a>
                <a href="{{ route('teams.index') }}"
                    class="px-4 py-2 text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 {{ request()->routeIs('teams.*') ? 'text-[#01FF87] bg-[#2a2a2a]/30' : '' }}">
                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Equipos
                </a>
                <a href="{{ route('match-requests.index') }}"
                    class="px-4 py-2 text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 {{ request()->routeIs('match-requests.*') ? 'text-[#01FF87] bg-[#2a2a2a]/30' : '' }}">
                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Partidos
                </a>
            </div>

            <!-- User Menu & Actions -->
            <div class="flex items-center space-x-3">
                <!-- Notifications -->
                @if($totalNotificationsCount > 0)
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 group">
                        <svg class="h-5 w-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <!-- Notification Badge -->
                        <span class="absolute top-0 right-0 h-5 w-5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#1a1a1a] shadow-lg shadow-[#01FF87]/30 animate-pulse">
                            {{ $totalNotificationsCount > 9 ? '9+' : $totalNotificationsCount }}
                        </span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-96 max-h-[32rem] overflow-y-auto bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl shadow-2xl z-50">
                        <div class="p-4 border-b border-[#2a2a2a] sticky top-0 bg-[#1a1a1a]">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-bold text-[#CDFDE6]">Notificaciones Pendientes</h3>
                                <span class="px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] text-xs font-bold rounded-full border border-[#01FF87]/30">
                                    {{ $totalNotificationsCount }}
                                </span>
                            </div>
                        </div>

                        <!-- Match Applications Section -->
                        @if($pendingMatchApplicationsCount > 0)
                        <div class="bg-[#1f1f1f]">
                            <div class="px-4 py-2 bg-[#2a2a2a]/30 border-b border-[#2a2a2a]">
                                <h4 class="text-sm font-semibold text-[#01FF87]">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Solicitudes de Partido ({{ $pendingMatchApplicationsCount }})
                                </h4>
                            </div>
                            <div class="divide-y divide-[#2a2a2a]">
                                @foreach($pendingMatchApplications as $application)
                                <div class="p-4 hover:bg-[#2a2a2a]/30 transition-colors duration-200">
                                    <div class="flex items-start gap-3">
                                        <!-- Team Avatar -->
                                        <div class="h-10 w-10 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-[#1f1f1f]">{{ substr($application->applicantTeam->name, 0, 1) }}</span>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-[#CDFDE6] mb-1">
                                                <span class="font-semibold text-[#01FF87]">{{ $application->applicantTeam->name }}</span>
                                                quiere jugar contra
                                                <span class="font-semibold">{{ $application->matchRequest->team->name }}</span>
                                            </p>

                                            <p class="text-xs text-gray-400 mb-2">
                                                <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $application->matchRequest->match_datetime->format('d M Y, H:i') }}
                                                <svg class="h-3 w-3 inline ml-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $application->matchRequest->location }}
                                            </p>

                                            @if($application->message)
                                            <p class="text-xs text-gray-400 italic mb-2 line-clamp-2 bg-[#2a2a2a]/50 p-2 rounded border-l-2 border-[#01FF87]/50">
                                                "{{ $application->message }}"
                                            </p>
                                            @endif

                                            <p class="text-xs text-gray-500 mb-3">
                                                <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $application->created_at->diffForHumans() }}
                                            </p>

                                            <!-- Actions -->
                                            <div class="flex gap-2">
                                                <form method="POST" action="{{ route('match-requests.applications.accept', [$application->matchRequest, $application]) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-1.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-xs font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 transition-all duration-200">
                                                        ✓ Aceptar
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('match-requests.applications.cancel', [$application->matchRequest, $application]) }}" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full px-3 py-1.5 bg-red-500/20 text-red-400 text-xs font-semibold rounded-lg hover:bg-red-500/30 border border-red-500/30 transition-all duration-200">
                                                        ✕ Rechazar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Team Join Requests Section -->
                        @if($pendingJoinRequestsCount > 0)
                        <div class="bg-[#1f1f1f]">
                            <div class="px-4 py-2 bg-[#2a2a2a]/30 border-b border-[#2a2a2a]">
                                <h4 class="text-sm font-semibold text-[#01FF87]">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Solicitudes de Equipo ({{ $pendingJoinRequestsCount }})
                                </h4>
                            </div>
                            <div class="divide-y divide-[#2a2a2a]">
                                @foreach($pendingJoinRequests as $request)
                            <div class="p-4 hover:bg-[#2a2a2a]/30 transition-colors duration-200">
                                <div class="flex items-start gap-3">
                                    <!-- User Avatar -->
                                    <div class="h-10 w-10 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-[#1f1f1f]">{{ substr($request->user->name, 0, 1) }}</span>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-[#CDFDE6] mb-1">
                                            <span class="font-semibold">{{ $request->user->name }}</span>
                                            quiere unirse a
                                            <span class="font-semibold text-[#01FF87]">{{ $request->team->name }}</span>
                                        </p>

                                        @if($request->message)
                                        <p class="text-xs text-gray-400 italic mb-2 line-clamp-2 bg-[#2a2a2a]/50 p-2 rounded border-l-2 border-[#01FF87]/50">
                                            "{{ $request->message }}"
                                        </p>
                                        @endif

                                        <p class="text-xs text-gray-500 mb-3">
                                            <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $request->created_at->diffForHumans() }}
                                        </p>

                                        <!-- Actions -->
                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('teams.join-requests.approve', [$request->team, $request]) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-1.5 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] text-xs font-bold rounded-lg hover:shadow-lg hover:shadow-[#01FF87]/20 transition-all duration-200">
                                                    ✓ Aprobar
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('teams.join-requests.reject', [$request->team, $request]) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-1.5 bg-red-500/20 text-red-400 text-xs font-semibold rounded-lg hover:bg-red-500/30 border border-red-500/30 transition-all duration-200">
                                                    ✕ Rechazar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="p-3 border-t border-[#2a2a2a] bg-[#1a1a1a] sticky bottom-0">
                            <div class="flex gap-2">
                                @if($pendingMatchApplicationsCount > 0)
                                <a href="{{ route('match-requests.my-requests') }}" class="flex-1 text-center px-3 py-2 text-xs font-medium text-[#01FF87] hover:text-[#00e676] hover:bg-[#2a2a2a]/30 rounded-lg transition-colors duration-200">
                                    Mis Partidos →
                                </a>
                                @endif
                                @if($pendingJoinRequestsCount > 0)
                                <a href="{{ route('teams.index') }}" class="flex-1 text-center px-3 py-2 text-xs font-medium text-[#01FF87] hover:text-[#00e676] hover:bg-[#2a2a2a]/30 rounded-lg transition-colors duration-200">
                                    Mis Equipos →
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- User Profile -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 group">
                        <div class="h-9 w-9 bg-gradient-to-br from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center group-hover:scale-105 transition-transform duration-300 shadow-lg shadow-[#01FF87]/20">
                            <span class="text-sm font-semibold text-[#1f1f1f]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden lg:block">
                            <p class="text-sm font-medium text-[#CDFDE6] group-hover:text-[#01FF87] transition-colors duration-200">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 group-hover:text-[#01FF87]/70 transition-colors duration-200">{{ Auth::user()->email }}</p>
                        </div>
                    </a>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-[#CDFDE6] hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all duration-200 group">
                        <svg class="h-4 w-4 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="hidden sm:inline">Cerrar sesión</span>
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button type="button"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 transition-all duration-200"
                    onclick="toggleMobileMenu()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden border-t border-[#2a2a2a]/50">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 text-base font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-[#01FF87] bg-[#2a2a2a]/30' : '' }}">
                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Panel
                </a>
                <a href="{{ route('teams.index') }}"
                    class="block px-3 py-2 text-base font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 {{ request()->routeIs('teams.*') ? 'text-[#01FF87] bg-[#2a2a2a]/30' : '' }}">
                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Equipos
                </a>
                <a href="{{ route('match-requests.index') }}"
                    class="block px-3 py-2 text-base font-medium text-[#CDFDE6] hover:text-[#01FF87] hover:bg-[#2a2a2a]/50 rounded-lg transition-all duration-200 {{ request()->routeIs('match-requests.*') ? 'text-[#01FF87] bg-[#2a2a2a]/30' : '' }}">
                    <svg class="h-4 w-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Partidos
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu JavaScript -->
<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }

    // Notifications Dropdown
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationsDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close notifications dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationsDropdown');
        const button = event.target.closest('button[onclick="toggleNotifications()"]');

        if (dropdown && !dropdown.contains(event.target) && !button) {
            dropdown.classList.add('hidden');
        }
    });

    // Prevent dropdown from closing when clicking inside
    document.getElementById('notificationsDropdown')?.addEventListener('click', function(event) {
        event.stopPropagation();
    });
</script>