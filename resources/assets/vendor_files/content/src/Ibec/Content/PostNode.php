<?php namespace Ibec\Content;

use Illuminate\Database\Eloquent\Model;
use Ibec\Translation\LanguageDependent;
use Illuminate\Support\Fluent;

class PostNode extends Model {

	use LanguageDependent;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'post_nodes';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'post_id';

	/**
	 * Indicates if the IDs are not auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['post_id', 'language_id', 'slug', 'title', 'teaser', 'content', 'fields', 'seo_description', 'seo_title', 'seo_keywords'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'fields' => 'object',
	];

	/**
	 * Attributes and relations hidden
	 *
	 * @var array
	 */
	protected $hidden = [
		'source'
	];

	/**
	 * Cached field config
	 *
	 * @var array
	 */
	protected $configCache = null;

	/**
	 * Cached field values
	 *
	 * @var array
	 */
	protected $fieldCache = null;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function source()
	{
		return $this->belongsTo('Ibec\Content\Post', 'post_id');
	}

	/*protected function loadConfigCache()
	{
		$configCache = $this->source->contentRoot->fields;

		$fieldCache = array_only(
			(array) $this->castAttribute('fields', $this->attributes['fields']),
			array_keys($configCache)
		);

		$this->casts = array_merge($this->casts, array_dot(['field' => $configCache]));

		array_walk($fieldCache, function(&$item, $key) use ($configCache)
		{
			$type = $configCache[$key];
			$item = ($type == 'date') ? $this->asDateTime($item) : $this->castAttribute('field.'.$key, $item);
		});

		$this->fieldCache = new Fluent($fieldCache);
		$this->configCache = $configCache;
	}*/

	/**
	 * Cast fields to types
	 *
	 * @return array
	 */
	/*public function getFieldsAttribute()
	{
		if ( ! $this->configCache)
		{
			$this->loadConfigCache();
		}

		return $this->fieldCache;
	}*/

	/**
	 * Get an attribute from the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);

		if ($value) return $value;

	}

	public function setSlugAttribute($value){
		$this->attributes['slug'] = substr($value, 0, 59);
	}
}
