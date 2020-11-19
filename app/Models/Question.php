<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = ['test_id', 'order', 'question', 'possible_answer_1', 'possible_answer_2', 'possible_answer_3', 'possible_answer_4', 'correct_answer'];


    public function test(){
        return $this->belongsTo('App\Models\Test');
    }
}
