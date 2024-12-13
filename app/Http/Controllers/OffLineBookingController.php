<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceAttributeValueItem;
use App\ServiceAttributeValue;
use App\OfflineBookingAttribute;
use App\OfflineBooking;
use App\CardAttribute;
use App\HomeSetting;
use App\UserCoupon;
use App\CardCoupon;
use App\Category;
use App\Address;
use App\Service;
use App\Coupon;
use App\User;
use App\City;
use App\Slot;
use App\Card;
use App\Seller;
use Session;

class OffLineBookingController extends Controller
{
    function booking_list(Request $request)
    {
        $data['bookings'] = OfflineBooking::orderBy('id','DESC')->where('card_process','Complete')->where('is_live','No')->get();
        $data['vendors'] = User::where('role','vendor')->where('is_verified','1')->where('verify','True')->get();
        return view('offline.booking_list',$data);
    }

    public function change_vendor(Request $request)
    {
        $card= OfflineBooking::find($request->booking_id);
        $seller = Seller::where('user_id',$request->vendor_id)->first();

        if($seller){
            
            if($card){

                $data['accept_user_id'] = $request->vendor_id;
                $data['accept_user_company_id'] = $seller->id ?? '';
                $data['status'] = 'Accept';
                $card->update($data);

                if($card->user && $card->user->email){
                           
                    $array['view']      = 'emails.booking_confirm_customer';
                    $array['subject']   = 'Your booking has been accepted!';
                    $array['data']      = $card;
                    \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
                }

                if($card->vendor && $card->vendor->email){
                    if($card && $card->user){
                        $customer = $card->user?$card->user->name:'';
                    } else {
                        $customer = 'No Name';
                    }

                    if($card && $card->service){
                        $service = $card->service?$card->service->name:'';
                    } else {
                        $service = 'No Service';
                    }

                    $array['view']      = 'emails.booking_confirm';
                    $array['subject']   = 'Booking Confirmation with '.$customer.' for '.$service;
                    $array['data']      = $card;
                    \Mail::to($card->vendor?$card->vendor->email:'')->send(new \App\Mail\Mail($array));
                }

                return redirect()->back()->with('success','Vendor change successfully.');

            } else {

                return redirect()->back()->with('error','Booking not found.');

            }

        } else {

            return redirect()->back()->with('error','Vendor not found.');

        }
        
        
    }

    function index(Request $request)
    {
        $data['user']       = User::whereId(Session::get('off_line_customer'))->first();
        $data['address']    = Address::where('user_id',Session::get('off_line_customer'))->get();
        $data['city']       = City::all();
        return view('offline.user_info',$data);
    }

    function create_account(Request $request)
    {
        $user = User::where('phone',$request->phone)->first();
        
        if($user && $user->role=='vendor'){
            // $data['msg'] = 'This number is from the vendor';
            // $data['status'] = false;
            return array(

                'status' => false,

                'msg' => 'This number is from the vendor',
    
            );
        } elseif ($user) {
            $data['user'] = $user;
            Session::put('off_line_customer', $user->id);
            $data['address'] = Address::where('user_id',Session::get('off_line_customer'))->get();
            $data['city']    = City::all();
            return array(

                'status' => true,
    
                'modal_view' => view('offline.user_details',$data)->render(),
    
            );
            // $data['status'] = true;
            // return $data;
        } else {
            $params['name'] = 'Guest';
	        $params['phone'] = $request->phone;
	        $params['password'] = $request->phone;
	        $params['registered_by'] = 'Web';
		    $params['is_verified'] = 1;
	        $params['is_active'] = 1;
	        $params['is_registered'] = 1;
	        $params['role'] = 'customer';
	        $params['verify'] = 'True';

            User::create($params);
            $user = User::where('phone',$request->phone)->first();
            Session::put('off_line_customer', $user->id);
            $data['user'] = $user;
            $data['address'] = Address::where('user_id',Session::get('off_line_customer'))->get();
            $data['city']    = City::all();
            return array(

                'status' => true,
    
                'modal_view' => view('offline.user_details',$data)->render(),
    
            );
            // $data['status'] = true;
            // $data['user'] = $user;
            // return $user;

        }
    }

    public function update_user_name(Request $request)
    {
        $params['name']    = $request->user_name;
        return User::whereId($request->user_id)->update($params);
    }

    public function update_user_email(Request $request)
    {
        $params['email']    = $request->user_email;
        return User::whereId($request->user_id)->update($params);
    }

    function get_user_list(Request $request) 
    {
        $users = User::where('role','customer')->where('phone', 'like', '%' . $request->phone . '%')->get();
        $html = '';
        if ($users) {		    
		    foreach ($users as $user) {
                $phone = str_replace(' ', '', $user->phone);
		        $html .= '<option value="' . $user->phone . '">' . $phone.' '. $user->name . '</option>';
		    }
		} else {
		    $html = '<option value="">No Data Found</option>';
		}
        
		$datas['res'] = $html;

		return $datas;
    }

    function store_address(Request $request)
    {
        if(Session::get('off_line_customer') && $request->address){
            $params['user_id']      = Session::get('off_line_customer');
            $params['city_id']      = $request->city_id;
            $params['address']      = $request->address;
            $params['address_type'] = $request->address_type;
            $params['flat_no']      = $request->flat_no;
            $params['building']     = $request->building;
            $params['locality']     = $request->locality;
            $params['latitude']     = $request->lat;
            $params['longitude']    = $request->long;
            $params['address_type'] = $request->address_type;

            $res = Address::create($params);
            if($res){
                return back()->with('success','Address created successfully.');
            } else {
                return back();
            }
        } else {
            return back()->with('success','Required field is missing.');
        }
    }

