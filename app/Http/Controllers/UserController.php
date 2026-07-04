<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use Kreait\Firebase\Exception\AuthException;

class UserController extends Controller
{
    /**
     * Listar usuarios desde Firestore (con búsqueda)
     */
    public function index(Request $request)
    {
        $firestore = App::make('firebase.firestore');

        $users = [];
        $search = trim($request->input('q', ''));

        try {
            $usersRef = $firestore->collection('users')->documents();

            foreach ($usersRef as $doc) {
                if ($doc->exists()) {
                    $data = $doc->data();
                    $data['uid'] = $doc->id();
                    $users[] = $data;
                }
            }

            // 🔍 Filtro en memoria si hay búsqueda
            if ($search !== '') {
                $searchLower = mb_strtolower($search, 'UTF-8');

                $users = array_filter($users, function ($user) use ($searchLower) {
                    $nombre = trim(
                        ($user['nombre'] ?? '') . ' ' .
                        ($user['primer_apellido'] ?? '') . ' ' .
                        ($user['segundo_apellido'] ?? '')
                    );

                    $cadena = mb_strtolower(
                        $nombre . ' ' .
                        ($user['correo'] ?? '') . ' ' .
                        ($user['area_trabajo'] ?? ''),
                        'UTF-8'
                    );

                    return str_contains($cadena, $searchLower);
                });
            }

            return view('users.index', [
                'users'  => $users,
                'search' => $search,
            ]);

        } catch (\Exception $e) {
            return view('users.index', [
                'users'  => [],
                'search' => $search,
            ])->withErrors([
                'firebase' => 'Error al conectar con Firebase: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Guardar usuario en Firebase Auth + Firestore
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'primer_apellido'  => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'correo'           => 'required|email',
            'password'         => 'required|min:6',
            'telefono'         => 'required|string|max:20',
            'area_trabajo'     => 'required|string',
            // 🔹 Incluye Webmaster
            'rol'              => 'required|in:Usuario,Administrador,Superadmin,Webmaster',
        ]);

        $auth = App::make('firebase.auth');
        $firestore = App::make('firebase.firestore');

        try {
            // Crear usuario en Firebase Authentication
            $createdUser = $auth->createUser([
                'email'       => $request->correo,
                'password'    => $request->password,
                'displayName' => $request->nombre . ' ' . $request->primer_apellido,
            ]);

            // Guardar datos en Firestore
            $firestore->collection('users')->document($createdUser->uid)->set([
                'uid'             => $createdUser->uid,
                'nombre'          => $request->nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido'=> $request->segundo_apellido,
                'correo'          => $request->correo,
                'telefono'        => $request->telefono,
                'area_trabajo'    => $request->area_trabajo,
                'rol'             => $request->rol,
                'created_at'      => now()->toDateTimeString(),
            ]);

            return redirect()->route('users.index')
                ->with('success', '✅ Usuario registrado en Firebase correctamente.');

        } catch (FirebaseEmailExists $e) {
            return back()->withErrors(['correo' => '⚠️ El correo ya está registrado en Firebase.']);
        } catch (AuthException $e) {
            return back()->withErrors(['firebase' => '⚠️ Error en Firebase Auth: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => '⚠️ Error inesperado: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(string $uid)
    {
        $firestore = App::make('firebase.firestore');

        try {
            $snapshot = $firestore->collection('users')->document($uid)->snapshot();

            if (! $snapshot->exists()) {
                abort(404, 'Usuario no encontrado.');
            }

            $user = $snapshot->data();
            $user['uid'] = $uid;

            return view('users.edit', compact('user'));

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->withErrors(['firebase' => 'Error al cargar usuario: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, string $uid)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'primer_apellido'  => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'correo'           => 'required|email',
            'password'         => 'nullable|min:6',
            'telefono'         => 'required|string|max:20',
            'area_trabajo'     => 'required|string',
            // 🔹 También aquí con Webmaster
            'rol'              => 'required|in:Usuario,Administrador,Superadmin,Webmaster',
        ]);

        $auth = App::make('firebase.auth');
        $firestore = App::make('firebase.firestore');

        try {
            // 1) Actualizar en Firebase Auth
            $authData = [
                'displayName' => $request->nombre . ' ' . $request->primer_apellido,
            ];

            if ($request->filled('correo')) {
                $authData['email'] = $request->correo;
            }

            if ($request->filled('password')) {
                $authData['password'] = $request->password;
            }

            $auth->updateUser($uid, $authData);

            // 2) Actualizar en Firestore (merge)
            $firestore->collection('users')->document($uid)->set([
                'nombre'          => $request->nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido'=> $request->segundo_apellido,
                'correo'          => $request->correo,
                'telefono'        => $request->telefono,
                'area_trabajo'    => $request->area_trabajo,
                'rol'             => $request->rol,
                'updated_at'      => now()->toDateTimeString(),
            ], ['merge' => true]);

            return redirect()->route('users.index')
                ->with('success', '✅ Usuario actualizado correctamente.');

        } catch (FirebaseEmailExists $e) {
            return back()->withErrors(['correo' => '⚠️ El correo ya está registrado en Firebase.']);
        } catch (AuthException $e) {
            return back()->withErrors(['firebase' => '⚠️ Error en Firebase Auth: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['general' => '⚠️ Error inesperado: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar usuario (Firebase Auth + Firestore)
     */
    public function destroy(string $uid)
    {
        $auth = App::make('firebase.auth');
        $firestore = App::make('firebase.firestore');

        try {
            // 1) Borrar de Firebase Auth
            $auth->deleteUser($uid);

            // 2) Borrar documento en Firestore
            $firestore->collection('users')->document($uid)->delete();

            return redirect()->route('users.index')
                ->with('success', '🗑️ Usuario eliminado correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['firebase' => 'Error al eliminar usuario: ' . $e->getMessage()]);
        }
    }
}

