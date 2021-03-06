<?php

namespace App\Http\Vender;

use Google\Service\YouTube;
use Google_Client;
use Google_Service_YouTube;
use App\Consts\YoutubeConsts;

use Illuminate\Support\Facades\Storage;

class CallYoutubeApi
{
    private $key = YoutubeConsts::API_KEY;
    private $client;
    private $youtube;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setDeveloperKey($this->key);
        $this->youtube = new Google_Service_YouTube($this->client);
    }

    /**
     * /v3/searchを呼び出す
     *
     * @param string $serachWord
     * @return array
     */
    public function serachList(String $searchWord)
    {
        $r = $this->youtube->search->listSearch('id', array(
            'q' => $searchWord,
            'maxResults' => 10,
            'order' => 'viewCount',
        ));

        return $r->items;
    }

    public function fetchPlayList(String $playlistId)
    {
        $playlist = $this->youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $playlistId,
            'maxResults' => 100,
        ));

        return $playlist;
    }
    public function fetchPlaylistItems(String $playlistId)
    {
        $playlist = $this->youtube->playlistItems->listPlaylistItems('snippet', array(
            'playlistId' => $playlistId,
            'maxResults' => 100,
        ));

        return $playlist;
    }

    public function playlistToVideosArray($newPlaylist)
    {
        $videos = [];
        foreach ($newPlaylist->items as $video) {
            $videos[] = $video->snippet->resourceId->videoId;
        }
        return $videos;
    }

    public function fetchLastThumbnail(Object $data)
    {
        $lastIndex = count($data->items) - 1;
        $thumbnail = $data->items[$lastIndex]->snippet->thumbnails->default->url;

        return $thumbnail;
    }

    public function prepareVidoesParam(Object $data, String $userId, String $playlistId)
    {
        $videos = [];
        $thumbnails = [];
        foreach ($data->items as $video) {
            if (isset($video->snippet)) {
                $videoId = $video->snippet->resourceId->videoId;
                if (!isset($data['add']) || in_array($videoId, $data['add'])) {
                    $title = $video->snippet->title;
                    $getThumbnailUrl = $video->snippet->thumbnails->default->url;
                    $putThumbnailPath = $this->putPath($userId, $videoId);
                    array_push($videos, ['code' => $videoId, 'video_list_id' => $playlistId, 'thumbnail' => $putThumbnailPath, 'title' => $title]);

                    $thumbnailImg = file_get_contents($getThumbnailUrl);
                    Storage::disk('public')->put($putThumbnailPath, $thumbnailImg);
                }
            }
        }
        return ['videosParam' => $videos];
    }

    public function putPath($userId, $fileName)
    {
        return  $userId . '/' . $fileName . '.jpg';
    }

    /**
     * /v3/videosを呼び出す
     *
     * @param string $id
     * @return array
     */
    public function videosList(String $id)
    {
        $r = $this->youtube->videos->listVideos('statistics,snippet', array(
            'id' => $id,
        ));

        return $r->items;
    }
}
