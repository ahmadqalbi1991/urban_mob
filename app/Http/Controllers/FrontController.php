<?php

namespace App\Http\Controllers;

use Auth;
use App\Slot;
use App\Card;
use App\User;
use App\City;
use App\Blog;
use App\Addon;
use App\Slider;
use App\Service;
use App\Address;
use App\Attribute;
use App\Locality;
use App\Setting;
use App\CardAddon;
use App\HomeSetting;
use App\AttributeValue;
use App\CardAttribute;
use App\ServiceGallery;
use App\ServiceAttributeValue;
use App\ServiceAttributeValueItem;
use App\Coupon;
use App\Question;
use App\Seller;
use App\UserCoupon;
use App\CardCoupon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\SellerService;
use App\Review;

class FrontController extends Controller
{
	public function index(Request $request)
	{
		$data['home_setting'] = Slider::first();
		// return $data['home_setting'];
		$data['services'] = Service::where('status','1')->orderBy('position')->get();
		$featured_services = Service::where('status','1')->where('featured','1')->get();
		$featured_services_status = "False";
		if(count($featured_services) && $featured_services){
			foreach($featured_services as $key => $featured){
              	if($featured->featured_banner){
              		$featured_services_status = "True";
              	}
			}
		}
		
		$data['featured_services'] = $featured_services;
		$data['featured_services_status'] = $featured_services_status;

		return view('web.home',$data);
	}

	public function search(Request $request)
	{
		$attr_items = AttributeValue::where('value', 'LIKE', "%$request->search%")->get();
		
		$attributeItemIds = [];
		$attributeIds = [];
		$serviceIds = [];
		if($attr_items && count($attr_items)){
			foreach ($attr_items as $key => $attribute) {
				array_push($attributeItemIds, $attribute->id);
			}			
		}

		foreach (Attribute::where('name', 'LIKE', "%$request->search%")->get() as $key => $val) {
			array_push($attributeIds, $val->id);
		}
		if(count($attributeItemIds) || count($attributeIds)){
			$serv_attr = ServiceAttributeValue::whereIn('attribute_id',$attributeIds)->orWhereIn('attribute_item_id',$attributeItemIds)->get();
			foreach ($serv_attr as $key => $item) {
				array_push($serviceIds, $item->service_id);
			}
		}
		if($serviceIds && count($serviceIds)){
			$data['services'] = Service::where('status','1')->whereIn('id', $serviceIds)->orderBy('position')->get();
		} else {
			$data['services'] = Service::where('status','1')->where('name', 'LIKE', "%$request->search%")->orderBy('position')->get();
		}
		
		return view('web.search',$data);
	}

	public function about_us()
	{
		return view('web.about_us');
	}

	public function contact_support()
	{
		return view('web.contact_support');
	}

	public function contact_support_post(Request $request)
	{

		$msg = 'Hello,';
		
		$msg .= ' Name :'.$request->name;
		
		$msg .= ', Email : '.$request->email;
		
		$msg .= ', Address : '.$request->address;
		
		$msg .= ', Phone : '.$request->phone;
		
		$msg .= ', Whatsapp : '.$request->whatsapp;
		
		$msg .= ', Comment : '.$request->comment;
		
		
		$array['view']      = 'emails.contact_support';
        $array['subject']   = 'Contact Support';
        $array['data']      = $msg;
        $res = \Mail::to('booknow@urbanmop.com')->send(new \App\Mail\Mail($array));
        return back()->with('success','Submit Successfully.');;
	}

	public function service_details($slug='', $delete='yes')
	{
		if(Auth::user()){
			\Session::put('user_id', Auth::user()->id);
		} else {
			if(!\Session::has('user_id')){
				\Session::put('user_id', mt_rand(1000000, 9999999));
			}
		}
		
		$serv =  Service::where('slug',$slug)->first();
		
		if(empty($serv)){
			return view('errors.404');;
		}

		$data['service'] = $serv;

		$id = $serv->id;

		if(Auth::user()){
			\Session::put('is_login','Yes');
		} else {
			\Session::put('is_login','No');
		}

		$card_infos = Card::where('service_id',$id)->where('user_id',\Session::get('user_id'))->where('is_checkout','Processing')->where('created_at', '<', date('y-m-d'))->get();
		foreach ($card_infos as $key => $card_info) {
			if($card_info){
				CardAddon::where('card_id',$card_info->id)->delete();
				CardAttribute::where('card_id',$card_info->id)->delete();
				$card_info->delete();
			}
		}
		

		$data['service_header'] = ServiceGallery::where('service_id',$id)->first();
		$data['card'] = Card::where('user_id',\Session::get('user_id'))->where('service_id',$id)->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->where('is_checkout','Processing')->orderBy('id', 'DESC')->first();

		$data['address'] = Address::where('user_id',\Session::get('user_id'))->orderBy('id', 'DESC')->first();
		$data['city'] = City::all();
		$data['locality'] = Locality::all();
		return view('web.service_details',$data);
	}

	public function get_child_cat_attr_items(Request $request)
	{
		$service = Service::find($request->service_id);
		$attribute_ids = [];
		foreach (ServiceAttributeValueItem::where('service_id',$request->service_id)->where('child_category_id',$request->child_category_id)->get() as $value) {
			array_push($attribute_ids, $value->id);
		}

		$satData = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('id',$request->sub_cat_id)->first();

		$datas=[];
		foreach(ServiceAttributeValue::where('service_id',$request->service_id)->whereIn('ser_attr_val_item_id',$attribute_ids)->get() as $attributeItems){
			if($attributeItems->attributeItem){
				
		        $data['id'] 					= $attributeItems->id;
		        $data['attributename'] 			= $attributeItems->attributeItem?$attributeItems->attributeItem->value:"";
		        $data['attribute_price'] 		= $attributeItems->attribute_price;
		        $data['service_id'] 			= $service->id;
		        $data['category_id'] 			= $service->category_id;
		        $data['child_category_id'] 		= $request->child_category_id;
		        $data['sub_cat_id'] 			= $request->sub_cat_id;
		        $data['main_sub_cat_id'] 		= $satData?$satData->sub_category_id:'';
		        $data['attribute_id'] 			= $attributeItems->attribute_id;
		        $data['attribute_name'] 		= $attributeItems->attribute?$attributeItems->attribute->name:"";
		        $data['attribute_item_id'] 		= $attributeItems->attribute_item_id;
		        $data['attribute_item_name'] 	= $attributeItems->attributeItem?$attributeItems->attributeItem->value:"";
		        array_push($datas, $data);
			}
		}
		return $datas;
	}

	public function sendOTP(Request $request)
	{
		$user=User::where('phone',$request->phone)->first();

        if($user && $user->is_registered==1){ 

            $data['msg'] = 'Number already Exist!';
            return $data;
        } 
        else
        {
            $otp=random_int(1000, 9999);
            User::where('id', $user->id)->update(['otp' => $otp]);
            $data['otp'] = $otp;
            $data['msg'] = 'OTP send successfully';
            return $data;
        }
	}

	public function register(Request $request)
	{
		if(User::where('phone',$request->phone)->where('is_active','1')->where('is_verified','1')->count()>0){
			$data['msg'] = 'This Number is Already Exist.';
			$data['status'] = 'False';
			return $data;
		} else {
			$otp 	= mt_rand(1000, 9999);
    		$phone  = $request->phone;
	        $msg 	= "OTP-".$otp;
	       
			$params['name'] = 'Guest';
	        // $params['email'] = $request->phone.'@gmail.com';
	        $params['phone'] = $request->phone;
	        $params['password'] = $request->phone;
	        $params['otp'] = $otp;
	        $params['registered_by'] = 'Web';
		    $params['is_verified'] = 1;
	        $params['is_active'] = 1;
	        $params['is_registered'] = 1;
	        $params['role'] = 'customer';
	        $params['verify'] = 'False';

	        $success = User::create($params);

	        $array['view'] 		= 'emails.otp';
            $array['subject'] 	= 'Your UrbanMop Signup OTP';
            $array['data'] 		= $otp;
  	        // \Mail::to('')->send(new \App\Mail\Mail($array));

	        $res=send_sms_to_mobile($phone,$msg);
	        $success['status'] = 'True';
	        return $success;
        }
	}

	public function login_with_otp(Request $request)
	{
		$user = User::where('phone',$request->phoneno)->where('is_active','1')->where('is_verified','1')->first();
		if($user){
			$otp = $request->otp1.''.$request->otp2.''.$request->otp3.''.$request->otp4;

			if($otp == $user->otp){
				Auth::login($user);
				if(Auth::user()){
					foreach (Card::where('user_id',\Session::get('user_id'))->where('is_checkout','Processing')->get() as $key => $card) {
						$params['user_id'] = Auth::user()->id;
						\Session::put('is_login','Yes');
						$params['is_login'] = 'Yes';
						$card->update($params);
					}
					\Session::put('user_id',Auth::user()->id);
				}
				$res['msg'] = 'Login Successfully.';
				$res['status'] = true;
				return $res;
			} else {
				$res['msg'] = 'Invalid OTP.';
				$res['status'] = false;
				return $res;
			}
		 	
		} else {
			
			$res['msg'] = 'Invalid User.';
			$res['status'] = false;
			return $res;
		}
		
	}

	public function logout()
	{
		Auth::logout();
		\Session::flush();
		return redirect('/');
	}

