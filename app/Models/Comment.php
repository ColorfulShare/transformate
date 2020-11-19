<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['discussion_id', 'user_id', 'comment', 'date', 'status'];

    public function discussion(){
        return $this->belongsTo('App\Models\Discussion');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
