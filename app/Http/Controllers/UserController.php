<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Http\Requests\UserInputPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(UserInputPost $request)
    {
        $param = User::prepareParamForRegister($request);

        $user = User::create($param);
        Auth::attempt($param, true);
        return response()->json(['result' => true, 'user' => $user]);
    }

    public function fetchProf(Request $request)
    {
        $result = false;
        $isFollow = false;
        $myUser = Auth::user();

        $fetch = User::fetchUserWithRelation($accountName = $request->input('accountName'));
        $user = User::prepareUserForResponse($fetch[0]->account_name, $fetch[0]->display_name, $fetch[0]->icon,);
        // フォローしているかチェックするために、マイページかどうかチェック
        // boolean型で渡せず、文字列でしか渡せなかったので文字列でのチェック
        if ($request->input('isMyProfile') === 'false') {
            $fetchFollow = User::checkFollowing($accountName, $myUser['account_name']);
        }
        if (isset($fetchFollow)) {
            $isFollow = true;
        }

        $result = true;

        $response = User::prepareResponseForFetch($result, $user, $fetch[0]->videoLists, $isFollow);

        return response()->json($response);
    }

    public function searchUser(Request $request)
    {
        $users = User::searchLike($request->input('searchKeyword'));
        return response()->json(['users' => $users]);
    }

    public function update(Request $request)
    {
        try {

            //Todo: Userクラス関数を作成
            $user = $request->only('account_name', 'display_name', 'icon_base64');
            $user['account_name'] = Auth::user()->account_name;
            preg_match('/data:image\/(\w+);base64,/', $user['icon_base64'], $matches);
            $extention = $matches[1];

            $img = preg_replace('/^data:image.*base64,/', '', $user['icon_base64']);
            $img = str_replace(' ', '+', $img);
            $fileData = base64_decode($img);

            $dir = rtrim($user['account_name'], '/') . '/';
            $fileName = md5($img);
            $path = $dir . $fileName . '.' . $extention;

            Storage::disk('public')->put($path, $fileData);

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
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['result' => false, 'user' => $updateUser]);
        }
        return response()->json(['result' => $result, 'user' => $updateUser]);
    }

    public function setIcon(Request $request, $storage)
    {

        return response()->json(['result' => true]);
    }
}
