<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'WelcomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

$router->get('news', 'NewsController@index');
$router->get('news/{id}', 'NewsController@show');

$router->get('pages', 'PagesController@index');
$router->get('pages/{slug}', 'PagesController@show');