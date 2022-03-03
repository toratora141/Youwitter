<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserInputPost;

class UserController extends Controller
{
    public function register(UserInputPost $request)
    {
        return User::create($request->all());
    }
}
