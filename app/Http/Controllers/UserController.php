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
        // return new JsonResponse([
        //     'user' => $user,
        //     'result' => $result,
        // ]);
    }

    public function update(Request $request)
    {

        $user = $request->only('account_name', 'display_name', 'icon_base64');
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
        return response()->json(['result' => $result, 'path' => $path, 'display_name' => $user['display_name']]);
    }

    public function setIcon(Request $request, $storage)
    {

        return response()->json(['result' => true]);
    }
}
