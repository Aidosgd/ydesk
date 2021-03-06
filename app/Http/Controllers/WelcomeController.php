<?php

namespace App\Http\Controllers;

use Ibec\Content\CategoryNode;
use Ibec\Content\Post;
use Ibec\Media\Gallery;

class WelcomeController extends Controller
{
    public function index()
    {
        $first_block = Post::whereHas('moderations', function ($q){
            $q->where('status', 1);
        })->whereHas('nodes', function ($q){
            $q->where('slug', 'first-block');
        })->first();

        $second_block = Post::whereHas('moderations', function ($q){
            $q->where('status', 1);
        })->whereHas('nodes', function ($q){
            $q->where('slug', 'second-block');
        })->first();

        $third_block = Post::whereHas('moderations', function ($q){
            $q->where('status', 1);
        })->whereHas('nodes', function ($q){
            $q->where('slug', 'third-block');
        })->first();

        $fourth_block = Post::whereHas('moderations', function ($q){
            $q->where('status', 1);
        })->whereHas('nodes', function ($q){
            $q->where('slug', 'fourth-block');
        })->first();

        $newsCategory = CategoryNode::where('slug', 'news')->first();
        $news = Post::whereHas('nodes', function($q)
        {
            $q->whereNotNull('title');
            $q->where(['language_id' => \App::getLocale()]);
        })->where('category_id', $newsCategory->category_id)->whereHas('moderations', function($q){
            $q->where('status', 1);
        });

        $news = $news->orderBy('display_date', 'desc')->take(10)->get();

        return view('welcome', compact('first_block', 'second_block', 'third_block', 'fourth_block', 'news'));
    }
}
