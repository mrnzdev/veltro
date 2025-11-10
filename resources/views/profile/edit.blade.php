@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Editar Perfil')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">Editar Perfil</h2>
                    <p class="text-gray-400">Actualiza la información de tu cuenta</p>
                </div>
                <a href="{{ route('profile.show') }}"
                    class="inline-flex items-center px-4 py-2 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Perfil
                </a>
            </div>
        </div>

        <!-- Profile Preview -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-6 mb-6">
            <h3 class="text-sm font-medium text-gray-400 mb-4">Vista Previa del Perfil</h3>
            <div class="flex items-center space-x-4">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" 
                        class="h-16 w-16 rounded-full object-cover border-4 border-[#01FF87]">
                @else
                    <div class="h-16 w-16 bg-gradient-to-r from-[#01FF87] to-[#00e676] rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-[#1f1f1f]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <p class="text-[#CDFDE6] font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-400">{{ Auth::user()->email }}</p>
                    @if(Auth::user()->avatar)
                        <p class="text-xs text-[#01FF87] mt-1">Foto de perfil de Google</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-[#2a2a2a] rounded-xl shadow-sm border border-[#3a3a3a] p-6">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Nombre Completo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" required
                            class="block w-full pl-10 pr-3 py-3 bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200 @error('name') border-red-500 focus:ring-red-500 @enderror"
                            value="{{ old('name', Auth::user()->name) }}" placeholder="Ingresa tu nombre completo">
                    </div>
                    @error('name')
                    <p class="mt-2 text-sm text-red-400 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-[#CDFDE6] mb-2">
                        Dirección de Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="block w-full pl-10 pr-3 py-3 bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl placeholder-gray-400 text-[#CDFDE6] focus:outline-none focus:ring-2 focus:ring-[#01FF87] focus:border-transparent transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 @enderror"
                            value="{{ old('email', Auth::user()->email) }}" placeholder="Ingresa tu dirección de email">
                    </div>
                    @error('email')
                    <p class="mt-2 text-sm text-red-400 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('profile.show') }}"
                        class="px-4 py-2 border border-[#3a3a3a] shadow-sm text-sm font-medium rounded-lg text-[#CDFDE6] bg-[#2a2a2a] hover:bg-[#3a3a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-[#1f1f1f] bg-[#01FF87] hover:bg-[#00e676] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#01FF87] transition-all duration-200">
                        Actualizar Perfil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection