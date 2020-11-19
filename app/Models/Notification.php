<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'title', 'description', 'icon', 'route', 'status', 'date'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
