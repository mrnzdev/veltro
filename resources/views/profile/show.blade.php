<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Perfil</title>

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
                        <a href="{{ route('dashboard') }}" class="h-8 w-8 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-[#1f1f1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </a>
                        <h1 class="text-xl font-bold text-[#CDFDE6]">{{ config('app.name', 'Laravel') }}</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-[#1f1f1f]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-[#CDFDE6]">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

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
    <main class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
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

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Configuración del Perfil</h2>
                    <p class="text-gray-400">Gestiona la información de tu cuenta y preferencias</p>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-4 py-2 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Panel
                </a>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-[#CDFDE6]">Información del Perfil</h3>
                <a href="{{ route('profile.edit') }}"
                    class="inline-flex items-center px-3 py-2 border border-[#01FF87] shadow-sm text-sm font-medium rounded-lg text-[#01FF87] bg-transparent hover:bg-[#01FF87] hover:text-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Perfil
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nombre Completo</label>
                    <p class="text-[#CDFDE6] font-medium">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Dirección de Email</label>
                    <p class="text-[#CDFDE6] font-medium">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Account Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Change Password -->
            <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-6">
                <h3 class="text-lg font-semibold text-[#CDFDE6] mb-4">Cambiar Contraseña</h3>
                <p class="text-gray-400 mb-4">Actualiza tu contraseña para mantener tu cuenta segura.</p>
                <button onclick="openPasswordModal()"
                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-[#01FF87] shadow-sm text-sm font-medium rounded-lg text-[#01FF87] bg-transparent hover:bg-[#01FF87] hover:text-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Cambiar Contraseña
                </button>
            </div>

            <!-- Delete Account -->
            <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-red-500/30 p-6">
                <h3 class="text-lg font-semibold text-red-400 mb-4">Eliminar Cuenta</h3>
                <p class="text-gray-400 mb-4">Elimina permanentemente tu cuenta y todos los datos asociados. Esta acción no se puede deshacer.</p>
                <button onclick="openDeleteModal()"
                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-500 shadow-sm text-sm font-medium rounded-lg text-red-400 bg-transparent hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Eliminar Cuenta
                </button>
            </div>
        </div>
    </main>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#2a2a2a] rounded-xl shadow-xl border border-[#3a3a3a] p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#CDFDE6]">Cambiar Contraseña</h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-[#CDFDE6]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-[#CDFDE6] mb-2">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password" required
                            class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-[#CDFDE6] mb-2">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" required
                            class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#CDFDE6] mb-2">Confirmar Nueva Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePasswordModal()"
                        class="px-4 py-2 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-[#1f1f1f] bg-[#01FF87] hover:bg-[#00e676] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#2a2a2a] rounded-xl shadow-xl border border-red-500/30 p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-red-400">Eliminar Cuenta</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-[#CDFDE6]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-[#CDFDE6] mb-4">Esta acción no se puede deshacer. Esto eliminará permanentemente tu cuenta y removerá todos tus datos de nuestros servidores.</p>
            </div>

            <form action="{{ route('profile.delete') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="space-y-4">
                    <div>
                        <label for="delete_password" class="block text-sm font-medium text-[#CDFDE6] mb-2">Ingresa tu contraseña</label>
                        <input type="password" id="delete_password" name="password" required
                            class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="delete_confirmation" class="block text-sm font-medium text-[#CDFDE6] mb-2">Escribe "ELIMINAR" para confirmar</label>
                        <input type="text" id="delete_confirmation" name="confirmation" required
                            class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="ELIMINAR">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                        Eliminar Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('passwordModal').classList.add('flex');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('passwordModal').classList.remove('flex');
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        // Close modals when clicking outside
        document.getElementById('passwordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>

</html>