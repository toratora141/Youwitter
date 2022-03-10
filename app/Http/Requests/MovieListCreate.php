<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovieListCreate extends FormRequest
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
            'id' => ['required', 'unique:movie_lists',],
            'user_id' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'プレイリストのURLが入力されていません。',
            'id.unique' => 'すでに登録されているプレイリストです。',
        ];
    }
    //TODO////////////////////
    //URLからplayListIdを抜き出し。
}
