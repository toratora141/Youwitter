<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'video_list_id',
        'thumbnail'
    ];

    protected $table = 'videos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}
