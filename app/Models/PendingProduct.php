<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingProduct extends Model
{
    protected $table = 'pending_products';

    protected $fillable = ['bank_transfer_id', 'efecty_payment_id', 'purchase_id', 'course_id', 'certification_id', 'podcast_id', 'membership_id', 'original_amount', 'amount', 'instructor_code', 'partner_code', 'gift'];

    public function bank_transfer(){
        return $this->belongsTo('App\Models\BankTransfer');
    }

    public function efecty_payment(){
        return $this->belongsTo('App\Models\EfectyPayment');
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

    public function coupon(){
        return $this->belongsTo('App\Models\Coupon');
    }
}
