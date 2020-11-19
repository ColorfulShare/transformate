<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceFile extends Model
{
    protected $table = 'resource_files';

    protected $fillable = ['lesson_id', 'podcast_id', 'master_class_id', 'link', 'filename', 'file_extension', 'file_icon'];


    public function lesson(){
        return $this->belongsTo('App\Models\Lesson');
    }

    public function podcast(){
        return $this->belongsTo('App\Models\Podcast');
    }

    public function master_class(){
        return $this->belongsTo('App\Models\MasterClass');
    }
}
