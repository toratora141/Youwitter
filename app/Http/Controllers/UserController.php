<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserInputPost;

class UserController extends Controller
{
    public function register(UserInputPost $request)
    {
        $param['account_name'] = $request['account_name'];
        $param['display_name'] = $request['display_name'];
        $param['password'] = Hash::make($request['password']);
        return User::create($param);
    }

    public function fetch(Request $request)
    {
        $result = false;
        $user = [];
        if (Auth::check()) {
            $user = Auth::user();
            $result = true;
        }
        return response()->json(['result' => $result, 'user' => $user]);
        return ['result' => $result, 'user' => $user];



        $user = $request->user();
        // return new JsonResponse([
        //     'user' => $user,
        //     'result' => $result,
        // ]);
    }

    public function update(Request $request)
    {
        $result = false;
        $update = $request->only('display_name', 'account_name');
        // if(Auth::update()){
        //     $user = Auth::user();
        // }

        // dd($update['account_name']);

        // $user = User::find($update['account_name']);
        // $user->display_name = $update['display_name'];
        // $user->save();

        User::where('account_name', $update['account_name'])
            ->update([
                'display_name' => $update['display_name']
            ]);
        return;
    }
}
