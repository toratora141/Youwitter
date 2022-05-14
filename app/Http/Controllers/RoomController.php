<?php

namespace App\Http\Controllers;


use App\Models\Room;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    private $myAccountName;

    public function __construct()
    {
        $this->myAccountName = Auth::user()->account_name;
    }

    public function fetch(Request $request)
    {
        if (!empty($roomId = $request->input('roomId'))) {
            $room = Room::where('room_id', $roomId)
                ->with('userList.user', 'message.user')
                ->first();
            return response()->json(['room' => $room]);
        }

        $rooms = Room::fetch($request, $this->myAccountName);

        return response()->json(['rooms' => $rooms]);
    }

    public function create(Request $request)
    {
        if (!empty($room = Room::searchOneRoom($request))) {
            return response()->json(['room' => $room]);
        }

        $paramForRoom = Room::preparePramForNewRoom();
        $paramForUserList = UserList::preparePramForNewUserList($request);
        $newRoom = Room::create($paramForRoom);
        $newRoom->userList()->createMany($paramForUserList);

        $room = Room::where('room_id', $newRoom->room_id)
            ->with('userList.user')
            ->first();

        return response()->json(['room' => $room]);
    }
}
