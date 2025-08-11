<?php

namespace App\Http\Controllers;

use App\Models\BlogPost; // Assuming you have a Post model
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index()
    {

        $posts = BlogPost::all();
        // dd($posts); // This will dump the posts and stop execution
        return view('posts', [
            'posts' => $posts,
        ]);
    }
}
