<?php

namespace App\Http\Requests;

use App\Enums\PreferenceForEnum;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniquePreferenceCombinationRule;
use Illuminate\Validation\Rule;

class StoreUserPreferenceRequest extends FormRequest
{
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
        return [
            'preferences' => ['array', 'required', 'min:1', 'max:10', new UniquePreferenceCombinationRule],
            'preferences.*.preference_type' => ['required', Rule::enum(PreferenceForEnum::class)],
            'preferences.*.preference_value' => ['required']
        ];
    }
}
