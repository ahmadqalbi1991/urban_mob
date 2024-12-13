<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'vendor_id', 'customer_id', 'amount','month','year'
    ];

     /**
     * Get the cusomer data.
     */
    public function customer()
    {
        return $this->belongsTo(User::class)->select(array('id', 'name', 'phone', 'email', 'city','address'));
    }

     /**
     * Get the vendor data.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class)->select(array('id', 'name', 'phone', 'email', 'city','address'));
    }
}
