<?php

namespace App\Http\Controllers;

use App\Helpers\RuleDictionary;
use App\Mail\EmailChange;
use App\Models\PendingUserChanges;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Rules\Capitalized;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $RuleDictionary = new RuleDictionary();
        switch($request->getRequestUri()) {
            case "/profile/text":
                $validator = Validator::make(array_filter($request->all()),
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
                );
                if($validator->fails())
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors($validator);
                User::where("id", Auth::id())->update($validator->validated());
                return redirect("/profile")->with(["title" => "Dane zmodyfikowano pomyślnie!"]);
            case "/profile/images":
                $names = [];
                $ruleset = array_unshift(RuleDictionary::$defaultFileRuleset, "sometimes");
                $validator = Validator::make(array_filter($request->all()),
                    [
                        "pfp" => $ruleset,
                        "banner" => $ruleset,
                    ],
                    RuleDictionary::$defaultFileErrorMessages
                );
                if($validator->fails())
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors($validator);
                $validated = $validator->validated();
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
        $validator = Validator::make($request->only('email'),
            $RuleDictionary->composeRules(["email"]),
            $RuleDictionary->composeErrorMessages(["required", "email"], ["max" => "Ten adres e-mail jest za długi."])
        );
        if($validator->fails())
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        if(User::where("email", $validator->validated()["email"])->exists()) // check if mail isn't in use
            return redirect()
                ->back()
                ->withInput()
                ->with(["title" => "Ten adres e-mail jest w użyciu.", "theme" => 2]);

        PendingUserChanges::where(["user_id" => Auth::id(), "user_change_statuses_id" => 1])->update(["user_change_statuses_id" => 3]);
        do {
            $uuid = Str::uuid()->toString(); // ensure uniqueness
        } while(PendingUserChanges::where('url_key', $uuid)->exists());
        PendingUserChanges::create([
            "user_id" => Auth::id(),
            "url_key" => $uuid,
            "user_change_types_id" => 2,
            "desiredValue" => $validator->validated()["email"]
        ]);
        try {
            Mail::to(Auth::user()->getEmailForVerification())->queue(new EmailChange($uuid, Auth::user()));
            return redirect("/profile")->with(["title" => "Udało się! Sprawdź swoją skrzynkę e-mail, by kontynuować.", "theme" => 1]);
        }
        catch (Exception) {
            return redirect("/profile")->with(["title" => "Wystąpił nieznany błąd.", "theme" => 2]);
        }
    }
    public function confirmChange($uuid){
        $action = PendingUserChanges::where(['user_id' => Auth::id(),'url_key' => $uuid, 'user_change_statuses_id' => 1])->first();
        if(!is_null($action)) {
            if(User::where("email", $action->desiredValue)->exists()) { // check if mail isn't in use
                $action->update(["user_change_statuses_id" => 2]);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(["title" => "Ten adres e-mail jest w użyciu.", "theme" => 2]);
            }
            User::where(['id' => Auth::id()])->update(["email" => $action->desiredValue]);
            $action->update(["user_change_statuses_id" => 4]);
            return redirect("/profile")->with(["title" => "Zatwierdzono zmiany."]);
        }
        else
            return redirect("/profile")->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }
    public function updatePassword(Request $request)
    {
        $RuleDictionary = new RuleDictionary();
        User::where("id", Auth::id())->update(
            $request->validate(
                $RuleDictionary->composeRules(["password"]),
                $RuleDictionary->composeErrorMessages(["required", "password", "confirmed"])
            )
        );
        return redirect("/profile");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
