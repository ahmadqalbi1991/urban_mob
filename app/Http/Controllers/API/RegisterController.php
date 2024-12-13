<?php

   

namespace App\Http\Controllers\API;

use Kreait\Firebase\Factory;

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Support\Facades\DB;

use App\User;

use App\UserTemp;

use App\SellerService;

use App\Seller;

use App\Address;

use App\Invite;

use App\RewardUser;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rule;

use Validator;

use File;

use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Database;

class RegisterController extends BaseController
{

    /**

     *  API Register

     *

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required',
            'phone' => 'required',
            'dial_code' => 'required',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $profile = '';

        if($request->hasFile('profile')){
            $imageName = $request->name.'-'.time().'.'.$request->profile->extension(); 
            $path = $request->profile->move(public_path('/uploads/user/'), $imageName);
            $profile = \URL::to('/').'/uploads/user/'.$imageName;
        }

        if(isset(request()->invite_code)){
            $code = request()->invite_code ?? "";
            if(!empty($code)){
                $is_invite = 1;
            }
        } else {
            $code = "";
            $is_invite = 0;
        }
        $input=[
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'dial_code'     => $request->dial_code,
            'device_token'  => 'fcmToken',
            'gender'        => $request->gender ?? '',
            'DOB'           => $request->DOB ?? '',
            'profile'       => $profile ?? '',
            'is_active'     =>  1,
            'is_registered' =>  1,
            'role'          =>  'customer',
            'registered_by' =>  'App',
            'invite'        =>  $code,
            'is_invite'        =>  1,
            'firebase_user_key' => $request->name . '_' . time()
        ];

        $user_temp = UserTemp::where('phone', $request->phone)->first();
        if (!$user_temp) {
            return $this->sendError("User already exists with the phone number!");
        }
        
        try {
            $user = User::create($input);  
        } catch (\Exception $e) {
            return $this->sendError("User already exists with the phone number!");
        }

        if($user)
        {   
            $user_temp=UserTemp::where(['phone'=>$request->phone])
            ->first();

            $params['user_device_token'] = $user_temp->user_device_token;
            $params['user_device_type'] = $user_temp->user_device_type;
            $params['device_cart_id'] = $user_temp->device_cart_id;

            $user->update($params);

            if($user_temp){
                $user_temp->delete();
            }

            $success = [];
            $success['token'] =  $user->createToken('milk-app')->accessToken; 
            $success['token_type'] =  'Bearer';  
            $success['user'] =  $user;  

            if($user && $user->email){
                $array['view']      = 'emails.customer_created';
                $array['subject']   = 'Welcome to UrbanMop - Your Journey to a Cleaner Home Begins Now!';
                $array['data']      = $user;
                \Mail::to($user->email)->send(new \App\Mail\Mail($array));
                
                $array['view']      = 'emails.admin_customer_created';
                $array['subject']   = 'New Customer Registration Notification - UrbanMop';
                $array['data']      = $user;
                \Mail::to("urbanmop.uae@gmail.com")->send(new \App\Mail\Mail($array));
            }                
    
            $device_tokens = $user->user_device_token;
            if($device_tokens != 0 && $device_tokens != null && $device_tokens != ""){
                sendNotification($device_tokens, array(
                    "title" => 'Registration Successfully!', 
                    "body" => 'Successfully Register.',
                    "type" => "customer",
                    "id"=> $user->id,
                ));
            }

            return $this->sendResponse($success, 'User Register successfully.');
        } else {
            return $this->sendError('Try Later!');

        }
    }

    /**

     * Login api

     *

     * @return \Illuminate\Http\Response

     */

    public function login(Request $request)

    {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 

            $user = Auth::user(); 

            $success['token'] =  $user->createToken('milk-app')->accessToken; 

            $success['token_type'] =  'Bearer'; 

            $success['user'] =  $user;

            $firebase_key = $user->firebase_user_key;
            if (empty($firebase_key)) {
                $firebase_key = $user->name . '_' . time();
            }

            User::where('id', $user->id)->update(['device_token'=>$request->device_token, 'firebase_user_key' => $firebase_key]);

            return $this->sendResponse($success, 'User login successfully.');

        } 

