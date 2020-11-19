<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    protected $fillable = ['bank', 'business_name', 'identification', 'account_number', 'account_type', 'status'];

    public function bank_transfers(){
    	return $this->hasMany('App\Models\BankTransfer');
    }

    public function scopeBank($query, $banco){
        if ($banco != ""){
            $query->where('bank', 'LIKE', '%'.$banco.'%');
        }
    }

}
