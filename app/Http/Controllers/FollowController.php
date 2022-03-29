<?php

namespace App\Http\Controllers;

use App\Models\follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request)
    {
        // $followAccountName = $request->only('followAccountName');
        // $param = [
        //     'user_id' => $request->only('followAccountName'),
        //     'follower' => Auth::user()->account_name,
        // ];
        // dd($request);
        $param['user_id'] = $request->followAccountName;
        $param['follower'] = Auth::user()->account_name;

        Follow::create($param);

        return response()->json(['result' => true]);
    }
}
