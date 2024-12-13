<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ShopDetail extends Model
{
    protected $table = 'shop_detail';
    protected $fillable = [
        'user_id', 'shop_name','shop_email','shop_phone','address','city','pincode','GSTIN','UPI'
    ];
    
}
