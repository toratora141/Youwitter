<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserInputPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function fetchProf()
    {
        $result = false;
        $user = Auth::user();
        $fetch = User::where('account_name', $user['account_name'])
            ->with('videoLists.videos')
            ->get();
        $result = true;
        return response()->json(['result' => $result, 'user' => $user, 'videoLists' => $fetch[0]->videoLists]);
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
