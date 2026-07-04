@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-success fw-bold">✅ Órdenes de mantenimiento terminadas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>Folio</th>
                        <th>Solicitante</th>
                        <th>Área responsable</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Administrador asignado</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenesFinalizadas as $req)
                        <tr>
                            <td class="text-center">
                                {{ $req['numeroFolio'] ?? '—' }}
                            </td>
                            <td>{{ $req['nombreSolicitante'] ?? '—' }}</td>
                            <td>{{ $req['areaResponsable'] ?? '—' }}</td>
                            <td>{{ $req['descripcion'] ?? '—' }}</td>
                            <td>{{ $req['tipoMantenimiento'] ?? '—' }}</td>

                            {{-- 👤 Administrador asignado --}}
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
                                <span class="badge bg-success">
                                    {{ $req['estado'] ?? 'Finalizado' }}
                                </span>
                            </td>

                            <td class="text-center">
                                {{ $req['fechaSolicitud'] ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No hay órdenes finalizadas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
