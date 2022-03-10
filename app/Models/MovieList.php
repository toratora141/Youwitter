<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MovieList extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'id',
        'user_id'
    ];

    protected $table = 'movie_lists';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
}
