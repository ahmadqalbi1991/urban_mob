<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $guarded = [];

    public function attribute_item()
    {
        return $this->belongsTo('App\AttributeValue', 'attribute_item_id', 'id');
    }
}
