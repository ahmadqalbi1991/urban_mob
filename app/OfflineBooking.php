<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfflineBooking extends Model
{
    protected $guarded = [];

    public function slot()

    {

        return $this->belongsTo(Slot::class);

    }

    public function address()

    {

        return $this->belongsTo(Address::class);

    }

    public function user()

    {

        return $this->belongsTo(User::class);

    }

    public function service()

    {

        return $this->belongsTo(Service::class);

    }

    public function category()

    {

        return $this->belongsTo(Category::class);

    }

    public function card_attribute()

    {

        return $this->hasMany('App\OfflineBookingAttribute', 'card_id','id');

    }

    public function vendor()

    {

        return $this->belongsTo('App\User', 'accept_user_id','id');

    }

    public function card_addon()

    {

        return $this->hasMany(CardAddon::class);

    }
}
