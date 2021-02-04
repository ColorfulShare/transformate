<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = ['title', 'user_id', 'slug', 'legend', 'description', 'date', 'time', 'image', 'image_movil', 'video', 'video_view_counter', 'benefits', 'benefits_img', 'mentor_section', 'mentor_section_title', 'mentor_section_img', 'credits', 'credits_title', 'informative_pdf', 'type', 'price', 'status', 'presale', 'presale_price', 'presale_datetime'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function subscriptions(){
    	return $this->hasMany('App\Models\EventSubscription');
    }

    public function images(){
    	return $this->hasMany('App\Models\EventImage');
    }

    public function testimonies(){
    	return $this->hasMany('App\Models\Testimony');
    }
}
