<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $table = 'commissions';

    protected $fillable = ['user_id', 'amount', 'referred_id', 'type', 'wallet', 'purchase_detail_id', 'liquidation_id', 'status', 'date'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function referred(){
        return $this->belongsTo('App\Models\User', 'referred_id', 'id');
    }

    public function purchase_detail(){
        return $this->belongsTo('App\Models\PurchaseDetail');
    }

    public function event_subscription(){
        return $this->belongsTo('App\Models\EventSubscription');
    }

    public function liquidation(){
        return $this->belongsTo('App\Models\Liquidation');
    }
}
