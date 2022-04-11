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

    public function prepareVideoParams($videoList, $userId)
    {
        $videos = [];
        $thumbnails = [];
        foreach ($videoList->items as $video) {
            $videoId = $video->snippet->resourceId->videoId;
            $title = $video->snippet->title;
            $getThumbnailUrl = $video->snippet->thumbnails->default->url;
            $putThumbnailPath = $this->putPath($userId, $videoId);
            array_push($videos, ['code' => $videoId, 'video_list_id' => $playlistId, 'thumbnail' => $putThumbnailPath, 'title' => $title]);

            $thumbnailImg = file_get_contents($getThumbnailUrl);
            Storage::disk('public')->put($putThumbnailPath, $thumbnailImg);
        }
        return ['videosParam' => $videos, 'videosThumbnails' => $thumbnails];
    }

    public function putPath($userId, $fileName)
    {
        return  $userId . '/' . $fileName . '.jpg';
    }
}
