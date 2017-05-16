<?php namespace Ibec\Content;

use Carbon\Carbon;
use Ibec\Admin\Presentable;
use Ibec\Media\HasFile;
use Ibec\Social\Comments\Commentable;
use Ibec\Social\Hits\Hitable;
use Ibec\Social\Likes\Likeable;
use Ibec\Social\Moderation\Moderateable;
use Ibec\Social\Rates\Rateable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ibec\Translation\HasNode;
use Ibec\Translation\Nodeable;
use Ibec\Media\HasImage;
use Ibec\Taxonomy\HasTags;
use McCool\LaravelAutoPresenter\HasPresenter;


class Post extends Model implements Nodeable, HasPresenter {

	use HasNode, HasTags, HasFile, HasImage, Hitable, Commentable, Rateable, Likeable, Moderateable;
	use Presentable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['root_id', 'user_id', 'category_id', 'meta', 'image_id', 'display_date', 'newsletter_enabled'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'meta' => 'json',
	];

	/**
	 * Date casts
	 *
	 * @var array
	 */
	protected $dates = [
		'deleted_at', 'published_from', 'published_till', 'display_date'
	];

	/**
	 * Eager loaded relations
	 *
	 * @var array
	 */
	protected $with = ['contentRoot'];

	/**
	 * @var array
	 */
	protected $hidden = ['nodes'];

	/**
	 * Relation to category
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo('Ibec\Content\Category');
	}

    public function paginate()
    {
        return 'sdfsdf';
	}

	/**
	 * Relation to root
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function contentRoot()
	{
		return $this->belongsTo('Ibec\Content\Root', 'root_id', 'id');
	}

	/**
	 * Relation to post author
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo(config('auth.providers.admins.model'), 'user_id');
	}

	/**
	 * @return  string
	 */
	public function getNodeClass()
	{
		return 'Ibec\Content\PostNode';
	}

	/**
	 *
	 */
	public function setDisplayDateAttribute($value)
	{
		if($value)
		$this->attributes['display_date'] = Carbon::createFromFormat('d-m-Y H-i', $value);
	}

	/**
	 * @param Builder $query
	 * @param null $date
	 */
	public function scopePublished(Builder $query, $date = null)
	{
		if (is_null($date))
		{
			$date = Carbon::now();
		}

		$query->where('published_from', '>=', $date);
		$query->where('published_till', '<', $date);
	}


}

