<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EfectyPayment extends Model
{
    protected $table = 'efecty_payments';

    protected $fillable = ['user_id', 'original_amount', 'amount', 'coupon_id', 'membership_discount', 'instructor_code_discount', 'status', 'payment_url', 'payment_id'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function pending_products(){
        return $this->hasMany('App\Models\PendingProduct');
    }

     public function coupon(){
        return $this->belongsTo('App\Models\Coupon');
    }

    public function scopeBank($query, $banco){
        if ($banco != ""){
            $query->where('bank', 'LIKE', '%'.$banco.'%');
        }
    }

}
