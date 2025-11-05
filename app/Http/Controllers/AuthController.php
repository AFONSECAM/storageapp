<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin(){
        return view('auth.login');
    }

    public function showRegister(){
        return view('auth.register');
    }

    public function login(Request $request){
        $request->validate(['email' =>'required|email', 'password' => 'required']);
        if(auth()->attempt($request->only('email', 'password'))){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()->withErrors(['email' => 'Credenciales inválidas']);
    }

    public function register(Request $request){
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required|min:6']);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->passsword),
            'role' => 'user',
        ]);

        auth()->login($user);
        return redirect('/dashboard');
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
