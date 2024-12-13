<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItems extends Model
{
    protected $table = 'shop_items';
    protected $fillable = [
        'item_id', 'price','user_id'
    ];

    /**
     * Get the item data.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
