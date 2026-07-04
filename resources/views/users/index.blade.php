@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Título + botón Crear usuario --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0 text-primary fw-bold">👥 Lista de usuarios</h1>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            ➕ Crear usuario
        </a>
    </div>

    {{-- Barra de búsqueda --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text"
                       name="q"
                       class="form-control"
                       placeholder="Buscar por nombre, correo o área..."
                       value="{{ old('q', $search ?? request('q')) }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">
                    🔍 Buscar
                </button>
            </div>
            @if(!empty($search ?? request('q')))
                <div class="col-auto">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        ❌ Limpiar
                    </a>
                </div>
            @endif
        </div>
    </form>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 30%;">Nombre completo</th>
                            <th>Correo</th>
                            <th>Área de trabajo</th>
                            <th>Rol</th>
                            <th class="text-center" style="width: 210px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $nombreCompleto = trim(
                                    ($user['nombre'] ?? '') . ' ' .
                                    ($user['primer_apellido'] ?? '') . ' ' .
                                    ($user['segundo_apellido'] ?? '')
                                );
                            @endphp

                            <tr>
                                <td>{{ $nombreCompleto !== '' ? $nombreCompleto : '—' }}</td>
                                <td>{{ $user['correo'] ?? '—' }}</td>
                                <td>{{ $user['area_trabajo'] ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $user['rol'] ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- ✏️ Editar --}}
                                        <a href="{{ route('users.edit', $user['uid']) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            ✏️ Editar
                                        </a>

                                        {{-- 🗑️ Eliminar --}}
                                        <form action="{{ route('users.destroy', $user['uid']) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay usuarios @if(!empty($search)) que coincidan con “{{ $search }}” @else registrados @endif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

