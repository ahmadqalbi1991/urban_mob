<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    public function vendor()

    {

        return $this->belongsTo('App\User', 'vendor_id','id');

    }

    public function user()

    {

        return $this->belongsTo('App\User', 'customer_id','id');

    }

    public function service()

    {

        return $this->belongsTo(Service::class);

    }
}
