<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $table = 'tickets_categories';

    protected $fillable = ['title', 'description'];

    public function tickets(){
        return $this->hasMany('App\Models\Ticket', 'category_id', 'id');
    }
}