	public function add_card(Request $request)
	{
		$service = Service::find($request->service_id);
		$data['is_login']           = \Session::get('is_login');
		$data['user_id']            = \Session::get('user_id');
        $data['service_id']         = $request->service_id;
        $data['service_name']       = $service->name;
        // $data['category_id']        = $request->category_id;
        $data['date']               = date('Y-m-d');
        $data['tran_id']            = 'UM-'.mt_rand(1000,99999);
        $data['category_id']  		= $service->parent_id;

        if(Card::where('user_id',\Session::get('user_id'))->where('service_id',$request->service_id)->where('status','Pending')->where('is_checkout','Processing')->where('payment_collected','No')->where('work_done','No')->count()>0){
        	
        	$card = Card::where('user_id',\Session::get('user_id'))->where('service_id',$request->service_id)->where('status','Pending')->where('is_checkout','Processing')->where('payment_collected','No')->where('work_done','No')->first();

        	$dataupdate['g_total']      = $request->attribute_price+$card->g_total;
        	$dataupdate['category_id']  		= $service->parent_id;
        	$gtotal = $request->attribute_price+$card->g_total;
        	$card->update($dataupdate);
        } else {
        	$data['g_total']            = $request->attribute_price;
        	$gtotal = $request->attribute_price;
        	$card = Card::create($data);
        }
        
        if($card){

        	$params['card_id']              = $card->id;
            $params['sub_cate_id']          = $request->sub_cate_id;
            $params['child_cate_id']    	= $request->child_category_id;
            $params['main_sub_cat_id']      = $request->main_sub_cat_id;
            $params['attribute_id']         = $request->attribute_id;
            $params['attribute_name']       = $request->attribute_name;
            $params['attribute_item_id']    = $request->attribute_item_id;
            $params['attribute_item_name']  = AttributeValue::where('id',$request->attribute_item_id)->value('value');
            $params['attribute_price']      = $request->attribute_price;
            $params['attribute_qty']        = '1';

            $qty = '1';
            if(CardAttribute::where('card_id',$card->id)->where('sub_cate_id',$request->sub_cate_id)->where('attribute_item_id',$request->attribute_item_id)->count()>0){
            	$card_atr = CardAttribute::where('card_id',$card->id)->where('sub_cate_id',$request->sub_cate_id)->where('attribute_item_id',$request->attribute_item_id)->first();	    
            	$qty = '1'+$card_atr->attribute_qty;	
            	$paramsupdate['attribute_qty'] 		= $qty;
            	// $paramsupdate['attribute_price'] 	= $request->attribute_price*$qty;
            	
            	$card_atr->update($paramsupdate);
            } else {
            	$cardattr = CardAttribute::create($params);
            }
            
        }
        $carddata['service_id'] = $request->service_id;
        $res['qty'] = $qty;
        $res['gtotal'] = $gtotal;
        return array(

            'res' => $res,

            'modal_view' => view('web.cart_details',$carddata)->render(),

        );
	}

	public function minus_booking(Request $request)
	{  
		$card = Card::where('user_id',\Session::get('user_id'))->where('service_id',$request->service_id)->where('status','Pending')->where('is_checkout','Processing')->where('payment_collected','No')->where('work_done','No')->first();
		$card_atr = CardAttribute::where('card_id',$card->id)->where('sub_cate_id',$request->sub_cate_id)->where('attribute_item_id',$request->attribute_item_id)->first();	
		
		if($card_atr->attribute_qty>1){
			$paramsupdate['attribute_qty'] 		= $card_atr->attribute_qty-1;
	    	$paramsupdate['attribute_price'] 	= $request->attribute_price;
	    	$qty = $card_atr->attribute_qty-1;
	    	
	    	$card_atr->update($paramsupdate);
		} else {
			$card_atr->delete();
			$qty = '0';
		}           	
    	

    	$g_total = '0';
		foreach (CardAttribute::where('card_id',$card->id)->get() as $key => $value) {
			$g_total += $value->attribute_price;
			// $g_total += $request->attribute_price*$value->attribute_qty;
		}
		$cardinfo['g_total'] 	= $g_total;
		Card::where('id',$card->id)->update($cardinfo);
        
        $carddata['service_id'] = $request->service_id;
        $res['qty'] = $qty;
        return array(

            'res' => $res,

            'modal_view' => view('web.cart_details',$carddata)->render(),

        );
	}

	public function remove_card_attr(Request $request)
	{

		$cardattr = CardAttribute::find($request->card_atr_id);
		if($cardattr){
			$cardattr->delete();
		}	
		
		$g_total = '0';
		// foreach (CardAttribute::where('card_id',$request->card_id)->get() as $key => $value) {
		// 	$g_total += $value->attribute_price*$value->attribute_qty;
		// }
		
		// if(CardAttribute::where('card_id',$request->card_id)->count()>0){
		// 	$ginfo['g_total'] = $g_total;
		// 	Card::where('id',$request->card_id)->update($ginfo);		
		// } else {
		// 	Card::where('id',$request->card_id)->delete();
		// }
		$res = Card::where('id',$request->card_id)->delete();
        return $res;
		// return back()->with('error','Item Removed.');
	}

	public function get_card_booking(Request $request)
	{
		$carddata['service_id'] = $request?$request->service_id:'';
		$res['qty'] = '0';
        return array(

            'res' => $res,

            'modal_view' => view('web.cart_details',$carddata)->render(),

        );
	}

	public function removed_card_attr($cardid = '', $id='')
	{
		$cardattr = CardAttribute::find($id);
		$cardattr->delete();
		$g_total = '0';
		foreach (CardAttribute::where('card_id',$cardid)->get() as $key => $value) {
			$g_total += $value->attribute_price*$value->attribute_qty;
		}
		if(CardAttribut::where('card_id',$cardid)->count()>0){
			$card['g_total'] = $g_total;
			Card::where('id',$cardid)->update($card);			
		} else {
			Card::where('id',$cardid)->delete();
		}
		$carddd = Card::where('id',$cardid)->first();
		if($carddd){
			return redirect('card/details/'.$carddd->service_id)->with('error','Item Removed.');
		} else {
			return back()->with('error','Item Removed.');
		}		
	}

	public function removed_booking_attr(Request $request)
	{
		$cardattr = CardAttribute::find($request->card_atr_id);
		$cardattr->delete();
		$g_total = '0';
		foreach (CardAttribute::where('card_id',$request->card_id)->get() as $key => $value) {
			$g_total += $value->attribute_price*$value->attribute_qty;
		}

		if(CardAttribute::where('card_id',$request->card_id)->count()>0){
			$ccrd = Card::where('id',$request->card_id)->first();
			$g_total += $ccrd?$ccrd->tip_id:'0';
			$g_total += $ccrd?$ccrd->cod_charge:'0';
			$ginfo['g_total'] = $g_total;
			$ginfo['coupon_id'] = null;
			$ginfo['coupon_amt'] 	= null;
			Card::where('id',$request->card_id)->update($ginfo);
			CardCoupon::where('card_id',$request->card_id)->delete();			
		} else {
			Card::where('id',$request->card_id)->delete();
			CardCoupon::where('card_id',$request->card_id)->delete();
		}
		
		$card = Card::where('id',$request->card_id)->first();

		$carddata['service_id'] = $card?$card->service_id:'';
		
        return array(
            'modal_view' => view('web.cart_details',$carddata)->render(),
        );
	}

	public function update_profile(Request $request)
	{
		// $this->validate($request,[
        //     'name' => 'required',
        // ]);
     
        $card = Card::where('user_id',Auth::user()?Auth::user()->id:'')->where('service_id',$request->service_id)->where('status','Pending')->where('is_checkout','Processing')->orderBy('id', 'DESC')->first();
        if($card){
        	// $cardparams['card_process'] = 'Complete';
        	$cardparams['booking_from'] = 'Web';
        	$cardparams['address_id'] 	= $request->address_id;
        	$cardparams['amount'] 		= $card->g_total;
        	$card->update($cardparams);
        }
		if(Auth::user()){
			$params['name'] 	= $request->name;
			
			if($request->email){
				$params['email']	= $request->email;
			}

			if($request->date){
				$params['DOB'] 		= $request->date;
			}
	        
	        if($request->gender){
	        	$params['gender'] 	= $request->gender;
	        }

	        $params['verify'] 	= 'True';

	        if($request->hasFile('profile')){
                $imageName = $request->name.'-'.time().'.'.$request->profile->extension(); 
                $path = $request->profile->move(public_path('/uploads/user/'), $imageName);
                $params['profile'] = $imageName;
            }

	        User::where('id',Auth::user()->id)->update($params);

	        if($request->data_from=='my_profile'){
	        	return back()->with('success','Your Profile Updated.');
	        }
	        if($request->address || $request->address_type || $request->flat_no || $request->building){
	        	$this->validate($request,[
		            'address' => 'required',
		            'address_type' => 'required',
		            'flat_no' => 'required',
		        ]);
	        	$address['user_id'] 		= Auth::user()->id;
		        $address['address'] 		= $request->address;
		        $address['address_type'] 	= $request->address_type;
		        $address['flat_no'] 		= $request->flat_no;
		        $address['building'] 		= $request->building;
		        $address['locality'] 		= $request->locality;
		        $address['city_id'] 		= $request->city_id;
		        $address['latitude'] 		= $request->lat;
		        $address['longitude'] 		= $request->long;
		        if($request->address_id){
		        	
		        	Address::where('id',$request->address_id)->update($address);
		        } else {
		        	$address = Address::create($address);
		        	$cardparams['address_id'] 	= $address->id;
        			$card->update($cardparams);
		        }
	        }
	        
	        return redirect('card/details/'.$request->service_id);
		} else {
			return back()->with('error','Login First.');
		}
	}

