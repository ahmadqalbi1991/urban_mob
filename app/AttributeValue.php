<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'id', 'attribute_id');
    }

    public function attribute_items()
    {
        return $this->belongsTo(ServiceAttributeValue::class, 'id', 'attribute_item_id');
    }
    
}
