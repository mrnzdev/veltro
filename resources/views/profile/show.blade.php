@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
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

        <!-- Header with Back Button -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Mi Perfil</h2>
                <p class="text-gray-400">Gestiona tu información personal y configuración de cuenta</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Panel
            </a>
        </div>

        <!-- Profile Header Card -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-8 mb-6">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" 
                            class="h-24 w-24 rounded-full object-cover border-4 border-[#01FF87] shadow-lg">
                    @else
                        <div class="h-24 w-24 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-4xl font-bold text-[#1f1f1f]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                        <h3 class="text-2xl font-bold text-[#CDFDE6]">{{ Auth::user()->name }}</h3>
                        @if(Auth::user()->hasGoogleLinked())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-300 border border-blue-500/30">
                                <svg class="h-3 w-3 mr-1" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Cuenta Google Vinculada
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-400 mb-3">{{ Auth::user()->email }}</p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Miembro desde {{ Auth::user()->created_at->format('M Y') }}
                        </div>
                        @if(Auth::user()->email_verified_at)
                            <div class="flex items-center text-[#01FF87]">
                                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Email Verificado
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Edit Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center px-4 py-2 border border-[#01FF87] shadow-sm text-sm font-medium rounded-lg text-[#01FF87] bg-transparent hover:bg-[#01FF87] hover:text-[#1f1f1f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Connected Accounts Section -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-6 mb-6">
            <h3 class="text-lg font-semibold text-[#CDFDE6] mb-4 flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                Cuentas Conectadas
            </h3>
            <p class="text-sm text-gray-400 mb-6">Gestiona las cuentas externas vinculadas a tu perfil</p>

            <!-- Google Account -->
            <div class="flex items-center justify-between p-4 bg-[#1f1f1f] rounded-lg border border-[#3a3a3a]">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 bg-white rounded-lg flex items-center justify-center">
                        <svg class="h-8 w-8" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[#CDFDE6] font-medium">Google</h4>
                        @if(Auth::user()->hasGoogleLinked())
                            <p class="text-sm text-gray-400">Conectado como {{ Auth::user()->email }}</p>
                        @else
                            <p class="text-sm text-gray-400">No conectado</p>
                        @endif
                    </div>
                </div>
                <div>
                    @if(Auth::user()->hasGoogleLinked())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-900/30 text-green-300 border border-green-500/30">
                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Conectado
                        </span>
                    @else
                        <a href="{{ route('auth.google') }}"
                            class="inline-flex items-center px-3 py-1.5 border border-[#3a3a3a] shadow-sm text-xs font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                            Conectar Cuenta
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Security & Authentication Section -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-6 mb-6">
            <h3 class="text-lg font-semibold text-[#CDFDE6] mb-4 flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Seguridad y Autenticación
            </h3>

            <!-- Authentication Method -->
            <div class="mb-6 p-4 bg-[#1f1f1f] rounded-lg border border-[#3a3a3a]">
                <label class="block text-sm font-medium text-gray-400 mb-2">Métodos de inicio de sesión</label>
                <div class="flex flex-wrap gap-2">
                    @if(Auth::user()->password)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#2a2a2a] text-[#CDFDE6] border border-[#3a3a3a]">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email y Contraseña
                        </span>
                    @endif
                    @if(Auth::user()->hasGoogleLinked())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-900/30 text-blue-300 border border-blue-500/30">
                            <svg class="h-3 w-3 mr-1" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            </svg>
                            Google OAuth
                        </span>
                    @endif
                </div>
                @if(Auth::user()->isOAuthOnly())
                    <p class="text-xs text-gray-400 mt-2">Tu cuenta solo usa Google para iniciar sesión. Puedes agregar una contraseña como respaldo.</p>
                @endif
            </div>

            <!-- Change Password Button -->
            <button onclick="openPasswordModal()"
                class="w-full inline-flex items-center justify-center px-4 py-3 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#1f1f1f] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                @if(Auth::user()->password)
                    Cambiar Contraseña
                @else
                    Establecer Contraseña
                @endif
            </button>
        </div>

        <!-- Danger Zone -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-red-500/30 p-6">
            <h3 class="text-lg font-semibold text-red-400 mb-2 flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Zona de Peligro
            </h3>
            <p class="text-sm text-gray-400 mb-6">Acciones irreversibles que afectan tu cuenta</p>
            
            <div class="p-4 bg-red-900/10 border border-red-500/20 rounded-lg">
                <h4 class="text-[#CDFDE6] font-medium mb-2">Eliminar Cuenta</h4>
                <p class="text-sm text-gray-400 mb-4">Elimina permanentemente tu cuenta y todos los datos asociados. Esta acción no se puede deshacer.</p>
                <button onclick="openDeleteModal()"
                    class="inline-flex items-center px-4 py-2 border border-red-500 shadow-sm text-sm font-medium rounded-lg text-red-400 bg-transparent hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Eliminar Mi Cuenta
                </button>
            </div>
        </div>
    </main>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#2a2a2a] rounded-xl shadow-xl border border-[#3a3a3a] p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#CDFDE6]">
                    @if(Auth::user()->password)
                        Cambiar Contraseña
                    @else
                        Establecer Contraseña
                    @endif
                </h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-[#CDFDE6]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @if(Auth::user()->isOAuthOnly())
                <div class="mb-4 p-3 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                    <p class="text-sm text-blue-300">
                        <svg class="h-4 w-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Agrega una contraseña como método de respaldo para acceder a tu cuenta.
                    </p>
                </div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    @if(Auth::user()->password)
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-[#CDFDE6] mb-2">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password" required
                                class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent">
                        </div>
                    @endif

                    <div>
                        <label for="password" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                            @if(Auth::user()->password)
                                Nueva Contraseña
                            @else
                                Contraseña
                            @endif
                        </label>
                        <input type="password" id="password" name="password" required
                            class="block w-full px-3 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                            @if(Auth::user()->password)
                                Confirmar Nueva Contraseña
                            @else
                                Confirmar Contraseña
                            @endif
                        </label>
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
                        @if(Auth::user()->password)
                            Actualizar Contraseña
                        @else
                            Establecer Contraseña
                        @endif
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
@endsection