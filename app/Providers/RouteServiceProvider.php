<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapApiRoutes();

//        dd(app()['routes']->get('GET'));

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $router = $this->app['router'];

        $segment = $this->app['request']->segment(1);

        if($segment == 'admin'){

            $router->group([
                'namespace' => $this->namespace,
                'prefix' => 'admin',
                'middleware' => config('admin.middlewares')], function($router)
            {
                require base_path('routes/admin/web.php');
            });

            return;
        }

//        $curLocale = in_array($segment, config('app.locales'))
//            ? $segment
//            : config('app.locales', [])[0];
//
//        $router->bind('lang', function($lang) use ($curLocale)
//        {
//            return $curLocale;
//        });
//
//        $this->app->setLocale($curLocale);
//
//        $this->app['view']->share('lang', 'en');

//        $group = [
//            'namespace' => $this->namespace,
//            'prefix' => '{lang?}',
//            'middleware' => ['web']
//        ];
//
//        Route::group($group, function ($router) {
//            require base_path('routes/web.php');
//        });

        $this->app['view']->share('lang', 'en');

        Route::group(['namespace' => $this->namespace], function($router)
        {
            require app_path('../routes/web.php');
        });

//        Route::group(['namespace' => $this->namespace, 'prefix' => 'admin'], function($router)
//        {
//            require app_path('../routes/admin/web.php');
//        });

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
