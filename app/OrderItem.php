<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'shop_item_id', 'item_id', 'item_name','item_brand','item_unit','item_qty','item_price','item_icon'
    ];

     /**
     * Get the order data.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
