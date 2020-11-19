<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = ['name', 'status', 'users', 'profiles', 'courses', 'certifications', 'podcasts', 'master_class', 'gifts', 'events', 'newsletters', 'tags', 'liquidations', 'banks', 'coupons', 'tickets', 'finances'];

    public function users(){
    	return $this->hasMany('App\Models\User');
    }
}
