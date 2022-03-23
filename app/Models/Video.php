<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'video_list_id',
        'thumbnail',
        'title'
    ];

    protected $table = 'videos';

    public function videoList()
    {
        return $this->belongsTo(VideoList::class);
    }
}
