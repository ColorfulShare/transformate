<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSubscription extends Model
{
    protected $table = 'event_subscriptions';

    protected $fillable = ['event_id', 'names', 'email', 'country', 'age', 'profession', 'phone', 'reason', 'instructor_code', 'partner_code', 'payment_method', 'payment_id', 'payment_amount', 'payment_date', 'payment_url', 'gift', 'gift_buyer', 'gift_code', 'gift_status', 'gift_admin', 'status', 'disabled'];

    public function event(){
    	return $this->belongsTo('App\Models\Event');
    }

    public function instructor(){
    	return $this->belongsTo('App\Models\User', 'instructor_code', 'id');
    }

    public function partner(){
    	return $this->belongsTo('App\Models\User', 'partner_code', 'id');
    }

    public function commission(){
        return $this->hasOne('App\Models\Commission');
    }
}