	public function card_details($id)
	{

		if(Auth::user()){
			// $card_infos = Card::where('service_id',$id)->where('user_id',Auth::user()->id)->where('is_checkout','Processing')->orderBy('id', 'DESC')->get();
			// return $card_infos;
			// foreach ($card_infos as $key => $value) {
			// 	if($key==0){

			// 	} else {
			// 		if($card_info){
			// 			CardAddon::where('card_id',$card_info->id)->delete();
			// 			CardAttribute::where('card_id',$card_info->id)->delete();
			// 			$card_info->delete();
			// 		}
			// 	}				
			// }
			

			if(Card::where('user_id',Auth::user()->id)->where('service_id',$id)->where('status','Pending')->where('payment_collected','No')->where('is_checkout','Processing')->where('work_done','No')->count() && count(Card::where('user_id',Auth::user()->id)->where('service_id',$id)->where('is_checkout','Processing')->where('status','Pending')->with('card_attribute')->where('payment_collected','No')->where('work_done','No')->first()->card_attribute)){
				$data['service'] 		= Service::find($id);
				$data['user'] 			= Auth::user();
				$data['address'] 		= Address::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->first();
				$data['address_info'] 	= Address::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
				$data['card'] 			= Card::where('user_id',Auth::user()->id)->where('service_id',$id)->where('status','Pending')->where('is_checkout','Processing')->where('payment_collected','No')->where('work_done','No')->orderBy('id', 'DESC')->first();
				$attr_ids = [];
				$addon_ids = [];
				if(isset($data['card']) && $data['card']){
					foreach ($data['card']->card_attribute as $key => $value) {
						array_push($attr_ids, $value->attribute_item_id);
					}
					foreach ($data['card']->card_addon as $key => $addon) {
						array_push($addon_ids, $addon->add_on_id);
					}					
				}

				$data['addons'] 	= Addon::where('service_id',$id)->whereIn('attribute_item_id',$attr_ids)->get();
				$data['slots'] 		= Slot::all();
				$data['addon_ids'] 	= $addon_ids;
				return view('web.card_info',$data);
			} else {
				return redirect('/')->with('error','Your Card is Empty.');
			}
			
		} else {
			return back()->with('error','Login First.');
		}
	}

	public function card_update(Request $request)
	{
		$crd_info = Card::where('id',$request->card_id)->first();
		
		if($crd_info && $crd_info->is_checkout != 'Done'){

			if($request->slot_id && $request->date){

				if($request->card_id){

					$add_res = Address::find($request->address_id);
					$shippingAddress = [];

					$shippingAddress['user_id'] 	= $add_res->user_id;
					$shippingAddress['city_id'] 	= $add_res->city_id;
					$shippingAddress['city_name'] 	= $add_res->city?$add_res->city->name:'';
					$shippingAddress['address'] 	= $add_res->address;
					$shippingAddress['address_type']= $add_res->address_type;
					$shippingAddress['flat_no'] 	= $add_res->flat_no;
					$shippingAddress['building'] 	= $add_res->building;
					$shippingAddress['locality'] 	= $add_res->locality_info?$add_res->locality_info->name:'';
					$shippingAddress['latitude'] 	= $add_res->latitude;
					$shippingAddress['longitude'] 	= $add_res->longitude;


					$data['address_id'] 		= json_encode($shippingAddress);
					$data['alternative_number'] = $request->alternative_number;
					$data['slot_id'] 			= $request->slot_id;
					$data['payment_moad'] 		= $request->payment_moad;
					if($request->payment_moad=='Card'){
						$data['payment_type'] 		= $request->payment_type;
					}
					$data['date'] 				= $request->date;
					$data['note'] 				= $request->note;
					$data['card_process'] 		= 'Complete';
					$data['payment_status'] 	= $request->payment_moad=='Cash'?'True':'Pending';

					if($request->payment_moad=='Cash'){
						$data['is_checkout'] 		= 'Done';
					} else {
						$data['is_checkout'] 		= 'Processing';
					}
					
					if(Card::where('id',$request->card_id)->where('service_type','Normal')->orderBy('id', 'DESC')->first()){
					
						$total = '0';
						foreach (CardAttribute::where('card_id',$request->card_id)->get() as $key => $crdatr) {
							$ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
							$total += $ttotal;
						}

						foreach (CardAddon::where('card_id',$request->card_id)->get() as $key => $addon) {
							$attotal = $addon->value;
							$total += $attotal;
						}

						$card = Card::where('id',$request->card_id)->where('service_type','Normal')->orderBy('id', 'DESC')->first();
						

						if($card && $card->coupon_id){
							$coupon = Coupon::where('id',$card?$card->coupon_id:'')->where('status','1')->first();
				              if($coupon){
				              	$user_coupon['user_id'] = $card->user_id;
								$user_coupon['coupon_id'] = $card->coupon_id;
								UserCoupon::create($user_coupon);

								$crd_coupon['card_id'] 		= $request->card_id;
								$crd_coupon['coupon_id'] 	= $coupon->id;
								$crd_coupon['code'] 		= $coupon->code;
								$crd_coupon['amount'] 		= $coupon->amount;
								$crd_coupon['min_amount'] 	= $coupon->min_amount;
								$crd_coupon['max_amount'] 	= $coupon->max_amount;
								$crd_coupon['type'] 		= $coupon->type;
								$crd_coupon['start_date'] 	= $coupon->start_date;
								$crd_coupon['end_date'] 	= $coupon->end_date;

								if(CardCoupon::where('card_id',$request->card_id)->exists()){
									CardCoupon::where('card_id',$request->card_id)->update($crd_coupon);
								} else {
									CardCoupon::create($crd_coupon);
								}	

				                $amount = $coupon->amount;
				                if($coupon->type=='Amt'){
				                  $coupon_Amt = $amount;
				                } else {
				                  $per = ($amount / 100) * $total;

				                  if($per>$coupon->max_amount){
					                $coupon_Amt = $coupon->max_amount;
					              } else {
					                $coupon_Amt = $per;
					              }

				                }
			              	} else {
				                $coupon_Amt = '00';
			              	}
				            $total -= $coupon_Amt;
						}
						$total += $card?$card->tip_id:'0';
						$total += $card?$card->cod_charge:'0';
			            $data['g_total'] = $total;
			            
					} elseif (Card::where('id',$request->card_id)->where('service_type','Maid')->orderBy('id', 'DESC')->first()) {

						$total = '0';
						foreach (CardAttribute::where('card_id',$request->card_id)->get() as $key => $crdatr) {
							$ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
							$total += $ttotal;
						}

						foreach (CardAddon::where('card_id',$request->card_id)->get() as $key => $addon) {
							$attotal = $addon->value;
							$total += $attotal;
						}

						$card = Card::where('id',$request->card_id)->where('service_type','Maid')->orderBy('id', 'DESC')->first();
						
						
						if($card && $card->material_status){
							$total += $card?$card->material_charge:'0';
						}
						
						if($card && $card->coupon_id){
							$coupon = Coupon::where('id',$card?$card->coupon_id:'')->where('status','1')->first();

				              if($coupon){

				              	$user_coupon['user_id'] = $card->user_id;
								$user_coupon['coupon_id'] = $card->coupon_id;
								UserCoupon::create($user_coupon);

								$crd_coupon['card_id'] 		= $request->card_id;
								$crd_coupon['coupon_id'] 	= $coupon->id;
								$crd_coupon['code'] 		= $coupon->code;
								$crd_coupon['amount'] 		= $coupon->amount;
								$crd_coupon['min_amount'] 	= $coupon->min_amount;
								$crd_coupon['max_amount'] 	= $coupon->max_amount;
								$crd_coupon['type'] 		= $coupon->type;
								$crd_coupon['start_date'] 	= $coupon->start_date;
								$crd_coupon['end_date'] 	= $coupon->end_date;

								if(CardCoupon::where('card_id',$request->card_id)->exists()){
									CardCoupon::where('card_id',$request->card_id)->update($crd_coupon);
								} else {
									CardCoupon::create($crd_coupon);
								}	

				                $amount = $coupon->amount;
				                if($coupon->type=='Amt'){
				                  $coupon_Amt = $amount;
				                } else {
				                  $per = ($amount / 100) * $total;

				                  if($per>$coupon->max_amount){
					                $coupon_Amt = $coupon->max_amount;
					              } else {
					              	
					                if($per>$coupon->max_amount){
						                $coupon_Amt = $coupon->max_amount;
					              	} else {
						                $coupon_Amt = $per;
					              	}

					              }
				                }
			              	} else {
				                $coupon_Amt = '00';
			              	}
				            $total -= $coupon_Amt;
						}
						$total += $card?$card->tip_id:'0';
						$total += $card?$card->cod_charge:'0';
			            $data['g_total'] = $total;

					} else {
					
						return back()->with('error','Some Thing Want Wrong.');
					}

					$data['payment_type'] = $request->payment_type;
					Card::where('id',$request->card_id)->update($data);
					\Session::put('booking_id',$request->card_id);
					
					if($request->payment_moad=='Cash'){		
						return redirect('confirm-order');						
					} else {
						if($request->payment_type=='Tabby'){
							$params['amount'] 			= $total;
							$params['order_id'] 		= $card->tran_id;
							$params['customer_id'] 		= $card->id;
							$params['customer_name'] 	= $card->user?$card->user->name:'';
					    	$params['customer_phone'] 	= $card->user?$card->user->phone:'';
					    	$params['customer_email'] 	= $card->user?$card->user->email:'';
					    	// $params['customer_phone'] 	= "500000001";
					    	// $params['customer_email'] 	= "string";
					    	$params['customer_dob'] 	= $card->user?$card->user->DOB:'';
					    	$params['items_array'] 		= '';
					    	$addr = "";
					    	// if($card->address && $card->address->address_type){
					    	// 	$aty = $card->address->address_type;
					    	// 	$addr .= "Address Type : $aty ";
					    	// }
					    	// if($card->address && $card->address->locality_info){
					    	// 	$locality_info = $card->address->locality_info;
					    	// 	$addr .= ", $locality_info ";
					    	// }
					    	$params['shipping_city'] = '';
					    	if($card->address && $card->address->city){
					    		$city_n = $card->address->city->name;
					    		$params['shipping_city'] = $city_n;
					    		$addr .= ", $city_n ";
					    	}

					    	if($card->address){
					    		$addddd = $card->address->address;
					    		$addr .= $addddd;
					    	}
					    	
							$params['shipping_address'] = $addr;
							
							return $this->pay_by_tabby($params);						
						} else {
							return redirect('payment/'.$request->card_id);
						}
					}
				} else {
					return back()->with('error','Some Thing Want Wrong.');
				}

			} else {
				return back()->with('error','Required Fields Missing.');
			}

		} else {
			return back();
		}
		
		
	}

