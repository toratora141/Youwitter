<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    /*
     * フォローしているユーザをfetch
     */
    public function fetch()
    {
        $follows = Follow::where('follower', Auth::user()->account_name)
            ->with(['user', 'videoLists.videos.good', 'videoLists.user'])
            ->get();
        return response()->json(['follows' => $follows]);
    }
    /*
     * フォローメソッド
     */
    public function follow(Request $request)
    {
        if (Follow::already($followAccountName = $request->followAccountName, $followedAccountName = Auth::user()->account_name)) {
            return;
        }

        DB::beginTransaction();
        try {
            //goodsテーブルのnotice_idを取得するため、先にnoticeレコードを作成
            $createNoticePram = Notice::prepareParam('follow', $followAccountName);
            $notice = Notice::create($createNoticePram);

            $createFollowParam = Follow::prepareParam($followAccountName, $followedAccountName, $notice->notice_id);
            $follow = Follow::create($createFollowParam);
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        DB::commit();
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
