<?php

namespace App\Http\Controllers;

use Ibec\Content\CategoryNode;
use Ibec\Content\Post;

class PagesController extends Controller
{
    public function index()
    {
        $newsCategory = CategoryNode::where('slug', 'info_pages')->first();
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

        return view('pages.index', compact('news', 'seo'));
    }

    public function show($lang, $slug)
    {
        $page = Post::whereHas('nodes', function($q) use ($slug)
        {
            $q->whereNotNull('title');
            $q->where(['language_id' => \App::getLocale()]);
            $q->where('slug', $slug);
        })->whereHas('moderations', function($q){
            $q->where('status', 1);
        })->first();

        $seo = [
            'title' => $page->node->seo_title,
            'description' => $page->node->seo_description,
            'keywords' => $page->node->seo_keywords,
        ];

        return view('pages.show', compact('page', 'seo'));
    }
}
