@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-primary fw-bold mb-0">✏️ Editar usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
            ⬅ Volver a la lista
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.update', $user['uid']) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $user['nombre'] ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Primer apellido</label>
                    <input type="text" name="primer_apellido" class="form-control"
                           value="{{ old('primer_apellido', $user['primer_apellido'] ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Segundo apellido</label>
                    <input type="text" name="segundo_apellido" class="form-control"
                           value="{{ old('segundo_apellido', $user['segundo_apellido'] ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control"
                           value="{{ old('correo', $user['correo'] ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nueva contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Déjalo vacío si no quieres cambiarla.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $user['telefono'] ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Área de trabajo</label>
                    <input type="text" name="area_trabajo" class="form-control"
                           value="{{ old('area_trabajo', $user['area_trabajo'] ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol</label>
                    @php
                        $rolActual = old('rol', $user['rol'] ?? 'Usuario');
                    @endphp
                    <select name="rol" class="form-select" required>
                        <option value="Usuario" {{ $rolActual === 'Usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="Administrador" {{ $rolActual === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Superadmin" {{ $rolActual === 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="Webmaster" {{ $rolActual === 'Webmaster' ? 'selected' : '' }}>Webmaster</option>
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-primary">
                        💾 Guardar cambios
                    </button>

                    {{-- Botón de eliminar opcional, si ya lo manejas en rutas --}}
                    @if(isset($user['uid']))
                        <form action="{{ route('users.destroy', $user['uid']) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger">
                                🗑️ Eliminar usuario
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

