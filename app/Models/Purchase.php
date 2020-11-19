<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    protected $fillable = ['user_id', 'original_amount', 'amount', 'payment_method', 'payment_id', 'coupon_id', 'membership_discount', 'instructor_code_discount', 'date', 'status', 'manual'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    
    public function membership(){
        return $this->belongsTo('App\Models\Membership');
    }

    public function details(){
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function commissions(){
        return $this->hasMany('App\Models\Commission');
    }

     public function coupon(){
        return $this->belongsTo('App\Models\Coupon');
    }

    public function scopePaymentMethod($query, $metodo){
        if ($metodo != ""){
            $query->where('payment_method', '=', $metodo);
        }
    }
}
