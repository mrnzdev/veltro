<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Edit {{ $team->name }}</title>

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
                    <a href="{{ route('teams.show', $team) }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        ← Back to Team
                    </a>
                    <a href="{{ route('teams.index') }}" class="text-[#CDFDE6] hover:text-[#01FF87] transition-colors duration-200">
                        All Teams
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Edit Team ⚽</h2>
            <p class="text-gray-400">Update your team information and settings.</p>
        </div>

        <!-- Form -->
        <div class="bg-[#2a2a2a] rounded-xl p-8 border border-[#3a3a3a]">
            <form method="POST" action="{{ route('teams.update', $team) }}">
                @csrf
                @method('PUT')

                <!-- Team Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Team Name *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $team->name) }}"
                           class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                           placeholder="Enter your team name"
                           required>
                    @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                              placeholder="Tell us about your team...">{{ old('description', $team->description) }}</textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Members -->
                <div class="mb-6">
                    <label for="max_members" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Maximum Members *
                    </label>
                    <select id="max_members" 
                            name="max_members"
                            class="w-full px-4 py-3 bg-[#3a3a3a] border border-[#4a4a4a] rounded-lg text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200"
                            required>
                        <option value="">Select maximum members</option>
                        @for($i = 2; $i <= 50; $i++)
                        <option value="{{ $i }}" {{ old('max_members', $team->max_members) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ $i === 11 ? '(Standard Football Team)' : '' }}
                        </option>
                        @endfor
                    </select>
                    @error('max_members')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Team Status (Owner only) -->
                @if($team->isOwnedBy(Auth::user()))
                <div class="mb-8">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $team->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#01FF87] focus:ring-[#01FF87] border-[#4a4a4a] rounded bg-[#3a3a3a]">
                        <span class="ml-2 text-sm text-[#CDFDE6]">Team is active</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-400">Inactive teams won't be visible to other users.</p>
                    @error('is_active')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-[#01FF87] to-[#00e676] text-[#1f1f1f] font-medium py-3 px-6 rounded-lg hover:opacity-90 transition-opacity duration-200">
                        Update Team
                    </button>
                    <a href="{{ route('teams.show', $team) }}" 
                       class="flex-1 text-center bg-[#3a3a3a] text-[#CDFDE6] font-medium py-3 px-6 rounded-lg hover:bg-[#4a4a4a] transition-colors duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Team Stats -->
        <div class="mt-8 bg-[#2a2a2a] rounded-xl p-6 border border-[#3a3a3a]">
            <h3 class="text-lg font-semibold text-[#CDFDE6] mb-4">Team Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-[#01FF87]">{{ $team->getCurrentMembersCount() }}</div>
                    <div class="text-sm text-gray-400">Current Members</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-[#01FF87]">{{ $team->max_members }}</div>
                    <div class="text-sm text-gray-400">Maximum Members</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-[#01FF87]">{{ $team->created_at->diffInDays(now()) }}</div>
                    <div class="text-sm text-gray-400">Days Active</div>
                </div>
            </div>
        </div>

        <!-- Danger Zone (Owner only) -->
        @if($team->isOwnedBy(Auth::user()))
        <div class="mt-8 bg-red-900/20 border border-red-500/30 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-red-400 mb-4">Danger Zone</h3>
            <p class="text-sm text-gray-400 mb-4">Once you delete a team, there is no going back. Please be certain.</p>
            <form method="POST" action="{{ route('teams.destroy', $team) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 transition-colors duration-200"
                        onclick="return confirm('Are you absolutely sure you want to delete this team? This action cannot be undone and will remove all team data, including member relationships.')">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Team
                </button>
            </form>
        </div>
        @endif
    </main>
</body>

</html>
