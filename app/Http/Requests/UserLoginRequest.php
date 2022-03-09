<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_name' => ['required',],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => '入力されていません。',
            'password.required' => '入力されていません。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $FailResponse = response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($FailResponse);
    }
}
