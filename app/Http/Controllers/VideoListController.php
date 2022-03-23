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
            DB::table('videos')->insert($prepare['videosParam']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return response()->json(['result' => 'true']);
    }

    // public function fetch()
    // {
    //     $user = Auth::user();
    //     $video_list = VideoLists::where('user_id', $user['account_name'])
    //     ->get()
    //     ->first();
    //     return response()->json(['video_list' => $video_list, 'result' => true]);
    // }
    public function fetch()
    {
        $user = Auth::user();
        $videoList = VideoList::where('user_id', $user['account_name'])
            ->get()
            ->first();
        // $videos = VideoList::where('user_id', $user['account_name'])
        $videos = VideoList::find($videoList['id'])
            ->videos()
            ->get();
        // $videos_array = DB::table('videos')->where('video_list_id', $videoList['id'])
        //     ->get();
        // dd($videos);
        return response()->json(['video_list' => $videoList, 'videos_array' => $videos, 'result' => true]);
    }
}
