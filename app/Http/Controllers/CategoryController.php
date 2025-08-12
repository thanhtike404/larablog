<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $categories = Category::all();
        // dd($categories);
        return view('categories', ['categories' => $categories]);
    }
}
