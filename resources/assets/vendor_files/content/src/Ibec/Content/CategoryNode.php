<?php namespace Ibec\Content;

use Illuminate\Database\Eloquent\Model;
use Ibec\Translation\LanguageDependent;

class CategoryNode extends Model {

	use LanguageDependent;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category_nodes';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'category_id';

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
	protected $fillable = ['category_id', 'language_id', 'title', 'description', 'seo_description', 'seo_title', 'seo_keywords', 'slug','published'];

	/**
	 * Attributes and relations hidden.
	 *
	 * @var array
	 */
	protected $hidden = [
		'source'
	];

	/**
	 * Cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'published' => 'boolean'
	];

	public function source()
	{
		return $this->belongsTo(Category::class, 'category_id');
	}
}