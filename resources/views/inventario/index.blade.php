@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-primary">📦 Inventario del Centro de Cómputo</h2>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        @foreach ($inventario[0] as $columna)
                            <th>{{ $columna }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (array_slice($inventario, 1) as $fila)
                        <tr>
                            @foreach ($fila as $celda)
                                <td>{{ $celda }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
