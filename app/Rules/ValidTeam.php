<?php

namespace App\Rules;

use App\Models\Team;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTeam implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validTeams = Team::all();
        foreach ($validTeams as $team)
            if($team->name === $value)
                return;
        $fail("validation.notValidTeam");
    }
}
