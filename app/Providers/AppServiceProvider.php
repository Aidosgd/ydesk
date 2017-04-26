<?php

namespace App\Providers;

use Ibec\Menu\Database\Menu;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Factory $view)
    {
        $menu = Menu::with('children')->find(1);

        $main_menu = $menu
            ->descendants()
            ->with('linkable')
            ->get()
            ->toHierarchy();

        $view->share('main_menu', $main_menu);

        $seo_fields = SeoFields::first();

        app()['seo_fields'] = $seo_fields;

        $seo = [
            'title' => $seo_fields->node->title,
            'description' => $seo_fields->node->description,
            'keywords' => $seo_fields->node->keywords
        ];

        $view->share(compact('seo'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
