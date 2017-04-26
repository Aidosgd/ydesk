<?php

Route::model('seo', 'App\Models\SeoFields');
Route::resource('seo', 'Admin\SeoController', ['as' => config('admin.uri')] );