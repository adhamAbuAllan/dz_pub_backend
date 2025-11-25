<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ValidationMessages;

class ClientWithoutCRRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        //     'name' => 'required|string|max:255',
        //     'nickname' => 'required|string|max:100',
        //     'identity_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        // use centralized helper
        return ValidationMessages::requiredMessages('clients_without_cr', ['name', 'nickname']);
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $msg = implode(', ', $errors);
        throw new HttpResponseException(response()->json([
            'status' => false,
            'msg' => $msg,
            'data' => null
        ], 422));
    }
}

