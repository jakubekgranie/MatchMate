<?php

namespace App\Helpers;

use App\Models\PendingUserChanges;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class  ControllerHelper
{
    public static function generateUUID() : string{
        do {
            $uuid = Str::uuid()->toString(); // ensure uniqueness
        } while(PendingUserChanges::where('url_key', $uuid)->exists());
        return $uuid;
    }
    public static function getParentUrl(string $requestUri) : string{
        $components = explode("/", $requestUri);
        array_pop($components);
        return implode("/", $components);
    }
    public static function getAction(string $uuid) : PendingUserChanges|RedirectResponse|null{
        $action = PendingUserChanges::with("user")
            ->where(['user_id' => Auth::id(),'url_key' => $uuid])
            ->whereHas('user', function ($query) {$query->where('role_id', '>', '1');})
            ->first();
        if(is_null($action))
            return redirect()
                ->back()
                ->with(["title" => "Niewystarczające uprawnienia.", "theme" => 2]);
        return match ($action->user_change_statuses_id) {
            2 => redirect()
                ->back()
                ->with(["title" => "Żądanie wygasło.", "theme" => 2]),
            3 => redirect()
                ->back()
                ->with(["title" => "Żądanie zostało nadpisane.", "theme" => 2]),
            4 => redirect()
                ->back()
                ->with(["title" => "Żądanie zostało zamknięte.", "theme" => 2]),
            default => $action,
        };
    }

    public static function imageUploader(int $id, string $model = User::class) : null|RedirectResponse
    {
        try {
            $RuleDictionary = new RuleDictionary();
            $validator = Validator::make(request()->only(['pfp', 'banner']),
                $RuleDictionary->composeRules(['pfp', 'banner'], [],true),
                $RuleDictionary->composeErrorMessages(
                    ['file', 'mimes'],
                    [
                        'banner.mimes' => 'Akceptujemy tylko pliki .png oraz .jpg.',
                        'max' => 'Ten plik jest za duży (max. 8MB).'
                    ])
            );
            if($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);
            }
            $names = [];
            $validated = $validator->validated();
            foreach ($validated as $pic) {
                $fieldName = array_search($pic, $validated);
                $dbName = $fieldName."_name";
                $oldFileName = Auth::user()->getAttribute($dbName);
                if (!is_null($oldFileName))
                    Storage::delete("images/{$fieldName}s/$oldFileName");
                $names[$dbName] = basename($pic->store("images/{$fieldName}s"));
            }
            $model::where("id", $id)->update($names);
            return null;
        }
        catch (ValidationException) {
            return redirect()
                ->back()
                ->withInput()
                ->with(["title" => "Nie można wysłać pliku. Spróbuj ponownie później.", "theme" => 2]);
        }
    }
}
