<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Service;

class SellerService extends Model
{
    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
