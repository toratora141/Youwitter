<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VideoListIdCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /* 以下のようなプレイリストのURLかチェック
         *
            * https://www.youtube.com/watch?v=xxxxxxxxx&list=PLxxxxxxxxxxxxxxxx
            * https://www.youtube.com/watch?v=xxxxxxxxx&list=PLxxxxxxxxxxxxxxxx
            * https://youtube.com/playlist?list=PLxxxxxxxxxxxxxxxx_
         *
        */
        if (!strpos($value, 'list=')) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'プレイリストのURLが正しくありません。';
    }
}
