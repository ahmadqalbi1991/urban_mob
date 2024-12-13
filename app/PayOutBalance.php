<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayOutBalance extends Model
{
    protected $guarded = [];

    public function card()

    {

        return $this->belongsTo(Card::class);

    }
}
