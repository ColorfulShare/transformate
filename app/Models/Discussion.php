<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $table = 'discussions';

    protected $fillable = ['course_id', 'certification_id', 'podcast_id', 'user_id', 'title', 'comment', 'date', 'status'];

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }

    public function certification(){
        return $this->belongsTo('App\Models\Certification');
    }

    public function podcast(){
        return $this->belongsTo('App\Models\Podcast');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }

    public function scopeBuscar($query, $busqueda){
        if ($busqueda != ""){
            $query->where('title', 'LIKE', '%'.$busqueda.'%');
        }
    }

    public function scopeCurso($query, $idCurso){
        if ($idCurso != ""){
            $query->where('course_id', '=', $idCurso);
        }
    }

    public function scopeCertificacion($query, $idCertificacion){
        if ($idCertificacion != ""){
            $query->where('certification_id', '=', $idCertificacion);
        }
    }

    public function scopePodcast($query, $idPodcast){
        if ($idPodcast != ""){
            $query->where('podcast_id', '=', $idPodcast);
        }
    }
}
