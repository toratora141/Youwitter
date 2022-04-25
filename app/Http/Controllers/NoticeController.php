<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function fetch(Request $request)
    {
        $notices = Notice::where('user_id', Auth::user()->account_name)
            ->with('good.video', 'follow.user', 'user')
            ->get();
        return response()->json(['result' => true, 'notices' => $notices]);
    }
}
