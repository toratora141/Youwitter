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
        'follower'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($follow) {
            $follow->action()->create([
                'user_id' => Auth::user()->account_name,
                'type' => 'follow'
            ]);
        });
        static::deleted(function ($follow) {
            $follow->action()->delete();
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
    public function action()
    {
        return $this->hasOne(Action::class, 'foreign_id');
    }
}
