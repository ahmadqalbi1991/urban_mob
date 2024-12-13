<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories recursively (for nested relationships).
     */
    public function childCategoriesRecursive()
    {
        return $this->subCategories()->with('childCategoriesRecursive');
    }

    public function toArray()
    {
        $array = parent::toArray();
    
        // Replace all null values with empty strings and convert integers to strings
        return array_map(function($value) {
            if ($value === null) {
                return "";
            }
    
            // Convert integers to strings
            if (is_int($value)) {
                return (string) $value;
            }
    
            return $value;
        }, $array);
    }
}
