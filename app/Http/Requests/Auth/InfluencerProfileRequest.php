<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ValidationMessages;

class InfluencerProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
         'rating'     => 'nullable|numeric|min:0|max:5',
            'bio'        => 'nullable|string|max:500',
            'gender'     => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'shake_number'  => 'nullable|string|max:20',
            'type_id'    => 'nullable|exists:influencer_types,id',
        //     'category_ids' => 'nullable|array',
        //     'category_ids.*' => 'exists:categories,id',
        //     'social_media_ids' => 'nullable|array',
        //     'social_media_ids.*' => 'exists:social_media,id',
        ];
    }
public function messages()
{
    return ValidationMessages::requiredMessages('influencers', [
        'rating', 'bio', 'gender', 'date_of_birth', 'shake_number', 'type_id'
    ]);
}

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
