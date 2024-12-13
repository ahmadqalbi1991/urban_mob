<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\ServiceAttributeValueItem;
use App\ServiceAttributeValue;
use App\AttributeValue;
use App\ServiceGallery;
use App\Attribute;
use App\Category;
use App\Service;
use App\SellerService;
use App\Seller;
use App\User;
use Validator;
use App\Slider;
use App\Payment;
use App\Card;
use App\PayOutBalance;
use App\CardAttribute;
use App\ChildCategory;
use App\CardAddon;
use App\UserTemp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Staff;

class SellerController extends BaseController
{

    public function staff_list(Request $request)
    {
        $staff = Staff::where('user_id', Auth::id())->get();
        return $this->sendResponse($staff, 'Staff list successfully.');
    }

    public function staff_added(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dial_code' => 'required|string|max:10',
            'phone' => 'required|string|max:15|unique:staff,phone',
        ]);

        $staff = Staff::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dial_code' => $request->dial_code,
            'phone' => $request->phone,
            'user_id' => Auth::id(),
        ]);
        return $this->sendResponse([], 'Staff added successfully.');
    }

    public function staff_deleted(Request $request, $id)
    {
        try {
            // Find the staff member by ID and user_id
            $staff = Staff::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
    
            // Delete the staff member
            $staff->delete();
    
            // Return success response
            return $this->sendResponse([], 'Staff deleted successfully.');
        } catch (\Exception $e) {
            // Handle exceptions (e.g., staff not found)
            return response()->json([
                'success' => "0",
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'data'    => [],
            ], 200);
        }
    }
    
    public function staff_updated(Request $request, $id)
    {
        $staff = Staff::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dial_code' => 'required|string|max:10',
            'phone' => 'required|string|max:15|unique:staff,phone,'.$staff->id,
        ]);

        $staff->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dial_code' => $request->dial_code,
            'phone' => $request->phone,
        ]);

        return $this->sendResponse([], 'Staff updated successfully.');
    }

    public function delete_user(Request $request)
    {
        $user = Auth::guard()->user();
        if ($user) {
            if (Auth::guard() instanceof \Illuminate\Auth\SessionGuard) {
                Auth::logout();
            }
            $user->delete();
            return $this->sendResponse($success, 'User deleted successfully.');
        }
        return $this->sendError('No authenticated user found.');
    }

    public function seller_register(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required',
            'phone'             => 'required',
            'dial_code'         => 'required',
            'city_id'           => 'required',
            'landline_number'   => 'required',
            'locality'          => 'required',
            'company_name'      => 'required',
            'profile'           => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $profile = '';
        if($request->hasFile('profile')){
            $imageName = str_replace(" ", "_", pathinfo($request->profile->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $request->profile->getClientOriginalExtension();
            $path = $request->file('profile')->storeAs('public/uploads/user', $imageName);
            $profile = url(Storage::url($path));
        }
        
        $input=[
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'dial_code'     => $request->dial_code,
            'device_token'  => 'fcmToken',
            'profile'       => $profile ?? '',
            'is_active'     =>  1,
            'is_registered' =>  1,
            'role'          =>  'vendor',
            'registered_by' =>  'App'
        ];

        try {
            $user = User::create($input);  

        } catch (\Exception $e) {
            return $this->sendError("User already exists with the phone number!");
        }

        if($user->role == "vendor" || $user->role == "seller"){
            $seller['company_name']     = $request->company_name;
            $seller['user_id']          = $user->id;
            $seller['landline_no']  = $request->landline_number;
            $seller['locality']         = $request->locality;
            $seller['city']             = $request->city_id;
            Seller::create($seller);                        
        }

        if($user)
        {   
            $user_temp=UserTemp::where(['phone'=>$request->phone])
            ->first();

            if($user_temp){
                $user_temp->delete();
            }

            $success = [];
            $success['token'] =  $user->createToken('milk-app')->accessToken; 
            $success['token_type'] =  'Bearer';  
            $success['user'] =  $user;  

            return $this->sendResponse($success, 'User Register successfully.');
        } else {
            return $this->sendError('Try Later!');

        }
    }

    public function seller_verify_otp(Request $request)
    {  
        if($request->phone && $request->dial_code && $request->otp)
        {
            $user=User::where(['phone'=>$request->phone, 'dial_code'=>$request->dial_code])->where('otp',$request->otp)->first();
            if($user){
                if($request->user_device_token && $request->user_device_type && $request->device_cart_id ){
                    $params['user_device_token'] = $request->user_device_token;
                    $params['user_device_type'] = $request->user_device_type;
                    $params['device_cart_id'] = $request->device_cart_id;
                    $user->update($params);
                }  
                $success['token'] =  $user->createToken('milk-app')->accessToken; 
                $success['token_type'] =  'Bearer';  
                $input=[
                    'id'            => (string) $user->id,
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'dial_code'         => $user->dial_code,
                    'phone'         => $user->phone,
                    'gender'        => $user->gender,
                    'DOB'           => $user->DOB,
                    'profile'       => $user->profile?\URL::to('/').'/uploads/user/'.$user->profile:'',
                    'verify'        =>  ($user->verify == "True") ? '1' : '0',
                    'is_active'     =>  (string) $user->is_active,
                    'is_registered' =>  (string) $user->is_registered,
                    'role'          =>  $user->role,
                    'registered_by' =>  $user->registered_by,
                ];

                $success['user'] =  $input;
                $success['is_new_user'] =  "0";

                return $this->sendResponse($success, 'Login successfully.');
            } else {
                $user=UserTemp::where(['phone'=>$request->phone, 'dial_code'=>$request->dial_code])->where('otp',$request->otp)->first();
                if($user){
                    if($request->user_device_token && $request->user_device_type && $request->device_cart_id ){
                        $params['user_device_token'] = $request->user_device_token;
                        $params['user_device_type'] = $request->user_device_type;
                        $params['device_cart_id'] = $request->device_cart_id;
                        $user->update($params);
                    }  
                    $input=[
                        'id'            => (string) $user->id,
                        'name'          => $user->name,
                        'email'         => $user->email,
                        'phone'         => $user->phone,
                        'gender'        => $user->gender ?? "",
                        'DOB'           => $user->DOB ?? "",
                        'profile'       => $user->profile?\URL::to('/').'/uploads/user/'.$user->profile:'',
                        'verify'        =>  ($user->verify == "True") ? '1' : '0',
                        'is_active'     =>  (string) $user->is_active,
                        'is_registered' =>  (string) $user->is_registered,
                        'role'          =>  $user->role,
                        'registered_by' =>  $user->registered_by,
                    ];
                    $success['user'] =  $input;
                    $success['is_new_user'] =  "1";
                    
                    return $this->sendResponse($success, 'OTP verified successfully.');
                } else {
                    return $this->sendError('Wrong OTP!');
                }
            }
        }
        else{
            return $this->sendError('Required field is empty!');
        }
    }

    public function login_otp(Request $request)

    {  

        if($request->phone && $request->dial_code)

        {
            
            $user=User::where(['phone'=>$request->phone, 'dial_code'=>$request->dial_code])->where(function($query){

                return $query

                ->where('role','seller')

                ->orWhere('role','vendor');

            })->first();

            if($user){ 

                if($user->is_active==1)

                {
                    
                    if($request->phone == '999888777'){
                        $otp= '1111';
                    } else {
                        $otp=random_int(1000, 9999);
                        $otp= '1111';
                    }
                    
                    User::where('id', $user->id)->update(['otp' => $otp]);

                    $message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety BxHhD18r5GO";
                    $msg = urlencode($message);
                    $mobile = $request->phone;

                    // send_sms_to_mobile($mobile,$msg);
                    
                    $success =  User::find($user->id);

                    return $this->sendResponse($success, 'OTP send successfully.');

                }

                else

                {

                    return $this->sendResponse([],'Account is inactive!.');

                }

            } 

            else{ 

                if(User::where('phone',$request->phone)->count()){

                     return $this->sendError('This Mobile Number is Already Exists');

                } else {

                    $params['name'] = 'Guest Vendor';
                    $params['email'] = $request->phone.'@gmail.com';
                    $params['phone'] = $request->phone;
                    $params['password'] = Hash::make('123456');
                    $params['registered_by'] = 'App';
                    $params['is_active'] = 1;
                    $params['is_registered'] = 1;
                    $params['role'] = 'vendor';
                    $params['verify'] = 'False';
                    $params['device_token'] = $request->device_token;

                    try {
                        $success = UserTemp::updateOrCreate(
                            ['phone' => $request['phone'], 'dial_code' => $request['dial_code']],
                            $params 
                        );
                    } catch (\Exception $e) {
                        return $this->sendError($e->getMessage());
                    }

                    if($request->phone == '500000001' || $request->phone == '500000002'){
                        $otp='1111';
                    } else {
                        $otp=random_int(1000, 9999);
                        $otp='1111';
                    }
                    
                    UserTemp::where('id', $success->id)->update(['otp' => $otp]);

                    $message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety BxHhD18r5GO";
                    $msg = urlencode($message);
                    $mobile = $request->phone;
                    // send_sms_to_mobile($mobile,$msg);
                    
                    return $this->sendResponse($success, 'OTP send successfully.');

                }
                
            }

        }

        else{

            return $this->sendError('Required field is empty!');

        }

    }

    public function update_seller(Request $request, $user_id)

    {   
        $valid_inputs=[

            'name' => 'required|string|max:200',
            'company_name' => 'required|string|max:200',
            // 'bank_name' => 'required',
            // 'ac_holder_name' => 'required',
            // 'ac_number' => 'required',
            // 'contact_ac_no' => 'required',

        ];

        $validator = Validator::make($request->all(),$valid_inputs );

        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors());       

        }

        $user = User::find($user_id);

        $params['name']             = $request->name;
        $params['email']            = $request->email;
        $params['verify']           = 'True';
        $params['is_active']        = 1;
        $params['is_registered']    = 1;
        // $params['is_verified']      = 1;

        if($request->hasFile('profile')){
            $imageName = time().'.'.$request->profile->extension(); 
            $path = $request->profile->move(public_path('/uploads/user/'), $imageName);
            $params['profile'] = $imageName;
        }

        $user->update($params);

        $seller_info = Seller::where('user_id',$user->id)->first();

        $seller['user_id']          = $user->id;
        $seller['company_name']     = $request->company_name;
        if($request->landline_no)
        $seller['landline_no']      = $request->landline_no;
        if($request->city)
        $seller['city']             = $request->city;
        if($request->locality)
        $seller['locality']         = $request->locality;
        if($request->address)
        $seller['address']          = $request->address;
        if($request->bank_name)
        $seller['bank_name']        = $request->bank_name;
        if($request->ac_holder_name)
        $seller['ac_holder_name']   = $request->ac_holder_name;
        if($request->ac_number)
        $seller['ac_number']        = $request->ac_number;
        if($request->contact_ac_no)
        $seller['contact_ac_no']    = $request->contact_ac_no;
       
        $seller['status']           = '1';
        if($request->is_registered)
        $seller['is_registered']    = $request->is_registered;
        if($request->vat_no)
        $seller['vat_no']           = $request->vat_no;

        if($request->hasFile('licence_file')){
            $imageName = time().'.'.$request->licence_file->extension(); 
            $path = $request->licence_file->move(public_path('/uploads/vendor_document/'), $imageName);
            $seller['licence_file'] = $imageName;
        }
        
        if(Seller::where('user_id',$user->id)->count()>0){
            Seller::where('user_id',$user->id)->update($seller);
            $seller_info = Seller::where('user_id',$user->id)->first();
        } else {
           $seller_info = Seller::create($seller);
        }

        if($request->service){
            foreach ($request->service as $key => $value) {
                $sellerservice['seller_id'] = $seller_info->id;
                $sellerservice['service_id'] = $value;
                if(!SellerService::where('seller_id',$seller_info->id)->where('service_id',$value)->count()>0){
                    SellerService::create($sellerservice);
                }
                
            }
        }
        
        $sellerSer = [];

        foreach (SellerService::where('seller_id',$seller_info->id)->get() as $key => $sllrser) {
            $service = Service::find($sllrser->service_id);
            $data['service_id'] = $service->id;
            $data['service_name'] = $service->name;
            array_push($sellerSer, $data);
        }

        $res['user_id']         = $user->id;
        $res['role']            = $user->role;
        $res['name']            = $user->name;
        $res['email']           = $user->email;
        $res['phone']           = $user->phone;
        $res['profile']         = \URL::to('/').'/uploads/user/'.$user->profile;
        $res['company_name']    = $seller_info->company_name;
        $res['landline_no']     = $seller_info->landline_no;
        $res['city']            = $seller_info->city;
        $res['address']         = $seller_info->address;
        $res['licence_file']    = \URL::to('/').'/uploads/vendor_document/'.$seller_info->licence_file;
        $res['bank_name']       = $seller_info->bank_name;
        $res['ac_holder_name']  = $seller_info->ac_holder_name;
        $res['ac_number']       = $seller_info->ac_number;
        $res['contact_ac_no']   = $seller_info->contact_ac_no;
        $res['is_registered']   = $seller_info->is_registered;
        $res['vat_no']          = $seller_info->vat_no;
        $res['is_verified']     = $user->is_verified;
        $res['vendor_service']  = $sellerSer;

        return $this->sendResponse($res, 'Vendor create successfully.');

    }


    public function update_seller_details(Request $request, $user_id)
    {  
        $seller['company_name']     = $request->company_name;
        $seller['landline_no']      = $request->landline_no;
        $seller['city']             = $request->city;
        $seller['address']          = $request->address;
        $seller['bank_name']        = $request->bank_name;
        $seller['ac_holder_name']   = $request->ac_holder_name;
        $seller['ac_number']        = $request->ac_number;
        $seller['contact_ac_no']    = $request->contact_ac_no;
        $seller['status']           = '1';

        if($request->hasFile('licence_file')){
            $imageName = time().'.'.$request->licence_file->extension(); 
            $path = $request->licence_file->move(public_path('/uploads/vendor_document/'), $imageName);
            $seller['licence_file'] = $imageName;
        }
        
        Seller::where('user_id',$user_id)->update($seller);
        $seller_info = Seller::where('user_id',$user_id)->first();

        if($request->service){
            foreach ($request->service as $key => $value) {
                $sellerservice['seller_id'] = $seller_info->id;
                $sellerservice['service_id'] = $value;
                if(!SellerService::where('seller_id',$seller_info->id)->where('service_id',$value)->count()>0){
                    SellerService::create($sellerservice);
                }
                
            }
        }
        
        $sellerSer = [];

        foreach (SellerService::where('seller_id',$seller_info->id)->get() as $key => $sllrser) {
            $service = Service::find($sllrser->service_id);
            $data['service_id'] = $service?$service->id:'';
            $data['service_name'] = $service?$service->name:'';
            array_push($sellerSer, $data);
        }

        $res['company_name']    = $seller_info->company_name;
        $res['landline_no']     = $seller_info->landline_no;
        $res['city']            = $seller_info->city;
        $res['address']         = $seller_info->address;
        $res['licence_file']    = \URL::to('/').'/uploads/vendor_document/'.$seller_info->licence_file;
        $res['bank_name']       = $seller_info->bank_name;
        $res['ac_holder_name']  = $seller_info->ac_holder_name;
        $res['ac_number']       = $seller_info->ac_number;
        $res['contact_ac_no']   = $seller_info->contact_ac_no;
        $res['vendor_service']  = $sellerSer;

        return $this->sendResponse($res, 'Vendor update successfully.');

    }

    public function update_seller_status(Request $request, $user_id)
    {        

        $params['is_active'] = $request->status;
      
        User::where('id',$user_id)->update($params);

        $user = User::where('id',$user_id)->first();

        return $this->sendResponse($user, 'Vendor update successfully.');
    }

    public function all_service()
    {
        $service = Service::where('status',1)->orderBy('id', 'DESC')->get();
        $datas = [];
        foreach ($service as $key => $value) {
            $data['id']     = $value->id;
            $data['title']  = $value->name;
            $data['image']  = \URL::to('/').'/uploads/service/'.$value->thumbnail_img;
            $data['price']  = $value->price;
            array_push($datas, $data);
        }
        return $this->sendResponse($datas, 'All Services');
    }

    public function all_service_name()
    {
        $service = Service::where('status',1)->orderBy('id', 'DESC')->get();
        $datas = [];
        foreach ($service as $key => $value) {
            $data['id']     = $value->id;
            $data['title']  = $value->name;
            array_push($datas, $data);
        }
        return $this->sendResponse($datas, 'All Services Name');
    }

    public function service_details($id)
    {
        if($id){
            $value = Service::find($id);

            $currencies = \DB::table('currencies')->where('default', '1')->first();
            $service_gallery = [];
            foreach (ServiceGallery::where('service_id',$value->id)->get() as $key => $galley) {
                if($galley->photos){
                    $service_gall['photo'] = \URL::to('/').'/uploads/service/gallery/'.$galley->photos;
                    array_push($service_gallery, $service_gall);
                }
            }
            $service_categorys = [];
            
            foreach (ServiceAttributeValueItem::where('service_id',$value->id)->get() as $key => $val) {

                
                $cate['category_id']    = $val->category_id;
                $cate['category_name']  = $val->category->name;

                if($val->sub_category_id){
                    $cate['sub_category_id'] = $val->sub_category_id;
                    $cate['sub_category_name'] = $val->sub_category?$val->sub_category->name:'';
                }
                if($val->child_category_id){
                    $cate['child_category_id'] = $val->child_category_id;
                    $cate['child_category_name'] = $val->child_category?$val->child_category_name:'';
                }

                $service_cat_atr = ServiceAttributeValue::where('service_id',$value->id)->where('ser_attr_val_item_id',$val->id)->get();
                $service_category_atr = [];
                foreach ($service_cat_atr as $key => $atr_item) {

                    $cat_atr_item['attribute_id']       = $atr_item->attribute_id;
                    $cat_atr_item['attribute_name']     = $atr_item->attribute?$atr_item->attribute->name:'';
                    $cat_atr_item['attribute_item_id']  = $atr_item->attribute_item_id;
                    $cat_atr_item['attribute_item']     = AttributeValue::where('id',$atr_item->attribute_item_id)->value('value');
                    $cat_atr_item['attribute_price']    = $atr_item->attribute_price;

                    array_push($service_category_atr, $cat_atr_item);

                }

                $cate['category_attribute'] = $service_category_atr;

                array_push($service_categorys, $cate);

            }
            $data['id']                 = $value->id;
            $data['user_name']          = $value->user?$value->user->name:'';
            $data['user_id']            = $value->user_id;
            $data['category_id']        = $value->parent_id;
            $data['category_name']      = Category::where('id',$value->parent_id)->value('name');
            $data['addon_id']           = $value->addon_id;
            $data['addon_name']         = $value->addon?$value->addon->name:'';
            $data['title']              = $value->name;
            $data['image']              = \URL::to('/').'/uploads/service/'.$value->thumbnail_img;
            $data['price_currency']     = $currencies?$currencies->symbol:'';
            $data['price']              = $value->price;
            $data['material_price']     = $value->material_price;
            $data['recommended']        = $value->recommended;
            $data['status']             = $value->status=='1'?'Active':'Inactive';
            $data['featured']           = $value->featured=='1'?'Yes':'No';
            $data['featured_banner']    = \URL::to('/').'/uploads/service/featured_banner/'.$value->featured_banner;
            $data['short_description']  = $value->short_description;
            $data['description']        = $value->description;
            $data['meta_title']         = $value->meta_title;
            $data['meta_description']   = $value->meta_description;
            $data['service_gallery']    = $service_gallery;
            $data['service_category']   = $service_categorys;
            // $attrs = ServiceAttributeValue::where('service_id',$value->id)->groupBy('attribute_id')->get();
            // $attr_array = [];
            // foreach ($attrs as $key => $atr) {
            //     $data_atr['attribute']  =  Attribute::whereId($atr->attribute_id)->first('name');

            //     $attr_items = [];
            //     foreach (ServiceAttributeValue::where('service_id',$value->id)->where('attribute_id',$atr->attribute_id)->get() as $key => $attr_val) {

            //         $atr_itm['attribute_item'] = AttributeValue::whereId($attr_val->attribute_item_id)->first()->value;
            //         $atr_itm['attribute_price'] = $attr_val->attribute_price;
            //         array_push($attr_items, $atr_itm);

            //     }

            //     $data_atr['attribute_items']  =  $attr_items;
            //     array_push($attr_array, $data_atr);

            // }
            // $data['service_attributes']    = $attr_array;
        
            return $this->sendResponse($data, 'Service Details');
        } else {
            return $this->sendError('Request field is empty!');
        }
        
    }

    public function featured_service()
    {
        $service = Service::where('status',1)->where('featured',1)->orderBy('id', 'DESC')->get();
        $datas = [];
        foreach ($service as $key => $value) {
            $data['id']                 = $value->id;
            $data['title']              = $value->name;
            $data['featured_banner']    = \URL::to('/').'/uploads/service/featured_banner/'.$value->featured_banner;
            array_push($datas, $data);
        }
        return $this->sendResponse($datas, 'Featured Services');
    }

    public function sing_up_banner(Request $request)
   {
       $success = Slider::first();

       $data['first_banner'] = \URL::to('/').'/uploads/signup-slider/'.$success->app_sign_first_slider;
       $data['first_short_description'] = $success->app_sign_first_link;
       $data['first_title'] = $success->app_sign_first_title;

       $data['second_banner'] = \URL::to('/').'/uploads/signup-slider/'.$success->app_sign_second_slider;
       $data['second_short_description'] = $success->app_sign_second_link;
       $data['second_title'] = $success->app_sign_second_title;

       $data['third_slider'] = \URL::to('/').'/uploads/signup-slider/'.$success->app_sign_third_slider;
       $data['third_short_description'] = $success->app_sign_third_link;
       $data['third_title'] = $success->app_sign_third_title;

       $data['four_slider'] = \URL::to('/').'/uploads/signup-slider/'.$success->app_sign_for_slider;
       $data['four_short_description'] = $success->app_sign_for_link;
       $data['four_title'] = $success->app_sign_for_title;

       return $this->sendResponse($data, 'Sing up Banner.');
   }


   public function get_payment(Request $request)
   {
        $payment = Payment::where('vendor_id',Seller::where('user_id',auth()->user()->id)->first()->id)->get();
        return $this->sendResponse($payment, 'Payment History.');
   }

   public function get_payout_history(Request $request)
   {
        $payment = PayOutBalance::where('vendor_id',auth()->user()->id)->get();
        // $payment = PayOutBalance::where('vendor_id',Seller::where('user_id',auth()->user()->id)->first()->id)->get();

        $pay_outs = [];

        foreach ($payment as $key => $pay) {

                $value = Card::where('id',$pay->card_id)->where('status', '!=' , 'Canceled')->first();

                if($value){
                    $service = Service::find($value->service_id);
                    $user = User::find($value->user_id);
                    $category = Category::find($value->category_id);

                    $data['booking_id']     = $value->id;
                    $data['tran_id']        = $value->tran_id;
                    $data['user_id']        = $value->user_id;
                    $data['user_name']      = $user?$user->name:'';
                    $data['user_email']     = $user?$user->email:'';
                    $data['user_mobile']    = $user?$user->phone:'';
                    $data['service_id']     = $value->service_id;
                    $data['staus']          = $value->status;
                    $data['service']        = $service->name;
                    $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
                    $data['category_id']    = $value->category_id;
                    $data['category']       = $category?$category->name:'';
                    $data['slot_id']        = $value->slot_id;
                    $data['slot']           = $value->slot?$value->slot->name:'';
                    $data['address_id']     = $value->address_id;
                    $building = $value->address?$value->address->building:'';
                    $flat_no = $value->address?$value->address->flat_no:'';
                    $address = $value->address?$value->address->address:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;
                    $data['payment_moad']   = $value->payment_moad;
                    $data['payment_status'] = $value->payment_status;
                    $data['note']           = $value->note;
                    $data['material_charge']= $value->material_charge;
                    $data['material_status']= $value->material_status;
                    $data['service_type']   = $value->service_type;
                    $data['alternative_number'] = $value->alternative_number;
                    $data['date']           = $value->date;
                    $data['tip']            = $value->tip_id;
                    $data['coupon_id']      = $value->coupon_id;
                    $data['is_checkout']    = $value->is_checkout;
                    
                    $card_attr = [];
                    $sub_total = '00';
                    $subtotal = '00';
                    $total = '00';
                    foreach (CardAttribute::where('card_id',$value->id)->get() as $key => $item) {

                        $sub_cat = Category::find($value->sub_cate_id);
                        $child_cat = ChildCategory::find($value->child_cate_id);

                        $params['sub_cate_id']          = $item->sub_cate_id;
                        $params['sub_cate_name']        = $sub_cat?$sub_cat->name:'';
                        $params['main_sub_cat_id']      = $item->main_sub_cat_id;
                        $params['main_sub_cat_name']    = $item->main_sub_cat?$item->main_sub_cat->name:'';
                        $params['child_cate_id']        = $item->child_cate_id;
                        $params['child_cate']           = $item->child_cate?$item->child_cate->name:'';
                        $params['attribute_id']         = $item->attribute_id;
                        $params['attribute_name']       = $item->attribute_name;
                        $params['attribute_item_id']    = $item->attribute_item_id;
                        $params['attribute_item_name']  = $item->attribute_item_name;
                        $params['attribute_qty']        = $item->attribute_qty;
                        $params['attribute_price']      = $item->attribute_price;
                        $params['service_type']         = $item->service_type;
                        $addons = [];

                        foreach (CardAddon::where('card_id',$value->id)->where('card_attribute_id',$item->id)->get() as $key => $cardaddon) {
                            $addon['card_id']               = $cardaddon->id;
                            $addon['card_attribute_id']     = $cardaddon->id;
                            $addon['add_on_id']             = $cardaddon->add_on_id;
                            $addon['name']                  = $cardaddon->name;
                            $addon['value']                 = $cardaddon->value;
                            $addon['percentage']            = $cardaddon->percentage;
                            array_push($addons,$addon);
                        }
                        $params['addon']      = $addons;
                        array_push($card_attr,$params);
                    }

                    $data['coupon']         = $value->coupon?$value->coupon->code:'';
                    $data['coupon_amt']     = $value->coupon_amt;
                    $data['subtotal']       = $value->amount;
                    $data['total']          = $value->g_total;
                    $data['card_attribute'] = $card_attr;
                    $pay->card_info = $data;
                }
            
            array_push($pay_outs, $pay);
        }
        
        return $this->sendResponse($payment, 'Payout History.');
       
   }

}
