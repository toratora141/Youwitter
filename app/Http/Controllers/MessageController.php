<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // public function fetch(Request $request)
    // {
    //     $messages = Message::fetchMessages($request->input('otherAccountName'), Auth::user()->account_name);

    //     return response()->json(['messages' => $messages]);
    // }

    // public function userList(Request $request)
    // {
    //     $userList = Message::fetchUserList(Auth::user()->account_name);

    //     return response()->json(['userList' => $userList]);
    // }

    public function create(Request $request)
    {
        $param = Message::preparePram(Auth::user()->account_name, $request->roomId, $request->messageText);
        $newMessage = Message::create($param);

        // $messages = Message::fetchMessages($request->input('otherAccountName'), Auth::user()->account_name);
        $messages = Message::where('room_id', $newMessage->room_id)
            ->with('user')
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
