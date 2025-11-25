<?php

namespace App\Http\Requests\Promation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class   PromotionLocationRequest extends FormRequest
{
    public function authorize() { return true; }



    public function rules()
    {
        return [
            'location' => 'required|string|max:255',
            'file_of_topics'   => 'nullable|array',
            'file_of_topics.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
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
