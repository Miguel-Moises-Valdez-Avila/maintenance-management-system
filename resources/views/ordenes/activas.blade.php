@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold">⚙️ Órdenes de mantenimiento activas</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-info text-center">
                    <tr>
                        <th>Folio</th>
                        <th>Descripción</th>
                        <th>Administrador</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenesActivas as $req)
                        <tr>
                            <td>{{ $req['numeroFolio'] ?? '—' }}</td>
                            <td>{{ $req['descripcion'] ?? '—' }}</td>
                            <td>
                                @foreach(($req['adminsAsignados'] ?? []) as $uid)
                                    <div>👤 {{ $adminsMap[$uid] ?? 'Administrador' }}</div>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $req['estado'] }}</span>
                            </td>
                            <td style="width:260px">
                                <form method="POST"
                                      action="{{ route('solicitudes.updateEstado', $req['id']) }}"
                                      class="estado-form">
                                    @csrf
                                    @method('PATCH')

                                    <select name="estado" class="form-select form-select-sm estado-select">
                                        <option value="En proceso">En proceso</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Rechazado">Rechazado</option>
                                    </select>

                                    <textarea name="razonBaul"
                                              class="form-control form-control-sm mt-2 d-none razon"
                                              placeholder="Motivo del rechazo"></textarea>

                                    <button class="btn btn-sm btn-primary mt-2 w-100">
                                        Actualizar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No hay órdenes activas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.estado-select').forEach(select => {
    select.addEventListener('change', e => {
        const form = e.target.closest('.estado-form');
        const textarea = form.querySelector('.razon');
        if (e.target.value === 'Rechazado') {
            textarea.classList.remove('d-none');
        } else {
            textarea.classList.add('d-none');
            textarea.value = '';
        }
    });
});
</script>
@endsection

