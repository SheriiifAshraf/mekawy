<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InLangRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = explode(".", $attribute)[1];

        if (!in_array($value, ['en', 'ar'])) {
            $fail(trans('messages.invalidLanguage'));
        }
    }
}
