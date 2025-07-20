<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // prévention CSRF/session fixation
            return redirect()->intended(route('projects.index'));
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ])->onlyInput('email');
    }


public function logout(Request $request)
{
    Auth::logout(); // Déconnecter l'utilisateur

    $request->session()->invalidate(); // Invalider la session

    $request->session()->regenerateToken(); // Régénérer le token CSRF pour sécurité

    return redirect()->route('login'); // Rediriger vers la page de connexion
}
}
