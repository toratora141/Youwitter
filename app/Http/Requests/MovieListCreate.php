<?php

namespace App\Http\Requests;

use App\Rules\MovieListIdCheck;
use Illuminate\Contracts\Validation\Validator;
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
            'id' => ['required', 'unique:movie_lists', new MovieListIdCheck],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'プレイリストのURLが入力されていません。',
            'id.unique' => 'すでに登録されているプレイリストです。',
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

    //URLからplayListIdを抜き出し。
    protected function passedValidation()
    {
        $data = $this->all();
        $movieListUrl = $data['id'];
        //list=の文字数だけずらしながら宣言
        $prepareSliceNum = strpos($movieListUrl, 'list=') + 5;
        $slicedMovieId = substr($movieListUrl, $prepareSliceNum);

        $mergeId = [];
        $mergeId['id'] = $slicedMovieId;
        $this->merge($mergeId);
    }
}
