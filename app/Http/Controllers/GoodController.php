<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Good;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GoodController extends Controller
{

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $paramForFill = Good::prepareParamForFill($request, Auth::user()->account_name);
            $good = Good::create($paramForFill);
            $good->action()->create([
                'type' => 'good',
                'foreign_id' => $good->id
            ])
                ->notice()->create([
                    'user_id' => $request->userId
                ]);
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
            // dd($good->good_id);
            Good::find($good->good_id)
                ->delete();
            // Good::where('user_id', Auth::user()->account_name)
            //     ->where('video_id', $request->videoId)
            //     ->action()
            //     ->delete();
            // Good::where('user_id', Auth::user()->account_name)
            //     ->where('video_id', $request->videoId)
            //     ->delete();
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
        }

        DB::commit();
        return response()->json(['result' => true]);
    }
}
