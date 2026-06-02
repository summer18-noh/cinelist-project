<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('movies.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email is required.',
            'email.email'       => 'Enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 6 characters.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('movies.index')
                ->with('toast_success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('movies.index');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|min:2|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required'      => 'Full name is required.',
            'name.min'           => 'Name must be at least 2 characters.',
            'email.required'     => 'Email is required.',
            'email.email'        => 'Enter a valid email address.',
            'email.unique'       => 'This email is already registered.',
            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

       return redirect()->route('login')
    ->with('toast_success', 'Account created! You can now sign in, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('toast_success', 'You have been logged out.');
    }
}