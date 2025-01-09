<?php

namespace App\Rules;

use App\Models\PasswordResetToken;
use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidResetPasswordTokenRule implements ValidationRule
{

    public function __construct(
        protected ?PasswordResetToken $passwordResetToken
    )
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $passes = true;

        if(isset($this->passwordResetToken)) {

            if(Carbon::createFromTimeString($this->passwordResetToken->created_at)->diffInMinutes(now()) >= config('app.reset_password_token_lifetime', 60)) {

                $passes = false;
            }
        } else {

            $passes = false;
        }

        if(!$passes) $fail(config('messages.auth.invalid_or_expired_token'));
    }
}
