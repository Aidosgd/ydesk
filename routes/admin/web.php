<?php

Route::model('seo', 'App\Models\SeoFields');
Route::resource('seo', 'Admin\SeoController', ['as' => config('admin.uri')] );

Route::model('font', 'App\Font');
Route::resource('font', 'Admin\FontController', ['as' => config('admin.uri')] );


$router->group([], function($router)
{
    $router->resource('roots.posts', 'Admin\PostsController', ['as' => config('admin.uri').'.content']);
});

$router->get('posts.json', [
    'as' => admin_prefix('content.posts.json'),
    function()
    {
        return \Ibec\Content\Post::with('category')->get()->toJson();
    }
]);

$router->post('roots/{roots}/posts/test', 'Admin\PostsController@addArticles');
$router->post('roots/{roots}/posts/{id}/test', 'Admin\PostsController@addArticles');