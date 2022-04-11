<?php

namespace App\Console;

use App\Models\Video;
use App\Models\VideoList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Vender\CallYoutubeApi;

class VideoListUpdateDaily
{
    /*
     * 再生リストの更新
     */
    public function __invoke()
    {
        var_dump('update all videoLists');
        //トランザクションのエラー回避
        DB::beginTransaction();
        try {
            $youtube = new CallYoutubeApi;
            $videoLists = new VideoList;
            $fetchVideoLists = $videoLists->with('videos')->get();

            foreach ($fetchVideoLists as $currentVideoList) {

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

                var_dump($newVideoList['add']);

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
                            ->where('code', $deleteVideo);
                        Storage::disk('public')->delete($youtube->putPath($userId, $deleteVideo));
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        DB::commit();
    }
}
