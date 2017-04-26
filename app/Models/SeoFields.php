<?php namespace App\Models;

use App\Models\SeoFieldsNode;
use Illuminate\Database\Eloquent\Model;
use Ibec\Translation\HasNode;
use Ibec\Translation\Nodeable;

class SeoFields extends Model implements Nodeable {

    use HasNode;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'seo_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Date casts
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Eager loaded relations
     *
     * @var array
     */
    protected $with = [];

    /**
     * @var array
     */
    protected $hidden = ['nodes'];


    /**
     * @return  string
     */
    public function getNodeClass()
    {
        return SeoFieldsNode::class;
    }
}