	function terms_condition()
	{
		$data['setting'] = Setting::where('title','terms-of-use')->first();
		return view('web.terms_condition',$data);
	}

	function privacy_policies()
	{
		$data['setting'] = Setting::where('title','privacy-policy')->first();
		return view('web.privacy_policies',$data);
	}

	public function add_addon(Request $request)
	{
		if($request->card_id && $request->addon_id){
			if($request->dtfrm=='Add'){
				$addon = Addon::find($request->addon_id);
				$params['card_id'] 				= $request->card_id;
				$params['card_attribute_id'] 	= $addon->attribute_item_id;
				$params['add_on_id'] 			= $addon->id;
				$params['name'] 				= $addon->name;
				$params['value'] 				= $addon->value;
				$params['percentage'] 			= $addon->percentage;
				CardAddon::create($params);

				$card = Card::find($request->card_id);
				$cardparams['g_total'] = $card->g_total+$addon->value;
				$card->update($cardparams);
			} else {
				$cardadd = CardAddon::where('card_id',$request->card_id)->where('add_on_id',$request->addon_id)->first();
				$card = Card::find($request->card_id);
				if($cardadd){
					$cardparams['g_total'] = $card->g_total-$cardadd->value;
					$card->update($cardparams);
					$cardadd->delete();
				}
			}
			
			$carddata['service_id'] = $card?$card->service_id:'';
		
	        return array(
	            'modal_view' => view('web.cart_details',$carddata)->render(),
	        );
		} else {
			return back()->with('error','Some Thing Want Wrong.');
		}
	}

	public function profile()
	{
		if(Auth::check()){
			$data['user'] = Auth::user();
			return view('web.my_profile',$data);
		} else {
			return back()->with('error','Login First.');
		}
	}

	public function myorders()
	{
		if(Auth::check()){
			$data['cards'] = Card::where('user_id',Auth::user()->id)->where('card_process','Complete')->orderBy('id', 'DESC')->get();

			$data['slot'] = Slot::get();
			return view('web.orders',$data);
		} else {
			return back()->with('error','Login First.');
		}
	}

	public function draftbookings()
	{
		if(Auth::check()){
			$data['cards'] = Card::where('user_id',Auth::user()->id)->where('payment_status','Draft')->orderBy('id', 'DESC')->get();

			return view('web.draftbookings',$data);
		} else {
			return back()->with('error','Login First.');
		}
	}

	public function confirm_order(Request $request)
	{
		error_reporting(E_ALL);
ini_set('display_errors', '1');
		$card = Card::where('id',\Session::get('booking_id'))->where('payment_status','True')->where('is_checkout','Done')->first();
		//print_r($card->toArray());
		if($card){

			if($card->user && $card->user->email){
				$array['view']      = 'emails.invoice';
		        $array['subject']   = 'Your Booking Invoice';
		        $array['data']      = $card;
		        try{
		        	\Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
				}catch(\Exception $e){
					print_r($e->getMessage());
						// Get error here
				}
			}

	        $adminarray['view']      = 'emails.invoice';
	        $adminarray['subject']   = 'You Have New Service Booking';
	        $adminarray['data']      = $card;
	       try{
	        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));
			}catch(\Exception $e){
					//print_r($e->getMessage());
						// Get error here
				}
//echo "here"; exit;
	        $tran_id = $card->tran_id;
	        $message = "Congratulation! You have successfully booked service with UrbanMop. \nYour booking ID is $tran_id \nView booking on UrbanMop \nhttps://www.urbanmop.com \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com";
			$msg = urlencode($message);
			if($card->user && $card->user->phone){

				$mobile = $card->user->phone;
				$res=send_sms_to_mobile($mobile,$msg);

			}

			$ser_users = get_seller_info_by_service($card?$card->service_id:'');
	        if($ser_users){
	        	foreach ($ser_users as $key => $value) {
		            if(isset($value->device_token)){
		            	
		                $token 	= $value->device_token;

		                $service = $card->service?$card->service->name:'No Service';
		                           
		                $title  = 'New Booking Arrived';
		                $body   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";
		                $text   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";

		                $data = send_notification($token, $title, $body, $text);
		            }
		        }
	        }			

			$dataReturn['card'] = $card;

			$dataReturn['is_checkout'] = 'Done';
			
