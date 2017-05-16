<?php namespace Ibec\Content;

use Ibec\Content\Menu\PostResolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class ContentServiceProvider extends ServiceProvider {

	/**
	 * Controllers base namespace
	 *
	 * @var string
	 */
	protected $namespace = 'Ibec\Content\Http\Controllers';

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$baseDir = __DIR__.'/../../../';

		$this->loadViewsFrom($baseDir . 'resources/views', 'content');
		$this->loadTranslationsFrom($baseDir . 'resources/lang', 'content');

		//Publish configuration
		$this->publishes([
			 $baseDir . 'config/content.php' => config_path('content.php'),
		], 'config');

		//Publish migrations
		$this->publishes([
			 $baseDir . 'database/migrations/' => base_path('/database/migrations'),
		], 'migrations');

		$this->bootRoutes();

		if ($this->app->bound('menu.resolver'))
		{
			$this->app['menu.resolver']->extend('posts', function()
			{
				return new PostResolver();
			});
		}
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

	/**
	 * Load service routes
	 *
	 * @return void
	 */
	protected function bootRoutes()
	{
		$group = [
			'namespace' => $this->namespace,
			'prefix' => $this->app['config']->get('admin.uri', 'admin') . '/content',
			'middleware' => $this->app['config']->get('admin.middlewares'),
		];

		$this->app['router']->group($group, function(Router $router)
		{
			require __DIR__ . '/Http/routes.php';
		});
	}

}