    function step2(Request $request)
    {
        Session::put('off_line_address_id', $request->address_id);
        $params['name']     = $request->user_name;
        $params['phone']    = $request->user_phone;
        $params['email']    = $request->user_email;

        User::whereId($request->user_id)->update($params);

        return redirect()->route('offline.service');
    }

    function service()
    {
        if(Session::get('off_line_customer')){

            $data['services'] = Service::where('status','1')->orderBy('position')->get();
            
            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
         
            return view('offline.service.index',$data);
        } else {
            return redirect()->route('offline.booking');
        }
        
    }

    function sub_cate_service(Request $request)
    {
        $check_cart = OfflineBooking::where('id',Session::get('off_line_booking_id'))->first();
        if($check_cart){
            foreach (OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get() as $key => $value) {
                $value->delete();
            }
            $check_cart->delete();
        }

        if(ServiceAttributeValueItem::where('service_id',$request->service_id)->count() && ServiceAttributeValueItem::where('service_id',$request->service_id)->with('sub_category')->first()->sub_category){
            $data['sub_cate'] = ServiceAttributeValueItem::where('service_id',$request->service_id)->groupBy('sub_category_id')->get();
            return array(

                'status' => true,

                'service' => 'normal',

                'modal_view' => view('offline.service.sub_cate',$data)->render(),

            );
        } else {
            $attribute_ids = [];
            $attribute = '';
            foreach (ServiceAttributeValueItem::where('service_id',$request->service_id)->get() as $prt => $value) {
                if($prt=='0'){
                    array_push($attribute_ids, $value->id);
                    $attribute = $value;
                }
                
            }

            Session::put('maid_category_id', $attribute->category_id);
            Session::put('maid_ser_attr_item_id', $attribute->id);

            $data['main_attr_itms'] = ServiceAttributeValue::whereIn('ser_attr_val_item_id',$attribute_ids)->get();

            return array(

                'status' => true,

                'service' => 'maid',
                
                'modal_view' => view('offline.service.main_attr_itms',$data)->render(),
    
            );
        }
    }

    function sub_child_cate_service(Request $request)
    {   
        $service = Service::find($request->service_id);
        
        if($request->service=='normal'){
            $child_id = 'false';
            foreach (ServiceAttributeValueItem::where('service_id',$request->service_id)->where('sub_category_id',$request->sub_category_id)->get() as $key => $check_itm) {
                if($check_itm->child_category_id>0){
                    $child_id = 'true';
                } 
            }
            
            if($child_id=='true'){
            
                $data['sub_child_cate'] = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('sub_category_id',$request->sub_category_id)->get();
                return array(

                    'status' => true,

                    'from' => 'child',

                    'service' => 'normal',
        
                    'modal_view' => view('offline.service.sub_child_cate',$data)->render(),
        
                );
            } else {
                
                $attribute_ids = [];
                $serv_atr_itm = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('sub_category_id',$request->sub_category_id)->get();
                foreach ($serv_atr_itm as $value) {
                    array_push($attribute_ids, $value->id);
                }

                $params=[];
                foreach(ServiceAttributeValue::whereIn('ser_attr_val_item_id',$attribute_ids)->get() as $attributeItems){
                    if($attributeItems->attributeItem){
                        
                        $attribute['id'] 					= $attributeItems->id;
                        $attribute['attributename'] 		= $attributeItems->attributeItem?$attributeItems->attributeItem->value:"";
                        $attribute['attribute_price'] 		= $attributeItems->attribute_price;
                        $attribute['service_id'] 			= $service->id;
                        $attribute['category_id'] 			= $service->category_id;
                        $attribute['child_category_id'] 	= $request->child_category_id;
                        $attribute['sub_cat_id'] 			= $request->sub_cat_id;
                        $attribute['main_sub_cat_id'] 		= $request->sub_category_id;
                        $attribute['attribute_id'] 			= $attributeItems->attribute_id;
                        $attribute['attribute_name'] 		= $attributeItems->attribute?$attributeItems->attribute->name:"";
                        $attribute['attribute_item_id'] 	= $attributeItems->attribute_item_id;
                        $attribute['attribute_item_name'] 	= $attributeItems->attributeItem?$attributeItems->attributeItem->value:"";
                        array_push($params, $attribute);
                    }
                }
                return array(

                    'status' => true,

                    'from' => 'attribute',
        
                    'modal_view' => view('offline.service.sub_child_attribute_list',compact('params'))->render(),
        
                );
            }
        } else {
            // Second Droup Down Id
            Session::put('maid_main_sub_cate_id', $request->sub_category_id);

            $data['service'] = Service::find($request->service_id);
	        $data['serviceItem'] = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('id', '!=' , Session::get('maid_ser_attr_item_id'))->first();
			$data['ser_attr_item_id'] = $request->sub_category_id;
            
            return array(

                'status' => true,
    
                'modal_view' => view('offline.service.second_attr_itms',$data)->render(),

            );
        }         
    }

