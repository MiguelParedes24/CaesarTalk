<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Events\UserLoggedIn;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Personaliza la autenticación para usar `userName`
        $credentials = $request->only('userName', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenera la sesión y redirige
            $request->session()->regenerate();

            $user = Auth::user();
            // Actualiza los datos del usuario
            if ($user instanceof User) {
                $user->last_login_at = Carbon::now();
                if ($user->last_login_before === null) {
                    // Guarda el valor de last_login_at en una sesión
                    session(['first_login_at' => $user->last_login_at]);
                }
                $user->new_messages_count = 0;
                $user->save();
            }

            // Redirige al usuario con el mensaje
            return redirect()->route('home');
        }


        // Si no se puede autenticar, redirigir con un mensaje de error
        return back()->withErrors([
            'userName' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('userName'); // Mantener el nombre de usuario ingresado
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


}
