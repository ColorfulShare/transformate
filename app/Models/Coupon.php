<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = ['name', 'discount', 'code', 'limit', 'applications', 'status', 'categories_availables'];


    public function users(){
        return $this->belongsToMany('App\Models\User', 'applied_coupons', 'coupon_id', 'user_id')->withPivot('status', 'opened_at', 'closed_at');
    }

    public function purchases(){
    	return $this->hasMany('App\Models\Purchase');
    }

    public function transfers(){
        return $this->hasMany('App\Models\BankTransfer');
    }

    public function efecty_payments(){
        return $this->hasMany('App\Models\EfectyPayment');
    }
}
