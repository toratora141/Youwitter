<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class SuggestUsers extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $users = User::limit(5)
            ->with('videoLists.videos.good')
            ->has('videoLists')
            ->get();
        return response()->json(['suggestUsers' => $users]);
    }
}
