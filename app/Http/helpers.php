<?php

use Illuminate\Support\Facades\Crypt;

use Carbon\Carbon;

use App\Wallet;

use App\Package;

use App\PackageAddons;

use App\SellerService;

use App\User;

use App\Seller;

use App\Card;

use BroadNet\Client;

// function encrypt($id)

// {

//     return Crypt::encrypt($id);

// }



// function decrypt($id)

// {

//     return Crypt::decrypt($id);

// }

  

function changeDateFormate($date){

    return Carbon::parse($date)->format('d M Y');    

}



function changeTimeFormate($date){

    return Carbon::parse($date)->format('h:i a');    

}



function changeDateTimeFormate($date){

    return Carbon::parse($date)->format('d M Y h:i a');    

}

   

function itemImagePath($image_name)

{   

    if(empty($image_name)){

        return asset('images/hand.jpg');

    }

    else{

        return asset('uploads/items/'.$image_name);

    }

}



function QRImagePath($image_name)

{   

    if(!empty($image_name)){

        return asset('uploads/QR/'.$image_name);

    }

}



if (!function_exists('isAdmin')) {

    function isAdmin()

    {

        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'super-admin')) {

            return true;

        }

        return false;

    }

}



if (!function_exists('isVendor')) {

    function isVendor()

    {

        if (Auth::check() && Auth::user()->role == 'vendor') {

            return true;

        }

        return false;

    }

}



if (!function_exists('isCustomer')) {

    function isCustomer()

    {

        if (Auth::check() && Auth::user()->role == 'customer') {

            return true;

        }

        return false;

    }

}


function wallet_history($data = [])
{
    $data = (object)$data;
    $WalletHistory = new \App\WalletHistory();
    $WalletHistory->user_id           = $data->user_id;
    $WalletHistory->amount            = $data->wallet_amount;
    $WalletHistory->pay_type          = $data->pay_type;
    $WalletHistory->description       = $data->description;
    $WalletHistory->is_earning        = isset($data->is_earning) ? $data->is_earning : 0;
    $WalletHistory->pay_method        = isset($data->pay_method) ? $data->pay_method : 0;
    $WalletHistory->created_at        = gmdate('Y-m-d H:i:00');
    $WalletHistory->updated_at        = gmdate('Y-m-d H:i:00');

    if ($WalletHistory->save()) {
        exec("php " . base_path() . "/artisan wallet_history:push " . $WalletHistory->id . " > /dev/null 2>&1 & ");
        return 1;
    }

    return 0;
}


if ( ! function_exists('send_common_sms')) {

    function send_sms($mobile="",$msg="",$unicode=false){

       



        $sender_id = env('SMS_SENDER_ID');         

        $username = env('SMS_USERNAME');

        $pwd = env('SMS_PASSWORD'); 

        $api = env('SMS_API_KEY');



        if($unicode==true)

        {

            $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api.'&senderid='.$sender_id.'&channel=2&DCS=8&flashsms=0&number='.$mobile.'&text='.rawurlencode($msg).'&route=1';

        }

        else

        {

            $url = 'https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey='.$api.'&senderid='.$sender_id.'&channel=2&DCS=0&flashsms=0&number='.$mobile.'&text='.rawurlencode($msg).'&route=1';

        }



        

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res=curl_exec($ch);

        curl_close($ch);



        // echo "<pre>";

        // print_r(json_decode($res));

        //  die('--');

    }

}



if ( ! function_exists('sendNotification')) {

    function sendNotification($device_tokens, $message)
    {

       $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');

        $registrationIds = array_chunk($device_tokens, 999);

        foreach($registrationIds as $registrationId) {

            $data = [
                "registration_ids" => $registrationId, 
                "data" => $message,
                "priority"=> "high"
            ];

            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            curl_close($ch);

        }

    }

}



function set_active( $route ) {

    if( is_array( $route ) ){

        return in_array(Request::path(), $route) ? 'active' : '';

    }

    return Request::path() == $route ? 'active' : '';

}



