<?php

Route::model('seo', 'App\Models\SeoFields');
Route::resource('seo', 'Admin\SeoController', ['as' => config('admin.uri')] );

Route::model('font', 'App\Font');
Route::resource('font', 'Admin\FontController', ['as' => config('admin.uri')] );