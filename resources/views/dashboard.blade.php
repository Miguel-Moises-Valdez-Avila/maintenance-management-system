@extends('layouts.app')

@section('content')
    <!-- Encabezado con datos del usuario -->
    <div class="flex items-center space-x-4 mb-10">
        <div class="w-16 h-16 bg-gradient-to-br from-itchetumal-blue to-itchetumal-orange text-white rounded-full flex items-center justify-center font-bold shadow-md">
            {{ strtoupper(substr(session('firebase_name', 'U'), 0, 2)) }}
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                {{ session('firebase_name', 'Nombre') }} {{ session('firebase_lastname1', '') }} {{ session('firebase_lastname2', '') }}
            </h2>
            <p class="text-sm text-gray-600 capitalize">
                Rol: <span class="font-semibold text-itchetumal-blue">{{ session('firebase_role', 'usuario') }}</span>
            </p>
        </div>
    </div>

    <!-- Mensaje de bienvenida con fondo degradado -->
    <div class="bg-gradient-to-b from-white to-orange-400 p-10 rounded-2xl shadow-lg text-center text-gray-900 mb-12">
        <h1 class="text-3xl font-extrabold mb-3">
            ¡Bienvenido, {{ session('firebase_name', 'Usuario') }}!
        </h1>
        <p class="text-lg opacity-90">
            Gestiona mantenimientos, solicitudes y usuarios desde tu panel principal.
        </p>
    </div>

   
@endsection
