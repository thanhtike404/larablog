<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

abstract class Controller
{
    //
    public function index()
    {
        $posts = BlogPost::all();
        dd($posts);
        return view('posts', ['posts' => $posts]);
    }
}
