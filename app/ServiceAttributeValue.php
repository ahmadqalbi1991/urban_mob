<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceAttributeValue extends Model
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

    public function service()

    {

        return $this->belongsTo(Service::class);

    }

    public function attributeValue()
    {
        return $this->belongsTo('App\AttributeValue', 'attribute_id','id');
    }

    public function attributeItem()
    {
        return $this->belongsTo('App\AttributeValue', 'attribute_item_id','id');
    }
}
