<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//youtubeAPI用のクラス
use App\Http\Vender\CallYoutubeApi;


class VideoLists extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'thumbnail',
        'first_video'
    ];

    protected $table = 'video_lists';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function prepareNewPlaylist($userId, $input)
    {
        $youtubeApi = new CallYoutubeApi();
        $playlistId = $input->id;
        $data = $youtubeApi->fetchPlayList($playlistId);
        $getThumbnailPath = $youtubeApi->fetchLastThumbnail($data);
        $videos = $youtubeApi->fetchVideoIdInPlaylist($data, $userId);
        $param['id'] = $playlistId;
        $param['user_id'] = $userId;
        $param['first_video'] = $videos[0]['id'];

        $saveThumbnailPath = $this->createPath($param['user_id'], $param['id']);
        $param['thumbnail'] = $saveThumbnailPath;
        return [
            'param' => $param,
            'thumbnailPath' => $getThumbnailPath,
            'videos' => $videos
        ];
    }

    public function createPath($userId, $movieId)
    {
        return $userId . '/' . $movieId . '.jpg';
    }
}
