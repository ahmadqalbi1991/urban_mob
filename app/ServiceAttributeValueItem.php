<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceAttributeValueItem extends Model
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

    public function service()

    {

        return $this->belongsTo(Service::class);

    }

    public function serviceAttributeValues()
    {
        return $this->hasMany(ServiceAttributeValue::class, 'ser_attr_val_item_id');
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
