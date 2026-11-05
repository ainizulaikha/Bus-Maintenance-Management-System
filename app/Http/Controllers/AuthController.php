<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials['is_active'] = true;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'Admin & Operation') {
                return redirect('/home');
            }

            if ($user->role === 'Mechanic') {
                return redirect('/home');
            }

            if ($user->role === 'Bus Driver') {
                return redirect('/home');
            }

            Auth::logout();

            return redirect('/login')
                ->withErrors(['user_id' => 'Your account role is not recognised.']);
        }

        return back()->withErrors([
            'user_id' => 'Invalid User ID or password, or your account is inactive.',
        ])->onlyInput('user_id');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
