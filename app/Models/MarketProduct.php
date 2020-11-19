<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketProduct extends Model
{
    protected $table = 'market_products';

    protected $fillable = ['user_id', 'category_id', 'subcategory_id', 'name', 'slug', 'description', 
        'cover', 'cover_name', 'price', 'file', 'filename', 'file_extension', 'file_icon', 'featured', 'status'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function category(){
        return $this->belongsTo('App\Models\MarketCategory', 'category_id');
    }

    public function subcategory(){
        return $this->belongsTo('App\Models\MarketSubcategory', 'subcategory_id');
    }

    //Relación con la tabla shopping_cart
    public function cart(){
        return $this->hasMany('App\Models\Product', 'product_id', 'id');
    }

    //Relación con la tabla pruchase_details
    public function purchase_details(){
        return $this->hasMany('App\Models\PurchaseDetail', 'product_id', 'id');
    }

    //Relación con la tabla pending_product
    public function pending_product(){
        return $this->hasMany('App\Models\PendingProduct', 'product_id', 'id');
    }

    //Relación con la tabla gift
    public function gifts(){
        return $this->hasMany('App\Models\Gift', 'product_id', 'id');
    }

    //Relación Producto - Usuario Comprador
    public function users(){
        return $this->belongsToMany('App\Models\User', 'products_users', 'product_id', 'user_id')->withPivot('date');
    }
}
