<?php

namespace App\Http\Controllers;

use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Login page returned
     */
    public function index(){
        return view('session.login');
    }
    public function store(Request $request){
        $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'min:8'],
        ]);
        if(!Auth::attempt(request(['email', 'password'])))
            throw ValidationException::withMessages([
                'email' => 'Nieprawidłowy adres e-mail lub hasło.',
            ]);
        request()->session()->regenerate();
        return redirect('/');
    }
    public function show(){

    }
    public function edit(){

    }
    public function update(){

    }
    public function destroy(){
        Auth::logout();
        session_destroy();
        return redirect('/');
    }
}
