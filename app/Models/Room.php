<?php

namespace App\Models;

use Google\Service\Bigquery\Resource\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\DB;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';
    protected $fillable = [
        'type'
    ];

    //user_listsテーブルとのリレーション
    public function userList()
    {
        return $this->hasMany(UserList::class, 'room_id');
    }

    //user_listsテーブルとのリレーション
    public function message()
    {
        return $this->hasMany(Message::class, 'room_id');
    }

    /*
     * @param Reuqest $reuqest
     * @param string $myAccountName
     *
     * @return RoomController fetch()
     */
    public function fetch($request, $myAccountName)
    {
        $userLists = UserList::select('room_id')
            ->where('user_id', $myAccountName);
        $rooms = Room::whereIn('room_id', $userLists)
            ->with('userList.user', 'message.user')
            ->get();;

        return $rooms;
    }

    public function checkExisting($request)
    {
        return self::searchOneRoom($request);
    }
    /*
SELECT *
from rooms r
inner join user_lists ul_a
on r.room_id = ul_a.room_id
where (user_id = 'user1' or user_id = 'user2')
and not exists(
	select *
    from user_lists ul_b
    where ul_a.room_id = ul_b.room_id
    and not ul_b.user_id = 'user1'
    and not ul_b.user_id = 'user2'
);
 */

    public function searchOneRoom($request)
    {
        $userLists = UserList::select('room_id')
            ->from('user_lists as userListA')
            ->whereIn('user_id', $request->users, 'and')
            ->whereNotExists(function ($query) use ($request) {
                $query->select()
                    ->from('user_lists as userListB')
                    ->where('userListB.room_id', 'userListA.room_id')
                    ->whereIn('user_id', $request->users, 'and', $not = true);
            });
        // foreach ($request->users as $user) {
        //     $userLists->whereExists(function ($query) use ($user) {
        //         $query->where('user_id', $user);
        //     });
        // }
        $room = Room::whereIn('room_id', $userLists)
            ->with('userList.user')
            ->first();
        return $room;
    }

    public function preparePramForNewRoom()
    {
        return [
            'type' => 'message'
        ];
    }
}
