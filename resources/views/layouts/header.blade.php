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
            </div>

            <!-- User Menu & Actions -->
            <div class="flex items-center space-x-3">
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
</script>
