<?php

namespace App\Http\Controllers;

use App\Models\VideoList;
use App\Http\Requests\VideoListsCreate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class VideoListController extends Controller
{
    public function listCreate(VideoListsCreate $request)
    {
        DB::beginTransaction();
        try {
            $videoListModel = new VideoList;
            $prepare = $videoListModel->prepareNewPlaylist(Auth::user()->account_name, $request);
            $videoListParam = $prepare['videoListParam'];
            $thumbnailPath = $prepare['thumbnailPath'];
            VideoList::create($videoListParam);

            $thumbnailImg = file_get_contents($thumbnailPath);
            Storage::disk('public')->put($prepare['saveThumbnailPath'], $thumbnailImg);

            //Todo::リレーション
            VideoList::find($videoListParam['id'])
                ->videos()
                ->createMany($prepare['videosParam']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th);
            throw new \Exception('失敗しました。');
        }
        DB::commit();
        return response()->json(['result' => 'true']);
    }

    /*
     * プレイリストの動画を取ってくる
     * @param $reuqest: クエリ文字列にid
    */
    public function fetch(Request $request)
    {
        $user = Auth::user();
        $videos = VideoList::find($request->input('id'))
            ->videos()
            ->get();
        return response()->json(['videos' => $videos, 'result' => true]);
    }

    public function update(Request $requst)
    {
        DB::beginTransaction();
        try {
            $fetchVideoLists = VideoList::with('videos')->get();
            VideoList::updateVideoList($fetchVideoLists);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json(['result' => true]);
    }
}
