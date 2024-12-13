<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageAddonItems extends Model
{		
	protected $table = 'package_addon_items';
    protected $fillable = [
        'addon_id', 'shop_item_id','qty'
    ];

    /**
     * Get the item data.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the shop item data.
     */
    public function shopItem()
    {
        return $this->belongsTo(ShopItems::class)->with('item');
    }
}
