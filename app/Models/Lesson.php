<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    protected $fillable = ['module_id', 'priority_order', 'title', 'description', 'video', 'filename', 'file_extension', 'file_icon', 'duration'];


    public function module(){
        return $this->belongsTo('App\Models\Module');
    }
    
    public function resource_files(){
    	return $this->hasMany('App\Models\ResourceFile');
    }

    //Relación Lección - Estudiantes
    public function students(){
        return $this->belongsToMany('App\Models\User', 'lesson_progress', 'lesson_id', 'user_id')->withPivot('progress', 'finished', 'updated_at');
    }
}
