<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'userName' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:4'],
        ], [
            'email.unique' => 'El correo electrónico ya está en uso, por favor elige otro.',
            'userName.unique' => 'El nombre de usuario ya está en uso, por favor elige otro.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'userName' => $request->userName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_login_at' => now(),
            'new_messages_count' => 0,
            'last_login_before' => null,
        ]);

        event(new Registered($user));

        Auth::login($user);


        return redirect()->route('home')->with('success', 'Registro exitoso');
    }
}
