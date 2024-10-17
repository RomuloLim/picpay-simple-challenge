<?php

namespace App\Rules;

use App\Enums\UserType;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanTransfer implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::findOrFail($value);

        if ($user->type === UserType::Logistic->value) {
            $fail('Logistic users cannot transfer money');
        }
    }
}
