<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventarioImport;

class InventarioController extends Controller
{
    /**
     * Mostrar el inventario importado desde Excel
     */
    public function index()
    {
        try {
            // 👇 Ruta del Excel en storage/app/inventario.xlsx
            $filePath = storage_path('app/inventario.xlsx');

            if (!file_exists($filePath)) {
                return back()->withErrors(['excel' => '⚠️ No se encontró el archivo inventario.xlsx en storage/app.']);
            }

            // Usamos el importador
            $import = new InventarioImport;
            Excel::import($import, $filePath);

            $data = $import->getData();

            return view('inventario.index', compact('data'));
        } catch (\Exception $e) {
            return back()->withErrors(['excel' => '⚠️ Error al cargar el inventario: ' . $e->getMessage()]);
        }
    }
}

