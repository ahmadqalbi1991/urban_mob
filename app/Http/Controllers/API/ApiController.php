<?php



namespace App\Http\Controllers\API;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

use Validator;

use Carbon\Carbon;

use App\User;

use App\City;

use App\Locality;

use App\WalletPaymentReport;

use App\ShopDetail;

use App\ShopItems;

use App\ShopMembers;

use App\Package;

use App\PackageItem;

use App\PackageLeave;

use App\PackageAddons;

use App\PackageAddonItems;

use App\Item;

use App\Order;

use App\OrderItem;

use App\Invoice;

use App\Transection;

use App\Slider;

use App\Address;

use App\Tip;

use App\Coupon;

use App\Card;

use App\HomeSetting;

use App\SellerService;

use App\Seller;

use App\Notification;

use App\Service;

use App\Services\FirebasePushNotificationService;

use App\Category;

use App\ServiceAttributeValueItem;

class ApiController extends BaseController

{
    
    protected $firebaseService;

    // Inject FirebasePushNotificationService through the constructor
    public function __construct(FirebasePushNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function customerInfo(Request $request)

    {   

        if($request->customerId)

        {   

            $user=User::where('id',$request->customerId)->where('role','customer')->first();

            if($user){ 

                return $this->sendResponse($user, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Customer Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

        

    }

    public function vendorInfo(Request $request)

    {   

        if($request->vendorId)

        {   

            $user=User::where('id',$request->vendorId)->where('role','vendor')->first();

            if($user){ 

                $user->shop = ShopDetail::select('*')->where('user_id', $request->vendorId)->first();

                return $this->sendResponse($user, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Vendor Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorSearch(Request $request)

    {   

        if($request->string && $request->customerId)

        {   

            $data=User::vendor()->active()

            ->where(function ($query) use ($request) {

                $query->where('name','LIKE','%'.$request->string.'%');

                $query->orWhere('phone','LIKE','%'.$request->string.'%');

                $query->orWhere('address','LIKE','%'.$request->string.'%');

                $query->orWhere('city','LIKE','%'.$request->string.'%');

            })->select('id','name', 'email','phone','address','city')->get(); 

            if($data){ 



                    foreach($data as $user)

                    {

                        $user->is_requested=ShopMembers::where('vendor_id',$user->id)->where('customer_id',$request->customerId)->exists();

                        if($user->is_requested)

                        {

                            $user->request_status=ShopMembers::where('vendor_id',$user->id)->where('customer_id',$request->customerId)->value('request_status');

                        }



                    }



                return $this->sendResponse($data, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Vendor Found!');

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorRequest(Request $request)

    {   

        if($request->customerId && $request->vendorId)

        {   

            $exist=ShopMembers::where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if($exist){

                return $this->sendError('Request already submitted!');

            }

            else{



                $input=[

                    'customer_id' => $request->customerId,

                    'vendor_id'=> $request->vendorId,

                    'request_status'=>'Pending'

                ];

                $lastId=ShopMembers::create($input)->request_id;

                if($lastId){ 



                        $device_tokens = User::where('id',$request->vendorId)->where('role','vendor')->pluck('device_token')->toArray();

                        sendNotification($device_tokens, array(

                          "title" => 'New Request', 

                          "body" => 'New shop member request received!',

                          "type" => "member_request",

                          "id"=> $lastId,

                        ));



                    return $this->sendResponse($lastId, 'Request send successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorList(Request $request)

    {   

        if($request->customerId)

        {   

            $ShopMembers = new ShopMembers();

            $data=$ShopMembers->getShopVendors($request->customerId);

            if($data){ 



                foreach($data as $user)

                    {

                        $user->is_package=Package::where('vendor_id',$user->vendor_id)->where('customer_id',$request->customerId)->exists();

                    }



                return $this->sendResponse($data, 'Loading...!');

            } 

            else{ 

                return $this->sendError('Try Later!.');

            }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function myVendorList(Request $request)

    {   

        if($request->customerId)

        {   

            $ShopMembers = new ShopMembers();

            $data=$ShopMembers->getShopVendors($request->customerId,'Accept');

            if($data){ 



                foreach($data as $user)

                    {

                        $user->is_package=Package::where('vendor_id',$user->vendor_id)->where('customer_id',$request->customerId)->exists();

                    }



                return $this->sendResponse($data, 'Loading...!');

            } 

            else{ 

                return $this->sendError('Try Later!.');

            }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorRequestCancel(Request $request)

    {   

        if($request->request_id && $request->vendorId && $request->customerId)

        {   

            $data = ShopMembers::where('request_id',$request->request_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if($data){ 

                $data->request_status = 'Cancel';

                $res=$data->save();

                    if($res)

                    {

                        $device_tokens = User::where('id',$request->vendorId)->where('role','vendor')->pluck('device_token')->toArray();

                        sendNotification($device_tokens, array(

                          "title" => 'Member request is cancel by customer!', 

                          "body" => '',

                          "type" => "member_request",

                          "id"=> $request->request_id,

                        ));

                    }

                    return $this->sendResponse($res, 'Request cancel successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorShopItems(Request $request)

    {   

        if($request->vendorId)

        {   

            $items=ShopItems::where('user_id',$request->vendorId)->with('item')->get();

            if($items){ 

               

                return $this->sendResponse($items, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Items Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

     public function vendorPackageRequest(Request $request)

    {   

        if($request->customerId && $request->vendorId && sizeof($request->shop_items)>0)

        {   

            $exist=Package::where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if($exist){

                return $this->sendError('Package Request already submitted!');

            }

            else{



                $input=[

                    'customer_id' => $request->customerId,

                    'vendor_id'=> $request->vendorId,

                    'package_type'=>$request->package_type,

                    'start_date'=>$request->start_date,

                    'package_status'=>'Pending'

                ];



                if($request->package_type=='Weekly' || $request->package_type=='Alternate')

                {

                    $input['week_day'] = Carbon::parse($request->start_date)->format('l');

                }



                $lastId=Package::create($input)->id;

                if($lastId){ 



                    $inputs=[];

                    foreach($request->shop_items as $item)

                    {

                        $inputs[]=['package_id'=>$lastId,'shop_item_id'=>$item['shop_item_id'],'qty'=>$item['qty']];

                    }

                    PackageItem::insert($inputs);



                    $device_tokens = User::where('id',$request->vendorId)->where('role','vendor')->pluck('device_token')->toArray();

                        sendNotification($device_tokens, array(

                          "title" => 'New Package', 

                          "body" => 'New package request received!',

                          "type" => "package_request",

                          "id"=> $lastId,

                        ));



                    return $this->sendResponse($lastId, 'Package Request send successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function PackageRequestCancel(Request $request)

    {   

        if($request->package_id && $request->vendorId && $request->customerId)

        {   

            $data = Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->package_status = 'Cancel';

                    $data->is_active = 0;

                    $res=$data->save();

                    if($res)

                    {

                        $device_tokens = User::where('id',$request->vendorId)->where('role','vendor')->pluck('device_token')->toArray();

                        sendNotification($device_tokens, array(

                          "title" => 'Package request', 

                          "body" => 'Package request is cancel by customer',

                          "type" => "package_request",

                          "id"=> $request->package_id,

                        ));

                    }

                    return $this->sendResponse($res, 'Package Request cancel successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function pauseSubscriptionPackage(Request $request)

    {   

        if($request->package_id && $request->vendorId && $request->customerId)

        {   

            $data = Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->is_active = 0;

                    $res=$data->save();

                    return $this->sendResponse($data, 'Subscription package pause successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function resumeSubscriptionPackage(Request $request)

    {   

        if($request->package_id && $request->vendorId && $request->customerId)

        {   

            $data = Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->is_active = 1;

                    $res=$data->save();

                    return $this->sendResponse($data, 'Subscription package resume successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function packageInfo(Request $request)

    {   

        if($request->package_id)

        {   

            $package=Package::where('id',$request->package_id)->with('items')->first();

            if($package){ 

                return $this->sendResponse($package, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Package Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorPackageList(Request $request)

    {   

        if($request->customerId)

        {   



            $query=Package::where('customer_id',$request->customerId);

            if($request->vendorId)

            {

                $query->where('vendor_id',$request->vendorId);

            }

            if($request->status)

            {

                $query->where('package_status',$request->status);

            }

            $package=$query->with('items')->with('vendor')->get();

            if($package){ 

                return $this->sendResponse($package, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Packages Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function customerPackageList(Request $request)

    {

        if($request->vendorId)

        {   



            $query=Package::where('vendor_id',$request->vendorId);

            if($request->customerId)

            {

                $query->where('customer_id',$request->customerId);

            }

            if($request->status)

            {

                $query->where('package_status',$request->status);

            }

            $package=$query->with('items')->with('customer')->get();

            if($package){ 

                return $this->sendResponse($package, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Packages Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function getCustomerRequestList(Request $request)

    {

        

        if($request->vendorId)

        {   

            $ShopMembers = new ShopMembers();

            $data=$ShopMembers->getShopMembers($request->vendorId,'','Accept');

            if($data){ 



                    foreach($data as $user)

                    {

                        $user->is_package=Package::where('vendor_id',$request->vendorId)->where('customer_id',$user->customer_id)->exists();

                    }



                    return $this->sendResponse($data, 'customer request successfully get!');

                } 

                else{ 

                    return $this->sendError('No Request found!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function getCustomerList(Request $request)

    {

        

        if($request->vendorId)

        {   

            $ShopMembers = new ShopMembers();

            $data=$ShopMembers->getShopMembers($request->vendorId,'Accept');

            if($data){ 



                    foreach($data as $user)

                    {

                        $user->is_package=Package::where('vendor_id',$request->vendorId)->where('customer_id',$user->customer_id)->exists();

                    }

                    return $this->sendResponse($data, 'my customer successfully get!');

                } 

                else{ 

                    return $this->sendError('No Request found!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function requestStatusChange(Request $request)

    {   

        if($request->requestId && $request->vendorId && $request->customerId && $request->status)

        {   

            $data = ShopMembers::where('request_id',$request->requestId)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->request_status = $request->status;

                    if($request->status=='Accept')

                    {

                         $data->is_active = 1;

                    }

                    $res=$data->save();

                    if($res)

                    {

                        $device_tokens = User::where('id',$request->customerId)->where('role','customer')->pluck('device_token')->toArray();

                        sendNotification($device_tokens, array(

                          "title" => 'Your request is '.$request->status, 

                          "body" => '',

                          "type" => "member_request",

                          "id"=> $request->request_id,

                        ));

                    }

                    return $this->sendResponse($res, 'Request status change successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function packageRequestUpdate(Request $request)

    {   

        if($request->package_id && $request->vendorId && $request->customerId && $request->status)

        {   

            $data = Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->package_status = $request->status;

                    if($request->status=='Accept')

                    {

                         $data->is_active = 1;

                    }

                    $res=$data->save();

                    return $this->sendResponse($res, 'Package Request update successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function packageItemsUpdate(Request $request)

    {   

        if($request->customerId && $request->vendorId && $request->package_id && sizeof($request->shop_items)>0)

        {   

            $exist=Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if(!$exist){

                return $this->sendError('Package not available!');

            }

            else{



                PackageItem::where('package_id', $request->package_id)->delete();

                $inputs=[];

                foreach($request->shop_items as $item)

                {

                    $inputs[]=['package_id'=>$request->package_id,'shop_item_id'=>$item['shop_item_id'],'qty'=>$item['qty']];

                }

                $res=PackageItem::insert($inputs);

                return $this->sendResponse($res, 'Package items update successfully!');

                

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function getItems(Request $request)

    {   

        $data=Item::active()

            ->where(function ($query) use ($request) {

                $query->where('name','LIKE','%'.$request->string.'%');

                $query->orWhere('brand','LIKE','%'.$request->string.'%');

            })->get(); 

        if($data){ 

            return $this->sendResponse($data, 'Loading...');

        } 

        else{ 

            return $this->sendError('No items Found!');

        } 

    }

    public function myShopItems(Request $request)

    {   

        if($request->vendorId)

        {   

            $uniq_items = ShopItems::where('user_id',$request->vendorId)->select('item_id')->distinct()->get();

            $shop_items=array();

            if($uniq_items)

            {

                foreach($uniq_items as $key=>$value)

                {   

                    $shop_items[$key]=Item::where('id',$value->item_id)->first();

                    $shop_items[$key]['variant']=ShopItems::where('user_id',$request->vendorId)->where('item_id',$value->item_id)->get();

                }

                return $this->sendResponse($shop_items, 'Loading...');

            }

            else

            {

                return $this->sendError('No items found in shop!');

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function myShopItemData(Request $request)

    {   

        if($request->vendorId && $request->itemId)

        {   

            $data = Item::where('id',$request->itemId)->first();

            if($data)

            {

                $data->variant=ShopItems::where('user_id',$request->vendorId)->where('item_id',$request->itemId)->get();

                return $this->sendResponse($data, 'Loading...');

            }

            else

            {

                return $this->sendError('No items found in shop!');

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function myShopItemStore(Request $request)

    {   

        if($request->vendorId && $request->itemId && sizeof($request->variant)>0)

        {   



            $inputs=[];

            $res=false;

            foreach($request->variant as $item)

            {

                if($item['price'])

                {

                    $inputs[]=[

                            'user_id'=>$request->vendorId,

                            'item_id'=>$request->itemId,

                            'quantity'=>$item['quantity'],

                            'unit'=>$item['unit'],

                            'price'=>$item['price'],

                            'is_available'=>$item['is_available'],

                        ];

                }

                

            }

            if($inputs)

            {

              $res=ShopItems::insert($inputs);  

            }

            if($res)

            {

                return $this->sendResponse($res, 'Items added in your shop successfully!');

            }

            else

            {

                return $this->sendError('Try Later!');

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function myShopItemUpdate(Request $request)

    {   

        if($request->vendorId && $request->itemId && sizeof($request->variant)>0)

        {   

            $result=$res=false; 

            $inputs=[];

            foreach($request->variant as $item)

            {

                if($item['price'])

                {

                    $inputs=[

                            'user_id'=>$request->vendorId,

                            'item_id'=>$request->itemId,

                            'quantity'=>$item['quantity'],

                            'unit'=>$item['unit'],

                            'price'=>$item['price'],

                            'is_available'=>$item['is_available'],

                        ];

                    if(isset($item['id']) && !empty($item['id']))

                    {

                        $res=ShopItems::where('id',$item['id'])->update($inputs);

                    }

                    else

                    {

                        $res=ShopItems::create($inputs);

                    }

                }

                if($res)

                {

                    $result=true; 

                }

            }

            

            if($result)

            {

                return $this->sendResponse($res, 'Items update in your shop successfully!');

            }

            else

            {

                return $this->sendError('Try Later!');

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function saveShopStore(Request $request)

    {

        if($request->vendorId)

        {   

            $id=$request->shopId;

            $valid_inputs=[

                'shop_name' => 'required|string|max:255',

                'shop_email'=>['required','email',Rule::unique('shop_detail','shop_email')->ignore($id)],

                'shop_phone' => ['required','digits:10',Rule::unique('shop_detail','shop_phone')->ignore($id)],

                'address' => 'required',

                'city' => 'required',

                'pincode'=>'required',

                'GSTIN'=>'required',

                'UPI'=>'required',

            ];

            $validator = Validator::make($request->all(),$valid_inputs );

            if($validator->fails()){

                return $this->sendError('Validation Error.', $validator->errors());       

            }



            $file=""; $image_name="";

            if(isset($request->QR) && !empty($request->QR))

        	{ 

        		$img=$request->QR;

        		$folderPath = "uploads/QR/";

	            $image_parts = explode(";base64,", $img);

	            $image_type_aux = explode("image/", $image_parts[0]);

	            $image_type = $image_type_aux[1];

	            $image_base64 = base64_decode($image_parts[1]);

	            $image_name='shop-'.time().'.'.$image_type;

	            $file = $folderPath.$image_name;

	            $res=file_put_contents($file, $image_base64);

	            if(empty($res))

	            {

	                $file="";

                    $image_name="";

	            }

        	}

            else

            {

                $image_name=$request->qr_image;

            }

                $input=[

                    'user_id'=>$request->vendorId,

                    'shop_name' => $request->shop_name,

                    'shop_email'=> $request->shop_email,

                    'shop_phone'=> $request->shop_phone,

                    'address'=>$request->address,

                    'city' => $request->city,

                    'pincode' => $request->pincode,

                    'GSTIN'=>$request->GSTIN,

                    'UPI' => $request->UPI,

                    'QR'=>$image_name

                ];

                $shopDetail=ShopDetail::where('user_id', $request->vendorId)->first();

                if($shopDetail)

                {

                    $res=ShopDetail::where('user_id', $request->vendorId)->update($input);

                }

                else{

                    $res=ShopDetail::create($input);

                }

                if($res){ 

                    $shopDetail=ShopDetail::where('user_id', $request->vendorId)->first();

                    return $this->sendResponse($shopDetail, 'Shop save successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function savePackageLeave(Request $request)

    {   

        if($request->customerId && $request->vendorId && $request->package_id && sizeof($request->leave_date)>0)

        {   

            $exist=Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if(!$exist){

                return $this->sendError('Package not available!');

            }

            else{



                PackageLeave::where('package_id',$request->package_id)->delete();

                $inputs=[];

                    foreach($request->leave_date as $value)

                    {

                        $inputs[]=['package_id'=>$request->package_id,'leave_date'=>$value];

                    }

                $res=PackageLeave::insert($inputs);

                if($res){ 

                        $data=PackageLeave::where('package_id',$request->package_id)->get();

                    return $this->sendResponse($data, 'Package leave save successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function viewPackageLeave(Request $request)

    {   

        if($request->customerId && $request->vendorId && $request->package_id)

        {   

            $exist=Package::where('id',$request->package_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if(!$exist){

                return $this->sendError('Package not available!');

            }

            else{

                $data=PackageLeave::where('package_id',$request->package_id)->get();

                if($data){ 

                        

                    return $this->sendResponse($data, 'Loading...');

                } 

                else{ 

                    return $this->sendError('No leave for this package!.');

                }

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function savePackageAddons(Request $request)

    {   

        if($request->customerId && $request->vendorId  && sizeof($request->shop_items)>0)

        {   



            $input=[

                'customer_id' => $request->customerId,

                'vendor_id'=> $request->vendorId,

                'addon_date'=>$request->addon_date,

                'status'=>'Pending'

            ];

            $lastId=PackageAddons::create($input)->id;

            if($lastId){ 



                $inputs=[];

                foreach($request->shop_items as $item)

                {

                    $inputs[]=['package_addons_id'=>$lastId,'shop_item_id'=>$item['shop_item_id'],'qty'=>$item['qty']];

                }

                PackageAddonItems::insert($inputs);

                return $this->sendResponse($lastId, 'Package add-ons save successfully!');

            } 

            else{ 

                return $this->sendError('Try Later!.');

            }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function addonsItemsUpdate(Request $request)

    {   

        if($request->addon_id && $request->customerId && $request->vendorId && sizeof($request->shop_items)>0)

        {   

            $exist=PackageAddons::where('id',$request->addon_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            

            if(!$exist){

                return $this->sendError('Package add-ons not available!');

            }

            else{



                PackageAddonItems::where('package_addons_id', $request->addon_id)->delete();

                $inputs=[];

                foreach($request->shop_items as $item)

                {

                    $inputs[]=['package_addons_id'=>$request->addon_id,'shop_item_id'=>$item['shop_item_id'],'qty'=>$item['qty']];

                }

                $res=PackageAddonItems::insert($inputs);

                return $this->sendResponse($res, 'Package addon items update successfully!');

                

            }

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function packageAddonCancel(Request $request)

    {   

        if($request->addon_id && $request->customerId && $request->vendorId)

        {   

            $data = PackageAddons::where('id',$request->addon_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->status = 'Cancel';

                    $res=$data->save();

                    return $this->sendResponse($res, 'Package Add-ons Request cancel successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function addonRequestStatusUpdate(Request $request)

    {   

        if($request->addon_id && $request->customerId && $request->vendorId && $request->status)

        {   

            $data = PackageAddons::where('id',$request->addon_id)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->status = $request->status;

                    $res=$data->save();

                    return $this->sendResponse($res, 'Package Add-ons Request update successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function vendorPackageAddonList(Request $request)

    {   

        if($request->customerId)

        {   



            $query=PackageAddons::where('customer_id',$request->customerId);

            if($request->vendorId)

            {

                $query->where('vendor_id',$request->vendorId);

            }

            if($request->status)

            {

                $query->where('status',$request->status);

            }

            if($request->date)

            {

                $query->whereDate('addon_date',$request->date);

            }

            $package=$query->with('items')->with('vendor')->get();

            if($package){ 

                return $this->sendResponse($package, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Packages Add-ons Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function customerPackageAddonList(Request $request)

    {

        if($request->vendorId)

        {   



            $query=PackageAddons::where('vendor_id',$request->vendorId);

            if($request->customerId)

            {

                $query->where('customer_id',$request->customerId);

            }

            if($request->status)

            {

                $query->where('status',$request->status);

            }

            if($request->date)

            {

                $query->whereDate('addon_date',$request->date);

            }

            $package=$query->with('items')->with('customer')->get();

            if($package){ 

                return $this->sendResponse($package, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Packages Add-ons Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function packageAddonInfo(Request $request)

    {   

        if($request->addon_id)

        {   

            $package=PackageAddons::where('id',$request->addon_id)->with('items')->first();

            if($package){ 

                return $this->sendResponse($package, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Package Add-ons Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function todayOrdersGenerate(Request $request)

    {

        

        if($request->vendorId)

        {   



            Order::where('vendor_id',$request->vendorId)->whereDate('order_date', Carbon::today())->delete();

            $result=false;



            $orders=todayOrders($request->vendorId);

            

            if($orders['packages'] || $orders['packageAddons']){ 

            	$data=[];

            	if($orders['packages'])

                {

                    foreach ($orders['packages'] as $key => $value) {

                        $data=array(

                        "package_id"=>$value->id,

                        "vendor_id"=>$value->vendor_id,

                        "customer_id"=>$value->customer_id,

                        "order_status"=>'Pending',

                        "order_date"=>Carbon::today(),

                        "total_amount"=>0,

                        );



                        $lastId=Order::create($data)->id;

                        if($lastId){ 

                                $result=true;

                                $items=array();

                                $total=0;

                                foreach($value->items as $item)

                                {       

                                    $total+=$item->qty*$item->shopItem->price;

                                    $items[]=[

                                            'order_id'=>$lastId,

                                            'shop_item_id'=>$item->shopItem->id,

                                            'item_id'=>$item->shopItem->item_id,

                                            'item_name'=>$item->shopItem->item->name,

                                            'item_brand'=>$item->shopItem->item->brand,

                                            'item_unit'=>$item->shopItem->quantity.' '.$item->shopItem->unit,

                                            'item_qty'=>$item->qty,

                                            'item_price'=>$item->shopItem->price,

                                            'item_total'=>$item->qty*$item->shopItem->price,

                                            'item_icon'=>$item->shopItem->item->icon

                                        ];

                                    

                                }

                                OrderItem::insert($items);

                                Order::where('id', $lastId)->update(['total_amount' => $total]);

                        }

                    }

                }

                if($orders['packageAddons'])

                {

                    foreach ($orders['packageAddons'] as $key => $value) {

                        $data=array(

                        "package_id"=>$value->id,

                        "vendor_id"=>$value->vendor_id,

                        "customer_id"=>$value->customer_id,

                        "order_status"=>'Pending',

                        "order_date"=>Carbon::today(),

                        "total_amount"=>0,

                        "is_extra_order"=>1

                        );



                        $lastId=Order::create($data)->id;

                        if($lastId){ 

                                $result=true;

                                $items=array();

                                $total=0;

                                foreach($value->items as $item)

                                {   

                                    $total+=$item->qty*$item->shopItem->price;

                                    $items[]=[

                                            'order_id'=>$lastId,

                                            'shop_item_id'=>$item->shopItem->id,

                                            'item_id'=>$item->shopItem->item_id,

                                            'item_name'=>$item->shopItem->item->name,

                                            'item_brand'=>$item->shopItem->item->brand,

                                            'item_unit'=>$item->shopItem->quantity.' '.$item->shopItem->unit,

                                            'item_qty'=>$item->qty,

                                            'item_price'=>$item->shopItem->price,

                                            'item_total'=>$item->qty*$item->shopItem->price,

                                            'item_icon'=>$item->shopItem->item->icon

                                        ];

                                }

                                OrderItem::insert($items);

                                Order::where('id', $lastId)->update(['total_amount' => $total]);

                                if($value->package_type=='Alternate')

                                {

                                    Package::where('id', $value->id)->update(['week_day' => Carbon::now()->addDays(2)->format('l')]);

                                }

                        } 

                    }

                }

                return $this->sendResponse($result, 'Loading...');

            } 

            else{ 

                return $this->sendError('No Packages Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function todayOrders(Request $request)

    {

    	if($request->vendorId || $request->customerId)

        {   

            $query=Order::whereDate('order_date', Carbon::today());

            if($request->customerId)

            {

                $query->where('customer_id',$request->customerId);

            }

            if($request->vendorId)

            {

                $query->where('vendor_id',$request->vendorId);

            }

            $orders=$query->with('items')->with('customer')->with('vendor')->get();

            if($orders)

            {

                return $this->sendResponse($orders, 'Loading...');

            } 

            else{ 

                return $this->sendError('No order Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function todayOrdersItemWise(Request $request)

    {

    	if($request->vendorId || $request->customerId)

        {   



            $query = DB::table('order_items');

            $query->join('orders', 'orders.id', '=', 'order_items.order_id');

            $query->where('orders.order_date',Carbon::today());

            if($request->customerId)

            {

                $query->where('orders.customer_id',$request->customerId);

            }

            if($request->vendorId)

            {

                $query->where('orders.vendor_id',$request->vendorId);

            }

            $query->groupBy('shop_item_id')->selectRaw('shop_item_id,item_id,item_name,item_brand,item_unit,item_price,sum(item_qty) as total_qty,item_icon');

            $orders= $query->get();

            if($orders)

            {

                return $this->sendResponse($orders, 'Loading...');

            } 

            else{ 

                return $this->sendError('No order Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function orderStatusUpdate(Request $request)

    {   

        if($request->orderId && $request->customerId && $request->vendorId && $request->status)

        {   

            $data = Order::where('id',$request->orderId)->where('vendor_id',$request->vendorId)->where('customer_id',$request->customerId)->first();

            if($data){ 

                    $data->order_status = $request->status;

                    $res=$data->save();

                    return $this->sendResponse($res, 'Order status update successfully!');

                } 

                else{ 

                    return $this->sendError('Try Later!.');

                }

            

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function monthOrders(Request $request)

    {

        if($request->vendorId || $request->customerId)

        {   

            if($request->month && $request->year)

            {

                $query=Order::whereMonth('order_date',$request->month)->whereYear('order_date', $request->year);

            }

            else

            {

                $query=Order::whereMonth('order_date', Carbon::today()->format('m'))->whereYear('order_date', Carbon::today()->format('Y'));

            }

            if($request->customerId)

            {

                $query->where('customer_id',$request->customerId);

            }

            if($request->vendorId)

            {

                $query->where('vendor_id',$request->vendorId);

            }



            $orders=$query->with('items')->with('customer')->with('vendor')->orderBy('order_date','DESC')->get();

            if($orders)

            {

                return $this->sendResponse($orders, 'Loading...');

            } 

            else{ 

                return $this->sendError('No order Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function invoices(Request $request)

    {

        if($request->vendorId || $request->customerId)

        {   



            $query=Invoice::orderBy('created_at','DESC');

            if($request->customerId)

            {

                $query->where('customer_id',$request->customerId);

            }

            if($request->vendorId)

            {

                $query->where('vendor_id',$request->vendorId);

            }

            if($request->month && $request->year)

            {

                $query->where('month',$request->month)->where('year', $request->year);

            }

            $invoices=$query->with('customer')->with('vendor')->get();

            if($invoices)

            {

                return $this->sendResponse($invoices, 'Loading...');

            } 

            else{ 

                return $this->sendError('No order Found!');

            } 

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }

    public function page_content(Request $request)

    {

        $data=DB::table('settings')->where('type','page')->where('title',$request->page)->first();

        return $this->sendResponse($data, 'Loading...');

    }

    public function transections(Request $request)

    {

        $transections=Transection::where('customer_id',auth()->user()->id)->get();

        if($transections)

        {

            return $this->sendResponse($transections, 'All ransactions.');

        } 

        else{ 

            return $this->sendError('No transections Found!');

        } 

    }

    public function walletBalance(Request $request)

    {

        if($request->vendorId && $request->customerId)

        {   

            $balance=getWallet($request->vendorId,$request->customerId);

            return $this->sendResponse($balance, 'Loading...');

        }

        else

        {

            return $this->sendError('Required field is empty!');

        }

    }



    public function payment_save_api(Request $request)

    {

        if($request->vendorId)

        {

           $request->validate([

            'amount' => 'required|numeric',

            'remark' => 'required|string'

          ]);

        $input=[

                    'customer_id' => auth()->user()->id,

                    'vendor_id'=> $request->vendorId,

                    'amount'=>$request->amount,

                    'remark'=>$request->remark,

                    'type'=>'Dr'

                ];



        $res=Transection::create($input);

        if($res)

        {

            // updateWallet($request->vendorId,auth()->user()->id,$request->amount,'Dr');
            auth()->user()->update([
                "wallet_balance" => (int) auth()->user()->wallet_balance - (int) $request->amount
            ]);

             return $this->sendResponse($res, 'Loading...');        

        }

        else

        {

            return response()->json($data = [

            'status' => 201,

            'msg' => 'Data Not Found'

            ]);

        }

       }

       else

       {

        return $this->sendError('Required field is empty!');

       }
   }

   public function home(Request $request)
   {
       $res = [];
   
       // --------------------- Slider Data -------------------------
       $sliderData = [];
       $success = Slider::first();
       
       // First slider
       $sliderData[] = [
           'id' => (string) $success->id,
           'image' => url('uploads/slider/'.$success->app_first_slider),
           'link' => $success->app_first_link ?? "",
           'title' => $success->app_first_title ?? "",
           'description' => $success->app_first_description ?? ""
       ];
   
       // Second slider
       $sliderData[] = [
           'id' => "2",
           'image' => url('uploads/slider/'.$success->app_second_slider),
           'link' => $success->app_second_link ?? "",
           'title' => $success->app_second_title ?? "",
           'description' => $success->app_second_description ?? ""
       ];
   
       // Third slider
       $sliderData[] = [
            'id' => "3",
           'image' => url('uploads/slider/'.$success->app_third_slider),
           'link' => $success->app_third_link ?? "",
           'title' => $success->app_third_title ?? "",
           'description' => $success->app_third_description ?? ""
       ];
   
       // Push Slider data into response
       $res['slider_data'] = $sliderData;
   
       // --------------------- All Services -------------------------
       $serviceData = [];
       $services = Service::where('status', 1)->get();
       
       foreach ($services as $service) {
            if(ServiceAttributeValueItem::where('service_id',$service->id)->with('sub_category')->first()->sub_category){
                $serviceData[] = [
                    'id' => (string) $service->id,
                    'title' => $service->name,
                    'image' => \URL::to('/').'/uploads/service/'.$service->thumbnail_img,
                    'price' => (string) $service->price,
                    'sub_cate_yes' => 'Yes',
                ];
            } else {
                $serviceData[] = [
                    'id' => (string) $service->id,
                    'title' => $service->name,
                    'image' => \URL::to('/').'/uploads/service/'.$service->thumbnail_img,
                    'price' => (string) $service->price,
                    'sub_cate_yes' => 'No',
                ];
            }
          
       }
   
       // Push Service data into response
       $res['services'] = $serviceData;
   
       // -------------------------- Categories ---------------------

        $services = Service::where('status', 1)->get();
        $serviceData = [];
        foreach ($services as $service) {
            if(ServiceAttributeValueItem::where('service_id',$service->id)->with('sub_category')->first()->sub_category){
                $serviceData[] = [
                    'id' => (string) $service->id,
                    'title' => $service->name,
                    'image' => \URL::to('/').'/uploads/service/'.$service->thumbnail_img,
                    'sub_cate_yes' => 'Yes',
                ];
            }else{
                $serviceData[] = [
                    'id' => (string) $service->id,
                    'title' => $service->name,
                    'image' => \URL::to('/').'/uploads/service/'.$service->thumbnail_img,
                    'sub_cate_yes' => 'No',
                ];
            }
        }
   
       // Push Category data into response
       $res['categories'] = $serviceData;
   
       // ------------------------------- Banner ---------------------
        $bannerData = [
            [
                'id' => '1',
                'image' => url('uploads/signup-slider/'.$success->app_sign_first_slider),
                'short_description' => $success->app_sign_first_link ?? "",
                'title' => $success->app_sign_first_title ?? "",
                'type' => "service"
            ],
            [
                'id' => '2',
                'image' => url('uploads/signup-slider/'.$success->app_sign_second_slider),
                'short_description' => $success->app_sign_second_link ?? "",
                'title' => $success->app_sign_second_title ?? "",
                'type' => "service"
            ],
            [
                'id' => '3',
                'image' => url('uploads/signup-slider/'.$success->app_sign_third_slider),
                'short_description' => $success->app_sign_third_link ?? "",
                'title' => $success->app_sign_third_title ?? "",
                'type' => "service"
            ],
            [
                'id' => '4',
                'image' => url('uploads/signup-slider/'.$success->app_sign_for_slider),
                'short_description' => $success->app_sign_for_link ?? "",
                'title' => $success->app_sign_for_title ?? "",
                'type' => "service"
            ]
        ];    
   
       // Push Banner data into response
       $res['banner_data'] = $bannerData;
   
       // ---------------------- Most Booking Service --------------
       $mostBookedServices = [];
       $featuredServices = Service::where('status', 1)->where('featured', 1)->orderBy('id', 'DESC')->get();
       
       foreach ($featuredServices as $service) {
        if(ServiceAttributeValueItem::where('service_id',$service->id)->with('sub_category')->first()->sub_category){
           $mostBookedServices[] = [
                'id' => (string) $service->id,
                'title' => $service->name,
                'price' => (string) $service->price,
                'image' => \URL::to('/').'/uploads/service/featured_banner/'.$service->featured_banner,
                'sub_cate_yes' => 'Yes',
            ];
        }else{
            $mostBookedServices[] = [
                'id' => (string) $service->id,
                'title' => $service->name,
                'price' => $service->price,
                'image' => \URL::to('/').'/uploads/service/featured_banner/'.$service->featured_banner,
                'sub_cate_yes' => 'No',
            ];
        }
       }
   
       // Push Most Booked Services data into response
       $res['most_booked_services'] = $mostBookedServices;
   
       // ---------------------- Get Service By Category --------------
       $category_id = 10;
       $serviceIds = ServiceAttributeValueItem::where('category_id', $category_id)
                   ->distinct()
                   ->pluck('service_id');
   
       $servicesByCategory = Service::where('status', 1)
                       ->whereIn('id', $serviceIds)
                       ->get();
   
       // Push Services by Category data into response
       $res['services_by_category'] = $servicesByCategory;
   
       return $this->sendResponse($res, 'Home Data.');
   }
   

   public function slider(Request $request)
   {
       $res = [];
       $success = Slider::first();

       $data['slider'] = url('uploads/slider/'.$success->app_first_slider);
       $data['link'] = $success->app_first_link ?? "";
       $data['title'] = $success->app_first_title ?? "";
       $data['description'] = $success->app_first_description ?? "";
       array_push($res, $data);
       $data1['slider'] = url('uploads/slider/'.$success->app_second_slider);
       $data1['link'] = $success->app_second_link ?? "";
       $data1['title'] = $success->app_second_title ?? "";
       $data1['description'] = $success->app_second_description ?? "";
       array_push($res, $data1);
       $data2['slider'] = url('uploads/slider/'.$success->app_third_slider);
       $data2['link'] = $success->app_third_link ?? "";
       $data2['title'] = $success->app_third_title ?? "";
       $data2['description'] = $success->app_third_description ?? "";
       array_push($res, $data2);
       
       return $this->sendResponse($res, 'Hoem Slider.');
   }

   public function sing_up_banner(Request $request)
   {
       $success = Slider::first();

       $data['first_banner'] = url('uploads/signup-slider/'.$success->app_sign_first_slider);
       $data['first_short_description'] = $success->app_sign_first_link ?? "";
       $data['first_title'] = $success->app_sign_first_title ?? "";

       $data['second_banner'] = url('uploads/signup-slider/'.$success->app_sign_second_slider);
       $data['second_short_description'] = $success->app_sign_second_link ?? "";
       $data['second_title'] = $success->app_sign_second_title ?? "";

       $data['third_slider'] = url('uploads/signup-slider/'.$success->app_sign_third_slider);
       $data['third_short_description'] = $success->app_sign_third_link ?? "";
       $data['third_title'] = $success->app_sign_third_title ?? "";

       $data['four_slider'] = url('uploads/signup-slider/'.$success->app_sign_for_slider);
       $data['four_short_description'] = $success->app_sign_for_link ?? "";
       $data['four_title'] = $success->app_sign_for_title ?? "";

       return $this->sendResponse($data, 'Sing up Banner.');
   }

    public function update_bank(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'bank_name'      => 'required|string|max:255', 
                'ac_holder_name' => 'required|string|max:255', 
                'ac_number'      => 'required|numeric|digits_between:10,20', 
            ]);

            $userId = auth()->user()->id;
            $seller = Seller::where('user_id', $userId)->first();

            if (!$seller) {
                return response()->json(['success' => false, 'message' => 'Seller not found'], 404);
            }

            $seller->update([
                'bank_name'      => $validatedData['bank_name'],
                'ac_holder_name' => $validatedData['ac_holder_name'],
                'ac_number'      => $validatedData['ac_number'],
            ]);

            return response()->json([
                'success' => "1",
                'error' => "",
                'data' => [],
                'message' => 'Bank details updated successfully',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => "0",
                'message' => 'Validation error',
                'error' => $e->errors(),
                'data'   => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'data'   => [],
            ], 200);
        }
    }

    public function update_license(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'address'       => 'required|string|max:255',
                'licence_file'  => 'required|file|mimes:pdf,jpg,png,doc|max:2048', 
                'services'      => 'required|string', 
            ]);

            $user = auth()->user();
            $userId = $user->id;
            
            $userFolder = storage_path('app/public/licenses/' . $userId); 

            if (!File::exists($userFolder)) {
                File::makeDirectory($userFolder, 0755, true);
            }

            $licenseFile = $request->file('licence_file');
            $licenseFileName = time() . '_' . $licenseFile->getClientOriginalName(); 
            $licenseFilePath = $licenseFile->storeAs('public/licenses/' . $userId, $licenseFileName); 

            $seller = Seller::where('user_id', $userId)->first();
            if ($seller) {
                $seller->update([
                    'address' => $validatedData['address'],
                    'licence_file' => $licenseFilePath,
                ]);
            } else {
                
                return response()->json([
                    'success' => "0", 
                    'error' => "", 
                    'data' => [], 
                    'message' => 'Seller not found'
                ], 200);
            }

            $services = explode(',', $validatedData['services']); 

            SellerService::where('seller_id', $seller->id)->delete();

            foreach ($services as $serviceId) {
                SellerService::create([
                    'seller_id' => $seller->id,
                    'service_id' => (int)$serviceId, 
                ]);
            }

            return response()->json([
                'success' => "1",
                'error' => "",
                'data' => [],
                'message' => 'License updated successfully',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success'   => "0",
                'message'   => 'Validation error',
                'error'     => $e->errors(),
                'data'      => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'data'    => [],
            ], 200);
        }
    }

    public function add_or_update_business_information(Request $request)
    {
        try {
            // Validation rules
            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'name'         => 'required|string|max:255',
                'email'        => 'required|email|max:255|unique:sellers,email,' . auth()->user()->id . ',user_id',
                'landline_no'  => 'nullable|string|max:20',
                'locality'     => 'required|string|max:255',
                'city'         => 'required|integer|exists:cities,id',
                'dial_code'    => 'required|string|max:10',
                'phone'        => 'required|string|max:20|unique:sellers,phone,' . auth()->user()->id . ',user_id',
            ]);
    
            // Check if seller information already exists for the authenticated user
            $seller = Seller::where('user_id', auth()->user()->id)->first();
    
            // If seller exists, update it; otherwise, create a new one
            if ($seller) {
                $seller->update($validatedData);
                $message = 'Business Information updated successfully';
            } else {
                $validatedData['user_id'] = auth()->user()->id;
                $seller = Seller::create($validatedData);
                $message = 'Business Information added successfully';
            }
    
            return $this->sendResponse($seller, $message);
    
        } catch (ValidationException $e) {
            return response()->json([
                'success' => "0",
                'message' => 'Validation error',
                'error'   => $e->errors(),
                'data'    => [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => "0",
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'data'    => [],
            ], 200);
        }
    }
    

   public function add_address(Request $request)
   {
       $params['user_id']       = auth()->user()->id;
       $params['address']       = $request->address;
       $params['address_type']  = $request->address_type;
       $params['flat_no']       = $request->flat_no;
       $params['building']      = $request->building;
       $params['locality']      = $request->locality;
       $params['latitude']      = $request->latitude;
       $params['longitude']     = $request->longitude;
       $params['city_id']       = $request->city_id;
       $params['is_active']     = "1";
       Address::where('user_id', auth()->user()->id)->update(['is_active'=>0]);
       $res = Address::create($params);
       return $this->sendResponse($res, 'Add Address Successfully');
   }

   public function edit_address(Request $request, $address_id='')
   {
        if($address_id)
        {
            $address = Address::find($address_id);
            $params['address']       = $request->address;
            $params['address_type']  = $request->address_type;
            $params['flat_no']       = $request->flat_no;
            $params['building']      = $request->building;
            $params['locality']      = $request->locality;
            $params['latitude']      = !empty($request->latitude) ? $request->latitude : "";
            $params['longitude']     = !empty($request->longitude) ? $request->longitude : "";
            $params['city_id']       = $request->city_id;
            $res = $address->update($params);
            if($res){
                return $this->sendResponse($params, 'Address Update Successfully');
            } else {
                return $this->sendResponse([], 'Try again');
            }
            
        } else {
            return $this->sendResponse([], 'Required field is empty');
        }
   }

   public function delete_address($user_id='', $address_id='')
   {
        if($user_id && $user_id!=='null')
        {
            $data = [];

            $address = Address::find($address_id);            
         
            if ($address) {
                $data = $address->delete();
            
                return response()->json(['message' => 'Address deleted successfully', 'data' => $data], 200);
            } else {
                return response()->json(['error' => 'Address not found'], 404);
            }
            
        } else {
            return $this->sendResponse([], 'Required field is empty');
        }
   }

   public function update_address($address_id='')
   {
     
            $data = [];

            $user_addresses = Address::where('user_id', auth()->user()->id)->get();

            foreach ($user_addresses as $addresses) {
                $addresses->update(['is_active' => 0]);
            }
            
            $address = Address::find($address_id);            
         
            if ($address) {
                $data = $address->update(['is_active'=>1]);
            
                return response()->json(['message' => 'Address updated successfully', 'data' => $data], 200);
            } else {
                return response()->json(['error' => 'Address not found'], 404);
            }
            
       
   }

   public function user_address()
   {
        
            $data = [];
            $address = Address::where('user_id',auth()->user()->id)->get();
            foreach ($address as $key => $value) {
                $params['id']           = (string) $value->id;
                $params['address']      = $value->address;
                $params['address_type'] = $value->address_type;
                $params['flat_no']      = $value->flat_no;
                $params['building']     = $value->building;
                $params['locality']     = $value->locality;
                $params['latitude']     = $value->latitude;
                $params['longitude']    = $value->longitude;
                $params['is_active']    = $value->is_active;
                $params['city_id']      = (string) $value->city_id;
                $params['city']         = City::where('id',$value->city_id)->value('name');
                array_push($data, $params);
            }
            
         
            if($data){
                return $this->sendResponse($data, 'User address');
            } else {
                return $this->sendResponse([], 'Try again');
            }
            
       
   }

   public function get_tips()
   {
        $data = Tip::get();
        return $this->sendResponse($data, 'Tips');
   }

   public function get_coupon()
   {
        $data = Coupon::where('status','1')->get();
        return $this->sendResponse($data, 'Coupons');
   }

   public function get_particular_coupon(Request $request)
   {
        $data = Coupon::where('status','1')->where('code',$request->code)->first();
        return $this->sendResponse($data, 'Coupons');
   }

   public function apply_coupon(Request $request)
   {
        $coupon = Coupon::where('code',$request?$request->coupon_code:'')->where('status','1')->first();
        if(!empty($coupon)){
            if($coupon->start_date <= date('Y-m-d') && $coupon->end_date >= date('Y-m-d')){

                $card = Card::where(['user_id'=>auth()->user()->id])
                ->where('is_checkout', '!=', 'Done')
                ->orderBy('id', 'DESC')
                ->get();
                
                if($card){
                    
                    $cards = [];
                    $total_amount = 0;
                    $coupon_amount = 0;
        
                    foreach ($card as $key => $value) {
                        if(empty($value->coupon_id)){
                            $total_amount += (int) $value->amount;
                            $coupon_amount += (int) $coupon->amount;

                            $params['coupon_id']    = $coupon->id;
                            $params['coupon_amt']   = $coupon->amount;
                            
                            $value->update($params);
                        }
                    }
        
                    $cards['before_total']     = (string) $total_amount;
                    $cards['after_total']     = (string) ((int) $total_amount - (int) $coupon_amount);

                } else {
                    return $this->sendResponse([], 'Checkout is empty');
                }
                return $this->sendResponse($cards, 'Coupon Applied');
            } else {
                return $this->sendResponse([], 'This Coupon is expired');
            }
        } else {
            return $this->sendResponse([], 'Coupon is not exist');
        }
   }

   public function get_city(Request $request)
   {
        $data['city'] = City::all();
        return $this->sendResponse($data, 'City');
   }

   public function get_locality(Request $request)
   {
        if($request->city_id){
            $locality = Locality::where('city_id',$request->city_id)->get();
            $datas = [];
            $ext['id'] = '';
            $ext['city_id'] = '';
            $ext['name'] = 'None';
            array_push($datas, $ext);
            foreach ($locality as $key => $value) {
                $params['id'] = $value->id;
                $params['city_id'] = $value->city_id;
                $params['name'] = $value->name;

                array_push($datas, $params);
            }
            $rwes['locality'] = $datas;
            return $this->sendResponse($rwes, 'Locality');
        } else {
            return $this->sendResponse([], 'City id is required');  
        }        
   }

   public function get_settings(Request $request)
   {
        $setting = HomeSetting::first();
        $data['min_cart_value'] = $setting->min_cart_value;
        $data['cash_surcharge'] = $setting->cash_surcharge;
       
        return $this->sendResponse($data, 'Settings');
   }


   public function get_contact(Request $request)
   {
        
        $data['contact1'] = '585814007';
        $data['contact2'] = '526188291';
       
        return $this->sendResponse($data, 'Contact');
   }

   public function checkNotification()
   {
        $service_id = '15';

        $data = get_seller_info_by_service($service_id);
        foreach ($data as $key => $value) {
            if(isset($value->device_token)){
                $token = $value->device_token;
                           
                $title  = 'Urbanmop';
                $body   = 'Welcome Urbanmop Team';
                $text   = 'Text';

                $data = send_notification($token, $title, $body, $text);
            }
        }
        return $data;
        $service_id = '1';
        $token = 'cLHuN6bnSbSUUAUaC1gnOC:APA91bGEO9En7N_IZoZzyIAxZAKZcZ8GokIdKA4nrMBvJMHVPTmRUiV0yQXBH4F6bqyTj7fhc_UBYE94-n33baSY0-bGu0Pbh-XfpSQjKSYIaSh5hlcbLdXwjsTVWHFcpgjRdtgpPL4D';
                           
        $title  = 'Urbanmop';
        $body   = 'Welcome Urbanmop Team';
        $text   = 'Text';

        $data = send_notification($token, $title, $body, $text);

        return $data;
   }

   public function version()
   {
        $data['version'] = '29';
        $data['iosversion'] = '48';
       
        return $this->sendResponse($data, 'Version');
   }

   public function vendor_version()
   {
        $data['version'] = '24';
        $data['iosversion'] = '3.2';
       
        return $this->sendResponse($data, 'Version');
   }

   public function vendor_noti()
    {
        $data = Notification::where('type','Vendor')->orderBy('id', 'DESC')->select('id','type','title','description')->get();
        return $this->sendResponse($data, 'Vendor Notification');
    }

    public function customer_noti()
    {
        $data = Notification::where('type','Customer')->orderBy('id', 'DESC')->select('id','type','title','description')->get();
        return $this->sendResponse($data, 'Customer Notification');
    }

    function payment_qr()
    {
        $setting = HomeSetting::first();

        if($setting && $setting->payment_barcode){
            return $this->sendResponse($setting->payment_barcode, 'Payment QR');
        } else {
            return $this->sendError('Payment QR Not Found!');
        }
    }
    
    public function test_sms(Request $request){
        $msg="Welcome to kisaanhelpline, your verification code is 1111 Regards: KH24 AGRO VENTURE Pvt. LTD.";

        send_sms('+923042721336',$msg);
    }
    
    public function wallet_payment_init(Request $request)
    {
        $status = "0";
        $o_data = [];
        $errors = [];
        $message = "Unable to initialize the payment";

        $user_id = auth()->user()->id;
        if($user_id == 0){
            $message = "session expired please login to continue";
            return response()->json([
                'status' => (string)$status,
                'message' => $message,
                'data' => (object)$o_data,
                'errors' => (object)$errors
            ],401);
        }

        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {


            $cards = Card::where(['user_id'=>auth()->user()->id])
            ->where('is_checkout', '!=', 'Done')
            ->get();
            $subtotal = 0;
            $total    = 0;
            if (!$cards->isEmpty()) {
                foreach ($cards as $card) {
                    if($card){
                        if(!empty($request->tip_amount)){
                            $data['tip_id']         = $request->tip_amount;
                        }
                        if(!empty($request->payment_type)){
                            $data['payment_moad']   = (string) $request->payment_type;
                        }
                        $data['card_process']   = 'Complete';
                        $data['payment_status'] = "True";
                        $data['payment_type']   = $request->payment_type;
                        $data['booking_from']   = 'App';
        
                        $subtotal += (int)$card->g_total;
                        $tip = 0;
                        if(!empty($request->tip_amount)){
                            // $tip = (int) (Tip::find($request->tip_id)->amount);
                            $tip = (int) $request->tip_amount;
                        }
                        $total += (int)$card->amount+$tip;
                        $data['amount'] = (string) ((int)$card->amount+(int)$tip);
                        $card->update($data);
                    }
                }
            }
            $amount = $total;
            
            if($request->payment_type == 3){
                $user = User::find($user_id);
                if($user->wallet_balance < $amount){
                    return response()->json(['status' => "0", 'message' => 'You dont have enough amount in your wallet', 'errors' => (object)[], 'oData' => (object)[]]);
                }
            }

            if($amount <= 1){
                return response()->json(['status' => "0", 'message' => 'Checkout empty', 'errors' => (object)[], 'oData' => (object)[]]);
            }

            $user = User::find($user_id);
            
            \Stripe\Stripe::setApiKey('sk_test_51KdqxdBjsMxFtgBedbg8geva4BTxE3rcJrjCY2YCYblxn8hcEC2l7mIIPMClBWzyPdOWbNyUKSFjLo7Sl9ZI7Ujf00jVnhJDUA');
                $checkout_session = \Stripe\PaymentIntent::create([
                    'amount' => $amount * 100,
                    'currency' => 'AED',
                    'description' => 'Wallet Recharge (via App)',
                    'shipping' => [
                        'name' => $user->name ?? $user->first_name . ' ' . $user->last_name,
                        'address' => [
                            'line1' => 'dubai mall',
                            'city' => 'dubai',
                            'state' => 'dubai',
                            'country' => 'uae',
                        ],
                    ],
                ]);

            $ref = $checkout_session->id;
            $invoice_id = $user_id . uniqid() . time();
            $paymentreport = [
                'transaction_id' => $invoice_id,
                'payment_status' => 'P',
                'user_id' => $user->id,
                'ref_id' => $ref,
                'amount' => $amount,
                'method_type' => $request->payment_type,
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            WalletPaymentReport::insert($paymentreport);
            $o_data['payment_ref'] = $checkout_session->client_secret;
            $o_data['invoice_id'] = $invoice_id;
            $status ="1";
            $message = "";

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object)$errors, 'oData' => (object)$o_data]);
    }

    public function wallet_recharge(Request $request)
    {
        $status = "0";
        $o_data = [];
        $errors = [];
        $message = "Failed to recharge the wallet";

        $user_id = auth()->user()->id;
        if($user_id == 0){
            $message = "session expired please login to continue";
            return response()->json([
                'status' => (string)$status,
                'message' => $message,
                'data' => (object)$o_data,
                'errors' => (object)$errors
            ],401);
        }
        
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $payment_det = WalletPaymentReport::where(['transaction_id' => $request->invoice_id, 'user_id' => $user_id, 'payment_status' => 'P'])->first();
            if ($payment_det) {
                $payamount = $payment_det->amount;
                $user = User::find($user_id);
                if ($user !== null) {
                    $user->wallet_balance = $user->wallet_balance + $payamount;
                    if ($user->save()) {
                        $data = [
                            'user_id' => $user_id,
                            'wallet_amount' => $payamount,
                            'pay_type' => 'RECHARGED',
                            'pay_method' => $payment_det->method_type,
                            'description' => 'Wallet Top up ',
                        ];

                        if (wallet_history($data)) {
                            WalletPaymentReport::where(['transaction_id' => $request->invoice_id, 'user_id' => $user_id])->update(['payment_status' => 'A']);
                            $status = "1";
                            $message = "Wallet recharged successfully";
                        }
                    }
                }
            }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object)$errors, 'oData' => (object)$o_data]);
    }

    public function wallet_init(Request $request)
    {
        $status = "0";
        $o_data = [];
        $errors = [];
        $message = "Failed to recharge the wallet";
        
        $user_id = auth()->user()->id;
        if($user_id == 0){
            $message = "session expired please login to continue";
            return response()->json([
                'status' => (string)$status,
                'message' => $message,
                'data' => (object)$o_data,
                'errors' => (object)$errors
            ],401);
        }
        
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_type' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user = User::find($user_id);

            \Stripe\Stripe::setApiKey('sk_test_51KdqxdBjsMxFtgBedbg8geva4BTxE3rcJrjCY2YCYblxn8hcEC2l7mIIPMClBWzyPdOWbNyUKSFjLo7Sl9ZI7Ujf00jVnhJDUA');
            $checkout_session = \Stripe\PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => 'AED',
                'description' => 'Wallet Recharge (via App)',
                'shipping' => [
                    'name' => $user->name ?? $user->first_name . ' ' . $user->last_name,
                    'address' => [
                        'line1' => 'dubai mall',
                        'city' => 'dubai',
                        'state' => 'dubai',
                        'country' => 'uae',
                    ],
                ],
            ]);

            $ref = $checkout_session->id;
            $invoice_id = $user_id . uniqid() . time();
            $paymentreport = [
                'transaction_id' => $invoice_id,
                'payment_status' => 'P',
                'user_id' => $user->id,
                'ref_id' => $ref,
                'amount' => $request->amount,
                'method_type' => $request->payment_type,
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            WalletPaymentReport::insert($paymentreport);
            $o_data['payment_ref'] = $checkout_session->client_secret;
            $o_data['invoice_id'] = $invoice_id;
            $message = "Wallet Recharge (via App)";
            $status = "1";

            // if ($user !== null) {
            //     $user->wallet_balance = $user->wallet_balance + $payamount;
            //     if ($user->save()) {
            //         $data = [
            //             'user_id' => $user_id,
            //             'wallet_amount' => $payamount,
            //             'pay_type' => 'RECHARGED',
            //             'pay_method' => $payment_det->method_type,
            //             'description' => 'Wallet Top up ',
            //         ];

            //         if (wallet_history($data)) {
            //             WalletPaymentReport::where(['transaction_id' => $request->invoice_id, 'user_id' => $user_id])->update(['payment_status' => 'A']);
            //             $status = "1";
            //             $message = "Wallet recharged successfully";
            //         }
            //     }
            // }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object)$errors, 'oData' => (object)$o_data]);
    }

    public function wallet_details(Request $request)
    {
        $status = "1";
        $o_data = [];
        $errors = [];
        $message = "";

        $user_id = auth()->user()->id;
        if ($user_id == 0) {
            $message = "session expired please login to continue";
            return response()->json([
                'status' => (string)$status,
                'message' => $message,
                'data' => (object)$o_data,
                'errors' => (object)$errors
            ], 401);
        }

        // Fetch last transaction details
        $last_history_det = \App\WalletHistory::where(['user_id' => $user_id])
            ->orderBy('id', 'desc')
            ->first();

        $user = User::find($user_id);

        // Fetch all wallet history
        $wallet_history = \App\WalletHistory::where(['user_id' => $user_id])
            ->orderBy('id', 'desc')
            ->get();

        foreach ($wallet_history as $key => $val) {
            $wallet_history[$key]->transaction_id = $val->id . strtotime($val->created_at);
            $wallet_history[$key]->wallet_amount = (string) round($val->wallet_amount, 2);
            $wallet_history[$key]->amount = (string) round($val->amount, 2);

            // Format created_at date correctly for display only
            $wallet_history[$key]->created_at_display = Carbon::parse($val->created_at)
                ->format('d M Y - h:i A');

            $pay_method = '';
            if ($val->pay_method == 1) {
                $pay_method = 'Credit Card';
            } elseif ($val->pay_method == 4) {
                $pay_method = 'Apple Pay';
            }
            $wallet_history[$key]->pay_method = $pay_method;
        }

        if ($last_history_det) {
            // Keep raw timestamp and provide formatted display version
            $last_history_det->created_at_display = Carbon::parse($last_history_det->created_at)
                ->format('d M Y - h:i A');
            $o_data['last_transaction'] = $last_history_det->toArray();
        } else {
            $o_data['last_transaction'] = (object)[];
        }

        $o_data['transaction']['list'] = $wallet_history;

        // Return response with raw and display-formatted dates
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => (object)$errors,
            'oData' => (object)$o_data
        ]);
    }

}

