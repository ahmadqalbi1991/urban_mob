<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Seller extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city_info()
    {
        return $this->belongsTo('App\City','city');
    }

    public function seller_service(){

        return $this->hasMany('App\SellerService','seller_id');

    }
}
