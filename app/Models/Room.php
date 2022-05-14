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
    public function messageOrderBy()
    {
        return $this->hasMany(Message::class, 'room_id')->orderBy('created_at', 'desc');
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
            ->with('userList.user', 'messageOrderBy.user')
            ->get();

        return $rooms;
    }

    /*
     * @param Request $request
     * @return App\Http\Controller\RoomController Object $room
     */
    public function searchOneRoom($request)
    {
        //パラメータで渡されたユーザ以外のユーザが存在しないレコードを探す
        $userLists = UserList::select('room_id')
            ->whereNotExists(function ($query) use ($request) {
                $query->select()
                    ->from('user_lists')
                    ->whereIn('user_id', $request->users, 'and', $not = true);
            });
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
