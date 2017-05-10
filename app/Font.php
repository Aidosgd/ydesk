<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    protected $table = 'fonts';
    
    protected $fillable = [
        'font_url', 'font_size', 'font_family',
    ];
}
