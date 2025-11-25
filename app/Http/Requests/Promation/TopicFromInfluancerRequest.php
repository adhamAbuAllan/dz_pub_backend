<?php

namespace App\Http\Requests\Promation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TopicFromInfluancerRequest extends FormRequest
{
    public function authorize() { return true; }

  

    public function rules()
    {
        return [
            'details'     => 'required|string',
            'have_sample' => 'required|in:yes,no',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
