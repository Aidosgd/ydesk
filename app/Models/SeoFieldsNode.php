<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ibec\Translation\LanguageDependent;
use Illuminate\Support\Fluent;

class SeoFieldsNode extends Model {

    use LanguageDependent;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'seo_fields_nodes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'seo_fields_id';

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
    protected $fillable = ['seo_fields_id', 'language_id', 'title', 'description', 'keywords'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

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
        return $this->belongsTo(SeoFields::class, 'seo_fields_id');
    }


    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($value) return $value;

    }

}
