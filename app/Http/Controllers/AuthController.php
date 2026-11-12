<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $data = $request->validate(['login' => ['required', 'string', 'max:255'], 'password' => ['required', 'string']]);
        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (! Auth::attempt([$field => $data['login'], 'password' => $data['password']], $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Email/username atau kata sandi tidak sesuai.'])->onlyInput('login');
        }
        $request->session()->regenerate();
        $request->session()->forget(['cart', 'checkout_token']);

        return redirect()->intended(route($request->user()->role === 'admin' ? 'admin.dashboard' : 'home'));
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('home')->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
