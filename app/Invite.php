<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'invite_code',
        'invited_user_id',
        'rewarded',
    ];

    /**
     * Get the user who created the invite.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the invited user.
     */
    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    /**
     * Generate a unique invite code.
     */
    public static function generateInviteCode()
    {
        return strtoupper(bin2hex(random_bytes(3))); // 6-character code
    }
}
