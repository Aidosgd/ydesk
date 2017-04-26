<?php namespace App\Http\Middleware;

use App\Models\CustomUser\User;
use Closure;

class LocalesRedirect {

    private $excluded_paths = [
        'admin',
        '_debugbar',
        'socialite'
    ];

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$segment = app()['request']->segment(1);

		if (!in_array($segment, $this->excluded_paths))
		{
			if (!in_array($segment, config('app.locales')))
				return redirect('/' . app()->getLocale() . '/' . app()['request']->path());
		}
		else{
			app()->setLocale(config('app.locales')[0]);
		}

		return $next($request);
	}

}
