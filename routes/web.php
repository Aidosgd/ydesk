<?php


foreach(array_merge([''], config('app.locales')) as $language) {
    Route::group([
        'middleware' => 'lang',
        'prefix' => $language,
    ], function () {

        Route::get('/', 'WelcomeController@index');

        Auth::routes();

        Route::get('/home', 'HomeController@index');

        Route::get('news', 'NewsController@index');
        Route::get('news/{id}', 'NewsController@show');

        Route::get('pages', 'PagesController@index');
        Route::get('pages/{slug}', 'PagesController@show');
    });
}