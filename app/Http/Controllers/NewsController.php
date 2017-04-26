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

        if($post->node->fields->post_url){

            $sites_html = file_get_contents($post->node->fields->post_url);

            $html = new \DOMDocument();
            @$html->loadHTML($sites_html);
            $meta_og = [
                'title' => '',
                'description' => '',
                'image' => '',
            ];

            foreach($html->getElementsByTagName('meta') as $meta) {
                if($meta->getAttribute('property')=='og:title'){
                    $meta_og['title'] = $meta->getAttribute('content');
                }
                if($meta->getAttribute('property')=='og:description'){
                    $meta_og['description'] = $meta->getAttribute('content');
                }
                if($meta->getAttribute('property')=='og:image'){
                    $meta_og['image'] = $meta->getAttribute('content');
                }
            }

            return view('news.show', compact('post', 'seo', 'meta_og'));
        }

        return view('news.show', compact('post', 'seo'));
    }
}
