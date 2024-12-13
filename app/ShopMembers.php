<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopMembers extends Model
{
    protected $table = 'shop_members';
    protected $primaryKey = 'request_id';
    protected $fillable = [
        'vendor_id', 'customer_id','request_status','is_active'
    ];

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
    
    public static function getShopMembers($vendor_id,$status="")
    {
        $sql = DB::table('shop_members');
            $sql->join('users', 'users.id', '=', 'shop_members.customer_id');
            $sql->where('shop_members.vendor_id', $vendor_id);
            if($status)
            {   
                if($status=='Decline'){
                    $sql->where('shop_members.request_status', 'Reject');
                    $sql->orWhere('shop_members.request_status', 'Cancel');
                }
                else{
                    $sql->where('shop_members.request_status', $status);
                }
            }
            $sql->select('shop_members.*', 'users.name','users.email','users.phone','users.address','users.city');
            $data= $sql->get();
        return $data;
    }
    public static function getShopVendors($customer_id,$status="")
    {
        $sql = DB::table('shop_members');
            $sql->join('users', 'users.id', '=', 'shop_members.vendor_id');
            $sql->where('shop_members.customer_id', $customer_id);
            if($status)
            {   
                if($status=='Decline'){
                    $sql->where('shop_members.request_status', 'Reject');
                    $sql->orWhere('shop_members.request_status', 'Cancel');
                }
                else{
                    $sql->where('shop_members.request_status', $status);
                }
            }
            $sql->select('shop_members.*', 'users.name','users.email','users.phone','users.address','users.city');
            $data= $sql->get();
        return $data;
    }
}
