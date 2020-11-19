<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['tag'];

    public function courses(){
        return $this->belongsToMany('App\Models\Course', 'courses_tags', 'tag_id', 'course_id');
    }

    public function certifications(){
        return $this->belongsToMany('App\Models\Certification', 'certifications_tags', 'tag_id', 'certification_id');
    }

    public function podcasts(){
        return $this->belongsToMany('App\Models\Podcast', 'podcasts_tags', 'tag_id', 'podcast_id');
    }

    public function master_class(){
        return $this->belongsToMany('App\Models\MasterClass', 'master_class_tags', 'tag_id', 'master_class_id');
    }
}
