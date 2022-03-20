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
        $param = $prepare['param'];
        $thumbnailPath = $prepare['thumbnailPath'];
        $videos = $prepare['videos'];
        $thumbnailImg = file_get_contents($thumbnailPath);
        VideoLists::create($param);
        Storage::disk('public')->put($thumbnailPath, $thumbnailImg);
        // dd('temp');

        $videoParam = [];
        foreach ($videos as $video) {
            array_push($videoParam, ['id' => $video['id'], 'video_list_id' => $param['id'], 'thumbnail' => $video['thumbnail']]);
            $videoImg = file_get_contents($video['thumbnail']);
            Storage::disk('public')->put($video['path'], $videoImg);
        }
        DB::table('videos')->insert($videoParam);

        return response()->json(['result' => 'true']);
    }

    public function fetch()
    {
        $user = Auth::user();
        $video_list = VideoLists::where('user_id', $user['account_name'])
            ->get()
            ->first();
        return response()->json(['video_list' => $video_list, 'result' => true]);
    }
}
