<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view("login.login");
    }

    public function post(Request $request)
    {        
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:32'
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->route('main');
        }

        return back()->withErrors([
            'email' => 'Email or password incorrect.',
        ])->onlyInput('email');
    }
}
