<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        $param = Message::preparePram(Auth::user()->account_name, $request->roomId, $request->messageText);
        $newMessage = Message::create($param);

        $messages = Message::where('room_id', $newMessage->room_id)
            ->with('user')
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
