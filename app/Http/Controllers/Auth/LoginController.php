<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ]);

        // Aquí validamos contra datos "quemados" para probar
        if ($request->email === "admin@test.com" && $request->password === "1234") {
            session(['user' => [
                'email' => $request->email,
                'name' => "Administrador de Prueba"
            ]]);

            return redirect()->route('dashboard')->with('success', 'Bienvenido 👋');
        }

        return back()->withErrors(['login' => 'Credenciales inválidas']);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}

