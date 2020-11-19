<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $table = 'gifts';

    protected $fillable = ['buyer_id', 'course_id', 'podcast_id', 'event_id', 'product_id', 'code', 'user_id', 'purchase_detail_id', 'admin_id', 'status', 'checked', 'applied_at'];

    public function buyer(){
    	return $this->belongsTo('App\Models\User', 'buyer_id');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

    public function podcast(){
        return $this->belongsTo('App\Models\Podcast');
    }

    public function event(){
        return $this->belongsTo('App\Models\Event');
    }

    public function product(){
        return $this->belongsTo('App\Models\MarketProduct', 'product_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function purchase_detail(){
        return $this->belongsTo('App\Models\PurchaseDetail', 'purchase_detail_id');
    }

    public function admin(){
        return $this->belongsTo('App\Models\User', 'admin_id');
    }
}
