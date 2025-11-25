<?php

namespace App\Http\Requests\Promation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ScriptPromotionRequest extends FormRequest
{
    public function authorize() { return true; }



    public function rules()
    {
        return [
            'recommendations'        => 'required|string',
            'file_of_recommendations' => 'nullable|array',
            'file_of_recommendations.*' => 'file|mimes:pdf,doc,docx|max:5120',
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
