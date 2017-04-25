<?php

namespace App\Http\Controllers;

use Ibec\Content\Post;

class NewsController extends Controller
{
    public function index()
    {
        $news = Post::whereHas('contentRoot', function($q){
            $q->where('slug', 'news');
        })->with('images')->get();

        dd($news);

        return view('news.index', compact('news'));
    }
}
