<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    protected $primaryKey = 'good_id';
    // public $incrementing = true;
    protected $table = "goods";
    protected $fillable = [
        'good_id',
        'user_id',
        'video_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($good) {
            $good->action()->delete();
        });
    }

    public function prepareParamForFill($request, $accountName)
    {
        return [
            'user_id' => $accountName,
            'video_id' => $request->videoId,
        ];
    }

    public function action()
    {
        return $this->hasOne(Action::class, 'foreign_id');
    }

    public function video()
    {
        return $this->hasOne(Video::class, 'code', 'video_id');
    }
}