if (!function_exists('todayOrders')) {

    function todayOrders($vendor_id="")

    {   



        $data = array();

        $query=Package::where('vendor_id',$vendor_id);

        $query->where('is_active',1);

        $query->where('package_status','Accept');

        $query->where(function ($query)  {

            $query->where('package_type','Daily');

            $query->whereDate('start_date','<=', Carbon::today());

        });

        $query->orWhere(function ($query) {

            $query->where('package_type','Weekly');

            $query->whereDate('start_date','<=', Carbon::today());

            $query->where('week_day', Carbon::today()->format('l'));

        });

        $query->orWhere(function ($query) {

            $query->where('package_type','Alternate');

            $query->whereDate('start_date','<=', Carbon::today());

            $query->where('week_day', Carbon::today()->format('l'));

        });

        $data['packages']=$query->with('items')->get();

        $data['packageAddons']=PackageAddons::where('vendor_id',$vendor_id)->where('status','Accept')->whereDate('addon_date',Carbon::today())->with('items')->get();

        return $data;

    }

}



if (!function_exists('todayOrdersCount')) {

    function todayOrdersCount($vendor_id="")

    {   



        $data1 = $data2 = 0;

        $query=Package::where('vendor_id',$vendor_id);

        $query->where('is_active',1);

        $query->where('package_status','Accept');

        $query->where(function ($query) {

            $query->where('package_type','Daily');

            $query->whereDate('start_date','<=', Carbon::today());

        });

        $query->orWhere(function ($query) {

            $query->where('package_type','Weekly');

            $query->whereDate('start_date','<=', Carbon::today());

            $query->where('week_day', Carbon::today()->format('l'));

        });

        $query->orWhere(function ($query) {

            $query->where('package_type','Alternate');

            $query->whereDate('start_date','<=', Carbon::today());

            $query->where('week_day', Carbon::today()->format('l'));

        });

        $data1=$query->count();

        $data2=PackageAddons::where('vendor_id',$vendor_id)->where('status','Accept')->whereDate('addon_date',Carbon::today())->count();

        return $data1+$data2;

    }

}





if (!function_exists('updateWallet')) {

    function updateWallet($vendor_id="",$customer_id="",$amount=0,$type="Cr")

    {   

        $balance=0;

        $wallet=Wallet::where('vendor_id',$vendor_id)->where('customer_id',$customer_id)->first();



      

        if($wallet)

        {   

           

            $balance=$wallet->balance;

            if($type=='Cr')

            {

                $balance+=$amount;

            }

            else

            {

                $balance-=$amount;

            }

            $input=[

                    'customer_id' => $customer_id,

                    'vendor_id'=> $vendor_id,

                    'balance'=>$balance,

                ];

            $res=Wallet::where('id',$wallet->id)->update($input);



            

        }

        else

        {   

           

            if($type=='Cr')

            {

                $balance+=$amount;

            }

            else

            {

                $balance-=$amount;

            }

            $input=[

                    'customer_id' => $customer_id,

                    'vendor_id'=> $vendor_id,

                    'balance'=>$balance,

                ];

            $res=Wallet::create($input); 

           

        }

    }

}



if (!function_exists('getWallet')) {

    function getWallet($vendor_id="",$customer_id="")

    {   

        $balance=0;

        $wallet=Wallet::where('vendor_id',$vendor_id)->where('customer_id',$customer_id)->first();

        if($wallet)

        {   

           

            $balance=$wallet->balance;

            

        }

        return $balance;

    }

}



if (!function_exists('vendorPayment')) {

    function vendorPayment($vendor_id="")

    {   

        $balance=0;

        $wallets=Wallet::where('vendor_id',$vendor_id)->get();

        if($wallets)

        {   

            foreach ($wallets as $key => $value) {

                $balance+=$value->balance;

            }

        }

        return $balance;

    }

}


