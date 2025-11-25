<?php

namespace App\Http\Requests\Promation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SocialMediaPromotionRequest extends FormRequest
{
    public function authorize() { return true; }


    public function rules()
    {
        return [
    'social_media' => 'required|array',
'social_media.*' => 'exists:social_media,id',

'social_media_types' => 'required|array',
'social_media_types.*' => 'exists:social_media_promation_types,id',


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
