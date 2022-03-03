<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserInputPost extends FormRequest
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
            'account_name' => ['required', 'unique:users', 'min:4', 'max:32'],
            'display_name' => ['required', 'min:1', 'max:32'],
            'password' => ['required', 'min:6', 'max:16'],
            'password_conform' => ['required', 'same:password'],
        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => '入力されていません',
            'account_name.unique' => 'すでに使用されているIDです',
            'account_name.min' => '4文字以上で入力してください。',
            'account_name.max' => '32文字以下で入力してください。',
            'display_name.required' => '入力されていません',
            'display_name.min' => '1文字以上で入力してください。',
            'display_name.max' => '32文字以下で入力してください。',
            'password.required' => '入力されていません',
            'password.min' => '6文字以上で入力してください。',
            'password.max' => '16文字以下で入力してください。',
            'password_conform.required' => '確認のためパスワードを再入力してください。',
            'password_conform.same' => 'パスワードが一致しません。',
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
