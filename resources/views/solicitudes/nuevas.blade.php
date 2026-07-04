@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">📥 Mantenimientos solicitados (nuevos)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-warning text-center">
                    <tr>
                        <th>Folio</th>
                        <th>Solicitante</th>
                        <th>Área responsable</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th style="width: 260px;">Asignar administrador</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudesNuevas as $req)
                        <tr>
                            <td class="text-center">
                                {{ $req['numeroFolio'] ?? '—' }}
                            </td>
                            <td>
                                {{ $req['nombreSolicitante'] ?? '—' }}
                            </td>
                            <td>
                                {{ $req['areaResponsable'] ?? '—' }}
                            </td>
                            <td>
                                {{ $req['descripcion'] ?? '—' }}
                            </td>
                            <td>
                                {{ $req['tipoMantenimiento'] ?? '—' }}
                            </td>
                            <td class="text-center">
                                {{ $req['fechaSolicitud'] ?? '—' }}
                            </td>
                            <td>
                                <form action="{{ route('solicitudes.updateEstado', $req['$adminsMap']) }}"
                                      method="POST"
                                      class="d-flex flex-column flex-md-row gap-2">
                                    @csrf
                                    @method('PATCH')

                                    {{-- Estado siempre se manda como Pendiente al asignar --}}
                                    <input type="hidden" name="estado" value="Pendiente">

                                    {{-- Selector de administrador --}}
                                    <select name="admin" class="form-select form-select-sm" required>
                                        <option value="">-- Seleccione administrador --</option>
                                        @foreach($adminsMap as $uid => $nombreAdmin)
                                            <option value="{{ $uid }}">
                                                {{ $nombreAdmin }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button class="btn btn-sm btn-primary mt-2 mt-md-0">
                                        Asignar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No hay solicitudes nuevas sin administrador asignado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