//send sms to mobile/phone
if ( ! function_exists('send_sms_to_mobile')) {
    function send_sms_to_mobile($mobile="",$msg=""){

        $curl = curl_init();

        curl_setopt_array($curl, array(
         CURLOPT_URL => 'http://51.210.118.93:8080/websmpp/websms?accesskey=OUXGeigVjbTet6J&sid=Urbanmop&mno=971'.$mobile.'&text='.$msg,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

    }
}

// Push Notification
if ( ! function_exists('send_notification')) {
    function send_notification($token, $title, $body, $text, $authtoken = null){

        $url = 'https://fcm.googleapis.com/v1/projects/urban-75edf/messages:send';
        if (empty($token) || empty($authtoken)) {
            return 'Missing required parameters (token or authtoken).';
        }
        
        $curl = curl_init();
        $payload = '{
            "message": {
              "token": "' . $token . '",
              "notification": {
                "title": "' . $title . '",
                "body": "' . $body . '"
              },
              "android": {
                "notification": {
                  "sound": "noti.wav",
                  "channel_id": "sound_channel"
                }
              }
            }
        }';
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/urban-75edf/messages:send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $payload,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$authtoken
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return $response;

    }
}

//send sms to mobile/phone
if ( ! function_exists('get_seller_info_by_service')) {
    function get_seller_info_by_service($service_id=""){

        $sellerservice = SellerService::where('service_id', $service_id)->get();

        $ids = [];
        foreach ($sellerservice as $key => $value) {
            array_push($ids, $value->seller_id);
        }

        $seller = Seller::whereIn('id',$ids)->get();

        $user_ids = [];
        foreach ($seller as $key => $ss) {
            array_push($user_ids, $ss->user_id);
        }

        $users = User::whereIn('id',$user_ids)->where('role', 'vendor')->where('is_active','1')->get();

        return $users;

    }
}


if ( ! function_exists('price_format')) {
    function price_format($price)
    {
        return round($price, 2);
    }
}

function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function update_booking($booking_id='')
{
    $booking = Card::find($booking_id);
    if($booking){
        if(empty($booking->encrypt)){

            $params['encrypt'] = generateRandomString();
            $res = $booking->update($params);
        
            if($res){
                $data['data'] = $booking;
                if($booking->user && $booking->user->email){
                    $array['view']      = 'emails.booking_completed';
                    $array['subject']   = 'Rating on Booking';
                    $array['data']      = $booking;
                    
                    \Mail::to($booking->user?$booking->user->email:'')->send(new \App\Mail\Mail($array));
                }
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    } else {
        return false;
    }
    
}

function send_single_notification($fcm_token, $notification, $data, $priority = 'high')
{
    // Set your project ID and access token
    $project_id = "urban-75edf";

    $access_token =getAccessToken(); // You'll need to generate this as described below
    //d($access_token);
    // Set the v1 endpoint
    $url = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";


    // Set the headers for the request
    $headers = [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ];

    // Make the request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));

    $payload = json_encode([
        'message' => [
            'token' => $fcm_token,
            'notification' => [
                "title" => $notification['title'],
                "body" => $notification['body']
            ],
            'data' =>convert_all_elements_to_string_fcm($data),
        ],
    ]);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    $curl_response = curl_exec($curl);

    curl_close($curl);

    if ($curl_response) {
        return json_decode($curl_response);
    } else {
        return false;
    }
}

function getAccessToken()
{

    //$jsonKey = json_decode(file_get_contents(config('firebase.FIREBASE_CREDENTIALS')), true);
    try {
        // Load the service account credentials JSON file
        $jsonKey = json_decode(file_get_contents(base_path('urban-75edf-firebase-adminsdk-cji2l-c4e23012b0.json')), true);

        $now = time();
        $token = [
            'iss' => $jsonKey['client_email'], // issuer
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600, // Token expiration time, set to 1 hour
            'iat' => $now // Token issued at time
        ];

// Encode the JWT
        $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $jwtHeader = base64_encode($jwtHeader);

        $jwtPayload = json_encode($token);
        $jwtPayload = base64_encode($jwtPayload);

// Sign the JWT using the private key
        openssl_sign($jwtHeader . '.' . $jwtPayload, $signature, $jsonKey['private_key'], 'sha256');
        $jwtSignature = base64_encode($signature);

// Concatenate the three parts to create the final JWT
        $assertion = $jwtHeader . '.' . $jwtPayload . '.' . $jwtSignature;

        // Prepare the cURL request
        // Now make the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion, // Use the generated JWT as the assertion
        ]));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            // Handle cURL error
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $authToken = json_decode($response, true);

        return $authToken['access_token'];
    } catch (Exception $e) {
        // Handle exceptions, e.g., log errors or throw a custom exception
        return null; // Or handle differently based on your application's needs
    }
}

function convert_all_elements_to_string_fcm($data = null, $emptyArrayShouldBeObject = false)
{
    if ($data != null) {
        array_walk_recursive($data, function (&$value, $key) use ($emptyArrayShouldBeObject) {
            if (!is_object($value)) {
                if ($value) {
                    $value = (string)$value;
                } else {
                    $value = (string)$value;
                }
            } else {
                $json = json_encode($value);
                $array = json_decode($json, true);

                array_walk_recursive($array, function (&$obj_val, $obj_key) use ($emptyArrayShouldBeObject) {
                    $obj_val = (string)$obj_val;
                });

                if (!empty($array)) {
                    $json = json_encode($array);
                    $value = json_decode($json);
                } else {
                    if ($emptyArrayShouldBeObject) {
                        $value = (object)[];
                    } else {
                        $value = [];
                    }
                }
            }
        });
    }
    return $data;
}