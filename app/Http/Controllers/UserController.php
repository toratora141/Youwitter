<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Http\Requests\UserInputPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;

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
    }

    public function fetchProf(Request $request)
    {
        $result = false;
        $isfollow = false;
        $myUser = Auth::user();
        //ToDo:
        // 変数名
        // followモデルにメソッドを作って、userモデルから呼び出し。全部移動
        $fetch = User::where('account_name', $request->input('accountName'))
            ->with('videoLists.videos')
            ->get();
        $user = [
            'account_name' => $fetch[0]->account_name,
            'display_name' => $fetch[0]->display_name,
            'icon' => $fetch[0]->icon,
        ];
        $followed = User::where('account_name', $request->input('accountName'))
            ->with('follows')
            ->get();
        $follower = Follow::where('follower', $request->input('accountName'))
            ->get();
        $follows = [
            'follower' => $follower,
            'followed' => $followed
        ];
        //マイプロフィールかどうかでDBからのfetchを変える
        // $myProfile = (bool)($request->input('myProfile'));
        // dd($myProfile);
        if ($request->input('myProfile') === 'false') {
            $fetchFollow = Follow::where('user_id', $request->input('accountName'))
                ->where('follower', $myUser['account_name'])
                ->first();
        }
        if (isset($fetchFollow)) {
            $isfollow = true;
        }
        // dd($fetch[0]->videoLists);
        $result = true;
        return response()->json(['result' => $result, 'user' => $user, 'videoLists' => $fetch[0]->videoLists, 'follows' => $follows, 'isFollow' => $isfollow]);
    }

    public function fetchUser(Request $request)
    {
        $fetch = User::where('account_name', $request->input('account_name'))
            ->with('videoLists.videos')
            ->get();
        $result = true;
        // dd($fetch);
        return response()->json(['result' => $result, 'user' => $fetch[0], 'videoLists' => $fetch[0]->videoLists]);
    }

    public function searchUser(Request $request)
    {
        $keyword = $request->input('searchKeyword');
        $pat = '%' . addcslashes($keyword, '%_\\') . '%';
        $users = User::where('account_name', 'LIKE', $pat)
            ->get();
        // dd($keyword);
        return response()->json(['users' => $users]);
    }

    public function update(Request $request)
    {

        //Todo: Userクラス関数を作成
        $user = $request->only('account_name', 'display_name', 'icon_base64');
        $user['account_name'] = Auth::user()->account_name;
        preg_match('/data:image\/(\w+);base64,/', $user['icon_base64'], $matches);
        $extention = $matches[1];

        $img = preg_replace('/^data:image.*base64,/', '', $user['icon_base64']);
        $img = str_replace(' ', '+', $img);
        $file_data = base64_decode($img);

        $dir = rtrim($user['account_name'], '/') . '/';
        $fileName = md5($img);
        $path = $dir . $fileName . '.' . $extention;

        Storage::disk('public')->put($path, $file_data);

        User::where('account_name', $user['account_name'])
            ->update([
                'display_name' => $user['display_name'],
                'icon' => $path
            ]);

        $result = true;
        $updateUser = [
            'account_name' => Auth::user()->account_name,
            'display_name' => $user['display_name'],
            'icon' => '/storage/' . $path
        ];
        return response()->json(['result' => $result, 'user' => $updateUser]);
    }

    public function setIcon(Request $request, $storage)
    {

        return response()->json(['result' => true]);
    }
}
