<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    //usersテーブルのリレーション: 多対一
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'account_name');
    }

    public function preparePramForNewUserList($request)
    {
        $return = array_map(function ($user) {
            return ['user_id' => $user];
        }, $request->users);

        return $return;
    }
}
