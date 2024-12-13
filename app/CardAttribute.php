<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardAttribute extends Model
{
    protected $guarded = [];

    public function main_sub_cat()

    {

        return $this->belongsTo('App\Category', 'main_sub_cat_id','id');

    }

    public function child_cate()

    {

        return $this->belongsTo('App\ChildCategory', 'child_cate_id','id');

    }

    public function toArray()
    {
        $array = parent::toArray();
    
        return array_map(function($value) {
            if ($value === null) {
                return "";
            }
    
            if (is_int($value)) {
                return (string) $value;
            }
    
            return $value;
        }, $array);
    }
}
