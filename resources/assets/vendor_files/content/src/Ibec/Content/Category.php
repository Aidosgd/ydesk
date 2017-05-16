<?php namespace Ibec\Content;

use Baum\Node as Model;
use Ibec\Admin\Presentable;
use Ibec\Media\HasImage;
use Ibec\Translation\HasNode;
use Ibec\Translation\Nodeable;
use McCool\LaravelAutoPresenter\HasPresenter;

class Category extends Model implements Nodeable, HasPresenter {

	use HasNode, HasImage, Presentable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

	/**
	 * @var array
	 */
	protected $hidden = ['nodes'];

	/**
	 * Relation to aliases.
	 *
	 * Renamed to contentRoot for compatibility with Baum
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function contentRoot()
	{
		if (is_null($this->getParentId()))
		{
			return $this->hasOne('Ibec\Content\Root', 'id', 'id');
		}
		else
		{
			return $this->getRoot()->contentRoot();
		}
	}

	/**
	 * Apply alias scope to query.
	 *
	 * @example  Category::byAlias($alias)->first()
	 *
	 * @param  $query
	 * @param  $slug
	 *
	 * @return  mixed
	 */
	public function scopeBySlug($query, $slug)
	{
		return $query->whereHas('alias', function($query) use ($slug)
		{
			$query->whereSlug($slug);
		});
	}

	/**
	 * @return  string
	 */
	public function getNodeClass()
	{
		return 'Ibec\Content\CategoryNode';
	}

	/**
	 * @param string $indent
	 * @param null $root
	 * @param array $excluded
	 * @return array
	 */
	public static function getIdentedList($indent = '-', $root = null, array $excluded = [])
	{

        if ($root && ( ! $root instanceof static))
        {
            $root = static::roots()->findOrFail($root);
        }

		$categories = ($root) ? $root->descendantsAndSelf(['id', 'depth']) : static::newQuery();

		if ($excluded)
		{
			foreach ($excluded as &$id)
			{
				if ( ! $id instanceof static)
				{
					$id = static::find($id);
				}
				$id = $id->descendantsAndSelf()->pluck('id');
			}

			$categories->whereNotIn('id', array_flatten($excluded));
		}

		$categories = $categories->with('nodes')->get(['id', 'depth']);

		$categoryList = [];

		$categories->each(function($item) use (&$categoryList, $indent)
		{
			$title = ($item->node) ? $item->node->title : $item->id;
			$categoryList[$item->id] = str_repeat($indent, $item->depth) . ' ' . $title;
		});

		return $categoryList;
	}

	/**
	 * @param array $excluded
	 *
	 * @return array
	 */
	public static function getRootList(array $excluded = [])
	{
		$categories = static::roots();

		if ($excluded)
		{
			$categories->whereNotIn('id', $excluded);
		}

		$categories = $categories->get(['id']);
		$categoryList = [];

		$categories->each(function($item) use (&$categoryList)
		{
			$title = ($item->node) ? $item->node->title : $item->id;
			$categoryList[$item->id] = $title;
		});

		return $categoryList;
	}

	public function posts()
	{
		return $this->hasMany('Ibec\Content\Post');
	}
}