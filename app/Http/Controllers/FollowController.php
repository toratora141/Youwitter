<?php

namespace App\Http\Controllers;

use App\Models\follow;
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
            ->with(['user', 'videoList.videos'])
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
        Follow::where('user_id', $request->followAccountName)
            ->where('follower', Auth::user()->account_name)
            ->delete();
        return response()->json(['result' => true]);
    }
}
