<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProgramaPreventivoController extends Controller
{
    /**
     * Módulo de Programa de Mantenimiento Preventivo
     * - Filtros: periodo + sala/laboratorio
     * - Muestra: checklist de hallazgos + programa por meses
     */
    public function index(Request $request)
    {
        $firestore = App::make('firebase.firestore');

        // Filtros seleccionados desde el formulario
        $selectedPeriodo = $request->input('periodo');
        $selectedSala    = $request->input('sala');

        // 🔹 Salas fijas (como en tu formato en papel)
        $salas = [
            'Biblioteca',
            'Laboratorio de Ciencias Económico Administrativas',
            'Laboratorio de Inglés',
            'Laboratorio de Ingeniería Eléctrica',
            'Laboratorio del Centro de Cómputo',
        ];

        // Periodos se siguen leyendo de Firebase
        $periodos = [];

        // Datos que se mandan a la vista
        $hallazgo  = null;   // documento de inspección/hallazgos
        $programas = [];     // filas del programa preventivo
        $error     = null;

        try {
            /*
             * 1) Obtener lista de periodos desde Firebase
             *    - Colección "inspecciones"
             *    - Colección "programas_mantenimiento"
             */

            // 🔸 Inspecciones (para periodos)
            $inspecciones = $firestore->collection('inspecciones')->documents();
            foreach ($inspecciones as $doc) {
                if (! $doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                if (!empty($data['periodo'])) {
                    $periodos[] = $data['periodo'];
                }
            }

            // 🔸 Programas de mantenimiento (para periodos)
            $progDocsAll = $firestore->collection('programas_mantenimiento')->documents();
            foreach ($progDocsAll as $doc) {
                if (! $doc->exists()) {
                    continue;
                }

                $data = $doc->data();
                if (!empty($data['periodo'])) {
                    $periodos[] = $data['periodo'];
                }
            }

            // Quitar duplicados y ordenar
            $periodos = array_values(array_unique($periodos));
            sort($periodos);

            /*
             * 2) Si ya se eligió periodo/sala, cargar detalle
             */
            if ($selectedPeriodo || $selectedSala) {

                // 🔹 Checklist / hallazgos
                $inspeccionesQuery = $firestore->collection('inspecciones');

                if ($selectedPeriodo) {
                    $inspeccionesQuery = $inspeccionesQuery->where('periodo', '=', $selectedPeriodo);
                }
                if ($selectedSala) {
                    $inspeccionesQuery = $inspeccionesQuery->where('sala', '=', $selectedSala);
                }

                $inspeccionesDocs = $inspeccionesQuery->documents();

                foreach ($inspeccionesDocs as $doc) {
                    if ($doc->exists()) {
                        $hallazgo = $doc->data();
                        break; // primer match
                    }
                }

                // 🔹 Programa de mantenimiento preventivo
                $progQuery = $firestore->collection('programas_mantenimiento');

                if ($selectedPeriodo) {
                    $progQuery = $progQuery->where('periodo', '=', $selectedPeriodo);
                }
                if ($selectedSala) {
                    // asumimos campo 'laboratorio' para nombre de sala
                    $progQuery = $progQuery->where('laboratorio', '=', $selectedSala);
                }

                $progDocs = $progQuery->documents();

                foreach ($progDocs as $doc) {
                    if ($doc->exists()) {
                        $programas[] = $doc->data();
                    }
                }
            }

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return view('programa.index', [
            'periodos'        => $periodos,
            'salas'           => $salas,           // 👉 ahora vienen del arreglo fijo
            'selectedPeriodo' => $selectedPeriodo,
            'selectedSala'    => $selectedSala,
            'hallazgo'        => $hallazgo,
            'programas'       => $programas,
            'error'           => $error,
        ]);
    }
}

