<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait JsonErrors {

    /**
     * @param Validator $validator
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()
                ->json(
                    [
                        'success'=>false,
                        'data'=>null,
                        'errors'=>$validator->errors()
                    ]
                    , Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
