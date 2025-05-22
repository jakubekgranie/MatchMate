<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\RuleDictionary;
use App\Models\Team;
use App\Rules\Capitalized;
use App\Rules\ExpandedAlpha;
use App\Rules\IsHex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('team.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request)
    {
        switch($request->getRequestUri()){
            case '/my-team/text':
                $RuleDictionary = new RuleDictionary();
                $validator = Validator::make(array_filter($request->only(['handle', 'motto', 'color'])),
                    $RuleDictionary->composeRules(['handle', 'motto', 'color'], [],true),
                    $RuleDictionary->composeErrorMessages([ExpandedAlpha::class, 'min', 'max', Capitalized::class, IsHex::class])
                );
                if($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors($validator);
                }
                Auth::user()->team->update($validator->validated());
                return redirect()
                    ->back()
                    ->with(["title" => "Zapisano zmiany."]);
            case '/my-team/images':
                $response = ControllerHelper::imageUploader(Auth::user()->team->id, Team::class, ["icon", "banner"]);
                if($response)
                    return $response;
                return redirect()
                    ->back()
                    ->with(["title" => "Zapisano zmiany."]);
        }
        return redirect()
            ->back()
            ->with(["title" => "Wystąpił nieznany błąd. Spróbuj ponownie później.", "theme" => 2]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
