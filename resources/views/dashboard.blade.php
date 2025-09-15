@extends('layouts.app')

@section('title', config('app.name', 'Laravel') . ' - Panel')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-[#CDFDE6] mb-2">¡Bienvenido de vuelta, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-gray-400">Aquí está lo que está pasando con tu cuenta hoy.</p>
    </div>
</div>
@endsection