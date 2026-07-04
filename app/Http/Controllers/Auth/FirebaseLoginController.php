<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class FirebaseLoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login con Firebase Auth y Firestore
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $auth = App::make('firebase.auth');
        $firestore = App::make('firebase.firestore');

        try {
            // 🔐 Autenticación con Firebase Auth
            $signInResult = $auth->signInWithEmailAndPassword(
                $request->email,
                $request->password
            );

            $firebaseUser = $signInResult->data();
            $uid = $firebaseUser['localId'] ?? null;

            if (!$uid) {
                return back()->withErrors(['firebase' => 'No se pudo obtener el UID del usuario.']);
            }

            // 🔎 Buscar datos del usuario en Firestore
            $userDoc = $firestore->collection('users')->document($uid)->snapshot();

            if ($userDoc->exists()) {
                $userData = $userDoc->data();

                // Guardamos datos completos en la sesión
                session([
                    'firebase_uid'       => $uid,
                    'firebase_email'     => $userData['correo'] ?? $firebaseUser['email'] ?? null,
                    'firebase_name'      => $userData['nombre'] ?? null,
                    'firebase_lastname1' => $userData['primer_apellido'] ?? null,
                    'firebase_lastname2' => $userData['segundo_apellido'] ?? null,
                    'firebase_role'      => strtolower($userData['rol'] ?? 'usuario'),
                    'firebase_area'      => $userData['area_trabajo'] ?? null,
                ]);
            } else {
                // Si no existe en Firestore, al menos guardamos lo básico
                session([
                    'firebase_uid'   => $uid,
                    'firebase_email' => $firebaseUser['email'] ?? null,
                    'firebase_name'  => $firebaseUser['displayName'] ?? 'Usuario',
                    'firebase_role'  => 'usuario',
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Bienvenido 👋');
        } catch (\Throwable $e) {
            return back()->withErrors([
                'firebase' => '⚠️ Error en login: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'firebase_uid',
            'firebase_email',
            'firebase_name',
            'firebase_lastname1',
            'firebase_lastname2',
            'firebase_role',
            'firebase_area',
        ]);

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
