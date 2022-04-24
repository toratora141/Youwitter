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
    ];

    public function action()
    {
        return $this->hasOne(Action::class, 'action_id', 'action_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'account_name', 'user_id');
    }
}
