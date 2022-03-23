<?php

namespace App\Http\Requests;

use App\Rules\VideoListIdCheck;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoListsCreate extends FormRequest
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
            'id' => ['required', 'unique:video_lists', new VideoListIdCheck],
            'user_id' => ['unique:video_lists']
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'プレイリストのURLが入力されていません。',
            'id.unique' => 'すでに登録されているプレイリストです。',
            'user_id.unique' => 'プレイリストは1つしか作成できません。',
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
        $videoListUrl = $data['id'];
        //list=の文字数だけずらしながら宣言
        $prepareSliceNum = strpos($videoListUrl, 'list=') + 5;
        $slicedMovieId = substr($videoListUrl, $prepareSliceNum);

        $mergeId = [];
        $mergeId['id'] = $slicedMovieId;
        $this->merge($mergeId);
    }
}
