<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardUser extends Model
{

    protected $fillable = ['reward_config_id', 'user_id', 'amounts', 'points', 'transection_id', 'date', 'booking_type'];

    public function rewardConfig()
    {
        return $this->belongsTo(RewardConfig::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
