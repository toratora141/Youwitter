<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\VideoList;
use App\Models\Video;
use Mockery\Undefined;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_name',
        'display_name',
        'icon',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function videoLists()
    {
        return $this->hasMany(VideoList::class, 'user_id', 'account_name');
    }

    public function follows()
    {
        return $this->hasMany(VideoList::class, 'user_id', 'account_name');
    }

    /*
     * ログイン時にcookieに保存する貯めの情報と取得、準備
     * [0]を指定しているので複数プレイリスト作成する場合は修正が必要
    */
    public function prepareUserCookie($account_name)
    {
        $fetch = User::where('account_name', $account_name)
            ->with('VideoLists.Videos')
            ->get();
        $param['user'] = [
            'account_name' => $fetch[0]->account_name,
            'display_name' => $fetch[0]->display_name,
            'icon' => $fetch[0]->icon
        ];
        if (isset($fetch[0]->videoLists[0])) {
            $param['videoLists'] = $fetch[0]->VideoLists;
            $param['videos'] = $fetch[0]->VideoLists[0]->Videos;
        }
        $param['videoLists'] = 'undefined';
        $param['videos'] = 'undefined';
        return $param;
    }
}
