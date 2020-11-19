<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterClass extends Model
{
    protected $table = 'master_class';

    protected $fillable = ['title', 'subtitle', 'slug', 'review', 'cover', 'cover_name', 'video_file', 'video_filename', 'status'];

    
    public function tags(){
        return $this->belongsToMany('App\Models\Tag', 'master_class_tags', 'master_class_id', 'tag_id');
    }

    public function resources(){
        return $this->hasMany('App\Models\ResourceFile');
    }

    public function scopeBusqueda($query, $busqueda){
        if (trim($busqueda) != "") {
            $query->Where(function($query) use ($busqueda) {
                $query->Where('title', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('subtitle', 'LIKE', '%'.$busqueda.'%');
            });
        }
        return $query;
    }
}
