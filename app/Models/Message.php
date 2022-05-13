<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'text',
    ];

    // public function fetchMessages($otherAccountName = null, $myAccountName)
    // {
    //     return Message::where('user_id', $otherAccountName)
    //         ->where('messaged_user_id', $myAccountName)
    //         ->orWhere(function ($query) use ($otherAccountName, $myAccountName) {
    //             $query->where('user_id', $myAccountName)
    //                 ->where('messaged_user_id', $otherAccountName);
    //         })
    //         ->with('user', 'messagedUser')
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    // }

    // public function fetchUserList($accountName)
    // {
    //     return Message::where('messaged_user_id', $accountName)
    //         ->orwhere('user_id', $accountName)
    //         ->with('user', 'messagedUser')
    //         ->get();
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'account_name');
    }

    public function preparePram($myAccountName, $roomId, $messageText)
    {
        return [
            'user_id' => $myAccountName,
            'room_id' => $roomId,
            'text' => $messageText,
        ];
    }
}
