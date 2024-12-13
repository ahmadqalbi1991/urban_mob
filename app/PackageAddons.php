<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageAddons extends Model
{		
	protected $table = 'package_addons';
    protected $fillable = [
        'vendor_id', 'customer_id', 'addon_date','status'
    ];

     /**
     * Get the items for the package.
     */
    public function items()
    {
        return $this->hasMany(PackageAddonItems::class)->with('shopItem');
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
