<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index() {
        $categories = Category::all();
        $posts = Post::latest()->take(6)->get();
        return view('welcome', compact('categories', 'posts'));
    }
}
