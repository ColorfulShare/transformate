<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'shopping_cart';

    protected $fillable = ['user_id', 'course_id', 'certification_id', 'podcast_id', 'membership_id', 
        'product_id', 'instructor_code', 'partner_code', 'gift', 'date'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

    public function certification(){
        return $this->belongsTo('App\Models\Certification');
    }

    public function podcast(){
        return $this->belongsTo('App\Models\Podcast');
    }

    public function membership(){
        return $this->belongsTo('App\Models\Membership');
    }

    public function market_product(){
        return $this->belongsTo('App\Models\MarketProduct', 'product_id');
    }

    public function partner(){
        return $this->belongsTo('App\Models\User', 'partner_code', 'id');
    }
}