    function get_sub_child_attribute(Request $request)
    {
        $service = Service::find($request->service_id);
		$attribute_ids = [];
		foreach (ServiceAttributeValueItem::where('service_id',$request->service_id)->where('child_category_id',$request->child_category_id)->get() as $value) {
			array_push($attribute_ids, $value->id);
		}
        
		$satData = ServiceAttributeValueItem::where('service_id',$request->service_id)->where('id',$request->sub_category_id)->first();

		$params=[];
		foreach(ServiceAttributeValue::where('service_id',$request->service_id)->whereIn('ser_attr_val_item_id',$attribute_ids)->get() as $attributeItems){
			if($attributeItems->attributeItem){
				
		        $attribute['id'] 					= $attributeItems->id;
		        $attribute['attributename'] 		= $attributeItems->attributeItem?$attributeItems->attributeItem->value:"";
		        $attribute['attribute_price'] 		= $attributeItems->attribute_price;
		        $attribute['service_id'] 			= $service->id;
		        $attribute['category_id'] 			= $service->category_id;
		        $attribute['child_category_id'] 	= $request->child_category_id;
		        $attribute['sub_cat_id'] 			= $attributeItems->id;
		        $attribute['main_sub_cat_id'] 		= $satData?$satData->sub_category_id:'';
		        $attribute['attribute_id'] 			= $attributeItems->attribute_id;
		        $attribute['attribute_name'] 		= $attributeItems->attribute?$attributeItems->attribute->name:"";
		        $attribute['attribute_item_id'] 	= $attributeItems->attribute_item_id;
		        $attribute['attribute_item_name'] 	= $attributeItems->attributeItem?$attributeItems->attributeItem->value:"";
		        array_push($params, $attribute);
			}
		}
       
        return array(

            'status' => true,

            'modal_view' => view('offline.service.sub_child_attribute_list',compact('params'))->render(),

        );
    }

    function get_material_price(Request $request)
    {
        
        $main_attribute     = ServiceAttributeValue::where('id',$request->sub_category_id)->first();
        $second_attribute   = ServiceAttributeValue::where('id',$request->attribute_detail_id)->first();
        $attribute_price    = $main_attribute->attribute_price * $second_attribute->attribute_price;
        $service = Service::find($request->service_id);
		if($service){
			$peramt = ($service->material_price * $attribute_price) / '100';
		} else {
			$peramt = '00';
		}
        return array(

            'status' => true,

            'modal_view' => view('offline.service.material_price',compact('peramt'))->render(),

        );
    }

