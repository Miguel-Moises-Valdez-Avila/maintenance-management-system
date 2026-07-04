@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">📦 Baúl de solicitudes pendientes</h1>

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
                        <th>Administrador asignado</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th style="width: 260px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendientes as $req)
                        <tr>
                            <td class="text-center">
                                {{ $req['numeroFolio'] ?? '—' }}
                            </td>
                            <td>{{ $req['nombreSolicitante'] ?? '—' }}</td>
                            <td>{{ $req['areaResponsable'] ?? '—' }}</td>
                            <td>{{ $req['descripcion'] ?? '—' }}</td>
                            <td>{{ $req['tipoMantenimiento'] ?? '—' }}</td>
                            <td>
                                @php
                                    $adminsAsignados = $req['adminsAsignados'] ?? [];
                                    if (!is_array($adminsAsignados)) {
                                        $adminsAsignados = [$adminsAsignados];
                                    }
                                    $adminsAsignados = array_filter($adminsAsignados);
                                @endphp

                                @if(empty($adminsAsignados))
                                    <span class="text-muted">Sin asignar</span>
                                @else
                                    @foreach($adminsAsignados as $uid)
                                        @php $nombreAdmin = $adminsMap[$uid] ?? null; @endphp
                                        @if($nombreAdmin)
                                            <div>👤 {{ $nombreAdmin }}</div>
                                        @else
                                            <div class="text-danger">👤 Admin no encontrado</div>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">
                                    {{ $req['estado'] ?? 'Pendiente' }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $req['fechaSolicitud'] ?? '—' }}
                            </td>
                            <td>
                                <form action="{{ route('solicitudes.updateEstado', $req['id']) }}"
                                      method="POST"
                                      class="d-flex flex-column gap-2 estado-form">
                                    @csrf
                                    @method('PATCH')

                                    <div class="d-flex gap-2">
                                        <select name="estado"
                                                class="form-select form-select-sm estado-select">
                                            <option value="Pendiente" {{ ($req['estado'] ?? '') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="En proceso" {{ ($req['estado'] ?? '') === 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                            <option value="Finalizado" {{ ($req['estado'] ?? '') === 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                            <option value="Rechazado" {{ ($req['estado'] ?? '') === 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                        </select>

                                        <button class="btn btn-sm btn-primary">
                                            Actualizar
                                        </button>
                                    </div>

                                    <div class="razon-rechazo-container {{ ($req['estado'] ?? '') === 'Rechazado' ? '' : 'd-none' }}">
                                        <textarea name="razonBaul"
                                                  class="form-control form-control-sm mt-1"
                                                  rows="2"
                                                  placeholder="Escribe la razón del rechazo...">{{ $req['razonBaul'] ?? '' }}</textarea>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No hay solicitudes pendientes en el baúl.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.estado-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const form = this.closest('.estado-form');
            const razonContainer = form.querySelector('.razon-rechazo-container');
            if (!razonContainer) return;

            if (this.value === 'Rechazado') {
                razonContainer.classList.remove('d-none');
            } else {
                razonContainer.classList.add('d-none');
                const textarea = razonContainer.querySelector('textarea');
                if (textarea) textarea.value = '';
            }
        });
    });
});
</script>
@endpush
@endsection
