<?php

namespace App\Http\Requests\Promation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PromotionBaseRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'client_id'                 => 'required|exists:clients,id',
            'influencer_id'             => 'required|exists:influencers,id',
            'price'                     => 'required|numeric|min:0',
            'should_influencer_movment' => 'required|in:yes,no',
            'have_a_form'               => 'required_unless:should_influencer_movment,yes|in:yes,no',
            'time_line'                 => 'required|string|max:50',
            'requirements'              => 'required|string|max:2000',
            'promation_type_id'         => 'nullable|in:1,2,3',
            'topic_is_ready'            => 'nullable|in:yes,no',
            'have_smaple'               => 'nullable|in:yes,no',
                'detials'           => 'nullable|string|max:512',
            'social_media'    => 'required|array',
            'social_media.*'  => 'exists:social_media,id',

            'social_media_types'        => 'required|array',
            'social_media_types.*'      => 'exists:social_media_promation_types,id',
            'file_of_topics'            => 'nullable|array',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // promation_type_id required only if have_a_form = yes
            if (isset($data['have_a_form']) && $data['have_a_form'] === 'yes' && empty($data['promation_type_id'])) {
                $validator->errors()->add('promation_type_id', 'promation_type_id is required when have_a_form is yes.');
            }

            // topic_is_ready required for image/video only when have_a_form = yes
            if (!empty($data['promation_type_id']) && in_array((int)$data['promation_type_id'], [1,2])
                && isset($data['have_a_form']) && $data['have_a_form'] === 'yes'
                && !isset($data['topic_is_ready'])) {
                $validator->errors()->add('topic_is_ready', 'topic_is_ready is required for image/video promotions.');
            }

            // have_smaple required when topic_is_ready = no and type = 1/2
            if (!empty($data['promation_type_id']) && in_array((int)$data['promation_type_id'], [1,2])
                && isset($data['topic_is_ready']) && $data['topic_is_ready'] === 'no'
                && !isset($data['have_smaple'])) {
                $validator->errors()->add('have_smaple', 'have_smaple is required when topic is not ready.');
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
