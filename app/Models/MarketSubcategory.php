<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketSubategory extends Model
{
    protected $table = 'market_subcategories';

    protected $fillable = ['category_id', 'title', 'slug', 'icon', 'color'];

    public function category(){
        return $this->belongsTo('App\Models\MarketCategory');
    }
    public function products(){
        return $this->hasMany('App\Models\MarketProduct', 'subcategory_id', 'id');
    }
}
