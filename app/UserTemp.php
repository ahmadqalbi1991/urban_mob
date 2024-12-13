<?php



namespace App;



use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;



class UserTemp extends Authenticatable

{

    use Notifiable,HasApiTokens,HasRoles;



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'name', 'email', 'password','phone','role','address','otp','registered_by','is_active','is_verified','is_registered','device_token','registered_by','gender','DOB','profile', 'verify','wallet_balance', 'user_device_token', 'user_device_type', 'device_cart_id', 'dial_code', 'firebase_user_key'

    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];



    /**

     * The attributes that should be cast to native types.

     *

     * @var array

     */

    protected $casts = [

        'email_verified_at' => 'datetime',

    ];



    /**

     * Scope a query to only include active users.

     *

     * @param  \Illuminate\Database\Eloquent\Builder  $query

     * @return \Illuminate\Database\Eloquent\Builder

     */

    public function scopeActive($query)

    {

        return $query->where('is_active', 1);

    }



     /**

     * Scope a query to only include front user.

     */

    public function scopeVendor($query)

    {

        return $query->where('role', 'vendor');

    }



    /**

     * Scope a query to only include operator.

     */

    public function scopeCustomer($query)

    {

        return $query->where('role', 'customer');

    }



     /**

     * Get the vendor shop data.

     */

    public function shop()

    {

        return $this->belongsTo(ShopDetail::class,'id','user_id')->select(array('id', 'shop_name', 'shop_phone', 'shop_email', 'city'));

    }

    public function seller()

    {

        return $this->belongsTo(Seller::class,'id','user_id');

    }

    public function address(){

        return $this->hasMany('App\Address','user_id');

    }

    public function bookings(){

        return $this->hasMany('App\Card','user_id');

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

