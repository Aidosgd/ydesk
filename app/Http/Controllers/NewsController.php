<?php

namespace App\Http\Controllers;

use Ibec\Content\CategoryNode;
use Ibec\Content\Post;

class NewsController extends Controller
{
    public function index()
    {
        $newsCategory = CategoryNode::where('slug', 'news')->first();
        $news = Post::whereHas('nodes', function($q)
        {
            $q->whereNotNull('title');
            $q->where(['language_id' => \App::getLocale()]);
        })->where('category_id', $newsCategory->category_id)->whereHas('moderations', function($q){
            $q->where('status', 1);
        });

        $seo = [
            'title' => 'News',
            'description' => '',
            'keywords' => '',
        ];

        $news = $news->orderBy('display_date', 'desc')->take(5)->get();

        return view('news.index', compact('news', 'seo'));
    }

    public function show($lang, $id)
    {
        $post = Post::whereHas('moderations', function ($q){
            $q->where('status', 1);
        })->where('id', $id)->first();

        $seo = [
            'title' => $post->node->seo_title,
            'description' => $post->node->seo_description,
            'keywords' => $post->node->seo_keywords,
        ];

        return view('news.show', compact('post', 'seo'));
    }
}
