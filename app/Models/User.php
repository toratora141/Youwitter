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
use Illuminate\Support\Facades\Hash;

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
        'remember_token',
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

    //UserControllerのregister関数でインスタンスの作成をする準備
    public function prepareParamForRegister($request)
    {
        $param = [
            'account_name' => $request->account_name,
            'display_name' => $request->display_name,
            'remember_token' => true,
            'password' => Hash::make($request->password)
        ];

        return $param;
    }

    //UserControllerのfetchProf関数の返り値用の関数
    public function prepareResponseForFetch($result, $user, $videoLists, $isFollow)
    {
        return [
            'result' => $result,
            'user' => $user,
            'videoLists' => $videoLists,
            'isFollow' => $isFollow
        ];
    }

    public function fetchUserWithRelation($accountName)
    {
        return User::where('account_name', $accountName)
            ->with('videoLists.videos.good', 'videoLists.user')
            ->get();
    }

    //プロフィールページのユーザをフォローしているかチェック
    //Follow.phpで行った方が保守性がいい気がしたので飛ばす
    public function checkFollowing($otherAccountName, $myAccountName)
    {
        return Follow::checkFollowing($otherAccountName, $myAccountName);
    }

    public function prepareUserForResponse($accountName, $displayName, $icon)
    {
        return [
            'account_name' => $accountName,
            'display_name' => $displayName,
            'icon' => $icon,
        ];
    }

    /*
     * like検索
     * @param $keyword string
     * @return users obj
     */
    public function searchLike($keyword)
    {
        $pat = '%' . addcslashes($keyword, '%_\\') . '%';
        $users = User::where('account_name', 'LIKE', $pat)
            ->get();
        return $users;
    }
}
