<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    protected $primaryKey = 'good_id';
    protected $fillable = [
        'user_id',
        'video_id'
    ];
}
