<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $this->validateUser();

        if (! hash_equals((string) $this->route('id'),
            (string) $this->user()->getKey())) {
            return false;
        }

        if (! hash_equals((string) $this->route('hash'),
            sha1($this->user()->getEmailForVerification()))) {
            return false;
        }

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
            //
        ];
    }

    /**
     * This method tries log the user in using the Id
     *
     * @throws AuthorizationException
     */
    public function validateUser(): ?JsonResponse
    {

        auth()->loginUsingId(User::findOrFail($this->route('id'))->id);

        try {
            if ($this->route('id') != $this->user()->getKey()) {
                throw new AuthorizationException;
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return null;
    }
}
