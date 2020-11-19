<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';

    protected $fillable = ['module_id', 'title', 'description'];


    public function module(){
        return $this->belongsTo('App\Models\Module');
    }

    public function questions(){
        return $this->hasMany('App\Models\Question');
    }

    //Relación Estudiantes - Tests 
    public function students(){
        return $this->belongsToMany('App\Models\User', 'tests_students', 'test_id', 'user_id')->withPivot('id', 'score', 'date');
    }
}
