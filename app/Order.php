<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'vendor_id', 'customer_id', 'order_status','total_amount','order_date', 'package_id', 'is_extra_order'
    ];

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

     /**
     * Get the cusomer data.
     */
    public function customer()
    {
        return $this->belongsTo(User::class)->select(array('id', 'name', 'phone', 'email', 'city'));
    }

     /**
     * Get the vendor data.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class)->select(array('id', 'name', 'phone', 'email', 'city'));
    }
}
