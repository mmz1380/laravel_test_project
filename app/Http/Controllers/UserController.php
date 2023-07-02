<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    public function create()
    {
        return view('users.register');
    }

    public function store(Request $request)
    {
        $formField = $request->validate([
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        // $formField['password'] = bcrypt($formField['password']);

        $user = User::create($formField);

        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in');
    }

    public function logout(Request $request)
    {
        $name = auth()->user()->name;
        auth()->logout();
        $request->session()->invalidate();
        request()
            ->session()
            ->regenerateToken();
        return redirect('/')->with('message', "User {{ $name }} succesfully logged out!");
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function authenticate(Request $request)
    {
        $formField = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (auth()->attempt($formField)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You logged in successfully');
        }
        return back()
            ->withErrors(['email' => 'Invalid Credentials'])
            ->onlyInput('email');
    }
}
