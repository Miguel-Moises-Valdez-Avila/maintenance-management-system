@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-primary fw-bold mb-0">👤 Registrar usuario</h1>
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
            <form action="{{ route('users.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Primer apellido</label>
                    <input type="text" name="primer_apellido" class="form-control" required value="{{ old('primer_apellido') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Segundo apellido</label>
                    <input type="text" name="segundo_apellido" class="form-control" value="{{ old('segundo_apellido') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control" required value="{{ old('correo') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" required value="{{ old('telefono') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Área de trabajo</label>
                    <input type="text" name="area_trabajo" class="form-control" required value="{{ old('area_trabajo') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="Usuario" {{ old('rol') === 'Usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="Administrador" {{ old('rol') === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Superadmin" {{ old('rol') === 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="Webmaster" {{ old('rol') === 'Webmaster' ? 'selected' : '' }}>Webmaster</option>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">
                        💾 Guardar usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

