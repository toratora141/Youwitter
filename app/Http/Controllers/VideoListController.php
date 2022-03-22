<?php

namespace App\Http\Controllers;

use App\Models\VideoLists;
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
        $videoListModel = new VideoLists;
        // dd(Auth::user()->account_name);
        $prepare = $videoListModel->prepareNewPlaylist(Auth::user()->account_name, $request);
        $videoListParam = $prepare['videoListParam'];
        $thumbnailPath = $prepare['thumbnailPath'];
        $thumbnailImg = file_get_contents($thumbnailPath);
        VideoLists::create($videoListParam);
        Storage::disk('public')->put($prepare['saveThumbnailPath'], $thumbnailImg);
        // dd('temp');

        $videosThumbnail = $prepare['videosThumbnails'];
        // $videos = $prepare['videosParam'];
        $videoParam = $prepare['videosParam'];

        foreach ($videosThumbnail as $thumbnail) {
            // array_push($videoParam, ['id' => $video['id'], 'video_list_id' => $videoListParam['id'], 'thumbnail' => $video['thumbnail']]);
            $videoImg = file_get_contents($thumbnail['url']);
            Storage::disk('public')->put($thumbnail['putPath'], $videoImg);
        }
        DB::table('videos')->insert($videoParam);

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
        $videoList = VideoLists::where('user_id', $user['account_name'])
            ->get()
            ->first();
        $videos_array = DB::table('videos')->where('video_list_id', $videoList['id'])
            ->get();
        return response()->json(['video_list' => $videoList, 'videos_array' => $videos_array, 'result' => true]);
    }
}
