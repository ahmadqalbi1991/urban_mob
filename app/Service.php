<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    public function category()

    {

        return $this->belongsTo(Category::class);

    }

    public function sub_category()

    {

        return $this->belongsTo('App\Category', 'sub_category_id','id');

    }

    public function child_category()

    {

        return $this->belongsTo('App\ChildCategory', 'child_category_id','id');

    }

    public function attribute()

    {

        return $this->belongsTo(Attribute::class);

    }

    public function addon()

    {

        return $this->belongsTo(Addon::class);

    }

    public function user()

    {

        return $this->belongsTo(User::class);

    }

    public function service_attr_val()

    {

        return $this->hasMany(ServiceAttributeValue::class);

    }

    public function service_attr_val_item()

    {

        return $this->hasMany('App\ServiceAttributeValueItem', 'service_id','id');

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
