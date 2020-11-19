<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    protected $table = 'events_images';

    protected $fillable = ['event_id', 'image', 'movil', 'instructor_section'];

    public function event(){
    	return $this->belongsTo('App\Models\Event');
    }
}
