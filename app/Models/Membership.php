<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
	protected $table = 'memberships';

    protected $fillable = [
        'name', 'type', 'price', 'status'
    ];

    public function users(){
        return $this->hasMany('App\Models\User');
    }

    public function purchases(){
        return $this->hasMany('App\Models\Purchase');
    }

    //Relación con la tabla shopping_cart
    public function cart(){
        return $this->hasMany('App\Models\Product');
    }

    public function pending_products(){
        return $this->hasMany('App\Models\PendingProduct');
    }

    public function purchase_details(){
        return $this->hasMany('App\Models\PurchaseDetail');
    }
}
