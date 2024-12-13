<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transection extends Model
{
    protected $fillable = [
        'vendor_id', 'customer_id', 'amount','type','remark'
    ];
    
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
