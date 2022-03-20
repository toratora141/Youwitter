<?php

namespace App\Http\Controllers;

use App\Models\VideoLists;
use App\Http\Requests\VideoListsCreate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoListController extends Controller
{
    public function listCreate(VideoListsCreate $request)
    {
        $videoListModel = new VideoLists;
        $prepare = $videoListModel->prepareNewPlaylist(Auth::user()->account_name, $request);
        $param = $prepare[0];
        $thumbnailPath = $prepare[1];
        $thumbnailImg = file_get_contents($thumbnailPath);
        Storage::disk('public')->put($param['thumbnail'], $thumbnailImg);
        VideoLists::create($param);

        return response()->json(['result' => 'true']);
    }

    public function fetch(Request $request)
    {
        $param = $request->only('account_name');
        $user = Auth::user();

        $video_list = VideoLists::where('user_id', $user['account_name'])
            ->get()
            ->first();
        return response()->json(['video_list' => $video_list]);
    }
}
