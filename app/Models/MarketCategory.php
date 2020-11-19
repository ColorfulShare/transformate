<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketCategory extends Model
{
    protected $table = 'market_categories';

    protected $fillable = ['title', 'slug', 'icon', 'color'];

    public function subcategories(){
        return $this->hasMany('App\Models\MarketSubcategory');
    }

    public function products(){
        return $this->hasMany('App\Models\MarketProduct', 'category_id', 'id');
    }
}
