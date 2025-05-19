<?php

namespace App\Helpers;

use App\Rules\Capitalized;
use App\Rules\NoWhitespaces;
use Illuminate\Validation\Rules\Password;

/**
 * **`RuleDictionary`**
 *
 * This class serves as **a synchronized validation rule and error message** hub.
 */
class RuleDictionary{

    /**
     * @var string[] **`$defaultRuleset`**
     *
     * Hosts **the complete default ruleset**.
     */
    private array $defaultRuleset;

    /**
     * @var string[] **`$defaultErrorMessages`**
     *
     * Hosts **the complete collection of error messages**.
     */
    private array $defaultErrorMessages;

    public function __construct()
    {
        $this->defaultRuleset = [
            // requires/sometimes rules are a part of the logic
            'name' => ['alpha', 'min:2', 'max:63', new Capitalized],
            'surname' => ['alpha', 'min:2', 'max:127', new Capitalized], // whitespace search incorporated into alpha
            'email' => ['email', 'max:254'],
            'password' => [new NoWhitespaces, Password::min(8)
                ->max(64) // assuming bcrypt
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            "age" => ["min:18", "max:120", "integer"],
            "height" => ["min:55", "max:272", "integer"],
            "weight" =>["min:20", "max:300", "integer"],
            "pfp" => ["file", "mimes:png", "max:8000000"],
            "banner" => ["file", "mimes:png,jpg", "max:8000000"]
        ];
        $this->defaultErrorMessages = [
            'required' => 'To pole jest wymagane.',
            'alpha' => 'Wykryto niedozwolone znaki.',
            'integer' => 'Wykryto niedozwoloną liczbę.',
            NoWhitespaces::class => 'Wartość zawiera spację.',
            'min' => 'Ta wartość jest zbyt krótka.',
            'max' => 'Ta wartość jest zbyt długa.',
            'email' => 'To nie adres email.',
            Capitalized::class => 'Niepoprawna wysokość znaków.',
            'confirmed' => 'Hasła się nie zgadzają.',
            'password' => [
                'password.min' => 'Hasło powinno zawierać min. 8 znaków',
                'password.max' => 'To hasło jest za długie.',
                'password.letters' => 'Hasło powinno zawierać co najmniej jedną małą i dużą literę.',
                'password.mixed' => 'Hasło powinno zawierać co najmniej jedną małą i dużą literę.',
                'password.numbers' => 'Hasło powinno zawierać cyfry.',
                'password.symbols' => 'Hasło powinno zawierać symbole.',
                'password.uncompromised' => 'Użyj innego hasła.',
            ],
            "file" => "To nie plik!",
            "mimes" => "Akceptujemy tylko pliki .png.",
            // "max" => "Plik przekracza rozmiar 8MB."
        ];
    }

    /**
     * **`composeRules()`**
     *
     * This method **returns a set of rulesets** based on the `$requested` parameter. **Adding custom values is also possible**, via `$additionalRules`.
     *
     * @param array $requested Required. **The list of wanted rulesets** from the dictionary.
     * @param array $additionalRules Optional. **Custom rules** to be added into the product.
     * @param boolean $patch Optional. Defines whether **all rulesets from should feature the `sometimes` rule *(when true)* or the `required` rule *(when false)***. Custom rules are **not affected**.
     * @return array
     */
    public function composeRules(array $requested, array $additionalRules = [], bool $patch = false) : array
    {
        $collected = [];
        foreach($requested as $item)
            if (array_key_exists($item, $this->defaultRuleset)) {
                $ruleset = $this->defaultRuleset[$item];
                if($item == "password" && request()->is("/register"))
                    array_unshift($ruleset, "confirmed");
                if($patch)
                    array_unshift($ruleset, "sometimes");
                else
                    array_unshift($ruleset, "required");
                $collected[$item] = $ruleset;
            }
        if(count($additionalRules) > 0)
            $collected = array_merge($collected, $additionalRules);
        return $collected;
    }

    /**
     * **`composeErrorMessages()`**
     *
     * This method **returns a set of error messages** based on the `$requested` parameter. **Adding custom messages is also possible**, via `$additionalRules`.
     *
     * @param array $requested Required. **The list of wanted rulesets** from the dictionary.
     * @param array $additionalRules Optional. **Custom rules** to be added into the product.
     * @return array
     */
    public function composeErrorMessages(array $requested, array $additionalRules = []) : array
    {
        $collected = [];
        foreach($requested as $item) {
            foreach ($additionalRules as $key => $value) {
                if (preg_match("/\.".preg_quote($item)."$/", $key)) {
                    $collected[$key] = $value;
                    unset($additionalRules[$key]);
                }
            }
            if($item == "password")
                $collected = array_merge($collected, $this->defaultErrorMessages[$item]);
            else
                $collected[$item] = $this->defaultErrorMessages[$item];
        }
        if(count($additionalRules) > 0)
            $collected = array_merge($collected, $additionalRules);
        return $collected;
    }
}
