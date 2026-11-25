<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\UniqueConstraintViolationException;
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'regex:/\A[a-z][a-z0-9_-]{2,39}\z/', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.regex' => 'Введите от 3 до 40 символов, начиная с маленькой латинской буквы.',
            'username.unique' => 'Это имя в адресе уже занято.',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            if (str_contains($e->getMessage(), 'users.username')) {
                throw ValidationException::withMessages(['username' => 'Это имя в адресе уже занято.']);
            }
            if (str_contains($e->getMessage(), 'users.email')) {
                throw ValidationException::withMessages(['email' => 'Этот адрес уже зарегистрирован.']);
            }
            throw $e;
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('gods.index', absolute: false));
    }
}
