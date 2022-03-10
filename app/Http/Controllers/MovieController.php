<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieListCreate;
use App\Models\MovieList;
use App\Http\Requests\MovieListInput;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class MovieController extends Controller
{
    public function listCreate(MovieListCreate $request)
    {
        return MovieList::create($request->all());
    }
}