        else{ 

            return $this->sendError('Unauthorised!');

        } 

    }

    public function seller_login_otp(Request $request)

    {  

        // if(User::where('phone',$request->phone)->count()>1){
        //     return $this->sendError('This Number is Already Exist!');
        // }

        if($request->phone && $request->dial_code)

        {
            
            $user=User::where(['phone'=>$request->phone, 'dial_code'=>$request->dial_code])->where(function($query){

                return $query

                ->where('role','customer');

            })->first();


            if($user){ 

                if($user->is_active==1)

                {

                    if($request->phone == '500000001' || $request->phone == '500000002'){
                        $otp='1111';
                    } else {
                        $otp=random_int(1000, 9999);
                        $otp='1111';
                    }

                    $firebase_key = $user->firebase_user_key;
                    if (empty($firebase_key)) {
                        $firebase_key = $user->name . '_' . time();
                    }


                    User::where('id', $user->id)->update(['otp' => $otp, 'firebase_user_key' => $firebase_key]);

                    // $msg    = "OTP-".$otp;
                    // $msg = preg_replace('/[^A-Za-z0-9\-]/', '', $msg); // Remove spaces and special characters
                    // send_sms_to_mobile($user->phone,$msg);

                    $message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety t3neMQrTubI";
                    $msg = urlencode($message);
                    $mobile = $user->phone;
                    $res=send_sms_to_mobile($mobile,$msg);

                    $success =  $user;
                    $input=[

                        'id'            => (string) $user->id,

                        'name'          => $user->name,

                        'email'         => $user->email,

                        'otp'           => (string) $otp,

                        'dial_code'     => $user->dial_code,

                        'phone'         => $user->phone,

                        'gender'        => $user->gender,

                        'DOB'           => $user->DOB,

                        'profile'       => \URL::to('/').'/uploads/user/'.$user->profile,

                        'is_active'     =>  "1",

                        'is_registered' =>  "1",

                        'role'          =>  'customer',

                        'registered_by' =>  'App1'

                    ];

                    // $msg="Welcome to kisaanhelpline, your verification code is ".$otp." Regards: KH24 AGRO VENTURE Pvt. LTD.";

                    // send_sms($request->phone,$msg);

                    return $this->sendResponse($input, 'OTP send successfully.');

                }

                else

                {

                    return $this->sendResponse([],'Account is inactive!.');

                }

            } 

            else{ 

                $input=[

                    'name'          => 'guest',
        
                    'email'         => '',
        
                    'phone'         => $request->phone,

                    'dial_code'     => $request->dial_code,
        
                    // 'password'   => Hash::make($request->phone),
        
                    'device_token'  => 'fcmToken',
        
                    'gender'        => '',
        
                    'DOB'           => '',
        
                    'profile'       => '',
        
                    'is_active'     =>  1,
        
                    'is_registered' =>  1,
        
                    'role'          =>  'customer',
        
                    'registered_by' =>  'App'
        
                ];

                try {
                    $user = UserTemp::updateOrCreate(
                        ['phone' => $input['phone'], 'dial_code' => $input['dial_code']],
                        $input 
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
                

                UserTemp::where('id', $user->id)->update(['otp' => $otp]);

                // $msg    = "OTP-".$otp;
                // $msg = preg_replace('/[^A-Za-z0-9\-]/', '', $msg); // Remove spaces and special characters
                // send_sms_to_mobile($user->phone,$msg);

                $message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety t3neMQrTubI";
                $msg = urlencode($message);
                $mobile = $user->phone;
                $res=send_sms_to_mobile($mobile,$msg);

                $success =  $user;
                $input=[

                    'id'            => (string) $user->id,

                    'name'          => $user->name,

                    'email'         => $user->email,

                    'otp'           => (string) $otp,

                    'phone'         => $user->phone,

                    'gender'        => $user->gender,

                    'DOB'           => $user->DOB,

                    'profile'       => \URL::to('/').'/uploads/user/'.$user->profile,

                    'is_active'     =>  "1",

                    'is_registered' =>  "1",

                    'role'          =>  'customer',

                    'registered_by' =>  'App1'

                ];

                // $msg="Welcome to kisaanhelpline, your verification code is ".$otp." Regards: KH24 AGRO VENTURE Pvt. LTD.";

                // send_sms($request->phone,$msg);

                return $this->sendResponse($input, 'OTP send successfully.');
            }

        }

        else{

            return $this->sendError('Required field is empty!');

        }

    }

    public function login_otp(Request $request)

    {  

        // if(User::where('phone',$request->phone)->count()>1){
        //     return $this->sendError('This Number is Already Exist!');
        // }

        if($request->phone && $request->dial_code)

        {
            
            $user=User::where(['phone'=>$request->phone, 'dial_code'=>$request->dial_code])->where(function($query){

                return $query

                ->where('role','customer');

            })->first();


            if($user){ 

                if($user->is_active==1)

                {

                    if($request->phone == '500000001' || $request->phone == '500000002'){
                        $otp='1111';
                    } else {
                        $otp=random_int(1000, 9999);
                        $otp='1111';
                    }

                    $firebase_key = $user->firebase_user_key;
                    if (empty($firebase_key)) {
                        $firebase_key = $user->name . '_' . time();
                    }

                    User::where('id', $user->id)->update(['otp' => $otp, 'firebase_user_key' => $firebase_key]);

                    $message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety t3neMQrTubI";
                    $msg = urlencode($message);
                    $mobile = $user->phone;

                    $success =  $user;
                    $input=[

                        'id'            => (string) $user->id,

                        'name'          => $user->name,

                        'email'         => $user->email,

                        'otp'           => (string) $otp,

                        'dial_code'     => $user->dial_code,

                        'phone'         => $user->phone,

                        'gender'        => $user->gender,

                        'DOB'           => $user->DOB,

                        'profile'       => \URL::to('/').'/uploads/user/'.$user->profile,

                        'is_active'     =>  "1",

                        'is_registered' =>  "1",

                        'role'          =>  'customer',

                        'registered_by' =>  'App1'

                    ];

                    // $msg="Welcome to kisaanhelpline, your verification code is ".$otp." Regards: KH24 AGRO VENTURE Pvt. LTD.";

                    // send_sms($request->phone,$msg);

                    return $this->sendResponse($input, 'OTP send successfully.');

                }

                else

                {

                    return $this->sendResponse([],'Account is inactive!.');

                }

            } 

            else{ 

                $input=[

                    'name'          => 'guest',
        
                    'email'         => '',
        
                    'phone'         => $request->phone,

                    'dial_code'     => $request->dial_code,
        
                    // 'password'   => Hash::make($request->phone),
        
                    'device_token'  => 'fcmToken',
        
                    'gender'        => '',
        
                    'DOB'           => '',
        
                    'profile'       => '',
        
                    'is_active'     =>  1,
        
                    'is_registered' =>  1,
        
                    'role'          =>  'customer',
        
                    'registered_by' =>  'App'
        
                ];

                try {
                    $user = UserTemp::updateOrCreate(
                        ['phone' => $input['phone'], 'dial_code' => $input['dial_code']],
                        $input 
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
                
                UserTemp::where('id', $user->id)->update(['otp' => $otp]);

                // $msg    = "OTP-".$otp;
                // $msg = preg_replace('/[^A-Za-z0-9\-]/', '', $msg); // Remove spaces and special characters
                // send_sms_to_mobile($user->phone,$msg);

                $message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety t3neMQrTubI";
                $msg = urlencode($message);
                $mobile = $user->phone;
                // send_sms_to_mobile($mobile,$msg);

                $success =  $user;
                $input=[

                    'id'            => (string) $user->id,

                    'name'          => $user->name,

                    'email'         => $user->email,

                    'otp'           => (string) $otp,

                    'phone'         => $user->phone,

                    'gender'        => $user->gender,

                    'DOB'           => $user->DOB,

                    'profile'       => \URL::to('/').'/uploads/user/'.$user->profile,

                    'is_active'     =>  "1",

                    'is_registered' =>  "1",

                    'role'          =>  'customer',

                    'registered_by' =>  'App1'

                ];

                // $msg="Welcome to kisaanhelpline, your verification code is ".$otp." Regards: KH24 AGRO VENTURE Pvt. LTD.";

                // send_sms($request->phone,$msg);

                return $this->sendResponse($input, 'OTP send successfully.');
            }

        }

        else{

            return $this->sendError('Required field is empty!');

        }

    }

    public function resend_otp(Request $request)
    {   

        if($request->phone)

        {

            $user=User::where('phone',$request->phone)->first();

            if($user){ 

                $otp=random_int(1000, 9999);
                $otp='1111';

                User::where('id', $user->id)->update(['otp' => $otp]);

                $success['user'] =  $user;

                //$msg="Welcome to kisaanhelpline, your verification code is ".$otp." Regards: KH24 AGRO VENTURE Pvt. LTD.";

                //send_sms($request->phone,$msg);

                return $this->sendResponse($success, 'OTP resend successfully.');

            } 

            else{ 

                $user=UserTemp::where('phone',$request->phone)->first();

                $otp=random_int(1000, 9999);
                $otp='1111';
                UserTemp::where('id', $user->id)->update(['otp' => $otp]);

                $user=UserTemp::where('phone',$request->phone)->first();

                $success['user'] =  $user;

                //$msg="Welcome to kisaanhelpline, your verification code is ".$otp." Regards: KH24 AGRO VENTURE Pvt. LTD.";

                //send_sms($request->phone,$msg);

                return $this->sendResponse($success, 'OTP resend successfully.');


            }

        }

        else{

            return $this->sendError('Required field is empty!');

        }

    }

    public function verify_otp(Request $request)
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
                
                // $success['token'] =  ''; 
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

                // $firebaseConfigPath = base_path('urban-mob-firebase.json');
                // $database = (new Factory())
                // ->withServiceAccount($firebaseConfigPath)
                // ->withDatabaseUri('https://urban-75edf-default-rtdb.firebaseio.com')
                // ->createDatabase();

                // if ($user->firebase_user_key == null) {
                //     $fb_user_refrence = $database->getReference('Users/')
                //         ->push([
                //             'fcm_token' => $user->user_device_token,
                //             'name' => $user->name,
                //             'email' => $user->email,
                //             'user_id' => $user->id,
                //             'active' => 1,
                //             'user_image' => $user->profile,
                //         ]);
                //     $user->firebase_user_key = $fb_user_refrence->getKey();
                // } else {
                //     $database->getReference('Users/' . $user->firebase_user_key . '/')->update(['fcm_token' => $user->fcm_token,'active' => 1,'user_image' => $user->profile]);
                // }

                
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

                        // $firebaseConfigPath = base_path('urban-mob-firebase.json');
                        // $database = (new Factory())
                        // ->withServiceAccount($firebaseConfigPath)
                        // ->withDatabaseUri('https://urban-75edf-default-rtdb.firebaseio.com')
                        // ->createDatabase();

                        // if ($user->firebase_user_key == null) {
                        //     $fb_user_refrence = $database->getReference('Users/')
                        //         ->push([
                        //             'fcm_token' => $user->user_device_token,
                        //             'name' => $user->name,
                        //             'email' => $user->email,
                        //             'user_id' => $user->id,
                        //             'active' => 1,
                        //             'user_image' => $user->profile,
                        //         ]);
                        //     $user->firebase_user_key = $fb_user_refrence->getKey();
                        // } else {
                        //     $database->getReference('Users/' . $user->firebase_user_key . '/')->update(['fcm_token' => $user->fcm_token,'active' => 1,'user_image' => $user->profile]);
                        // }

                    }  
                    
                    $input=[
    
                        'id'            => (string) $user->id,
    
                        'name'          => $user->name,
    
                        'email'         => $user->email,
    
                        'phone'         => $user->phone,
    
                        'gender'        => $user->gender,
    
                        'DOB'           => $user->DOB,
    
                        'profile'       => $user->profile??'',
    
                        'verify'        =>  ($user->verify == "True") ? '1' : '0',
    
                        'is_active'     =>  (string) $user->is_active,
    
                        'is_registered' =>  (string) $user->is_registered,
    
                        'role'          =>  $user->role,
    
                        'registered_by' =>  $user->registered_by,

                        'firebase_user_key' =>  $user->firebase_user_key,
    
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

    public function logout(Request $request)

    {

        $success=$request->user()->token()->revoke();

        return $this->sendResponse($success, 'Successfully logged out');

    }

    

    public function user_info(Request $request)

    {   

            $user_id=auth()->user()->id;

            $user=User::where('id',$user_id)->first();

            if($user){ 

                $exist = Invite::where('user_id', $user->id)->first();

                if ($exist) {
                    $invite = $exist;
                } else {
                    $invite = Invite::create([
                        'user_id' => $user->id,
                        'invite_code' => strtoupper(uniqid('INV-'))
                    ]);
                }

                $user->profile = $user->profile;

                $success['invite_code']     =  $invite->invite_code;
                $success['is_invite']       =  $user->is_invite;
                $success['user']            =  $user;
                $success['reward_points']   =  RewardUser::where('user_id', $user_id)->get()->sum('points');
                $success['reward_amount']   =  RewardUser::where('user_id', $user_id)->get()->sum('amounts');
                $address                    = Address::where(['user_id' => $user->id])->get();

                $success['address'] = $address->isEmpty() ? [] : $address;

                if($user->role=='vendor'){
                    $vendor_service = [];

                    $seller = Seller::where('user_id',$user->id)->orderBy('id', 'DESC')->first();

                    foreach (SellerService::where('seller_id',$seller?$seller->id:'')->get() as $key => $value) {
                        $daata['service_id'] = $value->service_id;
                        $daata['service_name'] = $value->service?$value->service->name:'';

                        array_push($vendor_service, $daata);
                    }

                    $success['company_name']    = $seller?$seller->company_name:'';
                    $success['landline_no']     = $seller?$seller->landline_no:'';
                    $success['address']         = $seller?$seller->address:'';
                    $success['licence_file']    = $seller->licence_file?\URL::to('/').'/uploads/vendor_document/'.$seller->licence_file:'';
                    $success['bank_name']       = $seller?$seller->bank_name:'';
                    $success['ac_holder_name']  = $seller?$seller->ac_holder_name:'';
                    $success['ac_number']       = $seller?$seller->ac_number:'';
                    $success['contact_ac_no']   = $seller?$seller->contact_ac_no:'';
                    $success['locality']        = $seller?$seller->locality:'';
                    $success['city']            = $seller?$seller->city:'';
                    $success['is_registered']   = $seller?$seller->is_registered:'';
                    $success['vat_no']          = $seller?$seller->vat_no:'';
                    

                    $success['vendor_service']  = $vendor_service;
                }

                return $this->sendResponse($success, 'Profile Info');

            } 

            else{ 

                return $this->sendError('No User Found.');

            } 

       

    }

    public function profile_update(Request $request)

    {   
            $data= [

                'name' => $request->name,

                'email' => $request->email,

                'dial_code' => $request->dial_code,

                'phone' => $request->phone,

                // 'address'=>$request->address,

                'city' => $request->city,

                ];

            $data['DOB'] = $request->DOB;
            $data['gender'] = $request->gender;
            $data['is_active'] = 1;
            $data['is_registered'] = 1;
            $data['verify'] = '1';

            if($request->hasFile('profile')){
                $imageName = $request->name.'-'.time().'.'.$request->profile->extension(); 
                $path = $request->profile->move(public_path('/uploads/user/'), $imageName);
                $data['profile'] = \URL::to('/').'/uploads/user/'.$imageName;
            }

            $res = auth()->user()->update($data);

            if($res){ 

                $user =  User::find(auth()->user()->id);

                $input['user']=[

                    'id'            => $user->id,

                    'name'          => $user->name,

                    'email'         => $user->email,

                    'dial_code'     => $user->dial_code,

                    'phone'         => $user->phone,

                    'gender'        => $user->gender,

                    'DOB'           => $user->DOB,

                    'profile'       => $user->profile,

                    'is_active'     =>  (string) $user->is_active,

                    'is_registered' =>  (string) $user->is_registered,

                    'role'          =>  $user->role,

                    'registered_by' =>  $user->registered_by,

                ];

                return $this->sendResponse($input, 'Profile Update Successfully');

            } 

            else{ 

                return $this->sendError('Try Later!');

            } 

        

    }

    public function remove_profile()
    {
        $user = User::find(auth()->user()->id);
        if($user){
            if (File::exists(public_path('uploads/user/'.$user->profile))) {
                File::delete(public_path('uploads/user/'.$user->profile));
            }
            $data['profile'] = null;
            $user->update($data);
            return $this->sendResponse([], 'Delete Successfully');
        } else {
            return $this->sendError('Invalid!');
        }
        
    }

}