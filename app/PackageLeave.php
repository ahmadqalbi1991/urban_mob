<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageLeave extends Model
{
    protected $table = 'package_leave';
    protected $fillable = [
        'package_id', 'leave_date'
    ];

     /**
     * Get the package data.
     */
    public function package()
    {
        return $this->belongsTo(Package::class)->with('vendor')->with('customer');
    }

}
