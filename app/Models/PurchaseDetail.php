<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';

    protected $fillable = ['purchase_id', 'course_id', 'certification_id', 'podcast_id', 'membership_id', 
        'product_id', 'original_amount', 'amount', 'instructor_code', 'partner_code'];

    public function purchase(){
        return $this->belongsTo('App\Models\Purchase');
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

    public function commission(){
        return $this->hasOne('App\Models\Commission');
    }

    public function partner(){
        return $this->belongsTo('App\Models\User', 'partner_code', 'id');
    }

    public function gift(){
        return $this->hasOne('App\Models\Gift');
    }
}
