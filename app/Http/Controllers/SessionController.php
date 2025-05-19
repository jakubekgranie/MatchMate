<?php

namespace App\Http\Controllers;

use App\Helpers\RuleDictionary;
use App\Rules\Capitalized;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $RuleDictionary = new RuleDictionary();
        $validator = Validator::make($request->all(),
            $RuleDictionary->composeRules(['email', 'password'])
        );
        if($validator->fails() || !Auth::attempt(request(['email', 'password']), $request->has("remember")))
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(["email" => "Nieprawidłowy adres e-mail lub hasło."]);
        request()->session()->regenerate();
        return redirect('/profile')->with(['title' => 'Zalogowano!', 'theme' => 0]);
    }
    public function show(){
        session("title", "a");
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
