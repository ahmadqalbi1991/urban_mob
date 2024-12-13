<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];

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

    public function slot()

    {

        return $this->belongsTo(Slot::class);

    }

    public function address()

    {

        return $this->belongsTo(Address::class);

    }

    public function coupon()

    {

        return $this->belongsTo(Coupon::class);

    }

    public function card_attribute()

    {

        return $this->hasMany(CardAttribute::class);

    }

    public function card_addon()

    {

        return $this->hasMany(CardAddon::class);

    }

    public function vendor()

    {

        return $this->belongsTo('App\User', 'accept_user_id','id');

    }


    public function seller()

    {

        return $this->belongsTo('App\Seller', 'accept_user_company_id','id');

    }
    
    public function toArray()
    {
        $array = parent::toArray();
    
        // Replace all null values with empty strings and convert integers to strings
        return array_map(function($value) {
            if ($value === null) {
                return "";
            }
    
            // Convert integers to strings
            if (is_int($value)) {
                return (string) $value;
            }
    
            return $value;
        }, $array);
    }
}
