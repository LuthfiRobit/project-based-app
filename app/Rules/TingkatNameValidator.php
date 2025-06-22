<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TingkatNameValidator implements ValidationRule
{
    protected string $jenjang;

    protected array $validLevelsByJenjang = [
        'SD'  => ['1', '2', '3', '4', '5', '6'],
        'MI'  => ['1', '2', '3', '4', '5', '6'],
        'SMP' => ['7', '8', '9'],
        'MTS' => ['7', '8', '9'],
        'SMA' => ['10', '11', '12', 'X', 'XI', 'XII'],
        'SMK' => ['10', '11', '12', 'X', 'XI', 'XII'],
        'MA'  => ['10', '11', '12', 'X', 'XI', 'XII'],
    ];

    public function __construct(string $jenjang)
    {
        $this->jenjang = $jenjang;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!isset($this->validLevelsByJenjang[$this->jenjang])) {
            $fail("The selected jenjang '{$this->jenjang}' is invalid.");
            return;
        }

        if (!in_array($value, $this->validLevelsByJenjang[$this->jenjang], true)) {
            $validList = implode(', ', $this->validLevelsByJenjang[$this->jenjang]);
            $fail("The {$attribute} '{$value}' is invalid for jenjang '{$this->jenjang}'. Allowed values: {$validList}.");
        }
    }
}
