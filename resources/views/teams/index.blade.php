<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Teams</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen" style="background-color: #1f1f1f; color: #CDFDE6;">
    <!-- Navigation -->
    <nav class="bg-[#2a2a2a] shadow-sm border-b border-[#3a3a3a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-[#CDFDE6]">{{ config('app.name', 'Veltro') }}</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        Dashboard
                    </a>
                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                        <div class="h-8 w-8 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center hover:scale-110 transition-transform duration-200">
                            <span class="text-sm font-medium text-[#1f1f1f]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">{{ Auth::user()->name }}</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-[#3a3a3a] shadow-sm text-sm leading-4 font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-8 bg-green-900/20 border border-green-500/30 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-[#01FF87]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-[#CDFDE6]">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-8 bg-red-900/20 border border-red-500/30 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-[#CDFDE6]">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Teams ⚽</h2>
                <p class="text-gray-400">Manage your teams and discover new ones to join.</p>
            </div>
            <a href="{{ route('teams.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Team
            </a>
        </div>

        <!-- My Teams Section -->
        @if($ownedTeams->count() > 0 || $memberTeams->count() > 0)
        <div class="mb-12">
            <h3 class="text-xl font-semibold text-[#CDFDE6] mb-6">My Teams</h3>
            
            <!-- Owned Teams -->
            @if($ownedTeams->count() > 0)
            <div class="mb-8">
                <h4 class="text-lg font-medium text-[#01FF87] mb-4">Teams I Own</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($ownedTeams as $team)
                    <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-lg font-semibold text-[#CDFDE6]">{{ $team->name }}</h5>
                            <span class="px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] text-xs font-medium rounded-full">Owner</span>
                        </div>
                        @if($team->description)
                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($team->description, 100) }}</p>
                        @endif
                        <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                            <span>{{ $team->getCurrentMembersCount() }}/{{ $team->max_members }} members</span>
                            <span>{{ $team->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('teams.show', $team) }}" 
                               class="flex-1 text-center px-3 py-2 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                                View
                            </a>
                            <a href="{{ route('teams.edit', $team) }}" 
                               class="flex-1 text-center px-3 py-2 bg-[#01FF87]/20 text-[#01FF87] rounded-lg hover:bg-[#01FF87]/30 transition-colors duration-200">
                                Edit
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
                <h4 class="text-lg font-medium text-[#01FF87] mb-4">Teams I'm In</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($memberTeams as $team)
                    <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-lg font-semibold text-[#CDFDE6]">{{ $team->name }}</h5>
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs font-medium rounded-full">
                                {{ $team->pivot->role === 'captain' ? 'Captain' : 'Member' }}
                            </span>
                        </div>
                        @if($team->description)
                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($team->description, 100) }}</p>
                        @endif
                        <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                            <span>Owner: {{ $team->owner->name }}</span>
                            <span>Joined {{ $team->pivot->joined_at ? $team->pivot->joined_at->diffForHumans() : 'Recently' }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('teams.show', $team) }}" 
                               class="flex-1 text-center px-3 py-2 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                                View
                            </a>
                            <form method="POST" action="{{ route('teams.leave', $team) }}" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-3 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                                        onclick="return confirm('Are you sure you want to leave this team?')">
                                    Leave
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
            <h3 class="text-xl font-semibold text-[#CDFDE6] mb-6">All Teams</h3>
            @if($allTeams->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allTeams as $team)
                <div class="bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a] hover:border-[#01FF87]/50 transition-colors duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-[#CDFDE6]">{{ $team->name }}</h5>
                        @if($team->hasMember(Auth::user()))
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">Member</span>
                        @elseif($team->hasSpaceForMembers())
                        <span class="px-2 py-1 bg-[#01FF87]/20 text-[#01FF87] text-xs font-medium rounded-full">Open</span>
                        @else
                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 text-xs font-medium rounded-full">Full</span>
                        @endif
                    </div>
                    @if($team->description)
                    <p class="text-gray-400 text-sm mb-4">{{ Str::limit($team->description, 100) }}</p>
                    @endif
                    <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                        <span>{{ $team->getCurrentMembersCount() }}/{{ $team->max_members }} members</span>
                        <span>Owner: {{ $team->owner->name }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('teams.show', $team) }}" 
                           class="flex-1 text-center px-3 py-2 bg-[#3a3a3a] text-[#CDFDE6] rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                            View
                        </a>
                        @if(!$team->hasMember(Auth::user()) && $team->hasSpaceForMembers())
                        <form method="POST" action="{{ route('teams.join', $team) }}" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-3 py-2 bg-[#01FF87]/20 text-[#01FF87] rounded-lg hover:bg-[#01FF87]/30 transition-colors duration-200">
                                Join
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
                <h3 class="mt-2 text-sm font-medium text-[#CDFDE6]">No teams found</h3>
                <p class="mt-1 text-sm text-gray-400">Get started by creating a new team.</p>
                <div class="mt-6">
                    <a href="{{ route('teams.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium rounded-lg hover:opacity-90 transition-opacity duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Team
                    </a>
                </div>
            </div>
            @endif
        </div>
    </main>
</body>

</html>
