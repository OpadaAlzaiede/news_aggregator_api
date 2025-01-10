<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePreferenceCombinationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $uniqueCombinations = collect($value)->unique(function ($item) {
            return $item['preference_type'].$item['preference_value'];
        });

        if(count($uniqueCombinations) !== count($value)) {

            $fail('Duplicate preferences are not allowed.');
        }
    }
}
