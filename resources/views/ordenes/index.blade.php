@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-primary fw-bold mb-4">⚙️ Órdenes Pendientes</h2>

    @if(!empty($ordenesActivas) && count($ordenesActivas) > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped align-middle">
                <thead class="table-warning text-center">
                    <tr>
                        <th>Área Solicitante</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordenesActivas as $orden)
                        @if(isset($orden['estado']) && $orden['estado'] === 'Pendiente')
                            <tr>
                                <td>{{ $orden['areaSolicitante'] ?? 'N/A' }}</td>
                                <td>{{ $orden['descripcion'] ?? 'Sin descripción' }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $orden['estado'] ?? 'Pendiente' }}</span>
                                </td>
                                <td>{{ $orden['fechaSolicitud'] ?? 'Sin fecha' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-secondary">No hay órdenes pendientes actualmente.</div>
    @endif
</div>
@endsection
