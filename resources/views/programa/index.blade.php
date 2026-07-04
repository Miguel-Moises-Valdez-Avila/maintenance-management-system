@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4 text-primary fw-bold">
        📆 Programa de Mantenimiento Preventivo
    </h1>

    {{-- Mensajes --}}
    @if($error)
        <div class="alert alert-danger">
            ⚠️ Error al obtener datos de Firebase: {{ $error }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('programa.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Periodo</label>
                    <select name="periodo" class="form-select">
                        <option value="">-- Seleccione periodo --</option>
                        @foreach($periodos as $p)
                            <option value="{{ $p }}" {{ $selectedPeriodo === $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sala / Laboratorio</label>
                    <select name="sala" class="form-select">
                        <option value="">-- Todas las salas --</option>
                        @foreach($salas as $s)
                            <option value="{{ $s }}" {{ $selectedSala === $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        🔍 Buscar
                    </button>

                    <a href="{{ route('programa.index') }}" class="btn btn-outline-secondary">
                        🔄 Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- CHECKLIST / HALLAZGOS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white fw-semibold">
            📋 Checklist / Hallazgos del área
        </div>
        <div class="card-body">

            @if($hallazgo)
                <div class="mb-3">
                    <p class="mb-1"><strong>Periodo:</strong> {{ $hallazgo['periodo'] ?? '—' }}</p>
                    <p class="mb-1"><strong>Sala / Espacio revisado:</strong> {{ $hallazgo['sala'] ?? '—' }}</p>
                    <p class="mb-1"><strong>Jefe de área:</strong> {{ $hallazgo['jefe_area'] ?? '—' }}</p>
                    <p class="mb-1"><strong>Atendido por:</strong> {{ $hallazgo['atendido_por'] ?? '—' }}</p>
                    <p class="mb-1"><strong>Fecha:</strong> {{ $hallazgo['fecha'] ?? '—' }}</p>
                </div>

                @php
                    $listaHallazgos = $hallazgo['hallazgos'] ?? [];
                @endphp

                @if(is_array($listaHallazgos) && count($listaHallazgos) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-center" style="width: 120px;">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listaHallazgos as $h)
                                    <tr>
                                        <td>{{ $h['descripcion'] ?? $h['detalle'] ?? '—' }}</td>
                                        <td class="text-center">{{ $h['cantidad'] ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">
                        No hay hallazgos registrados para los filtros seleccionados.
                    </p>
                @endif

            @else
                <p class="text-muted mb-0">
                    Selecciona un periodo (y opcionalmente una sala) y haz clic en <strong>Buscar</strong>
                    para ver el checklist.
                </p>
            @endif
        </div>
    </div>

    {{-- PROGRAMA DE MANTENIMIENTO PREVENTIVO --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between">
            <span>📊 Programa de Mantenimiento Preventivo (por meses)</span>
            @if($selectedPeriodo)
                <span>Periodo: <strong>{{ $selectedPeriodo }}</strong></span>
            @endif
        </div>
        <div class="card-body">

            @if(count($programas) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 220px;">Laboratorio / Sala</th>
                                <th style="min-width: 160px;">Servicio</th>
                                <th style="min-width: 80px;">Tipo</th>
                                <th>ENE</th>
                                <th>FEB</th>
                                <th>MAR</th>
                                <th>ABR</th>
                                <th>MAY</th>
                                <th>JUN</th>
                                <th>JUL</th>
                                <th>AGO</th>
                                <th>SEPT</th>
                                <th>OCT</th>
                                <th>NOV</th>
                                <th>DIC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($programas as $p)
                                @php
                                    $prog = $p['programacion'] ?? [];
                                @endphp
                                <tr>
                                    <td class="text-start">
                                        {{ $p['laboratorio'] ?? $p['sala'] ?? '—' }}
                                    </td>
                                    <td class="text-start">
                                        {{ $p['servicio'] ?? 'Mantenimiento preventivo' }}
                                    </td>
                                    <td>
                                        {{ $p['tipo_servicio'] ?? $p['tipo'] ?? '' }}
                                    </td>
                                    <td>{{ $prog['ENE']  ?? '' }}</td>
                                    <td>{{ $prog['FEB']  ?? '' }}</td>
                                    <td>{{ $prog['MAR']  ?? '' }}</td>
                                    <td>{{ $prog['ABR']  ?? '' }}</td>
                                    <td>{{ $prog['MAY']  ?? '' }}</td>
                                    <td>{{ $prog['JUN']  ?? '' }}</td>
                                    <td>{{ $prog['JUL']  ?? '' }}</td>
                                    <td>{{ $prog['AGO']  ?? '' }}</td>
                                    <td>{{ $prog['SEPT'] ?? '' }}</td>
                                    <td>{{ $prog['OCT']  ?? '' }}</td>
                                    <td>{{ $prog['NOV']  ?? '' }}</td>
                                    <td>{{ $prog['DIC']  ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 small text-muted">
                    <p class="mb-1">
                        <strong>Nota:</strong> Los rangos de días (por ejemplo <em>25–30</em>) indican la semana
                        programada para realizar el mantenimiento en ese laboratorio.
                    </p>
                </div>
            @else
                <p class="text-muted mb-0">
                    No se encontraron programas de mantenimiento para los filtros seleccionados.
                </p>
            @endif
        </div>
    </div>

</div>
@endsection
