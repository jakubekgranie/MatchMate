<?php

namespace App\Helpers;

use App\Models\PendingUserChanges;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    public static function getAction(string $uuid) : PendingUserChanges|RedirectResponse{
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

    public static function imageUploader(int $id, string $model = User::class, array $columnNames = ["pfp", "banner"]) : RedirectResponse|null
    {
        try{
            $RuleDictionary = new RuleDictionary();
            $input = [];
            $request = request();
            if ($request->hasFile('pfp'))
                $input['pfp'] = $request->file('pfp');
            if ($request->hasFile('banner'))
                $input['banner'] = $request->file('banner');
            $toBeValidated = array_keys($input);
            $validator = Validator::make($input,
                $RuleDictionary->composeRules($toBeValidated, [], true),
                $RuleDictionary->composeErrorMessages(
                    ['file', 'mimes'],
                    [
                        'banner.mimes' => 'Akceptujemy tylko pliki .png oraz .jpg.',
                        'max' => 'Ten plik jest za duży (max. 8MB).'
                    ])
            );
            if ($validator->fails())
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);

            $names = [];
            $validated = array_values($validator->validated());
            for ($i = 0; $i < sizeof($validated); $i++) {
                $fieldName = $columnNames[array_search($toBeValidated[$i], $columnNames)];;
                $dbName = $fieldName . "_name";
                $oldFileName = Auth::user()->getAttribute($dbName);
                $path = "images/{$fieldName}s";
                File::ensureDirectoryExists($path);
                if (!is_null($oldFileName))
                    Storage::delete("$path/$oldFileName");
                $names[$dbName] = basename($validated[$i]->store($path));
            }
            $model::where("id", $id)->update($names);
            return null;
        }
        catch (Exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with(["title" => "Nie można wysłać pliku. Spróbuj ponownie później.", "theme" => 2]);
        }
    }
}
