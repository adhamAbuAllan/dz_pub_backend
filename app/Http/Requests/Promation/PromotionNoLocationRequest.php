<?php

namespace App\Http\Requests\Promation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PromotionNoLocationRequest extends FormRequest
{
    public function authorize() { return true; }

 
    public function rules()
    {
        return [
            // Only required if movement = no
            'promation_type_id'  => 'required|in:1,2', // image or video only
            'topic_is_ready'     => 'required|in:yes,no',
            'have_sample'        => 'nullable|in:yes,no', // required only if topic_is_ready=no

            // Optional topic files (only if topic ready)
            'file_of_topics'     => 'nullable|array',
            'file_of_topics.*'   => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // have_sample required only when topic is not ready
            if (isset($data['topic_is_ready']) && $data['topic_is_ready'] === 'no'
                && !isset($data['have_sample'])) {
                $validator->errors()->add('have_sample', 'have_sample is required when topic is not ready.');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
