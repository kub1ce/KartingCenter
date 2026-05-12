<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone'    => ['required', 'string', 'regex:/^\+?[0-9\s\-\(\)]{10,20}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'     => 'Введите имя.',
            'name.max'          => 'Имя не может быть длиннее 255 символов.',
            'email.required'    => 'Введите email.',
            'email.email'       => 'Введите корректный email.',
            'email.unique'      => 'Этот email уже зарегистрирован.',
            'phone.required'    => 'Введите номер телефона.',
            'phone.regex'       => 'Введите корректный номер телефона (10–20 цифр, можно + и пробелы).',
            'password.required' => 'Введите пароль.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role_id'  => Role::User,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('schedule.index'));
    }
}
