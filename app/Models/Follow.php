<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'follower',
        'notice_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($follow) {
            $follow->notice()->delete();
        });
    }

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'account_name');
    }
    public function followUser()
    {
        return $this->belongsTo(User::class, 'follower', 'account_name');
    }
    public function videoLists()
    {
        return $this->belongsTo(VideoList::class, 'user_id', 'user_id');
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }

    public function prepareParam($followAccountName, $followedAccountName, $noticeId)
    {
        $param = [
            'user_id' => $followAccountName,
            'follower' => $followedAccountName,
            'notice_id' => $noticeId
        ];

        return $param;
    }

    //プロフィールページのユーザをフォローしているかチェック
    public function checkFollowing($otherAccountName, $myAccountName)
    {
        return Follow::where('user_id', $otherAccountName)
            ->where('follower', $myAccountName)
            ->first();
    }
}
