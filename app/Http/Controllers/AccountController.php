<?php

namespace App\Http\Controllers;

use App\Helpers\RuleDictionary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Rules\Capitalized;

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
        $RuleDictionary = new RuleDictionary();
        Auth::login(User::create(
            $request->validate(
                $RuleDictionary->composeRules(['name', 'surname', 'email', 'password']),
                $RuleDictionary->composeErrorMessages(['required', 'alpha', 'min', 'max', 'email', 'password', 'confirmed', Capitalized::class],
                [
                    'name.min' => 'To imię jest za krótkie.',
                    'name.max' => 'To imię jest zbyt długie.',
                    'surname.min' => 'To nazwisko jest za krótkie.',
                    'surname.max' => 'To nazwisko jest zbyt długie.',
                    'email.min' => 'Ten email jest za krótki.',
                    'email.max' => 'Ten email jest zbyt długi.',
                ])
            )
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
    public function update(Request $request)
    {
        switch($request->getRequestUri()) {
            case "/profile/text":
                $RuleDictionary = new RuleDictionary();
                User::where("id", Auth::id())->update(
                    $request->validate(
                        $RuleDictionary->composeRules(['name', 'surname', 'height', 'age', 'weight'], [],true),
                        $RuleDictionary->composeErrorMessages(['required', 'alpha', 'min', 'max', 'integer', Capitalized::class],
                        [
                           'height.min' => 'Skontaktuj się z Obsługą Klienta.',
                           'height.max' => 'Skontaktuj się z Obsługą Klienta.',
                           'age.min' => 'Nieodpowiedni wiek.',
                           'age.max' => 'Nieodpowiedni wiek.',
                           'weight.min' => 'Nieodpowiednia waga.',
                           'weight.max' => 'Nieodpowiednia waga.',
                        ])
                    )
                );
                break;
            case "/profile/images":
                $names = [];
                $ruleset = array_unshift(RuleDictionary::$defaultFileRuleset, "sometimes");
                $toBeSent = [
                    "pfp" => $ruleset,
                    "banner" => $ruleset,
                ];
                $validated = $request->validate($toBeSent);
                foreach ($validated as $pic) {
                    $fieldName = array_search($pic, $validated);
                    $dbName = $fieldName."_name";
                    $oldFileName = Auth::user()->getAttribute($dbName);
                    if(!is_null($oldFileName))
                        Storage::delete("images/{$fieldName}s/$oldFileName");
                    $names[$dbName] = basename($pic->store("images/{$fieldName}s"));
                }
                User::where("id", Auth::id())->update($names);
                break;
        }
        return redirect("/profile");
    }
    public function updateMail(Request $request)
    {
        $RuleDictionary = new RuleDictionary();
        User::where("id", Auth::id())->update(
            $request->validate(
                $RuleDictionary->composeRules(["email"]),
                $RuleDictionary->composeErrorMessages(["required", "email"], ["max" => "Ten adres e-mail jest za krótki."])
            )
        );
        return redirect("/profile");
    }
    public function updatePassword(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
