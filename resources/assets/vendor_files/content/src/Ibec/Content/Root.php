<?php namespace Ibec\Content;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Root
 * @package Ibec\Content
 *
 * @property-read  \Ibec\Content\Category  $category Related category
 */
class Root extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roots';

	/**
	 * Use slug as primary key.
	 *
	 * @var string
	 */
	protected $primaryKey = 'slug';

	/**
	 * Indicates if the IDs are not auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * The model is not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id', 'slug'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'title' => 'array',
		'config' => 'array',
	];

	/**
	 * Relation to category
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo('Ibec\Content\Category', 'id');
	}

	/**
	 * Title accessor
	 *
	 * @return string
	 */
	public function getLocaleTitleAttribute()
	{
		$locale = app()->getLocale();
		$fallback = config('app.fallback_locale');

		$title = $this->castAttribute('title', $this->attributes['title']);

		if (isset($title[$locale]))
		{
			return $title[$locale];
		}
		elseif (isset($title[$fallback]))
		{
			return $title[$fallback];
		}

		return ucfirst($this->slug);
	}

	/**
	 * @var null|\Illuminate\Support\Collection
	 */
	protected static $loaded = null;

	/**
	 * Get loaded roots
	 *
	 * @param null $slug
	 * @return \Illuminate\Support\Collection|mixed|null
	 */
	public static function getLoaded($slug = null)
	{
		if (is_null(static::$loaded))
		{
			static::$loaded = static::query()->with('category.nodes')->get()->keyBy('slug');
		}
		return (is_null($slug)) ? static::$loaded : static::$loaded->get($slug);
	}


	public	function terms()
	{
		return $this->hasMany('Ibec\Taxonomy\Term','root_id', 'id');
	}

	public	function fields()
	{
		return $this->morphMany('Ibec\Admin\Fields\Field', 'fieldable', null, null, 'id');
	}

	/**
	 * Get config value
	 *
	 * @param $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function config($key, $default = null)
	{
		return array_get($this->getAttributeValue('config'), $key, $default);
	}
}
