<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'subcategories';

    protected $fillable = ['category_id', 'title'];

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function courses(){
    	return $this->hasMany('App\Models\Course');
    }

    public function certifications(){
    	return $this->hasMany('App\Models\Certification');
    }

    public function podcasts(){
        return $this->hasMany('App\Models\Podcast');
    }

    public function products(){
        return $this->hasMany('App\Models\MarketProduct');
    }
}
