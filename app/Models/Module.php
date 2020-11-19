<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $fillable = ['course_id', 'certification_id', 'podcast_id', 'priority_order', 'title'];


    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

    public function certification(){
        return $this->belongsTo('App\Models\Certification');
    }

    public function podcast(){
        return $this->belongsTo('App\Models\Podcast');
    }

    public function lessons(){
        return $this->hasMany('App\Models\Lesson');
    }

    public function tests(){
        return $this->hasMany('App\Models\Test');
    }
}
