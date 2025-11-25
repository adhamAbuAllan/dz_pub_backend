<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ValidationMessages;

class ClientWithCRRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return[
            // 'reg_owner_name'      => 'required|string|max:255',
            // 'nsititution_name'    => 'required|string|max:255',
            // 'institution_address' => 'required|string|max:500',
            // 'rc_number'           => 'required|string|max:100|unique:clients,rc_number',
            // 'nis_number'          => 'required|string|max:100|unique:clients,nis_number',
            // 'iban'                => 'nullable|string|max:34',

      ];
    }
public function messages()
{
    return ValidationMessages::requiredMessages('clients_with_cr', [
        'reg_owner_name',
        'nsititution_name',
        'institution_address',
        'rc_number',
        'nis_number',
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
