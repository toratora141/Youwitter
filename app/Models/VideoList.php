<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//youtubeAPI用のクラス
use App\Http\Vender\CallYoutubeApi;
use Illuminate\Support\Facades\Storage;


class VideoList extends Model
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

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function prepareNewPlaylist($userId, $input)
    {
        $youtubeApi = new CallYoutubeApi();
        $playlistId = $input->id;
        $data = $youtubeApi->fetchPlayList($playlistId);
        $getThumbnailPath = $youtubeApi->fetchLastThumbnail($data);
        $videos = $youtubeApi->prepareVidoesParam($data, $userId, $playlistId);
        $videosParam = $videos['videosParam'];
        $videoListParam['id'] = $playlistId;
        $videoListParam['user_id'] = $userId;
        $videoListParam['first_video'] = $videosParam[0]['code'];

        $saveThumbnailPath = $this->createPath($videoListParam['user_id'], $videoListParam['id']);
        $videoListParam['thumbnail'] = $saveThumbnailPath;
        return [
            'videoListParam' => $videoListParam,
            'thumbnailPath' => $getThumbnailPath,
            'saveThumbnailPath' => $saveThumbnailPath,
            'videosParam' => $videos['videosParam'],
        ];
    }

    public function createPath($userId, $movieId)
    {
        return $userId . '/' . $movieId . '.jpg';
    }

    public function updateVideoList($currentVideoList)
    {
        $youtube = new CallYoutubeApi();
        $userId = $currentVideoList->user_id;
        $videoListId = $currentVideoList->id;

        //新しい再生リストをfetch
        $newVideoList = $youtube->fetchPlaylistItems($videoListId);
        $newVideos = $youtube->playlistToVideosArray($newVideoList);


        //新しい再生リストと比べるために今の再生リストを配列で定義
        $currentVideos = [];
        foreach ($currentVideoList->videos as $video) {
            $currentVideos[] = $video->code;
        }

        //追加、削除された動画
        $newVideoList['add'] = array_diff($newVideos, $currentVideos);
        $newVideoList['delete'] = array_diff($currentVideos, $newVideos);

        //更新用の動画パラメータを準備
        $updateVideos = $youtube->prepareVidoesParam($newVideoList, $userId, $videoListId);

        if (!empty($updateVideos['videosParam'])) {
            VideoList::find($videoListId)
                ->videos()
                ->createMany($updateVideos['videosParam']);
            VideoList::find($videoListId)
                ->update(['thumbnail' => $updateVideos['videosParam'][0]['thumbnail']]);
        }

        if (!empty($newVideoList['delete'])) {
            foreach ($newVideoList['delete'] as $deleteVideo) {
                VideoList::find($videoListId)
                    ->videos()
                    ->where('code', $deleteVideo)
                    ->delete();
                Storage::disk('public')->delete($youtube->putPath($userId, $deleteVideo));
            }
        }
    }
}
