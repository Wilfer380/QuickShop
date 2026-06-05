<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $email = Str::lower(trim((string) $request->email));
        $request->merge(['email' => $email]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:30'],
            'document' => ['nullable', 'string', 'max:40', 'unique:users,documento'],
            'role' => ['required', 'in:empleado,supervisor,admin'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[A-ZÁÉÍÓÚÑ]/u',
                'regex:/\d/',
                'regex:/[^A-Za-z0-9]/u',
            ],
            'terms' => ['accepted'],
        ], [
            'password.required' => 'La contraseña no cumple con los caracteres requeridos.',
            'password.min' => 'La contraseña no cumple con los caracteres requeridos.',
            'password.regex' => 'La contraseña no cumple con los caracteres requeridos.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'phone' => $request->phone,
            'documento' => $request->document,
            'role' => $request->role,
            'password' => $request->password,
        ]);

        event(new Registered($user));

        return redirect()
            ->route('login')
            ->with('status', 'Cuenta creada. Ahora inicia sesión con tu correo y contraseña.')
            ->withInput(['email' => $email]);
    }
}