			return view('web.confirm_order',$dataReturn);
		} else {
			return back()->with('error','Some Thing Want Wrong.');
		}

		
	}

	public function apply_coupon(Request $request)
	{
		$coupon = Coupon::where('code',$request->coupon_code)->where('status','1')->first();

		$coupon_use = UserCoupon::where('user_id',Auth::user()->id)->where('coupon_id',$coupon->id)->count();
		if($coupon){
			$card = Card::find($request->card_id);
			if($coupon->id==$card->coupon_id){
				$data['msg'] 	= 'This Coupon Already Applied';
				$data['status'] = 'False';
			} else {
				if($coupon_use < $coupon->user_used){
					if($coupon->start_date<=date('Y-m-d') && $coupon->end_date>=date('Y-m-d') ){
						if($card->coupon_id){
							$total = $card->amount;
						} else {
							$total = $card->g_total;
						}
		              	$old_amt = $total;

		              	$total -= $card->tip_id;
		              	
		              	$total -= $card->cod_charge;

		              	if($total>=$coupon->min_amount){
		              		if($coupon){
		              			
				                $amount = $coupon->amount;
				                if($coupon->type=='Amt'){
				                  $coupon_Amt = $amount;
				                } else {
				                  	$per = ($amount / 100) * $total;

				                  	if($per>$coupon->max_amount){
				                  		$coupon_Amt = $coupon->max_amount;
				                  	} else {
				                  		$coupon_Amt = price_format($per);
				                  	}

				                }
			              	} else {
				                $coupon_Amt = '00';
			              	}
			              	$params['before_coupon_amt'] = $old_amt;
			              	$old_amt -= $coupon_Amt;
			              	$params['coupon_amt'] 	= $coupon_Amt;
			              	$params['g_total'] 		= $old_amt;
							$params['coupon_id'] 	= $coupon->id;
							$card->update($params);

							$crd_coupon['card_id'] 		= $request->card_id;
							$crd_coupon['coupon_id'] 	= $coupon->id;
							$crd_coupon['code'] 		= $coupon->code;
							$crd_coupon['amount'] 		= $coupon->amount;
							$crd_coupon['min_amount'] 	= $coupon->min_amount;
							$crd_coupon['max_amount'] 	= $coupon->max_amount;
							$crd_coupon['type'] 		= $coupon->type;
							$crd_coupon['start_date'] 	= $coupon->start_date;
							$crd_coupon['end_date'] 	= $coupon->end_date;

							if(CardCoupon::where('card_id',$request->card_id)->exists()){
								CardCoupon::where('card_id',$request->card_id)->update($crd_coupon);
							} else {
								CardCoupon::create($crd_coupon);
							}							

							$data['card_amt'] 	= $card->g_total;
							$data['amt'] 	= $coupon_Amt;
							$data['msg'] 	= 'Coupon Apply Successfully';
							$data['status'] = 'True';
							$data['service_id'] = $card->service_id;
		              	} else {
		              		$data['msg'] 	= "This coupon's minimum amount is less than your total";
							$data['status'] = 'False';
		              	}
					} else {
						$data['msg'] 	= 'Coupon Expired';
						$data['status'] = 'False';
					}
				} else {
					$data['msg'] 	= 'Coupon Usage Limit Exceeded';
					$data['status'] = 'False';
				}
			}
			
		} else {
			$data['msg'] 	= 'Invalid Coupon';
			$data['status'] = 'False';
		}
		return $data;
	}

	public function remove_coupon(Request $request)
	{
		$card = Card::find($request->card_id);

		if($card){
			$params['g_total'] 		= $card->before_coupon_amt;
			$params['coupon_id'] 	= null;
			$params['coupon_amt'] 	= null;
			$card->update($params);

			CardCoupon::where('card_id',$request->card_id)->delete();

			$data['msg'] 	= 'Coupon Remove Successfully';
			$data['status'] = 'True';
			$data['service_id'] = $card->service_id;
		} else {
			$data['msg'] 	= 'Data Not Found!';
			$data['status'] = 'False';
		}
		return $data;
	}

	public function pay_tip(Request $request)
	{
		$card = Card::find($request->card_id);
		if($card->tip_id && isset($card->tip_id)){
			$params['tip_id'] 	= $request->tip;
			$gottal = $card->g_total-$card->tip_id;
			$params['g_total'] 	= $gottal+$request->tip;
			$params['amount'] 	= $gottal+$request->tip;

		} else {
			$params['tip_id'] 	= $request->tip;
			$params['g_total'] 	= $card->g_total+$request->tip;
			$params['amount'] 	= $card->g_total+$request->tip;
		}
		
		$card->update($params);
		$carddata['service_id'] = $card?$card->service_id:'';
		
        return array(
            'modal_view' => view('web.cart_details',$carddata)->render(),
        );
	}

	public function cod_charge(Request $request)
	{
		$setting = HomeSetting::first();
		$codamt  = $setting?$setting->cash_surcharge:'0';

		$card = Card::find($request->card_id);
		
		$params['payment_moad'] = $request->moad;
		if($request->moad=='Cash'){
			$params['cod_charge'] 	= $codamt;
			$params['g_total'] 	= $codamt+$card->g_total;
			$params['amount'] 	= $codamt+$card->g_total;
		} else {
			$params['cod_charge'] 	= '0';
			$params['g_total'] 	= $card->g_total-$card->cod_charge;
			$params['amount'] 	= $card->g_total-$card->cod_charge;
		}
		$card->update($params);
		$carddata['service_id'] = $card?$card->service_id:'';
		
        return array(
            'modal_view' => view('web.cart_details',$carddata)->render(),
        );
	}

	public function myaddress()
	{
		if(Auth::check()){
			$data['address'] 	= Address::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
			$data['city'] 		= City::all();
			$data['locality'] 	= Locality::get();
			return view('web.address',$data);
		} else {
			return back()->with('error','Login First.');
		}
	}

	public function get_locality(Request $request)
	{
		$locality = Locality::where('city_id',$request->city_id)->get();
        
        $data['locality'] = $locality;
        return array(
            'modal_view' => view('web.locality',$data)->render(),
        );
	}

	public function editaddress($id)
	{
		$data['address'] = Address::find($id);
		$data['city'] = City::all();
		$data['locality'] = Locality::get();
		return view('web.edit_address',$data);
	}

	public function update_address(Request $request)
	{
        $address['latitude'] 		= $request->lat;
        $address['longitude'] 		= $request->long;
        $address['address'] 		= $request->address;
        $address['address_type'] 	= $request->address_type;
        $address['flat_no'] 		= $request->flat_no;
        $address['building'] 		= $request->building;
        $address['locality'] 		= $request->locality;
        $address['city_id'] 		= $request->city_id;
        $res = Address::where('id',$request->id)->update($address);
        if($res){
        	return back()->with('success','Update Successfully.');
        } else {
        	return back()->with('error','Try Again.');
        }        
	}

	public function removeaddress($id='')
	{
		Address::where('id',$id)->delete();
		return back()->with('success','Delete Successfully.');
	}

	public function store_address(Request $request)
	{
		$address['user_id'] 		= Auth::user()->id;
        $address['address'] 		= $request->address;
        $address['address_type'] 	= $request->address_type;
        $address['flat_no'] 		= $request->flat_no;
        $address['building'] 		= $request->building;
        $address['locality'] 		= $request->locality;
        $address['city_id'] 		= $request->city_id;

        $res = Address::create($address);
        if($res){
        	return back()->with('success','Create Successfully.');
        } else {
        	return back()->with('error','Try Again.');
        }        
	}

	public function update_slot(Request $request)
	{
		$params['slot_id'] 	= $request->slot_id;
		$params['date'] 	= $request->date;
		Card::where('id',$request->card_id)->update($params);

		$card = Card::find($request->card_id);

		if($card->user && $card->user->email){
			$array['view']      = 'emails.change_slot_customer';
	        $array['subject']   = 'Slot Changed';
	        $array['data']      = $card;
	        
	        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
	    }

	    if($card->vendor && $card->vendor->email){
			$array['view']      = 'emails.change_slot_customer';
	        $array['subject']   = 'Slot Changed';
	        $array['data']      = $card;
	        
	        \Mail::to($card->vendor?$card->vendor->email:'')->send(new \App\Mail\Mail($array));
	    }

		$adminarray['view']      = 'emails.change_slot_admin';
        $adminarray['subject']   = 'Slot Changed';
        $adminarray['data']      = $card;
       
        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

		return back()->with('success','Slot Update Successfully.');
	}

	public function subAttribute(Request $request)
	{
		if(\Session::has('user_id')){
			$data['user_id']            = \Session::get('user_id');
	        $data['service_id']         = $request->service_id;
	        $ser_info = Service::find($request->service_id);
	        $data['service_name']       = $ser_info->name;
	        $data['category_id']        = $request->category_id;
	        $data['date']               = date('Y-m-d');
	        $data['tran_id']            = 'UM-'.mt_rand(1000,99999);
	        $data['service_type']       = 'Maid';
	        $data['material_status']	= 'Not';
	        $data['material_charge']	= '';

	        $valItems = ServiceAttributeValue::find($request->item_id);
	        if(Card::where('user_id',\Session::get('user_id'))->where('service_id',$request->service_id)->where('service_type','Maid')->where('status','Pending')->where('payment_collected','No')->where('is_checkout','Processing')->where('work_done','No')->count()>0){
	        	$card = Card::where('user_id',\Session::get('user_id'))->where('is_checkout','Processing')->where('service_id',$request->service_id)->where('service_type','Maid')->where('status','Pending')->where('payment_collected','No')->where('work_done','No')->first();
	        	$dataupdate['g_total']      = $valItems->attribute_price;
	        	$gtotal 					= $valItems->attribute_price+$card->g_total;
	        	$card->update($dataupdate);
	        } else {
	        	$data['g_total']  	= $valItems->attribute_price;
	        	$gtotal 			= $valItems->attribute_price;
	        	$card = Card::create($data);
	        }
	        
	        if($card){
	        	
	        	$params['card_id']              = $card->id;
	            $params['attribute_id']         = $valItems->attribute_item_id;
	            $params['attribute_name']       = $valItems->attributeItem?$valItems->attributeItem->value:'';
	            $params['attribute_item_id']    = $request->attribute_item_id;
	            $params['attribute_item_name']  = '';
	            $params['attribute_price']      = $valItems->attribute_price;
	            $params['attribute_qty']        = '1';
	            $params['service_type']        	= 'Maid';

	            $qty = '1';

	            if(CardAttribute::where('card_id',$card->id)->count()>0){
	            	$cardattr = CardAttribute::where('card_id',$card->id)->first();	            	
	            	$cardattr->update($params);
	            } else {
	            	$cardattr = CardAttribute::create($params);
	            }
	            
	        }
	        $response['service'] = Service::find($request->service_id);
	        $response['serviceItem'] = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('id', '!=' , $request->ser_attr_item_id)->first();
			$response['ser_attr_item_id'] = $request->ser_attr_item_id;
			$response['card_id'] = $card->id;
			$response['card_attribute_id'] = $cardattr->id;
	       return array(
	            'modal_view' => view('web.sub_attribute',$response)->render(),
	        );
	    } else {
	    	return back()->with('error','Login First.');
	    }		
	}

	public function updateSubAttribute(Request $request)
	{
		$card 			= Card::find($request->card_id);
		$card_atr 		= CardAttribute::where('id',$request->card_attribute_id)->first();
		$service_atr 	= ServiceAttributeValue::find($request->attribute_detail_id);
		$ftotal 		= $card_atr->attribute_price*$service_atr->attribute_price;

		$card_info['material_status']	= 'Not';
        $card_info['material_charge']	= null;
		$card_info['g_total'] = $ftotal;
		$card->update($card_info);

		// $card_atr_info['attribute_price'] 		= $ftotal;
		$card_atr_info['attribute_qty'] 		= $service_atr->attribute_price;
		$card_atr_info['attribute_item_id'] 	= $service_atr->attributeItem?$service_atr->attributeItem->id:'';
		$card_atr_info['attribute_item_name'] 	= $service_atr->attributeItem?$service_atr->attributeItem->value:'';
		$card_atr->update($card_atr_info);

		$service = Service::find($card?$card->service_id:'');
		if($service){
			$peramt = ($service->material_price * $ftotal) / '100';
		} else {
			$peramt = '00';
		}
		
		$response['card_id'] = $card?$card->id:'';
		return array(
			'peramt' => $peramt,
            'modal_view' => view('web.sub_attribute_card',$response)->render(),
        );
	}

	public function update_material_charge(Request $request)
	{
		$card = Card::find($request->card_id);
		$params['material_status'] = $request->status;
		if($request->status=='Not'){
			$params['material_charge'] = '0';
			$params['g_total'] = $card->g_total-$card->material_charge;
			$card->update($params);
		} else {
			$params['material_charge'] = $request->material_amt;
			$params['g_total'] = $request->material_amt+$card->g_total;
			if($card->material_status=='Not'){
				$card->update($params);
			}
		}
	
		
		$response['card_id'] = $request?$request->card_id:'';
		return array(
			'peramt' => $request->material_amt,
            'modal_view' => view('web.sub_attribute_card',$response)->render(),
        );
	}

	public function getLocation(Request $request)
	{
		$apiKey = 'AIzaSyBvGq8LjejiKHaI5lPEUZVYbwOYSwhZMEs';

		// Retrieve the search query from the form submission
		$location = $_POST['req'];

		// Prepare the URL for the Places Autocomplete API request
		$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . urlencode($location) . '&key=' . $apiKey;

		// Make the API request using cURL
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);

		// Decode the JSON response
		$data = json_decode($response, true);
		return $data;

	}

	public function store_question(Request $request)
	{
		$data['mobile_no'] = $request->mobileno;
        Question::create($data);
        return back()->with('success','Your Question Successfully Submit.');
	}

	public function payment($booking_id)
	{
		$card = Card::find($booking_id);
		\Session::put('booking_id',$booking_id);
		if($card){
			$amount = $card->g_total;
			$booking_id = $card->tran_id;
			$return_url = \URL::to('/paymentsuccess');
			$failure_return_url = \URL::to('/paymentfailure');
			$service = $card->service?$card->service->name:'NA';
			
			$client = new \GuzzleHttp\Client();

			$response = $client->request(
				'POST', 
				'https://business.mamopay.com/manage_api/v1/links', 
				[
			  		'body' => '{
			  			"name":"UrbanMop",
			  			"description":"'.$service.'",
			  			"capacity":1,
			  			"active":true,
			  			"return_url":"'.$return_url.'",
			  			"failure_return_url": "'.$failure_return_url.'",
			  			"processing_fee_percentage":0,
			  			"amount":"'.$amount.'",
			  			"amount_currency":"AED",
			  			"is_widget":false,
			  			"enable_tabby":false,
			  			"enable_message":false,
			  			"enable_tips":false,
			  			"enable_customer_details":false,
			  			"enable_quantity":false,
			  			"enable_qr_code":false,
			  			"send_customer_receipt":false,
						"external_id":"'.$booking_id.'"}',
				  	'headers' => [
					    'Authorization' => 'Bearer sk-3b63062a-a66c-40af-b877-7eda10ce1d32',
					    'accept' => 'application/json',
					    'content-type' => 'application/json',
				  	],
			]);

			$data = $response->getBody();
			$fdata = json_decode($data, true);
			
			return redirect($fdata['payment_url']);
		} else {
			return back()->with('error','Try Again.');
		}
		
	}

	public function paymentsuccess(Request $request)
	{
		$crd_info = Card::where('id',\Session::get('booking_id'))->first();
		if($crd_info && $crd_info->is_checkout != 'Done'){

			if($request->status=='captured'){
	
				$data['paymentTranId'] 	= $request->transactionId;
				$data['paymentLinkId'] 	= $request->paymentLinkId;
				$data['payment_status'] = 'True';
				$data['is_checkout'] 	= 'Done';

				Card::where('id',\Session::get('booking_id'))->update($data);
				$card = Card::where('id',\Session::get('booking_id'))->first();

				if($card->user && $card->user->email){
					$array['view']      = 'emails.invoice';
			        $array['subject']   = 'Your Booking Invoice';
			        $array['data']      = $card;
			        
			        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
			    }

				$adminarray['view']      = 'emails.invoice';
		        $adminarray['subject']   = 'You Have New Service Booking';
		        $adminarray['data']      = $card;
		       
		        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

		        $tran_id = $card->tran_id;
		        $message = "Congratulation! You have successfully booked service with UrbanMop. \nYour booking ID is $tran_id \nView booking on UrbanMop \nhttps://www.urbanmop.com \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com";
				$msg = urlencode($message);
				if($card->user && $card->user->phone){

					$mobile = $card->user->phone;
					$res=send_sms_to_mobile($mobile,$msg);

				}

				$home_set = HomeSetting::first();
				if($home_set && $home_set->admin_mobile){

					$admin_m_n = $home_set->admin_mobile;
					send_sms_to_mobile($admin_m_n,$msg);	

				}
				

				return redirect('confirm-order');
			} else {
				return back()->with('error','Payment Field Try Again.');
			}

		} else {
			return redirect('/');
		}
		
	}

	public function paymentfailure(Request $request)
	{
		$card = Card::where('id',\Session::get('booking_id'))->first();

		if($card){

			$data['paymentTranId'] 	= $request->transactionId;
			$data['paymentLinkId'] 	= $request->paymentLinkId;
			$data['payment_status'] = 'False';
			$data['is_checkout'] 	= 'Done';

			$card->update($data);

			if($card->user && $card->user->email){
				$array['view']      = 'emails.booking_faild_customer';
		        $array['subject']   = 'Your Booking Failed';
		        $array['data']      = $card;
		        
		        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
		    }

			$adminarray['view']      = 'emails.booking_faild_admin';
	        $adminarray['subject']   = 'Booking Failed';
	        $adminarray['data']      = $card;
	       
	        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

			return redirect('failed');

		} else {

			return redirect('/')->with('error','Payment Field Try Again.');
		}
	}

	public function failed()
	{
		$data['card'] = Card::where('id',\Session::get('booking_id'))->first();
		if($data['card']){
			return view('web.failed',$data);
		} else {
			return redirect('/');
		}
		
	}

	public function blogs()
	{
		$data['blogs'] = Blog::where('status','1')->get();
		
		return view('web.blog',$data);
	}

	public function blog_details($id)
	{
		$data['blog'] = Blog::where('slug',$id)->first();

		if($data['blog']){
			return view('web.blog_details',$data);
		} else {
			return back();
		}
		
	}

	public function become_vendor()
	{
		$data['city'] = City::all();
		$data['services'] = Service::all();
		return view('web.vendor_form',$data);
	}

	public function store_vendor(Request $request)
	{
        

        if($request->is_registered=='Yes'){

        	$this->validate($request,[
	            'person_name' 	=> 'required|string|max:200',
	            'store_name' 	=> 'required|string|max:200',
	            'mobile_no' 	=> 'required',
	            'vat_no' 		=> 'required',
	            'email' 		=> 'required|email|unique:users,email',
	            'business_license' 		=> 'required',
	        ]);

        } else {
        	$this->validate($request,[
	            'person_name' 	=> 'required|string|max:200',
	            'store_name' 	=> 'required|string|max:200',
	            'mobile_no' 	=> 'required',
	            'email' 		=> 'required|email|unique:users,email',
	            'business_license' 		=> 'required',
	        ]);
        }

        $params['role']             = 'vendor';
        $params['name']             = $request->person_name;
        $params['email']            = $request->email;
        $params['phone']            = $request->mobile_no;
        $params['city']            	= $request->city_id;
        $params['verify']           = 'True';
        $params['is_registered']    = 'Web';
        $params['is_active']        = 0;
        $params['is_registered']    = 1;
        $params['is_verified']      = 0;

        $user = User::create($params);

        if($user){
        	$seller['user_id']          = $user->id;
	        $seller['company_name']     = $request->store_name;
	        $seller['landline_no']      = $request->landline_number;
	        $seller['city']             = $request->city_id;
	        $seller['address']          = $request->address;
	        $seller['bank_name']        = $request->bank_name;
	        $seller['ac_holder_name']   = $request->ac_holder_name;
	        $seller['ac_number']        = $request->ac_number;
	        // $seller['contact_ac_no']    = $request->mobile_no;
	        $seller['status']           = '1';
	        $seller['is_registered']    = $request->is_registered;
	        $seller['vat_no']           = $request->vat_no;

	        if($request->hasFile('business_license')){
	            $imageName = time().'.'.$request->business_license->extension(); 
	            $path = $request->business_license->move(public_path('/uploads/vendor_document/'), $imageName);
	            $seller['licence_file'] = $imageName;
	        }
	        
	        if(Seller::where('user_id',$user->id)->count()>0){
	            Seller::where('user_id',$user->id)->update($seller);
	            $seller_info = Seller::where('user_id',$user->id)->first();
	        } else {
	           $seller_info = Seller::create($seller);
	        }
	        $serviceIds = [];
	        if($request->service_id){
	            foreach ($request->service_id as $key => $value) {
	                $sellerservice['seller_id'] = $seller_info->id;
	                $sellerservice['service_id'] = $value;
	                // if(!SellerService::where('seller_id',$seller_info->id)->where('service_id',$value)->count()>0){
	                    SellerService::create($sellerservice);
	                    $ssss = Service::find($value);
	                    array_push($serviceIds, $ssss?$ssss->name:'');
	                // }	                
	            }
	        }

	        $address['user_id'] 		= $user->id;
	        $address['address'] 		= $request->address;
	        $address['address_type'] 	= $request->address_type;
	        $address['flat_no'] 		= $request->flat_no;
	        $address['building'] 		= $request->building;
	        $address['locality'] 		= $request->locality;
	        $address['city_id'] 		= $request->city_id;

	        Address::create($address);

	        if ($seller_info) {
	        	if($request->email){
					$array['view']      = 'emails.vendor_created';
			        $array['subject']   = 'New Vendor Created';
			        $array['data']      = $request->store_name;
			        
			        \Mail::to($request->email)->send(new \App\Mail\Mail($array));
			    }

				$adminarray['view']      = 'emails.vendor_created_admin';
		        $adminarray['subject']   = 'New Vendor Created';
		        $adminarray['data']      = $seller_info;
		       
		        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));
	        }

	        return back()->with('success','Vendor create successfully.');	        
        } else {
        	return back()->with('error','Try Again.');
        }
        return back()->with('error','Try Again.');
	}

	public function get_live_address(Request $request)
	{
		$apiKey = 'AIzaSyBHrKkhwSDWrr45yqGAt2GjgF0adHLAkTU';

		$location = $request->location;

		$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . urlencode($location) . '&key=' . $apiKey;

		// Make the API request using cURL
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);

		// Decode the JSON response
		$data = json_decode($response, true);
		
		// Check if the response contains predictions
		$html = '';
		if ($data['status'] === 'OK') {
		    $predictions = $data['predictions'];

		    // Loop through the predictions and retrieve the place names
		    foreach ($predictions as $prediction) {
		        $placeName = $prediction['description'];
		        $html .= '<option value="' . $placeName . '">' . $placeName . '</option>';
		    }
		} else {
		     $html = '<option value="">No Data Found</option>';
		}
		$datas['res'] = $html;

		return $datas;
	}

	public function get_lat_long(Request $request)
	{
		$apiKey = 'AIzaSyBHrKkhwSDWrr45yqGAt2GjgF0adHLAkTU';

		$location = $request->location;

		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($location) . "&key=" . $apiKey;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($response, true);

		if ($data['status'] === 'OK') {
		    $res['latitude'] = $data['results'][0]['geometry']['location']['lat'];
		    $res['longitude'] = $data['results'][0]['geometry']['location']['lng'];
		    $res['status'] = '1';
		} else {
		    $res['status'] = '0';
		}
		return $res;
	}


    public function checkmobileno(Request $request)
    {
    	if(User::where('phone',$request->phone)->count()>0){
	       	$data['status'] = "True";
    	} else {
    		
    		$otp 	= mt_rand(1000, 9999);
    		$phone  = $request->phone;
	       
			$params['name'] = 'Guest';
	        $params['phone'] = $request->phone;
	        $params['password'] = $request->phone;
	        $params['registered_by'] = 'Web';
		    $params['is_verified'] = 1;
	        $params['is_active'] = 1;
	        $params['is_registered'] = 1;
	        $params['role'] = 'customer';
	        $params['verify'] = 'False';

	        $success = User::create($params);

    		$data['status'] = "True";

    	}
      
        return $data;
    }

	public function send_sms(Request $request)
    {
    	
    	if(User::where('phone',$request->phone)->count()>0){
    		$otp 	= mt_rand(1000, 9999);
    		$phone  = $request->phone;
	        // $msg 	= "OTP-".$otp;
	        // $msg = preg_replace('/[^A-Za-z0-9\-]/', '', $msg); // Remove spaces and special characters
  			$User['otp'] = $otp;
  	        User::where('phone',$request->phone)->update($User);

  	        $array['view'] 		= 'emails.otp';
            $array['subject'] 	= 'Your UrbanMop Login OTP';
            $array['data'] 		= $otp;
  	        // \Mail::to('user@gmail.com')->send(new \App\Mail\Mail($array));

	    	$message = "Your UrbanMop account LOGIN verification code is $otp \nDo not share this code with anyone for account safety.";
			$msg = urlencode($message);

	        $res=send_sms_to_mobile($phone,$msg);
	       	$data['status'] = "True";
	        
    	} else {
    		$data['status'] = 'false';
    		$data['msg'] = 'Your Mobile Number Does Not Exist.';
    	}
      
        return $data;
    }

    public function get_slot(Request $request)
    {
    	$slot = Slot::get();

    	$nowtime = date("H:i");
		
		$date = date('H:i', strtotime($nowtime . ' + 2 Hours'));

    	$html = '<option value="">Select Slot</option>';
        
        if($request->from=='current'){
        	foreach ($slot as $key => $row) {
	    		if($row->check_in > $date){
		    		$html .= '<option value="' . $row->id . '">' . $row->name . '</option>';
		    	}
	    	}
        } else {
        	foreach ($slot as $key => $row) {
		    	$html .= '<option value="' . $row->id . '">' . $row->name . '</option>';
	    	}
        }
    	
        echo $html;
    }

    // Tabby Work

    public function test_tabby()
    {
    	$params['order_id'] 		= '000001';
    	$params['customer'] 		= '000001'; // booking row id
    	$params['amount'] 			= '2000';
    	$params['customer_name'] 	= '';
    	$params['customer_phone'] 	= '';
    	$params['customer_email'] 	= '';
    	$params['customer_dob'] 	= '';
    	$params['items_array'] 		= '';

    	return $this->pay_by_tabby($params);
    }


    public function pay_by_tabby($request)
    {

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.tabby.ai/registration/v1/sessions',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
		  "lang": "en",
		  "merchant_code": "UMUAE",
		  "merchant_urls": {
		    "success": "https://www.urbanmop.com/tabby/payment/response",
		    "cancel": "https://www.urbanmop.com/tabby/cancel/response",
		    "failure": "https://www.urbanmop.com/tabby/failure/response"
		  },
		  "buyer": {
		    "phone": "'.$request["customer_phone"].'", 
            "email": "'.$request["customer_email"].'",
            "name": "'.$request["customer_name"].'",
		    "dob": "2019-08-24"
		  },
		  "buyer_history": {
		    "registered_since": "2019-08-24T14:15:22Z",
		    "loyalty_level": 0,
		    "wishlist_count": 0,
		    "is_social_networks_connected": true,
		    "is_phone_number_verified": true,
		    "is_email_verified": true
		  }
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer sk_a8d65278-c694-419c-8bb0-17cbbf38a455',
		    'Content-Type: application/json',
		    'Cookie: _cfuvid=gDYieiE1Ewcg2iyAIcyDxeVxCdKPKEm7BQbfFz9ql3g-1703145517954-0-604800000'
		  ),
		));

		curl_exec($curl);

		curl_close($curl);
		


		$curl = curl_init();
		// return $request;
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.tabby.ai/api/v2/checkout',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
			    "payment": {
			        "amount": "'.$request["amount"].'", 
			        "currency": "AED", 
			        "description": "string",
			        "buyer": {
			            "phone": "'.$request["customer_phone"].'", 
			            "email": "'.$request["customer_email"].'",
			            "name": "'.$request["customer_name"].'",
			            "dob": "2019-08-24" 
			        },
			        "buyer_history": {
			            "registered_since": "2019-08-24T14:15:22Z", 
			            "loyalty_level": 0,
			            "wishlist_count": 0, 
			            "is_social_networks_connected": true,
			            "is_phone_number_verified": true, 
			            "is_email_verified": true 
			        },
			        "order": {
			            "tax_amount": "0.00",
			            "shipping_amount": "0.00",
			            "discount_amount": "0.00",
			            "updated_at": "2019-08-24T14:15:22Z",
			            "reference_id": "'.$request["order_id"].'",
			            "items": [
			                {
			                    "title": "string", 
			                    "description": "string", 
			                    "quantity": 1, 
			                    "unit_price": "'.$request["amount"].'", 
			                    "discount_amount": "0.00",
			                    "reference_id": "string",
			                    "image_url": "http://example.com",
			                    "product_url": "http://example.com",
			                    "gender": "Male",
			                    "category": "string",  
			                    "color": "string",
			                    "product_material": "string",
			                    "size_type": "string",
			                    "size": "string",
			                    "brand": "string"
			                }
			            ]
			        },
			        "order_history": [
			            {
			                "purchased_at": "2019-08-24T14:15:22Z", 
			                "amount": "'.$request["amount"].'", 
			                "payment_method": "card", 
			                "status": "new",
			                "buyer": { 
			                    "phone": "'.$request["customer_phone"].'", 
					            "email": "'.$request["customer_email"].'",
					            "name": "'.$request["customer_name"].'", 
			                    "dob": "2019-08-24" 
			                },
			                "shipping_address": {
			                    "city": "'.$request["shipping_city"].'",
			                    "address": "'.$request["shipping_address"].'",
			                    "zip": "string" 
			                },
			                "items": [
			                    {
			                        "title": "string",
			                        "description": "string",
			                        "quantity": 1,
			                        "unit_price": "'.$request["amount"].'", 
			                        "discount_amount": "0.00",
			                        "reference_id": "string",
			                        "image_url": "http://example.com",
			                        "product_url": "http://example.com",
			                        "ordered": 0,
			                        "captured": 0,
			                        "shipped": 0,
			                        "refunded": 0,
			                        "gender": "Male",
			                        "category": "string",
			                        "color": "string",
			                        "product_material": "string",
			                        "size_type": "string",
			                        "size": "string",
			                        "brand": "string"
			                    }
			                ]
			            }
			        ],
			        "shipping_address": {
			            "city": "'.$request["shipping_city"].'", 
			            "address": "'.$request["shipping_address"].'",
			            "zip": "00000" 
			        },
			        "meta": {
			            "order_id": "'.$request["order_id"].'", 
			            "customer": "00002" 
			        },
			        "attachment": {
			            "body": "{\\"flight_reservation_details\\": {\\"pnr\\": \\"TR9088999\\",\\"itinerary\\": [...],\\"insurance\\": [...],\\"passengers\\": [...],\\"affiliate_name\\": \\"some affiliate\\"}}",
			            "content_type": "application/vnd.tabby.v1+json"
			        }
			    },
			    "lang": "en", 
			    "merchant_code": "UMUAE", 
			    "merchant_urls": {
			        "success": "https://www.urbanmop.com/tabby/payment/response",
			        "cancel": "https://www.urbanmop.com/tabby/cancel/response",
			        "failure": "https://www.urbanmop.com/tabby/failure/response"
			    }
			}',
			  CURLOPT_HTTPHEADER => array(
			    'Authorization: Bearer sk_a8d65278-c694-419c-8bb0-17cbbf38a455',
			    'Content-Type: application/json'
			  ),
			));

			$response = curl_exec($curl);

		curl_close($curl);

		$respo = json_decode($response, true);
		// return $respo;
		if($respo && isset($respo['configuration']) && isset($respo['configuration']['available_products']) && isset($respo['configuration']['available_products']['installments'])){
			return redirect($respo['configuration']['available_products']['installments'][0]['web_url']);
		} else {
			return back()->with('error','Required Fields is Empty. Try Again.');
		}
		
		// echo "<pre>";
		// print_r($respo['configuration']['available_products']['installments'][0]['web_url']);

    }

    public function tabby_response(Request $request)
    {
    	$params['payment_id'] = $request->payment_id;
    	return $this->check_tabby_status($params); 	
    }

    public function tabby_cancel(Request $request)
    {
    	$params['payment_id'] = $request->payment_id;
    	return $this->check_tabby_status($params);  
    }

    public function tabby_failure(Request $request)
    {
    	$params['payment_id'] = $request->payment_id;
    	return $this->check_tabby_status($params);  
    }

    public function check_tabby_status($request)
    {
    	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.tabby.ai/api/v1/payments/'.$request['payment_id'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer sk_a8d65278-c694-419c-8bb0-17cbbf38a455'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$respo = json_decode($response, true);
		// echo "<pre>"; print_r($respo);die();
		if($respo && $respo['status'] != 'error'){
			// -------------- Capture Payment

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.tabby.ai/api/v1/payments/'.$request['payment_id'].'/captures',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS =>'{
			  "amount": "'.$respo["amount"].'",
			  "reference_id": "string",
			  "tax_amount": "0.00",
			  "shipping_amount": "0.00",
			  "discount_amount": "0.00",
			  "created_at": "string",
			  "items": [
			    {
			      "title": "string",
			      "description": "string",
			      "quantity": 1,
			      "unit_price": "0.00",
			      "discount_amount": "0.00",
			      "reference_id": "string",
			      "image_url": "http://example.com",
			      "product_url": "http://example.com",
			      "gender": "Male",
			      "category": "string",
			      "color": "string",
			      "product_material": "string",
			      "size_type": "string",
			      "size": "string",
			      "brand": "string"
			    }
			  ]
			}',
			  CURLOPT_HTTPHEADER => array(
			    'Content-Type: application/json',
			    'Authorization: Bearer sk_a8d65278-c694-419c-8bb0-17cbbf38a455'
			  ),
			));

			$catp = curl_exec($curl);
			$catp = json_decode($catp, true);
			curl_close($curl);

			// -------------- End Capture
			// echo "<pre>"; print_r($catp);die();
			$card = Card::where('tran_id',$respo['meta']['order_id'])->first();
			if($card){
				
				$orderID = $respo['meta']['order_id'];
				$pstatus = $respo['status'];

				$params['tabby_payment_status'] = $pstatus;
				$params['tabby_payment_response_id'] = $request['payment_id'];
				$params['is_checkout'] = 'Done';

				if($pstatus == 'CREATED'){
					$params['payment_status'] = 'False';
					$params['tabby_payment_response_id'] = $request['payment_id'];
					$res = Card::where('id',$card->id)->update($params);

				    if($card->user && $card->user->email){
						$array['view']      = 'emails.booking_faild_customer';
				        $array['subject']   = 'Your Booking Failed';
				        $array['data']      = Card::where('id',$card->id)->first();
				        
				        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
				    }

					$adminarray['view']      = 'emails.booking_faild_admin';
			        $adminarray['subject']   = 'Booking Failed';
			        $adminarray['data']      = Card::where('id',$card->id)->first();
			       
			        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

			        $tran_id = $card->tran_id;
			        
			        $message = "Your payment created. \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com";
					$msg = urlencode($message);
					if($card->user && $card->user->phone){

						$mobile = $card->user->phone;
						$res=send_sms_to_mobile($mobile,$msg);

					}

					return redirect('/')->with('error','Your payment is created. Contact UrbanMop Support.');
					
				} elseif ($pstatus == 'AUTHORIZED'){

					$params['payment_status'] = 'True';
					$params['tabby_payment_response_id'] = $request['payment_id'];
					$res = Card::where('id',$card->id)->update($params);

					if($res) {

						$adminarray['view']      = 'emails.invoice';
				        $adminarray['subject']   = 'You Have New Service Booking';
				        $adminarray['data']      = Card::where('id',$card->id)->first();
				       
				        // \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

				        if($card->user && $card->user->email){
							$array['view']      = 'emails.invoice';
					        $array['subject']   = 'Your Booking Invoice';
					        $array['data']      = $card;
					        
					        // \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
					    }

				        $tran_id = $card->tran_id;
				        $message = "Congratulation! You have successfully booked service with UrbanMop. \nYour booking ID is $tran_id \nView booking on UrbanMop \nhttps://www.urbanmop.com \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com";
				        
						// $msg = urlencode($message);
						// if($card->user && $card->user->phone){

						// 	$mobile = $card->user->phone;
						// 	$res=send_sms_to_mobile($mobile,$msg);

						// }
						
						\Session::put('booking_id',$card->id);

						return redirect('confirm-order');
					} else {
						
						return redirect('/')->with('error','Something went wrong. Contact UrbanMop Support.');
					}
				} else {
					$params['payment_status'] = 'False';
					$params['tabby_payment_response_id'] = $request['payment_id'];
					$res = Card::where('id',$card->id)->update($params);

					if($card->user && $card->user->email){
						$array['view']      = 'emails.booking_faild_customer';
				        $array['subject']   = 'Your Booking Failed';
				        $array['data']      = $card;
				        
				        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
				    }

					$adminarray['view']      = 'emails.booking_faild_admin';
			        $adminarray['subject']   = 'Booking Failed';
			        $adminarray['data']      = $card;
			       
			        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

					return redirect('/')->with('error','Your payment is created. Contact UrbanMop Support.');
				}

			} else {
				return redirect('/')->with('error','Something went wrong. Contact UrbanMop Support.');
			}
			
		} else {
			return redirect('/')->with('error','Your payment failed. Contact UrbanMop Support.');	
		}
		
    }

    public function cronjob_tabby_status(Request $request)
    {	
    	$bookings = Card::where('payment_status','False')->where('tabby_payment_status','CREATED')->whereDate('created_at', Carbon::today()->subDays(1))->get();

    	foreach ($bookings as $key => $card) {
    		
    		$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.tabby.ai/api/v1/payments/'.$card->tabby_payment_response_id,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
			    'Authorization: Bearer sk_a8d65278-c694-419c-8bb0-17cbbf38a455'
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			$respo = json_decode($response, true);

			if($respo && $respo['status']);
			{
				$pstatus = $respo['status'];
				if($pstatus == 'AUTHORIZED')
				{

					$params['payment_status'] = 'True';
					$params['tabby_payment_status'] = $pstatus;

					$res = Card::where('id',$card->id)->update($params);

					if($res) 
					{

						$adminarray['view']      = 'emails.invoice';
				        $adminarray['subject']   = 'You Have New Service Booking';
				        $adminarray['data']      = Card::where('id',$card->id)->first();
				       
				        // \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

				        if($card->user && $card->user->email){
							$array['view']      = 'emails.invoice';
					        $array['subject']   = 'Your Booking Invoice';
					        $array['data']      = $card;
					        
					        // \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
					    }

				        $tran_id = $card->tran_id;
				        $message = "Congratulation! You have successfully booked service with UrbanMop. \nYour booking ID is $tran_id \nView booking on UrbanMop \nhttps://www.urbanmop.com \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com";
				        
						$msg = urlencode($message);
						if($card->user && $card->user->phone){

							$mobile = $card->user->phone;
							// $res=send_sms_to_mobile($mobile,$msg);

						}
					}
				}
    		}   		

    		print_r($card->id); echo " | "; print_r($card->tabby_payment_response_id); echo " | "; print_r($respo['status']);
    	}

    	$cron['datetime'] = date('Y-m-d H:i:s');

		\DB::table('cron_jobs')->insert($cron);
	}

    
    public function checkmail($id)
    {
    	$data['data'] = Card::find($id);

    	return view('emails.invoice',$data);
    }

    function booking_review($id)
	{
		$card = Card::where('encrypt',$id)->first();
	
		if($card){

			$review = Review::where('booking_id',$card->id)->first();
			if($review){
				return redirect('/')->with('error','Review already submitted');
			} else {
				$data['booking'] = $card;
				return view('web.review_form',$data);
			}
		} else {
			return redirect('/')->with('error','Try Again.');
		}
		
	}

	function store_review(Request $request)
	{
		$params['booking_id'] = $request->booking_id;
		$params['service_id'] = $request->service_id;
		$params['vendor_id'] = $request->vendor_id;
		$params['customer_id'] = $request->customer_id;
		$params['rating'] = $request->rating;
		$params['opinion'] = $request->opinion;

		$res = Review::create($params);

		if($res){
			return redirect('/')->with('success','Review submit successfully');
		} else {
			return back()->with('error','Try Again.');
		}
	}
}



