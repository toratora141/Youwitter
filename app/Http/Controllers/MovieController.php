<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieListCreate;
use App\Models\MovieList;
use App\Http\Requests\MovieListInput;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Vender\CallYoutubeApi;


class MovieController extends Controller
{
    public function listCreate(MovieListCreate $request)
    {
        //Todo: api_keyをコンフィグ？に
        $api_key = "AIzaSyD84yQx4yaM7rfYrP4Uv6OD0eb9U7jv3OQ";
        $youtubeApi = new CallYoutubeApi();
        $playlistId = $request->id;
        $param['playListId'] = $playlistId;
        $data = $youtubeApi->fetchPlayList($playlistId);
        $thumbnail = $youtubeApi->fetchLastThumbnail($data);
        $videos = $youtubeApi->fetchVideoIdInPlaylist($data);
        $param['id'] = $playlistId;
        $param['user_id'] = Auth::user()->account_name;
        $param['first_video'] = $videos[0];

        //TODO: Movieクラス関数の作成
        $thumbnailImg = file_get_contents($thumbnail);
        $thumbnailPath = $param['user_id'] . '/' . $param['first_video'] . '.jpg';
        Storage::disk('public')->put($thumbnailPath, $thumbnailImg);
        $param['thumbnail'] = $thumbnailPath;



        MovieList::create($param);

        return response()->json(['data' => $data]);
    }

    public function fetch(Request $request)
    {
        $param = $request->only('account_name');
        // dd($param);
        // dd($request);
        $user = Auth::user();

        $movie_list = MovieList::where('user_id', $user['account_name'])
            ->get()
            ->first();
        return response()->json(['movie_list' => $movie_list]);
    }
}
