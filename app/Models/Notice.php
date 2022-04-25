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
}
