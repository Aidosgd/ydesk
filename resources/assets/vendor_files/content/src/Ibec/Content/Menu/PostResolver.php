<?php namespace Ibec\Content\Menu;

use Ibec\Content\Post;
use Ibec\Menu\Resolvers\ResolverContract;

class PostResolver implements ResolverContract {

	/**
	 * Get default link value
	 *
	 * @return string
	 */
	public function getDefaultParams()
	{
		return ['type' => 'post', 'id' => null];
	}

	/**
	 * Get form fields
	 *
	 * @return array
	 */
	public function getFormFields()
	{
		return view('content::_menu.posts', ['url' => admin_route('content.posts.json')])->render();
	}

	/**
	 * Process link attributes to URL
	 *
	 * @param $link
	 * @return mixed
	 */
	public function getLink($link)
	{
		return '/posts/'.array_get($link, 'id', 'undefined');
	}

}