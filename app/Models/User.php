<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'names', 'last_names', 'slug', 'birthdate', 'gender', 'country', 'state', 'username', 'email', 'email_token',
        'phone', 'password', 'role_id', 'afiliate_code', 'afiliate_direct', 'avatar', 'profession', 
        'review', 'balance', 'event_balance', 'status', 'membership_id', 'membership_expiration', 'membership_courses', 'sponsor_id',
        'charging_method', 'paypal_email', 'business_name', 'identification', 'bank', 'account_number', 
        'account_type', 'save_payment_method', 'provider', 'provider_id', 'facebook', 'instagram', 
        'twitter', 'youtube', 'pinterest', 'curriculum', 'course_review', 'video_presentation', 'profile_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    //Solo si es administrador
    public function profile(){
        return $this->belongsTo('App\Models\Profile');
    }

    public function membership(){
        return $this->belongsTo('App\Models\Membership');
    }

    //Relación Instructor - Cursos
    public function courses(){
        return $this->hasMany('App\Models\Course');
    }

    //Relación Instructor - Certificaciones
    public function certifications(){
        return $this->hasMany('App\Models\Certification');
    }

    //Relación Instructor - Podcasts
    public function podcasts(){
        return $this->hasMany('App\Models\Podcast');
    }

    public function events(){
    	return $this->hasMany('App\Models\Event');
    }

    //Relación Estudiante - Cursos 
    public function courses_students(){
        return $this->belongsToMany('App\Models\Course', 'courses_students', 'user_id', 'course_id')->withPivot('progress', 'start_date', 'ending_date');
    }

    //Relación Estudiante - Certificaciones
    public function certifications_students(){
        return $this->belongsToMany('App\Models\Certification', 'certifications_students', 'user_id', 'certification_id')->withPivot('progress', 'start_date', 'ending_date');
    }

    //Relación Estudiante - Podcasts 
    public function podcasts_students(){
        return $this->belongsToMany('App\Models\Podcast', 'podcasts_students', 'user_id', 'podcast_id');
    }

    //Relación Estudiante - Lecciones
    public function lessons(){
        return $this->belongsToMany('App\Models\Lesson', 'lesson_progress', 'user_id', 'lesson_id')->withPivot('progress', 'finished', 'updated_at');
    }

    //Relación Estudiante - Pruebas
    public function tests(){
        return $this->belongsToMany('App\Models\Test', 'tests_students', 'user_id', 'test_id')->withPivot('id', 'score', 'date');
    }

    //Relación Estudiante - Compras
    public function purchases(){
        return $this->hasMany('App\Models\Purchase');
    }

    //Relación Instructor - Comisiones
    public function commissions(){
        return $this->hasMany('App\Models\Commission');
    }

    //Relación Usuario Referido - Comisiones
    public function commissions_referred(){
        return $this->hasMany('App\Models\Commission', 'referred_id', 'id');
    }

    //Relación Instructor - Liquidaciones
    public function liquidations(){
        return $this->hasMany('App\Models\Liquidation');
    } 

    //Relación Estudiante - Valoraciones de Curso
    public function ratings(){
        return $this->hasMany('App\Models\Rating');
    }

    //Discusiones (Foro Preguntas)
    public function discussions(){
        return $this->hasMany('App\Models\Discussion');
    }

    //Comentarios (Foro Respuestas)
    public function comments(){
        return $this->hasMany('App\Models\Comments');
    }

    public function sponsor(){
        return $this->belongsTo('App\Models\User', 'sponsor_id');
    }

    public function notifications(){
        return $this->hasMany('App\Models\Notification');
    }

    public function tickets(){
        return $this->hasMany('App\Models\Ticket');
    }

    public function bank_transfers(){
        return $this->hasMany('App\Models\BankTransfer');
    }

    public function efecty_payments(){
        return $this->hasMany('App\Models\EfectyPayment');
    }

    public function coupons(){
        return $this->belongsToMany('App\Models\Coupon', 'applied_coupons', 'user_id', 'coupon_id')->withPivot('status', 'opened_at', 'closed_at');
    }

    //***Relación Usuarios Propietarios - Productos del Marketplace
    public function products(){
        return $this->hasMany('App\Models\MarketProduct');
    }

    //Relación Usuarios Compradores - Productos del Marketplace 
    public function products_users(){
        return $this->belongsToMany('App\Models\MarketProduct', 'products_users', 'user_id', 'product_id')->withPivot('date');
    }

    public function subscriptions(){
        return $this->hasMany('App\Models\EventSubscription', 'instructor_code', 'id');
    }

    public function partner_subscriptions(){
        return $this->hasMany('App\Models\EventSubscription', 'partner_code', 'id');
    }

    public function partner_items_cart(){
        return $this->hasMany('App\Models\Product', 'partner_code', 'id');
    }

    public function partner_purchase_details(){
        return $this->hasMany('App\Models\PurchaseDetail', 'partner_code', 'id');
    }
    
    //***** SCOPES *****//
    public function scopeBusquedaGlobal($query, $busqueda){
        if ($busqueda != ""){
            $query->where('names', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('last_names', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('email', 'LIKE', '%'.$busqueda.'%');
        }
    }
    
}
