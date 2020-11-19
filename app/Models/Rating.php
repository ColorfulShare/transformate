<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = ['user_id', 'course_id', 'certification_id', 'podcast_id', 'name', 'title', 'comment', 'points', 'date'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

    public function certification(){
        return $this->belongsTo('App\Models\Certification');
    }

    public function podcast(){
        return $this->belongsTo('App\Models\Podcast');
    }
}
