<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Action extends Model
{
    use HasFactory;

    protected $primaryKey = 'action_id';
    protected $table = 'actions';
    protected $fillable = [
        'type',
        'foreign_id'
    ];

    public static function boot()
    {
        parent::boot();

        // static::created(function ($notice) {
        //     $notice->notice()->create([
        //         'user_id' => Auth::user()->account_name
        //     ]);
        // });

        static::deleted(function ($action) {
            $action->notice()->delete();
        });
    }

    public function notice()
    {
        return $this->hasOne(Notice::class, 'action_id', 'foreign_id');
    }
    public function good()
    {
        return $this->hasOne(Good::class, 'good_id', 'foreign_id');
    }
    public function follow()
    {
        return $this->hasOne(Follow::class, 'id', 'foreign_id');
    }
}
