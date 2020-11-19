<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    protected $table = 'testimonies';

    protected $fillable = ['event_id', 'text', 'image', 'image_movil', 'autor'];

    public function event(){
    	return $this->belongsTo('App\Models\Event');
    }
}
