<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $primaryKey = 'notice_id';
    protected $fillable = [
        'user_id',
        'type',
    ];

    public function prepareParam($type, $userId)
    {
        $param = [
            'type' => $type,
            'user_id' => $userId
        ];

        return $param;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'account_name');
    }

    public function good()
    {
        return $this->hasOne(Good::class, 'notice_id');
    }

    public function follow()
    {
        return $this->belongsTo(Follow::class, 'notice_id');
    }
}
