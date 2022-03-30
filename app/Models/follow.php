<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'follower'
    ];

    //連続フォロー回避のため、既に同じfollowが存在しているかチェック
    /*
     *@param
     *  $followAccountName: フォローするユーザのアカウント名 string
     *  $myAccountName: 自分のアカウント名
     *
     * @return
     *  boolean フォローしていればtrue
    */
    public function already($followAccountName, $myAccountName)
    {
        $searchFollowing = Follow::where('user_id', $followAccountName)
            ->where('follower', $myAccountName)
            ->first();
        return isset($searchFollowing);
    }
}
