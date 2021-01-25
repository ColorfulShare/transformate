<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = ['user_id', 'category_id', 'subcategory_id', 'mentor_route_time', 'title', 
        'subtitle', 'slug', 'review', 'description', 'cover', 'cover_name', 'miniature_cover', 'miniature_cover_name', 'preview', 
        'preview_name', 'preview_cover', 'preview_cover_name', 'objectives',  'requirements', 'destination', 'material_content', 
        'price', 'currency', 'cover_home', 'image_cover', 'featured', 'original', 'status', 'search_keys', 'evaluation_review', 'sent_for_review', 
        'reviewed_at', 'published_at'];


    //Relación Curso - Instructor
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
        return $this->belongsToMany('App\Models\Tag', 'courses_tags', 'course_id', 'tag_id');
    }

    public function modules(){
        return $this->hasMany('App\Models\Module');
    }

    //Relación Curso - Estudiantes
    public function students(){
        return $this->belongsToMany('App\Models\User', 'courses_students', 'course_id', 'user_id')->withPivot('progress', 'start_date', 'ending_date');
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
