<?php namespace Ibec\Content\Http\Controllers;


use App\Post;
use Ibec\Admin\Http\Controllers\Controller;
use Illuminate\Database\DatabaseManager;

class DashboardController extends Controller {

	public function getIndex(DatabaseManager $db)
	{
		$posts = Post::query();
		$posts->leftJoin('hits', function($join)
		{
			$join->on('hits.hitable_id', '=', 'posts.id')
				->where('hits.hitable_type', '=', 'Ibec\Content\Post');
		});
		$posts->groupBy('posts.id');
		$posts->orderBy('chart_param', 'desc');
		$posts = $posts->get(['posts.*', $db->raw('COUNT(*) as `chart_param`')]);

		return view('content::dashboard', ['posts' => $posts]);
	}

}