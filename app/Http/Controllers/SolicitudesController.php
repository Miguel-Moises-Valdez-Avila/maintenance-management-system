<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SolicitudesController extends Controller
{
    /**
     * Construye mapa uid => nombre completo de todos los administradores.
     */
    private function buildAdminsMap($firestore): array
{
    $adminsMap = [];

    $users = $firestore->collection('users')->documents();
    foreach ($users as $doc) {
        if (!$doc->exists()) {
            continue;
        }

        $data = $doc->data();

        // ✅ YA NO FILTRAMOS POR ROL, SOLO MAPEAMOS TODOS LOS USERS
        $uid = $doc->id(); // MUY IMPORTANTE: UID = ID DEL DOCUMENTO

        $nombreCompleto = trim(
            ($data['nombre'] ?? '') . ' ' .
            ($data['primer_apellido'] ?? '') . ' ' .
            ($data['segundo_apellido'] ?? '')
        );

        if ($nombreCompleto === '') {
            $nombreCompleto = $data['correo'] ?? $uid;
        }

        $adminsMap[$uid] = $nombreCompleto;
    }

    return $adminsMap;
}


    /**
     * 2️⃣ Solicitudes de mantenimiento ASIGNADAS (no finalizadas)
     *    Ruta: solicitudes.index
     */
    public function index()
    {
        $firestore = App::make('firebase.firestore');

        $requests = [];

        try {
            $documents = $firestore->collection('requests')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                $data['id'] = $doc->id();

                $estado = $data['estado'] ?? 'Pendiente';
                $adminsAsignados = $data['adminsAsignados'] ?? [];

                if (!is_array($adminsAsignados)) {
                    $adminsAsignados = [$adminsAsignados];
                }
                $adminsAsignados = array_filter($adminsAsignados);

                // Aquí solo mostramos solicitudes:
                //  - Con al menos un admin asignado
                //  - Y que NO estén finalizadas
                if (!empty($adminsAsignados) && $estado !== 'Finalizado') {
                    $requests[] = $data;
                }
            }

            // Ordenar recientes primero por fechaSolicitud (si existe)
            usort($requests, fn($a, $b) =>
                strtotime($b['fechaSolicitud'] ?? '') <=> strtotime($a['fechaSolicitud'] ?? '')
            );

            $adminsMap = $this->buildAdminsMap($firestore);

            return view('solicitudes.index', compact('requests', 'adminsMap'));

        } catch (\Exception $e) {
            return view('solicitudes.index', [
                'requests'  => [],
                'adminsMap' => [],
            ])->withErrors([
                'firebase' => 'Error al obtener información: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * 3️⃣ Baúl de solicitudes PENDIENTES
     *    Ruta: solicitudes.baul
     */
    public function baul()
    {
        $firestore = App::make('firebase.firestore');

        $pendientes = [];

        try {
            $documents = $firestore->collection('requests')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                $data['id'] = $doc->id();

                if (($data['estado'] ?? '') === 'Pendiente') {
                    $pendientes[] = $data;
                }
            }

            usort($pendientes, fn($a, $b) =>
                strtotime($b['fechaSolicitud'] ?? '') <=> strtotime($a['fechaSolicitud'] ?? '')
            );

            $adminsMap = $this->buildAdminsMap($firestore);

            return view('solicitudes.baul', compact('pendientes', 'adminsMap'));

        } catch (\Exception $e) {
            return view('solicitudes.baul', [
                'pendientes' => [],
                'adminsMap'  => [],
            ])->withErrors([
                'firebase' => 'Error al obtener pendientes: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * 1️⃣ Mantenimientos solicitados (NUEVAS: sin admin asignado)
     *    Ruta: solicitudes.nuevas
     */
    public function nuevas()
    {
        $firestore = App::make('firebase.firestore');

        $solicitudesNuevas = [];

        try {
            $documents = $firestore->collection('requests')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                $data['id'] = $doc->id();

                $adminsAsignados = $data['adminsAsignados'] ?? [];
                if (!is_array($adminsAsignados)) {
                    $adminsAsignados = [$adminsAsignados];
                }
                $adminsAsignados = array_filter($adminsAsignados);

                // Nuevas = sin administradores asignados
                if (empty($adminsAsignados)) {
                    $solicitudesNuevas[] = $data;
                }
            }

            usort($solicitudesNuevas, fn($a, $b) =>
                strtotime($b['fechaSolicitud'] ?? '') <=> strtotime($a['fechaSolicitud'] ?? '')
            );

            $adminsMap = $this->buildAdminsMap($firestore);

            return view('solicitudes.nuevas', compact('solicitudesNuevas', 'adminsMap'));

        } catch (\Exception $e) {
            return view('solicitudes.nuevas', [
                'solicitudesNuevas' => [],
                'adminsMap'         => [],
            ])->withErrors([
                'firebase' => 'Error al obtener solicitudes: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Actualizar estado + adminsAsignados + razón de rechazo.
     * Se usa en nuevas, index y baúl.
     */
    public function updateEstado(Request $request, $id)
{
    $firestore = App::make('firebase.firestore');

    // ✅ Validación: si rechaza, razón obligatoria
    $request->validate([
        'estado' => 'required|string',
        'razonBaul' => 'nullable|string|max:500',
    ]);

    if ($request->estado === 'Rechazado' && trim((string)$request->razonBaul) === '') {
        return back()->withErrors([
            'razonBaul' => '⚠️ Debes escribir la razón del rechazo.'
        ])->withInput();
    }

    try {
        $update = [];

        // Estado
        $update[] = ['path' => 'estado', 'value' => $request->estado];

        // ✅ Razón: si es Rechazado se guarda, si no, se limpia
        if ($request->estado === 'Rechazado') {
            $update[] = ['path' => 'razonBaul', 'value' => trim((string)$request->razonBaul)];
        } else {
            $update[] = ['path' => 'razonBaul', 'value' => ''];
        }

        // Asignación admin (si viene)
        if ($request->filled('admin')) {
            $update[] = ['path' => 'adminsAsignados', 'value' => [$request->admin]];
        }

        $firestore->collection('requests')->document($id)->update($update);

        return redirect()->back()->with('success', '✅ Estado actualizado correctamente.');

    } catch (\Exception $e) {
        return back()->withErrors(['firebase' => 'Error al actualizar: ' . $e->getMessage()]);
    }
}

}
