<?php

namespace App\Http\Requests;

use App\Models\PasswordResetToken;
use App\Rules\ValidResetPasswordTokenRule;
use App\Traits\JsonErrors;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    use JsonErrors;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $token = $this->get('token');
        $passwordResetToken = PasswordResetToken::where('token', $token)->first();

        return [
            'token' => [
                'required',
                new ValidResetPasswordTokenRule($passwordResetToken),
            ],
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($passwordResetToken) {
                    if ($passwordResetToken && $value !== $passwordResetToken->email) {
                        $fail(config('messages.auth.invalid_email'));
                    }
                },
            ],
            'password' => [
                'required',
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'confirmed',
            ],
        ];
    }
}
