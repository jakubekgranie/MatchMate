<?php

namespace App\Http\Controllers;

use App\Helpers\RuleDictionary;
use App\Models\User;
use App\Rules\Capitalized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Login page returned
     */
    public function create(){
        return view('session.login');
    }
    public function store(Request $request){
        if(!Auth::user()) {
            $RuleDictionary = new RuleDictionary();
            $validator = Validator::make($request->only('email', 'password'),
                $RuleDictionary->composeRules(['email', 'password'])
            );

            $user = User::where('email', $validator->validated()['email'])->first();
            if(!$user || !Hash::check($validator->validated()['password'], $user->password))
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(["email" => "Nieprawidłowy adres e-mail lub hasło."]);

            if($user->awaiting_review)
                return view('session.limbo', ["user" => $user]);
            Auth::login($user, $request->has("remember"));
            request()->session()->regenerate();
            return redirect()->intended('/profile')->with(['title' => 'Zalogowano!', 'theme' => 0]);
        }
        else
            return redirect('/profile');
    }
    public function show(){
        return view('session.profile');
    }
    public function edit(){

    }
    public function update(){

    }
    public function destroy(){
        Auth::logout();
        return redirect('/');
    }
}
