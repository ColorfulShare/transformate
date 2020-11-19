<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    protected $table = 'liquidations';

    protected $fillable = ['user_id', 'amount', 'payment_method', 'transaction_id', 'status', 'date', 'processed_at', 'admin'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function commissions(){
        return $this->hasMany('App\Models\Commission');
    }
}
