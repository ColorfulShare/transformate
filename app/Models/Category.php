<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['title', 'slug', 'icon', 'color', 'image'];

    public function subcategories(){
        return $this->hasMany('App\Models\Subcategory');
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
