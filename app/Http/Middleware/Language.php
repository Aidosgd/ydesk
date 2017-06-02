<?php namespace App\Http\Middleware;

use Closure;

class Language {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$path = explode('/', $request->path());

		if(isset($path[0]))
		{
            $lang = $path[0];
            if(in_array($lang, config('app.locales')))
            {
                app()->setLocale($lang);
			}
		}

		return $next($request);
	}

}
