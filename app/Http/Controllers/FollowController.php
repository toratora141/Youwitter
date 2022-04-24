<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /*
     * フォローしているユーザをfetch
     */
    public function fetch()
    {
        $follows = Follow::where('follower', Auth::user()->account_name)
            ->with(['user', 'videoLists.videos.good'])
            ->get();
        return response()->json(['follows' => $follows]);
    }
    /*
     * フォローメソッド
     */
    public function follow(Request $request)
    {
        if (Follow::already($request->followAccountName, Auth::user()->account_name)) {
            return;
        }
        $param['user_id'] = $request->followAccountName;
        $param['follower'] = Auth::user()->account_name;

        Follow::create($param);

        return response()->json(['result' => true]);
    }

    /*
     * フォロー解除メソッド
    */
    public function delete(Request $request)
    {
        $param['user_id'] = $request->followAccountName;
        $temp = Follow::where('user_id', $request->followAccountName)
            ->where('follower', Auth::user()->account_name)
            ->first();
        Follow::find($temp->id)
            ->delete();
        return response()->json(['result' => true]);
    }

    public function list(Request $request)
    {
        $followed = Follow::where('follower', $request->input('accountName'))
            ->with('user')
            ->get();
        $follower = Follow::where('user_id', $request->input('accountName'))
            ->with('followUser')
            ->get();
        return response()->json(['followed' => $followed, 'follower' => $follower]);
    }
}
