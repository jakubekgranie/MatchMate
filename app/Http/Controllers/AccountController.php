<?php

namespace App\Http\Controllers;

use App\Helpers\RuleDictionary;
use App\Mail\CredentialsChange;
use App\Mail\DeletionConfirmation;
use App\Mail\RejectionNotification;
use App\Mail\ReviewUser;
use App\Models\PendingUserChanges;
use App\Models\Team;
use App\Models\User;
use App\Rules\ExpandedAlpha;
use App\Rules\ValidTeam;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        if(!Auth::user()) {
            $RuleDictionary = new RuleDictionary();
            $validator = Validator::make($request->only('name', 'surname', 'team', 'email', 'password', 'password_confirmation'),
                $RuleDictionary->composeRules(['name', 'surname', 'team', 'email', 'password']),
                $RuleDictionary->composeErrorMessages(['required', ValidTeam::class, ExpandedAlpha::class, 'min', 'max', 'email', 'password', 'confirmed', Capitalized::class],
                    [
                        'name.min' => 'To imię jest za krótkie.',
                        'name.max' => 'To imię jest zbyt długie.',
                        'surname.min' => 'To nazwisko jest za krótkie.',
                        'surname.max' => 'To nazwisko jest zbyt długie.',
                        'email.min' => 'Ten email jest za krótki.',
                        'email.max' => 'Ten email jest zbyt długi.',
                    ])
            );
            if ($validator->fails())
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            $validated = $validator->validated();
            $validated['team_id'] = Team::where(['name' => $validated["team"]])->first()->id;
            $user = User::create($validated);
            $uuid = AccountController::generateUUID();
            $teamCaptain = User::with('team')
                ->where('role_id', 2)
                ->whereHas('team', function ($query) use ($validator) {
                    $query->where('name', $validator->validated()['team']);
                })
                ->first();
            PendingUserChanges::create([
                'user_id' => $teamCaptain->id,
                'url_key' => $uuid,
                'user_change_types_id' => 4,
                'desired_value' => $user->id
            ]);
            try {
                Mail::to($teamCaptain->email)->queue(new ReviewUser($user, $uuid, $teamCaptain->name));
                return view('session.limbo', ["user" => $user])->with(["title" => "Zarejestowano!"]);
            }
            catch (Exception) {
                $user->delete();
                return redirect("/register")->with(["title" => "Wystąpił nieznany błąd.", "theme" => 2]);
            }
        }
        else
            return redirect('/profile');
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
                    $RuleDictionary->composeErrorMessages(['required', ExpandedAlpha::class, 'min', 'max', 'integer', Capitalized::class],
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
                $validator = Validator::make(array_filter($request->all()),
                    $RuleDictionary->composeRules(['pfp', 'banner'], [],true),
                    $RuleDictionary->composeErrorMessages(
                        ['file', 'mimes'],
                        [
                            'banner.mimes' => 'Akceptujemy tylko pliki .png oraz .jpg.',
                            'max' => 'Ten plik jest za duży (max. 8MB).'
                        ])
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
        return redirect("/profile")->with(["title" => "Zalogowano"]);
    }
    private static function generateUUID() : string{
        do {
            $uuid = Str::uuid()->toString(); // ensure uniqueness
        } while(PendingUserChanges::where('url_key', $uuid)->exists());
        return $uuid;
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

        PendingUserChanges::where(["user_id" => Auth::id(), "user_change_types_id" => 2, "user_change_statuses_id" => 1])->update(["user_change_statuses_id" => 3]);
        $uuid = AccountController::generateUUID();
        PendingUserChanges::create([
            "user_id" => Auth::id(),
            "url_key" => $uuid,
            "user_change_types_id" => 2,
            "desired_value" => $validator->validated()["email"]
        ]);
        try {
            Mail::to(Auth::user()->getEmailForVerification())->queue(new CredentialsChange($uuid, Auth::user(), 1));
            return redirect("/profile")->with(["title" => "Udało się! Sprawdź swoją skrzynkę e-mail, by kontynuować.", "theme" => 1]);
        }
        catch (Exception) {
            return redirect("/profile")->with(["title" => "Wystąpił nieznany błąd.", "theme" => 2]);
        }
    }
    public function confirmChange($uuid){
        $action = PendingUserChanges::where(['user_id' => Auth::id(),'url_key' => $uuid, 'user_change_statuses_id' => 1])->first();
        if(!is_null($action)) {
            switch($action->user_change_types_id){
                case 1:
                    User::where(['id' => Auth::id()])->update(["password" => $action->desired_value]);
                    break;
                case 2:
                    if(User::where("email", $action->desired_value)->exists()) { // check if mail isn't in use
                        $action->update(["user_change_statuses_id" => 2]);
                        return redirect()
                            ->back()
                            ->withInput()
                            ->with(["title" => "Ten adres e-mail jest w użyciu.", "theme" => 2]);
                    }
                    User::where(['id' => Auth::id()])->update(["email" => $action->desired_value]);
                    break;
                case 3:
                    User::where(['id' => Auth::id()])->delete();
                    Auth::logout();
                    break;
            }
            $action->update(["user_change_statuses_id" => 4]);
            return redirect("/profile")->with(["title" => "Zatwierdzono zmiany."]);
        }
        else
            return redirect("/profile")->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }
    public function reject($uuid)
    {
        $action = PendingUserChanges::where(['user_id' => Auth::id(),'url_key' => $uuid, 'user_change_types_id' => 4])->first();
        if(!is_null($action)){
            $user = User::where(['id' => intval($action->desired_value)])->first();
            Mail::to($user->email)->queue(new RejectionNotification($user->name, $user->team->name));
            $user->delete();
            return redirect("/profile")->with(["title" => "Odrzucono."]);
        }
        else
            return redirect("/profile")->with(["title" => "Nieznane żądanie. Spróbuj ponownie.", "theme" => 2]);
    }
    public function updatePassword(Request $request)
    {
        $RuleDictionary = new RuleDictionary();
        $validator = Validator::make($request->only('password', 'password_confirmation'),
            $RuleDictionary->composeRules(["password"], ["password" => "confirmed"]),
            $RuleDictionary->composeErrorMessages(["required", "password", "confirmed"])
        );
        if($validator->fails())
            return redirect()
                ->back()
                ->withErrors(["password" => $validator->errors()->first()]);

        PendingUserChanges::where(["user_id" => Auth::id(), "user_change_types_id" => 1, "user_change_statuses_id" => 1])->update(["user_change_statuses_id" => 3]);
        $uuid = AccountController::generateUUID();
        PendingUserChanges::create([
            "user_id" => Auth::id(),
            "url_key" => $uuid,
            "user_change_types_id" => 1,
            "desired_value" => Hash::make($validator->validated()["password"])
        ]);
        try {
            Mail::to(Auth::user()->getEmailForVerification())->queue(new CredentialsChange($uuid, Auth::user(), 0));
            return redirect("/profile")->with(["title" => "Udało się! Sprawdź swoją skrzynkę e-mail, by kontynuować.", "theme" => 1]);
        }
        catch (Exception) {
            return redirect("/profile")->with(["title" => "Wystąpił nieznany błąd.", "theme" => 2]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $uuid = AccountController::generateUUID();
        PendingUserChanges::create([
            "user_id" => Auth::id(),
            "url_key" => $uuid,
            "user_change_types_id" => 3,
        ]);
        try {
            Mail::to(Auth::user()->getEmailForVerification())->queue(new DeletionConfirmation($uuid, Auth::user()));
            return redirect("/profile")->with(["title" => "Udało się! Sprawdź swoją skrzynkę e-mail, by kontynuować.", "theme" => 1]);
        }
        catch (Exception) {
            return redirect("/profile")->with(["title" => "Wystąpił nieznany błąd.", "theme" => 2]);
        }
    }
}
