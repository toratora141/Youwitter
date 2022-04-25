<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Good extends Model
{
    use HasFactory;

    protected $primaryKey = 'good_id';
    // public $incrementing = true;
    protected $table = "goods";
    protected $fillable = [
        'user_id',
        'video_id',
    ];

    public static function boot()
    {
        parent::boot();

        // static::created(function ($follow) {
        //     $follow->action()->create([
        //         'user_id' => Auth::user()->account_name,
        //         'type' => 'good'
        //     ]);
        // });

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
        return $this->belongsTo(Action::class, 'foreign_id', 'good_id');
    }

    public function video()
    {
        return $this->hasOne(Video::class, 'code', 'video_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'account_name', 'user_id');
    }

    public function createRelationRecord($good, $userId)
    {
        $good->action()
            ->create([
                'type' => 'good',
                'foreign_id' => $good->good_id,
                'user_id' => Auth::user()->account_name
            ])
            ->notice()
            ->create([
                'user_id' => $userId,
            ]);
    }
}
