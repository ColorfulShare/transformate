<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTracking extends Model
{
    protected $table = 'tickets_tracking';

    protected $fillable = ['ticket_id', 'user_id', 'reply', 'reply_type', 'reply_order'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function ticket(){
        return $this->belongsTo('App\Models\Ticket');
    }
}
