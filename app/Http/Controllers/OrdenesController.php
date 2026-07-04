<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class OrdenesController extends Controller
{
    /**
     * Construir mapa uid → nombre completo (para TODOS los usuarios)
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

            // UID = ID del documento en Firestore
            $uid = $doc->id();

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
     * Órdenes activas (estado != Finalizado)
     */
    public function index()
    {
        $firestore = App::make('firebase.firestore');

        $ordenesActivas = [];

        try {
            $documents = $firestore->collection('requests')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                $estado = $data['estado'] ?? '';

                // Activas = TODO lo que NO está Finalizado
                if ($estado === 'Finalizado') {
                    continue;
                }

                $data['id'] = $doc->id();
                $ordenesActivas[] = $data;
            }

            // Mapa uid → nombre
            $adminsMap = $this->buildAdminsMap($firestore);

            return view('ordenes.activas', compact('ordenesActivas', 'adminsMap'));

        } catch (\Exception $e) {
            return view('ordenes.activas', [
                'ordenesActivas' => [],
                'adminsMap'      => [],
            ])->withErrors([
                'firebase' => 'Error al obtener órdenes activas: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Órdenes terminadas (estado = Finalizado)
     */
    public function terminadas()
    {
        $firestore = App::make('firebase.firestore');

        $ordenesFinalizadas = [];

        try {
            $documents = $firestore->collection('requests')->documents();

            foreach ($documents as $doc) {
                if (!$doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                $estado = $data['estado'] ?? '';

                if ($estado !== 'Finalizado') {
                    continue;
                }

                $data['id'] = $doc->id();
                $ordenesFinalizadas[] = $data;
            }

            $adminsMap = $this->buildAdminsMap($firestore);

            return view('ordenes.terminadas', compact('ordenesFinalizadas', 'adminsMap'));

        } catch (\Exception $e) {
            return view('ordenes.terminadas', [
                'ordenesFinalizadas' => [],
                'adminsMap'          => [],
            ])->withErrors([
                'firebase' => 'Error al obtener órdenes terminadas: ' . $e->getMessage(),
            ]);
        }
    }
}
