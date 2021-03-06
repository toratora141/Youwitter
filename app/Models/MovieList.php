<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//youtubeAPI用のクラス
use App\Http\Vender\CallYoutubeApi;

class MovieList extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'id',
        'user_id',
        'thumbnail',
        'first_video'
    ];

    protected $table = 'movie_lists';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function prepareNewPlaylist($movieInfo, $userId)
    {
        $youtubeApi = new CallYoutubeApi();
        $playlistId = $movieInfo->id;
        $data = $youtubeApi->fetchPlayList($playlistId);
        $thumbnail = $youtubeApi->fetchLastThumbnail($data);
        $videos = $youtubeApi->fetchVideoIdInPlaylist($data);
        $param['id'] = $playlistId;
        $param['user_id'] = $userId;
        $param['first_video'] = $videos[0];

        //TODO: Movieクラス関数の作成
        $thumbnailImg = file_get_contents($thumbnail);
        $thumbnailPath = $this->createPath($param['user_id'], $param['playlistId']);
        $param['thumbnail'] = $thumbnailPath;
        return [$param, $thumbnailImg];
    }

    public function createPath($userId, $movieId)
    {
        return $userId . '/' . $movieId . '.jpg';
    }
}
