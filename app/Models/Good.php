<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Good extends Model
{
    use HasFactory;

    protected $primaryKey = 'good_id';
    protected $table = "goods";
    protected $fillable = [
        'video_id',
        'notice_id',
        'user_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($good) {
            $good->notice()->delete();
        });
    }

    public function prepareParam($request, $noticeId, $userId)
    {
        return [
            'video_id' => $request->videoId,
            'notice_id' => $noticeId,
            'user_id' => $userId
        ];
    }

    public function video()
    {
        return $this->hasOne(Video::class, 'code', 'video_id');
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }
}
