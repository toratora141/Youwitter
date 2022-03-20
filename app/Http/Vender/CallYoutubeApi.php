<?php

namespace App\Http\Vender;

use Google_Client;
use Google_Service_YouTube;

class CallYoutubeApi
{
    private $key = 'AIzaSyD84yQx4yaM7rfYrP4Uv6OD0eb9U7jv3OQ';
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

    public function fetchLastThumbnail(Object $data)
    {
        $lastIndex = count($data->items) - 1;
        $thumbnail = $data->items[$lastIndex]->snippet->thumbnails->default->url;

        return $thumbnail;
    }

    public function fetchVideoIdInPlaylist(Object $data, String $userId)
    {
        $videoIds = [];
        foreach ($data->items as $video) {
            $videoId = $video->snippet->resourceId->videoId;
            $thumbnail = $video->snippet->thumbnails->default->url;
            // dd($thumbnail);/
            array_push($videoIds, ['id' => $videoId, 'path' => $this->setPath($userId, $videoId), 'thumbnail' => $thumbnail]);
        }
        // dd('temp');
        return $videoIds;
    }

    public function setPath($userId, $fileName)
    {
        return '/storage/' . $userId . '/' . $fileName . '.jpg';
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
