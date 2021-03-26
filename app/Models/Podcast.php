<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $table = 'podcasts';

    protected $fillable = ['user_id', 'category_id', 'subcategory_id', 'mentor_route_time', 'title', 'subtitle', 'slug', 'inspired_in',
        'review', 'objectives', 'destination', 'material_content', 'importance', 'prologue', 'potential_impact', 'price', 'currency', 
        'audio_file', 'audio_filename', 'cover', 'cover_name', 'preview', 'preview_name', 'cover_home', 'image_cover', 'featured', 
        'original', 'status', 'search_keys', 'evaluation_review', 'sent_for_review', 'reviewed_at', 'published_at'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function subcategory(){
        return $this->belongsTo('App\Models\Subcategory');
    }

    public function tags(){
        return $this->belongsToMany('App\Models\Tag', 'podcasts_tags', 'podcast_id', 'tag_id');
    }

    public function resources(){
        return $this->hasMany('App\Models\ResourceFile');
    }

    //Relación Podcast - Estudiantes
    public function students(){
        return $this->belongsToMany('App\Models\User', 'podcasts_students', 'podcast_id', 'user_id');
    }

    //Relación con la tabla shopping_cart
    public function cart(){
        return $this->hasMany('App\Models\Product');
    }
    
    //Relación con la tabla purchase_details
    public function purchases(){
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function ratings(){
        return $this->hasMany('App\Models\Rating');
    }

    public function discussions(){
        return $this->hasMany('App\Models\Discussion');
    }

    public function pending_products(){
        return $this->hasMany('App\Models\PendingProduct');
    }

    public function scopeTitulo($query, $titulo){
        if ($titulo != ""){
            $query->where('title', 'LIKE', '%'.$titulo.'%');
        }
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
