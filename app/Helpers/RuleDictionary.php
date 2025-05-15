<?php

namespace App\Helpers;

use App\Rules\Capitalized;
use Illuminate\Validation\Rules\Password;


/**
 * **`RuleDictionary`**
 *
 * This class serves as **a synchronized validation rule and error message** hub.
 */
class RuleDictionary{

    /**
     * @var array **$defaultRuleset**
     *
     * Hosts **the complete default ruleset**.
     */
    private array $defaultRuleset;

    /**
     * @var array **$defaultErrorMessages**
     *
     * Hosts **the complete collection of error messages**.
     */
    private array $defaultErrorMessages;

    /**
     * @var array **$defaultFileRuleset**
     *
     * Hosts **the *universal* collection of file rules**. *Rule merging requires manual effort.*
     */
    public static array $defaultFileRuleset = ["file", "mimes:png,webp", "max:8000000"];

    public function __construct()
    {
        $this->defaultRuleset = [
            'name' => ['required', 'alpha', 'min:2', 'max:63', new Capitalized],
            'surname' => ['required', 'alpha', 'min:2', 'max:127', new Capitalized],
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->max(64) // assuming bcrypt
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            "age" => ["sometimes", "min:18", "max:120", "integer"],
            "height" => ["sometimes", "min:55", "max:272", "integer"],
            "weight" =>["sometimes", "min:20", "max:300", "integer"]
        ];
        $this->defaultErrorMessages = [
            'required' => 'To pole jest wymagane.',
            'alpha' => 'Wykryto niedozwolone znaki.',
            'integer' => 'Wykryto niedozwoloną liczbę.',
            'min' => 'Ta wartość jest zbyt krótka.',
            'max' => 'Ta wartość jest zbyt długa.',
            'email' => 'To nie adres email.',
            Capitalized::class => 'Niepoprawna wysokość znaków.',
            'confirmed' => 'Hasła się nie zgadzają.',
            'password' => [
                'password.min' => 'Hasło powinno zawierać min. 8 znaków',
                'password.max' => 'To hasło jest za długie.',
                'password.letters' => 'Hasło powinno zawierać co najmniej jedną małą i dużą literę..',
                'password.mixed' => 'Hasło powinno zawierać co najmniej jedną małą i dużą literę.',
                'password.numbers' => 'Hasło powinno zawierać cyfry.',
                'password.symbols' => 'Hasło powinno zawierać symbole.',
                'password.uncompromised' => 'Użyj innego hasła.',
            ]
        ];
    }

    /**
     * **`composeRules()`**
     *
     * This method **returns a set of rulesets** based on the `$requested` parameter. **Adding custom values is also possible**, via `$additionalRules`.
     *
     * @param array $requested Required. **The list of wanted rulesets** from the dictionary.
     * @param array $additionalRules Optional. **Custom rules** to be added into the product.
     * @param boolean $patch Optional. Defines whether **all rulesets from should feature the `sometimes` rule**. Custom rules are **not affected**.
     * @return array
     */
    public function composeRules(array $requested, array $additionalRules = [], bool $patch = false) : array
    {
        $collected = [];
        foreach($requested as $item)
            if (array_key_exists($item, $this->defaultRuleset))
                $collected[$item] = $this->defaultRuleset[$item];
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
        $oldAR = $additionalRules;
        foreach($requested as $item) {
            foreach ($additionalRules as $key => $value) {
                if (preg_match("/\." . preg_quote($item) . "$/", $key)) {
                    $collected[$key] = $value;
                    unset($additionalRules[$key]);
                }
            }
            if($item === "password")
                $collected = array_merge($collected, $this->defaultErrorMessages[$item]);
            else
                $collected[$item] = $this->defaultErrorMessages[$item];
        }
        if(count($additionalRules) > 0)
            $collected = array_merge($collected, $additionalRules);
        return $collected;
    }
}
