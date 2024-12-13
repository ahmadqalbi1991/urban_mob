<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'vendor_id', 'customer_id','package_status','package_type','start_date','is_active'
    ];

     /**
     * Get the items for the package.
     */
    public function items()
    {
        return $this->hasMany(PackageItem::class)->with('shopItem');
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

     /**
     * Get the leave for the package.
     */
    public function leave()
    {
        return $this->hasMany(PackageLeave::class)->orderBy('leave_date','DESC')->limit(50);
    }
}