    function step3_Store(Request $request)
    {
        $check_atr = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
        if(count($check_atr)){
            return redirect()->route('step3');
        } else {
            return redirect()->back()->with('error','Please Select Any Attribute.');
        }
        
        if(empty($request->service_id) || empty($request->sub_category_id) || empty($request->attribute_id)){
            return redirect()->back()->with('error','Please Select Any Attribute.');
        }
        $service = Service::find($request->service_id);
        $total = '0';
        $params['user_id']            = Session::get('off_line_customer');
        $params['service_id']         = $service->id;
        $params['service_name']       = $service->name;
        $params['category_id']        = $service->parent_id;
        // $params['date']               = date('Y-m-d');
        $params['tran_id']            = 'UM-'.mt_rand(1000,99999);
        $params['booking_from']       = 'Offline';
        $params['payment_moad']       = 'Cash';
        $params['payment_status']     = 'True';

        $add_res = Address::find(Session::get('off_line_address_id'));
        $shippingAddress = [];

        $shippingAddress['user_id']     = $add_res->user_id ?? '';
        $shippingAddress['city_id']     = $add_res->city_id ?? '';
        $shippingAddress['city_name']   = $add_res->city?$add_res->city->name:'';
        $shippingAddress['address']     = $add_res->address ?? '';
        $shippingAddress['address_type']= $add_res->address_type ?? '';
        $shippingAddress['flat_no']     = $add_res->flat_no ?? '';
        $shippingAddress['building']    = $add_res->building ?? '';
        $shippingAddress['locality']    = $add_res->locality_info?$add_res->locality_info->name:'';
        $shippingAddress['latitude']    = $add_res->latitude ?? '';
        $shippingAddress['longitude']   = $add_res->longitude ?? '';

        $params['address_id']       = json_encode($shippingAddress);

        if($request->service=='maid'){
           
            $params['service_type']       = 'Maid';
            if($request->materialscharge=='Yes'){
                $params['material_status']	  = 'Apply';
	            $params['material_charge']	  = $request->material_charge;
            } else {
                $params['material_status']	  = 'Not';
	            $params['material_charge']	  = null;
            }
            
            $old_amount = '0';
            if(OfflineBooking::whereId(Session::get('off_line_booking_id'))->where('card_process','Working')->first()){
                OfflineBooking::whereId(Session::get('off_line_booking_id'))->update($params);
                $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
                // $old_amount += $off_line_booking->amount;
                $old_amount += $off_line_booking->material_charge;
            } else {
                $off_line_booking = OfflineBooking::create($params);
            }
            

            if($off_line_booking){
                Session::put('off_line_booking_id', $off_line_booking->id);

                $ser_attribute   = ServiceAttributeValue::find($request->sub_category_id);
                $second_attribute   = ServiceAttributeValue::find($request->attribute_id);

                $attr_params['card_id'] = $off_line_booking->id;
                $attr_params['main_sub_cat_id'] = $request->sub_category_id;
                $attr_params['attribute_id'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'';
                $attr_params['attribute_name'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->value:'';
                $attr_params['attribute_price'] = $ser_attribute->attribute_price;
                $attr_params['attribute_item_id'] = $second_attribute->attributeItem?$second_attribute->attributeItem->id:'';
                $attr_params['attribute_item_name'] = $second_attribute->attributeItem?$second_attribute->attributeItem->value:'';;
                $attr_params['attribute_qty'] = $second_attribute->attribute_price;
                $attr_params['service_type'] = 'Maid';

                $total += $ser_attribute->attribute_price*$second_attribute->attribute_price;

                $f_total['amount'] = $total+$old_amount;
                $off_line_booking->update($f_total);

                if(OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->where('attribute_item_id',$second_attribute->attributeItem?$second_attribute->attributeItem->id:'')->first()){
                    OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->where('attribute_item_id',$second_attribute->attributeItem?$second_attribute->attributeItem->id:'')->update($attr_params);
                    $res = OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->where('attribute_item_id',$second_attribute->attributeItem?$second_attribute->attributeItem->id:'')->first();
                } else {
                    $res = OfflineBookingAttribute::create($attr_params);
                }
                
                if($res){
                    return redirect()->route('step3');
                } else {
                    return back()->with('error','Try Again.');
                }
            } else {
                return back()->with('error','Try Again.');
            }
	        
        } else {
            
            $params['material_status']	  = 'Not';
	        $params['material_charge']	  = null;

            $old_amount = '0';
            if(OfflineBooking::whereId(Session::get('off_line_booking_id'))->where('card_process','Working')->first()){
                OfflineBooking::whereId(Session::get('off_line_booking_id'))->update($params);
                $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
                $old_amount += $off_line_booking->amount;
            } else {
                $off_line_booking = OfflineBooking::create($params);
            }

            if($off_line_booking){
                Session::put('off_line_booking_id', $off_line_booking->id);
                $ser_itm = Category::find($request->sub_category_id);
                foreach($request->attribute_id as $key => $attribute_id){
                    $ser_attribute   = ServiceAttributeValue::find($attribute_id);

                    $attr_params['card_id'] = $off_line_booking->id;
                    $attr_params['sub_cate_id'] = $ser_attribute->id;
                    $attr_params['main_sub_cat_id'] = $request->sub_category_id;
                    $attr_params['child_cate_id'] = $request->child_category_id;
                    $attr_params['attribute_id'] = $ser_itm->id;
                    $attr_params['attribute_name'] = $ser_itm->name;
                    $attr_params['attribute_price'] = $ser_attribute->attribute_price;
                    $attr_params['attribute_item_id'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'';
                    $attr_params['attribute_item_name'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->value:'';;
                    $attr_params['attribute_qty'] = $request->qty[$key];
                    $attr_params['service_type'] = 'Normal';

                    $total += $ser_attribute->attribute_price*$request->qty[$key];
                    
                    if(OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_itm->id)->where('attribute_item_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->first()){
                        OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_itm->id)->where('attribute_item_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->update($attr_params);
                        $res = OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_itm->id)->where('attribute_item_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->first();
                    } else {
                        $res = OfflineBookingAttribute::create($attr_params);
                    }
                }
                
                $f_total['amount'] = $total+$old_amount;
                $off_line_booking->update($f_total);
                
                if($request->attribute_id){
                    return redirect()->route('step3');
                } else {
                    return back()->with('error','Try Again.');
                }
            } else {
                return back()->with('error','Try Again.');
            }
        }
    }

    function add_attr_in_cart(Request $request)
    {
        $service = Service::find($request->service_id);
        $total = '0';
        if(empty(OfflineBooking::whereId(Session::get('off_line_booking_id'))->where('card_process','Working')->first())){
            $params['user_id']            = Session::get('off_line_customer');
            $params['service_id']         = $service->id;
            $params['service_name']       = $service->name;
            $params['category_id']        = $service->parent_id;
            // $params['date']               = date('Y-m-d');
            $params['tran_id']            = 'UM-'.mt_rand(1000,99999);
            $params['booking_from']       = 'Offline';
            $params['payment_moad']       = 'Cash';
            $params['payment_status']     = 'True';
            $params['address_id']         = Session::get('off_line_address_id');
        }
        

        if($request->service=='maid'){
           
            $params['service_type']       = 'Maid';
            if($request->materialscharge=='Yes'){
                $params['material_status']	  = 'Apply';
	            $params['material_charge']	  = $request->material_charge;
            } else {
                $params['material_status']	  = 'Not';
	            $params['material_charge']	  = null;
            }
            
            $old_amount = '0';
            if(OfflineBooking::whereId(Session::get('off_line_booking_id'))->where('card_process','Working')->first()){
                OfflineBooking::whereId(Session::get('off_line_booking_id'))->update($params);
                $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
                // $old_amount += $off_line_booking->amount;
                $old_amount += $off_line_booking->material_charge;
            } else {
                $off_line_booking = OfflineBooking::create($params);
            }
            

            if($off_line_booking){
                Session::put('off_line_booking_id', $off_line_booking->id);

                $ser_attribute   = ServiceAttributeValue::find($request->sub_category_id);
                $second_attribute   = ServiceAttributeValue::find($request->attribute_id);

                $attr_params['card_id'] = $off_line_booking->id;
                $attr_params['main_sub_cat_id'] = $request->sub_category_id;
                $attr_params['attribute_id'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'';
                $attr_params['attribute_name'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->value:'';
                $attr_params['attribute_price'] = $ser_attribute->attribute_price;
                $attr_params['attribute_item_id'] = $second_attribute->attributeItem?$second_attribute->attributeItem->id:'';
                $attr_params['attribute_item_name'] = $second_attribute->attributeItem?$second_attribute->attributeItem->value:'';;
                $attr_params['attribute_qty'] = $second_attribute->attribute_price;
                $attr_params['service_type'] = 'Maid';

                $total += $ser_attribute->attribute_price*$second_attribute->attribute_price;

                $f_total['amount'] = $total+$old_amount;
                $off_line_booking->update($f_total);

                if(OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->where('attribute_item_id',$second_attribute->attributeItem?$second_attribute->attributeItem->id:'')->first()){
                    OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->where('attribute_item_id',$second_attribute->attributeItem?$second_attribute->attributeItem->id:'')->update($attr_params);
                    $res = OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->where('attribute_item_id',$second_attribute->attributeItem?$second_attribute->attributeItem->id:'')->first();
                } else {
                    $res = OfflineBookingAttribute::create($attr_params);
                }
                
                if($res){
                    $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

                    return array(

                        'status' => true,
            
                        'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
            
                    );
                } else {
                    $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

                    return array(

                        'status' => false,
            
                        'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
            
                    );
                }
            } else {

                $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

                return array(

                    'status' => false,
        
                    'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
        
                );
            }
	        
        } else {
            
            $params['material_status']	  = 'Not';
	        $params['material_charge']	  = null;

            $old_amount = '0';
            if(OfflineBooking::whereId(Session::get('off_line_booking_id'))->where('card_process','Working')->first()){
                OfflineBooking::whereId(Session::get('off_line_booking_id'))->update($params);
                $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
                $old_amount += $off_line_booking->amount;
            } else {
                $off_line_booking = OfflineBooking::create($params);
            }

            if($off_line_booking){
                Session::put('off_line_booking_id', $off_line_booking->id);
                $ser_itm = Category::find($request->sub_category_id);
              
                $ser_attribute   = ServiceAttributeValue::find($request->attribute_id);

                $attr_params['card_id'] = $off_line_booking->id;
                $attr_params['sub_cate_id'] = $ser_attribute->id;
                $attr_params['main_sub_cat_id'] = $request->sub_category_id;
                $attr_params['child_cate_id'] = $request->child_category_id;
                $attr_params['attribute_id'] = $ser_itm->id;
                $attr_params['attribute_name'] = $ser_itm->name;
                $attr_params['attribute_price'] = $ser_attribute->attribute_price;
                $attr_params['attribute_item_id'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'';
                $attr_params['attribute_item_name'] = $ser_attribute->attributeItem?$ser_attribute->attributeItem->value:'';;
                $attr_params['attribute_qty'] = $request->qty;
                $attr_params['service_type'] = 'Normal';

                $total += $ser_attribute->attribute_price*$request->qty;
                
                if(OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_itm->id)->where('attribute_item_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->first()){
                    OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_itm->id)->where('attribute_item_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->update($attr_params);
                    $res = OfflineBookingAttribute::where('card_id',$off_line_booking->id)->where('attribute_id',$ser_itm->id)->where('attribute_item_id',$ser_attribute->attributeItem?$ser_attribute->attributeItem->id:'')->first();
                } else {
                    $res = OfflineBookingAttribute::create($attr_params);
                }
                
                $f_total['amount'] = $total+$old_amount;
                $off_line_booking->update($f_total);
                
                if($request->attribute_id){
                    $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

                    return array(

                        'status' => true,
            
                        'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
            
                    );
                } else {
                    $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

                    return array(

                        'status' => false,
            
                        'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
            
                    );
                }
            } else {
                $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

                    return array(

                        'status' => false,
            
                        'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
            
                    );
            }
        }
    }

    function remove_attr_in_cart(Request $request)
    {
        $attribute_items = OfflineBookingAttribute::where('id',$request->attribute_id)->first();    
        
        if($attribute_items){

            $attribute_items->delete();

            return $this->re_update_cart();
        } else {
            return $this->re_update_cart();
        }
    }

    function re_update_cart()
    {
        $booking = OfflineBooking::where('id',Session::get('off_line_booking_id'))->first();
        if($booking){

            $attribute_items = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
            $total = '0';
            foreach ($attribute_items as $key => $attribute_item) {
                $total = $attribute_item->attribute_qty*$attribute_item->attribute_price;
            }

            $params['amount'] = $total;

            $booking->update($params);

            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

            return array(

                'status' => true,
    
                'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
    
            );

        } else {

            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();

            return array(

                'status' => false,
    
                'modal_view' => view('offline.service.add_to_cart_list',$data)->render(),
    
            );

        }
    }

    function step3()
    {        
        if(Session::has('off_line_booking_id')){
            $data['off_line_booking'] = OfflineBooking::find(Session::get('off_line_booking_id'));
            $data['slots'] = Slot::all();

            $add_res = Address::find(Session::get('off_line_address_id'));
            $shippingAddress = [];

            $shippingAddress['user_id']     = $add_res->user_id ?? '';
            $shippingAddress['city_id']     = $add_res->city_id ?? '';
            $shippingAddress['city_name']   = $add_res->city?$add_res->city->name:'';
            $shippingAddress['address']     = $add_res->address ?? '';
            $shippingAddress['address_type']= $add_res->address_type ?? '';
            $shippingAddress['flat_no']     = $add_res->flat_no ?? '';
            $shippingAddress['building']    = $add_res->building ?? '';
            $shippingAddress['locality']    = $add_res->locality_info?$add_res->locality_info->name:'';
            $shippingAddress['latitude']    = $add_res->latitude ?? '';
            $shippingAddress['longitude']   = $add_res->longitude ?? '';

            $params['address_id']         = json_encode($shippingAddress);
            OfflineBooking::whereId(Session::get('off_line_booking_id'))->update($params);
            
            return view('offline.schedule.index',$data);
        } else {
            return redirect()->route('offline.booking');
        }
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

    function step4_store(Request $request)
    {
        $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
        
        if($off_line_booking){            
            $params['date']     = $request->slot_date;
            $params['slot_id']  = $request->slot_id;
            $params['note']     = $request->booking_instruction;
            $off_line_booking->update($params);
            return redirect()->route('step4');
        } else {
            return redirect()->route('offline.booking');
        }
        
    }

    function step4()
    {
        $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));

        if($off_line_booking){
            $data['off_line_booking'] = $off_line_booking;
            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
            return view('offline.amount_details.index',$data);
        } else {
            return redirect()->route('offline.booking');
        }
        
    }

    function pay_tip(Request $request)
    {
        if(Session::has('off_line_booking_id')){
            $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
            $params['tip_id']     = $request->tip;
            $off_line_booking->update($params);
            $data['off_line_booking'] = OfflineBooking::find(Session::get('off_line_booking_id'));
            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
            return array(

                'status' => true,
    
                'modal_view' => view('offline.amount_details.attribute_list',$data)->render(),
    
            );
        } else {
            return array(

                'status' => false,
    
            );
        }
    }

    function pay_charge(Request $request)
    {
        if(Session::has('off_line_booking_id')){
            $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
            $params['offline_charge']     = $request->charge;
            $off_line_booking->update($params);
            $data['off_line_booking'] = OfflineBooking::find(Session::get('off_line_booking_id'));
            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
            return array(

                'status' => true,
    
                'modal_view' => view('offline.amount_details.attribute_list',$data)->render(),
    
            );
        } else {
            return array(

                'status' => false,
    
            );
        }
    }

    function pay_discount(Request $request)
    {
        if(Session::has('off_line_booking_id')){
            $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
            $params['offline_discount']     = $request->discount;
            $off_line_booking->update($params);
            $data['off_line_booking'] = OfflineBooking::find(Session::get('off_line_booking_id'));
            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
            return array(

                'status' => true,
    
                'modal_view' => view('offline.amount_details.attribute_list',$data)->render(),
    
            );
        } else {
            return array(

                'status' => false,
    
            );
        }
    }

    public function apply_coupon(Request $request)
	{
		$coupon = Coupon::where('code',$request->coupon_code)->where('status','1')->first();
        $off_line_booking = OfflineBooking::find(Session::get('off_line_booking_id'));
		$coupon_use = UserCoupon::where('user_id',$off_line_booking->user_id)->where('coupon_id',$coupon->id)->count();
		if($coupon){
			$card = OfflineBooking::find(Session::get('off_line_booking_id'));
			if($coupon->id==$card->coupon_id){
				
                return array(

                    'status' => false,

                    'msg'    => "This Coupon Already Applied",
        
                );
			} else {
				if($coupon_use < $coupon->user_used){
					if($coupon->start_date<=date('Y-m-d') && $coupon->end_date>=date('Y-m-d') ){

						$total = $card->amount;

                        $old_amt = $total;

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

							$crd_coupon['card_id'] 		= $off_line_booking->id;
							$crd_coupon['coupon_id'] 	= $coupon->id;
							$crd_coupon['code'] 		= $coupon->code;
							$crd_coupon['amount'] 		= $coupon->amount;
							$crd_coupon['min_amount'] 	= $coupon->min_amount;
							$crd_coupon['max_amount'] 	= $coupon->max_amount;
							$crd_coupon['type'] 		= $coupon->type;
							$crd_coupon['start_date'] 	= $coupon->start_date;
							$crd_coupon['end_date'] 	= $coupon->end_date;

							if(CardCoupon::where('card_id',$card->id)->exists()){
								CardCoupon::where('card_id',$card->id)->update($crd_coupon);
							} else {
								CardCoupon::create($crd_coupon);
							}							

							// $data['card_amt'] 	= $card->g_total;
							// $data['amt'] 	= $coupon_Amt;
							// $data['msg'] 	= 'Coupon Apply Successfully';
							// $data['status'] = True;
							// $data['service_id'] = $card->service_id;

                            $data['off_line_booking'] = OfflineBooking::find(Session::get('off_line_booking_id'));
                            $data['attribute_items'] = OfflineBookingAttribute::where('card_id',Session::get('off_line_booking_id'))->get();
                            
                            return array(

                                'status' => true,

                                'msg'    => "Coupon Apply Successfully",
                    
                                'modal_view' => view('offline.amount_details.attribute_list',$data)->render(),
                    
                            );

		              	} else {
		              		// $data['msg'] 	= "This coupon's minimum amount is less than your total";
							// $data['status'] = False;

                            return array(

                                'status' => false,

                                'msg'    => "This coupon's minimum amount is less than your total",
                    
                            );
		              	}
					} else {
						// $data['msg'] 	= 'Coupon Expired';
						// $data['status'] = False;
                        return array(

                            'status' => false,

                            'msg'    => "Coupon Expired",
                
                        );
					}
				} else {
					// $data['msg'] 	= 'Coupon Usage Limit Exceeded';
					// $data['status'] = False;
                    return array(

                        'status' => false,

                        'msg'    => "Coupon Usage Limit Exceeded",
            
                    );
				}
			}
			
		} else {
			// $data['msg'] 	= 'Invalid Coupon';
			// $data['status'] = False;
            return array(

                'status' => false,

                'msg'    => "Invalid Coupon",
    
            );
		}
		return $data;
	}

    function confirm_booking(Request $request)
    {
        $off_line_booking   = OfflineBooking::find(Session::get('off_line_booking_id'));
        $params['g_total']          = $request->g_total;
        $params['card_process']     = 'Complete';
        $params['is_checkout']      = 'Done';
        $params['booking_from']     = 'Offline';
        $off_line_booking->update($params);
        
        Session::put('f_off_line_booking_id', $off_line_booking->id);
        Session::forget('off_line_customer');
        Session::forget('off_line_address_id');
        Session::forget('maid_category_id');
        Session::forget('maid_ser_attr_item_id');
        Session::forget('maid_main_sub_cate_id');
        Session::forget('off_line_booking_id');
        return redirect()->route('step5');
    }

    function send_payment_list(Request $request)
    {
        $booking = OfflineBooking::find(Session::get('f_off_line_booking_id'));
    
        if($booking){
            $params['payment_link'] = $request->payment_link;
            $booking->update($params);

            $card = OfflineBooking::find(Session::get('f_off_line_booking_id'));


            if($card->user && $card->user->email){
				$array['view']      = 'emails.invoice_offline_booking';
		        $array['subject']   = 'Your Booking Invoice';
		        $array['data']      = $card;
		        
		        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
			}

	        $adminarray['view']      = 'emails.invoice_offline_booking';
	        $adminarray['subject']   = 'You Have New Service Booking';
	        $adminarray['data']      = $card;
	       
	        \Mail::to('booking@urbanmop.com')->send(new \App\Mail\Mail($adminarray));

            $tran_id = $card->tran_id;
	        $message = "Congratulation! You have successfully booked service with UrbanMop. \nYour booking ID is $tran_id \nView booking on UrbanMop \nhttps://www.urbanmop.com \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com. \nYour Payment Link $card->payment_link";
            
			$msg = urlencode($message);
			if($card->user && $card->user->phone){

				$mobile = $card->user->phone;
				$res=send_sms_to_mobile($mobile,$msg);

			}

            return redirect()->route('offline.bookings')->with('success','Payment link send on mail.');
        } else {
            return back()->with('warning','Try again booking not found.');
        }
    }

    function step5()
    {
        $data['off_line_booking']   = OfflineBooking::find(Session::get('f_off_line_booking_id'));
        return view('offline.payment.index',$data);
    }

    function update_payment(Request $request)
    {
        // $setting = HomeSetting::first();
		// $codamt  = $setting?$setting->cash_surcharge:'0';
        $trans_id = str_replace(" ","-",$request->tran_id);

        $checkTrans = OfflineBooking::where('paymentLinkId',$trans_id)->first();
        if($checkTrans && isset($checkTrans)){
            return back()->with('warning','This transaction id is already exists.'); 
        } else {
            
            $params['payment_moad']         = $request->payment_moad;
            $params['paymentLinkId']        = $trans_id;
            $params['payment_collected']    = 'Yes';
            $res = OfflineBooking::whereId($request->booking_id)->update($params);
            if($res){
                return back()->with('success','Payment updated successfully.');
            } else {
                return back()->with('error','Try again.'); 
            }
        }
    }

    function update_live_payment(Request $request)
    {
        $trans_id = str_replace(" ","-",$request->tran_id);

        $checkTrans = Card::where('paymentLinkId',$trans_id)->first();
        if($checkTrans && isset($checkTrans)){
            return back()->with('warning','This transaction id is already exists.'); 
        } else {
            
            $params['payment_moad']         = $request->payment_moad;
            $params['paymentLinkId']        = $trans_id;
            $params['tabby_payment_response_id']        = $trans_id;
            $params['payment_collected']    = 'Yes';
            $res = Card::whereId($request->booking_id)->update($params);
            if($res){
                return back()->with('success','Payment updated successfully.');
            } else {
                return back()->with('error','Try again.'); 
            }
        }
    }

    function launch_booking($id)
    {
        $off_line_booking = OfflineBooking::find($id);

        if($off_line_booking){
            $data['user_id']                = $off_line_booking->user_id;
            $data['service_id']             = $off_line_booking->service_id;
            $data['service_name']           = $off_line_booking->service_name;
            $data['category_id']            = $off_line_booking->category_id;
            $data['slot_id']                = $off_line_booking->slot_id;
            $data['address_id']             = $off_line_booking->address_id;
            $data['tran_id']                = $off_line_booking->tran_id;
            $data['paymentLinkId']          = $off_line_booking->paymentLinkId;
            $data['payment_moad']           = $off_line_booking->payment_moad;
            $data['payment_status']         = $off_line_booking->payment_status;
            $data['note']                   = $off_line_booking->note;
            $data['alternative_number']     = $off_line_booking->alternative_number;
            $data['date']                   = $off_line_booking->date;
            $data['tip_id']                 = $off_line_booking->tip_id;
            $data['coupon_id']              = $off_line_booking->coupon_id;
            $data['status']                 = $off_line_booking->status;
            $data['accept_user_id']         = $off_line_booking->accept_user_id;
            $data['amount']                 = $off_line_booking->amount;
            $data['coupon_amt']             = $off_line_booking->coupon_amt;
            $data['g_total']                = $off_line_booking->g_total;
            $data['offline_discount']       = $off_line_booking->offline_discount;
            $data['offline_charge']         = $off_line_booking->offline_charge;
            $data['before_coupon_amt']      = $off_line_booking->before_coupon_amt;
            $data['payment_collected']      = $off_line_booking->payment_collected;
            $data['service_start_datetime'] = $off_line_booking->service_start_datetime;
            $data['service_completed']      = $off_line_booking->service_completed;
            $data['service_completed_date'] = $off_line_booking->service_completed_date;
            $data['work_done']              = $off_line_booking->work_done;
            $data['cod_charge']             = $off_line_booking->cod_charge;
            $data['cod_status']             = $off_line_booking->cod_status;
            $data['material_status']        = $off_line_booking->material_status;
            $data['material_charge']        = $off_line_booking->material_charge;
            $data['card_process']           = $off_line_booking->card_process;
            $data['service_type']           = $off_line_booking->service_type;
            $data['is_checkout']            = $off_line_booking->is_checkout;
            $data['is_login']               = $off_line_booking->is_login;
            $data['booking_from']           = $off_line_booking->booking_from;
            $data['payment_link']           = $off_line_booking->payment_link;

            $card = Card::create($data);
            if($card){

                $card_params['card_id'] = $card->id;
                CardCoupon::where('card_id',$off_line_booking->id)->update($card_params);
                $off_attributes = OfflineBookingAttribute::where('card_id',$off_line_booking->id)->get();

                foreach ($off_attributes as $key => $value) {
                    $attr['card_id']                = $card->id;
                    $attr['sub_cate_id']            = $value->sub_cate_id;
                    $attr['main_sub_cat_id']        = $value->main_sub_cat_id;
                    $attr['child_cate_id']          = $value->child_cate_id;
                    $attr['attribute_id']           = $value->attribute_id;
                    $attr['attribute_name']         = $value->attribute_name;
                    $attr['attribute_item_id']      = $value->attribute_item_id;
                    $attr['attribute_item_name']    = $value->attribute_item_name;
                    $attr['attribute_qty']          = $value->attribute_qty;
                    $attr['attribute_price']        = $value->attribute_price;
                    $attr['service_type']           = $value->service_type;
                    CardAttribute::create($attr);
                }
                $off_update['is_live'] = 'Yes';
                $off_line_booking->update($off_update);

                if(empty($off_line_booking->accept_user_id)){
                    $ser_users = get_seller_info_by_service($off_line_booking?$off_line_booking->service_id:'');
                    if($ser_users){
                        foreach ($ser_users as $key => $value) {
                            if(isset($value->device_token)){
                                
                                $token 	= $value->device_token;
        
                                $service = $off_line_booking->service?$off_line_booking->service->name:'No Service';
                                           
                                $title  = 'New Booking Arrived';
                                $body   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";
                                $text   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";
        
                                $data = send_notification($token, $title, $body, $text);
                            }
                        }
                    }
                }
                
            }
            return back()->with('success','Booking is live successfully.');
        } else {
            return back()->with('error','Booking not found.');
        }
    }

    function view_booking($id)
    {
        $card = OfflineBooking::find($id);
        return view('offline.booking_show',compact('card'));
    }

    public function checkmail()
    {
		$data = OfflineBooking::find(136);
// return view('emails.invoice_offline_booking',compact('data'));
        $adminarray['view']      = 'emails.invoice_offline_booking';
        $adminarray['subject']   = 'New Vendor Created';
        $adminarray['data']      = $data;
        $res = \Mail::to('email@gmail.com')->send(new \App\Mail\Mail($adminarray));
        return $res;
    }

    function cencal_booking($id)
    {
        $card = OfflineBooking::find($id);
        if($card){

            $data['status'] = 'Canceled';

            $card->update($data);

            return back()->with('success','Booking is canceled successfully.');

        } else {

            return back()->with('error','Booking not found.');

        }        
    }

    function delete_booking($id)
    {
        $card = OfflineBooking::find($id);

        if($card){

            $card->delete();

            return back()->with('success','Booking is delete successfully.');

        } else {

            return back()->with('error','Booking not found.');

        }  
    }

    public function change_slot($id='')
    {
        $id = decrypt($id);

        $data['card'] = OfflineBooking::find($id);

        $data['slots'] = Slot::all();

        $data['from'] = 'Offline';

        return view('offline.reschedule',$data);

    }

    public function update_time_slot(Request $request)
    {
        $params['slot_id']  = $request->slot_id;
        $params['date']     = $request->date;

        if($request->from=='Offline'){

            OfflineBooking::where('id',$request->booking_id)->update($params);

            $card = OfflineBooking::find($request->booking_id);

        } else {

           Card::where('id',$request->booking_id)->update($params);

            $card = Card::find($request->booking_id); 

        }        

        if($card && $card->user && $card->user->email){
            $array['view']      = 'emails.change_slot_customer';
            $array['subject']   = 'Slot Changed';
            $array['data']      = $card;
            
            \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
        }

        if($card && $card->vendor && $card->vendor->email){
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
}
