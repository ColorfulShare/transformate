<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $table = 'certifications';

    protected $fillable = ['user_id', 'category_id', 'subcategory_id', 'mentor_route_time', 'title',
        'subtitle', 'slug', 'review', 'description', 'cover', 'cover_name', 'preview', 'preview_name', 
        'preview_cover', 'preview_cover_name', 'objectives', 'requirements', 'destination', 
        'material_content', 'price', 'currency', 'cover_home', 'image_cover', 'puntuaction', 'featured', 
        'original', 'status', 'evaluation_review', 'sent_for_review', 'reviewed_at', 'published_at'];

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
        return $this->belongsToMany('App\Models\Tag', 'certifications_tags', 'certification_id', 'tag_id');
    }

    public function modules(){
        return $this->hasMany('App\Models\Module');
    }

    //Relación Estudiante - Certificaciones
    public function students(){
        return $this->belongsToMany('App\Models\User', 'certifications_students', 'certification_id', 'user_id')->withPivot('progress', 'start_date', 'ending_date');
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
}
