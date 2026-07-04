<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\FirebaseLoginController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\OrdenesController;
use App\Http\Controllers\ProgramaPreventivoController;


/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/* 🔐 Login / Logout */
Route::get('/login', [FirebaseLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [FirebaseLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [FirebaseLoginController::class, 'logout'])->name('logout');

/* 🏠 Dashboard */
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Gestión de Usuarios
|--------------------------------------------------------------------------
*/

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');        // Listado
    Route::get('/create', [UserController::class, 'create'])->name('create'); // Form crear
    Route::post('/', [UserController::class, 'store'])->name('store');        // Guardar nuevo

    // ✏️ Editar
    Route::get('/{uid}/edit', [UserController::class, 'edit'])->name('edit');

    // 💾 Actualizar
    Route::put('/{uid}', [UserController::class, 'update'])->name('update');

    // 🗑️ Eliminar
    Route::delete('/{uid}', [UserController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Inventario
|--------------------------------------------------------------------------
*/

Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');

/*
|--------------------------------------------------------------------------
| Solicitudes (Firebase)
|--------------------------------------------------------------------------
| NUEVAS        -> solicitudes.nuevas
| ASIGNADAS     -> solicitudes.index
| BAÚL (FINAL)  -> solicitudes.baul
| CAMBIAR ESTADO -> solicitudes.updateEstado
|--------------------------------------------------------------------------
*/

Route::prefix('solicitudes')->name('solicitudes.')->group(function () {

    // 1. Mantenimientos solicitados → Nuevas solicitudes SIN administrador asignado
    Route::get('/nuevas', [SolicitudesController::class, 'nuevas'])->name('nuevas');

    // 2. Solicitudes de mantenimiento → solicitudes YA asignadas
    Route::get('/', [SolicitudesController::class, 'index'])->name('index');

    // 3. Baúl → solicitudes FINALIZADAS
    Route::get('/baul', [SolicitudesController::class, 'baul'])->name('baul');

    // Cambiar estado de solicitud
    Route::patch('/{id}/estado', [SolicitudesController::class, 'updateEstado'])->name('updateEstado');
});

/*
|--------------------------------------------------------------------------
| Órdenes (Firebase)
|--------------------------------------------------------------------------
*/

Route::get('/ordenes/activas', [OrdenesController::class, 'index'])->name('ordenes.activas');
Route::get('/ordenes/terminadas', [OrdenesController::class, 'terminadas'])->name('ordenes.terminadas');

// 🔹 Programa de Mantenimiento Preventivo (Inventario → Programa)
Route::get('/programa-preventivo', [ProgramaPreventivoController::class, 'index'])
    ->name('programa.index');

    // Solicitudes (nuevas, asignadas, baúl)
Route::prefix('solicitudes')->name('solicitudes.')->group(function () {
    Route::get('/nuevas', [SolicitudesController::class, 'nuevas'])->name('nuevas');
    Route::get('/',       [SolicitudesController::class, 'index'])->name('index');
    Route::get('/baul',   [SolicitudesController::class, 'baul'])->name('baul');
    Route::patch('/{id}/estado', [SolicitudesController::class, 'updateEstado'])->name('updateEstado');
});

// Órdenes
Route::get('/ordenes/activas',    [OrdenesController::class, 'index'])->name('ordenes.activas');
Route::get('/ordenes/terminadas',[OrdenesController::class, 'terminadas'])->name('ordenes.terminadas');
Route::patch('/ordenes/{id}/estado', [OrdenesController::class, 'updateEstado'])->name('ordenes.updateEstado');
