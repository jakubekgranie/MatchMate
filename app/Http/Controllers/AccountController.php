<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::login(User::create(
            $request->validate([
                'name' => ['required', 'alpha', 'max:63'],
                'surname' => ['required', 'alpha', 'max:127'],
                'email' => ['required', 'email', 'max:254'],
                'password' => ['required', 'confirmed', Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                ]
            ])
        ), $request->has('remember'));
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        switch($request->getRequestUri()) {
            case "/profile/text":
                break;
            case "/profile/images":
                break;
        }
        return redirect("/profile");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
