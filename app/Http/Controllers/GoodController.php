<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Good;
use App\Models\Notice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GoodController extends Controller
{

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            //goodsテーブルのnotice_idを取得するため、先にnoticeレコードを作成
            $noticeParam = Notice::prepareParam('good', $request->userId);
            $notice = Notice::create($noticeParam);

            $goodParam = Good::prepareParam($request, $notice->notice_id, Auth::user()->account_name,);
            $good = Good::create($goodParam);
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
        }

        DB::commit();
        return response()->json(['result' => true]);
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $good = Good::where('user_id', Auth::user()->account_name)
                ->where('video_id', $request->videoId)
                ->first();
            Good::find($good->good_id)
                ->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        DB::commit();
        return response()->json(['result' => true]);
    }
}
