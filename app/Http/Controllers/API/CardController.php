<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Invite;
use App\Wallet;
use App\User;
use App\Card;
use App\Category;
use App\ChildCategory;
use App\Service;
use App\CardAttribute;
use App\CardAddon;
use App\Coupon;
use App\Seller;
use App\SellerService;
use App\HomeSetting;
use App\PayOutBalance;
use App\Payment;
use App\UserCoupon;
use App\CardCoupon;
use App\Address;
use App\Review;
use App\Packages;
use App\Slot;
use App\Transection;
use App\RewardConfig;
use App\RewardUser;
use App\ServiceAttributeValue;
use App\ServiceAttributeValueItem;
use App\Attribute;
use App\AttributeValue;
use App\Services\FirebasePushNotificationService;
use Kreait\Firebase\Database;

class CardController extends BaseController
{
    
    protected $firebaseService;

    // Inject FirebasePushNotificationService through the constructor
    public function __construct(FirebasePushNotificationService $firebaseService, Database $database)
    {
        $this->firebaseService = $firebaseService;
        $this->database = $database;
    }

    public function get_selected_price_by_card(Request $request)
    {
    
        $data = [];
        $totalAmount = 0;
        $card = Card::find($request->card_id);
        $service = Service::find($card->service_id);
        $material_total = ($card->material_status == "Apply") ? (int) $service->material_price : 0;
        $material = 0;
        if (CardAttribute::where('card_id', $card->id)->exists()) {
            $data["service_name"] = $service->name;
            
            foreach (CardAttribute::where('card_id', $card->id)->get() as $value) {

                $service = ServiceAttributeValue::where('attribute_item_id',$value->attribute_item_id)->first();
                $service_item = ServiceAttributeValueItem::where('id',$service->ser_attr_val_item_id)->first();
                $attribute = Attribute::where('id',$service->attribute_id)->first();
                $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                
                
                $params['attribute_price']      = $service->attribute_price ?? null;
                $params['attribute_qty']        = $value->attribute_qty ?? null;
                $params['service_type']         = '';
                
                $data["attribute"][] = ["name"=>$attribute_item->value,"price"=>$service->attribute_price];
                $attribute_total = $params['attribute_price'] * $params['attribute_qty'];
                
                if($attribute->name == 'Hours'){
                    $totalAmount *= (int) $attribute_total; 
                } else {
                    
                    $totalAmount += $attribute_total; 
                }

            } 
            
        }     
        $material = ($request->material_status == "Apply") ? (float) $material_total : 0;

        $data["sub_total"] = (string) $totalAmount;
        $totalAmount += $material;
        
        $data["material"] = (string) $material;
        $data["total_amount"] = (string) $totalAmount;

        return $this->sendResponse($data, 'Updated Price!');
    }

    public function get_selected_price(Request $request)
    {
    
        $data = [];
        $totalAmount = 0;
        $service = Service::find($request->service_id);
        $material_default = ($request->material_status == "Apply") ? (float) $service->material_price : 0;
        $material_total = 0;
        $material = 0;
        if(isset($request->item_object)){
            $data["service_name"] = $service->name;
            $c = 0;
            foreach (json_decode($request->item_object) as $key => $value) {
                if(!isset($value->sub_category_id)){
                    $service = ServiceAttributeValue::where('attribute_item_id',$value->attribute_item_id)->first();
                    $service_item = ServiceAttributeValueItem::where('id',$service->ser_attr_val_item_id)->first();
                    $attribute = Attribute::where('id',$service->attribute_id)->first();
                    $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                    
                    $params['attribute_price']      = $service->attribute_price ?? null;
                    $params['attribute_qty']        = $value->attribute_qty ?? null;
                    $params['service_type']         = '';
                    
                    $data["attribute"][] = [
                        "category"=>Category::find($service_item->category_id)->name,
                        "name"=>$attribute_item->value,
                        "price"=>(string) $service->attribute_price,
                    ];
                    $attribute_total = $params['attribute_price'] * $params['attribute_qty'];
                    
                    if($attribute->name == 'Hours'){
                        $totalAmount *= (int) $attribute_total; 
                    } else {
                        
                        $totalAmount += $attribute_total; 
                    }
                    
                    preg_match('/\d+/', $attribute_item->value, $matches);

                    if (count($matches) > 0) {
                        $number = (int) $matches[0];
                    
                        if ($attribute->name == 'Hours') {
                            if ($number > 1) {
                                if($material_total > 0){
                                    $material_total *= ($request->material_status == "Apply" ? $number : 0) 
                                                        * (int) $value->attribute_qty;
                                } else {
                                    $material_total += ($request->material_status == "Apply" ? $number : 0) 
                                                        * (int) $value->attribute_qty;
                                }
                            } else {
                                $c++;
                                if((int) $value->attribute_qty > 1){
                                    $material_total += (float) $material_default * (float) $value->attribute_qty;
                                }
                            }
                        } else {
                            if ($number > 1) {
                                if($material_total > 0){
                                    $material_total *= ($request->material_status == "Apply" ? $number : 0) 
                                                        * (int) $value->attribute_qty;
                                } else {
                                    $material_total += ($request->material_status == "Apply" ? $number : 0) 
                                                        * (int) $value->attribute_qty;
                                }
                            } else {
                                $c++;
                                if((int) $value->attribute_qty > 1){
                                    
                                    $material_total += (float) $material_default * (float) $value->attribute_qty;
                                }
                            }
                        }
                    }

                } else {
                    $service_item = ServiceAttributeValueItem::where([
                        'service_id'=>$request->service_id,
                        'category_id'=>$value->category_id,
                        'sub_category_id'=>$value->sub_category_id,
                        'child_category_id'=>$value->child_category_id,
                        ])->first();
                    if(!empty($service_item)){
                        $service = ServiceAttributeValue::where([
                            'ser_attr_val_item_id'=>$service_item->id,
                            'attribute_item_id'=>$value->attribute_item_id,
                            ])->first();
                        $attribute = Attribute::where('id',$service->attribute_id)->first();
                        $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                        
                        $params['attribute_price']      = $service->attribute_price ?? null;
                        $params['attribute_qty']        = $value->attribute_qty ?? null;
    
                        $data["attribute"][] = [
                            "category"=>Category::find($service_item->category_id)->name,
                            "sub_category"=>Category::find($service_item->sub_category_id)->name,
                            "child_category"=>ChildCategory::find($service_item->child_category_id)->name,
                            "name"=>$attribute_item->value,
                            "price"=>(string) $service->attribute_price];
                    
                        
                        $attribute_total = $params['attribute_price'] * $params['attribute_qty'];
                        
                        if($attribute->name == 'Hours'){
                            $totalAmount *= (int) $attribute_total; 
                        } else {
                            
                            $totalAmount += $attribute_total; 
                        }

                        preg_match('/\d+/', $attribute_item->value, $matches);

                        if (count($matches) > 0) {
                            $number = (int) $matches[0];
                        
                            if ($attribute->name == 'Hours') {
                                if ($number > 1) {
                                    $material_total += ($request->material_status == "Apply" ? $material_default * $number : 0) 
                                                        * (int) $value->attribute_qty;
                                } else {
                                    $c++;
                                    if((int) $value->attribute_qty > 1){
                                        $material_total += (float) $material_default * (float) $value->attribute_qty;
                                    }
                                }
                            } else {
                                if ($number > 1) {
                                    $material_total += ($request->material_status == "Apply" ? $material_default * $number : 0) 
                                                        * (int) $value->attribute_qty;
                                } else {
                                    $c++;
                                    if((int) $value->attribute_qty > 1){
                                        
                                        $material_total += (float) $material_default * (float) $value->attribute_qty;
                                    }
                                }
                            }
                        }
                        
                    } 
                    $params['service_type']         = '';
                }
            } 
            
        }     
        
        if($c==2){
            $material_total += $material_default;
        } else {
            $material_total *= $material_default;
        }
        $material = ($request->material_status == "Apply") ? (float) $material_total : 0;

        $data["sub_total"] = (string) $totalAmount;
        $totalAmount += $material;
        
        // if(isset($request->selected_packages)){                
        //     $selectedPackageIds = explode(',', $request->selected_packages);

        //     $packages = Packages::whereIn('id', $selectedPackageIds)->get();

        //     foreach ($packages as $package) {
        //         $data["packages"][] = ["name"=>$package->name, "price"=>(string) $package->amount]; 
        //         $totalAmount += (int) $package->amount; 
        //     }
        // }
        $data["material"] = (string) $material;
        $data["total_amount"] = (string) $totalAmount;

        return $this->sendResponse($data, 'Updated Price!');
    }

    public function get_price(Request $request)
    {
        if($request->cart_id){

            $data = [];
            $totalAmount = 0;
            $cart = Card::find($request->cart_id);
            $service = Service::find($cart->service_id);
            $material_total = ($cart->material_status == "Apply") ? (int) $cart->material_charge : 0;
            $material = 0;
            if (CardAttribute::where('card_id', $cart->id)->exists()) {
                $data["service_name"] = $service->name;
                foreach (CardAttribute::where('card_id', $cart->id)->get() as $value) {
                   
                $service = ServiceAttributeValue::where('attribute_item_id',$value->attribute_item_id)->first();
                $service_item = ServiceAttributeValueItem::where('id',$service->ser_attr_val_item_id)->first();
                $attribute = Attribute::where('id',$service->attribute_id)->first();
                $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                
                $params['attribute_price']      = $service->attribute_price ?? null;
                $params['attribute_qty']        = $value->attribute_qty ?? null;
                $params['service_type']         = '';
                
                $data["attribute"][] = ["name"=>$attribute_item->value,"price"=>$service->attribute_price];
                $attribute_total = $params['attribute_price'] * $params['attribute_qty'];
                
                if($attribute->name == 'Hours'){
                    $totalAmount *= (int) $attribute_total; 
                } else {
                    
                    $totalAmount += $attribute_total; 
                }

                preg_match('/\d+/', $attribute_item->value, $matches);
                
                $material += ($request->material_status == "Apply") ? (int) $material_total : 0;
                $material *= (int) $value->attribute_qty;

                $cardattr = CardAttribute::create($params);
                    // if(($key == "add_on") ? $value->add_on : 0){
                    //     foreach ($value->add_on as $key => $addon) {
                    //         $addon_info['card_id']               = $card->id;
                    //         $addon_info['card_attribute_id']     = $cardattr->id;
                    //         $addon_info['add_on_id']             = $addon->add_on_id;
                    //         $addon_info['name']                  = $addon->name;
                    //         $addon_info['value']                 = $addon->value;
                    //         $addon_info['percentage']            = $addon->percentage;
                            
                    //         // Add addon value to sub_total
                    //         $sub_total += $addon->value;
                    //         CardAddon::create($addon_info);
                    //     }
                    // }
                } 
                
            }
            $data["sub_total"] = (string) $totalAmount;
            $totalAmount += $material;
            
            // if(isset($request->selected_packages)){                
            //     $selectedPackageIds = explode(',', $request->selected_packages);

            //     $packages = Packages::whereIn('id', $selectedPackageIds)->get();

            //     foreach ($packages as $package) {
            //         $data["packages"][] = ["name"=>$package->name, "price"=>(string)$package->amount]; 
            //         $totalAmount += (int) $package->amount; 
            //     }
            // }
            $data["material"] = (string) $material;
            $data["date"] = $cart->date ?? "";
            $data["time"] = ($cart->slot_id) ? Slot::find($cart->slot_id)->name : "";
            $data["total_amount"] = (string) $totalAmount;

            return $this->sendResponse($data, 'Updated Price!');
        } else {
            return $this->sendResponse([], 'Missing Fields Required!');
        }
    }

    public function get_reward_price($totalAmount = 0)
    {
        // Fetch reward and exchange values from the configuration
        $reward = RewardConfig::where('name', 'reward')->first();
        $exchange = RewardConfig::where('name', 'exchange')->first();

        if (!$reward || !$exchange) {
            return [
                'total_before_rewards' => (string) 0,
                'reward_amount_used' => (string) 0, 
                'remaining_reward_amount' => (string) 0,   
                'remaining_reward_points' => (int) 0,      
                'final_amount' => (string) 0                        
            ];
        }

        $rewardPerDollar = $reward->value;  
        $exchanges = $exchange->value;      

        $rewardAmount = floor($totalAmount / $exchanges);
        $rewardPoints = floor($rewardAmount * $rewardPerDollar);

        $finalAmount = $totalAmount;

        if ($rewardAmount > $totalAmount) {
            $remainingRewardAmount = $rewardAmount - $totalAmount;
            $finalAmount = 0;

            // Calculate the remaining reward points based on the unused reward amount
            $remainingRewardPoints = $remainingRewardAmount * $rewardPerDollar;
        } else {
            // Otherwise, deduct the reward amount from the total and set no remaining rewards
            $finalAmount = $rewardAmount;
            $remainingRewardAmount = 0;
            $remainingRewardPoints = 0;
        }
        
        return [
            'total_before_rewards' => (string) $totalAmount,
            'reward_amount_used' => (string) ($totalAmount - $finalAmount), 
            'remaining_reward_amount' => (string) $remainingRewardAmount,   
            'remaining_reward_points' => (int) $remainingRewardPoints,      
            'final_amount' => (string) $finalAmount                        
        ];
    }


    public function handle_reward($created_at, $id = 0, $totalAmount = 0)
    {
        // Fetch reward and exchange values from the configuration
        $reward = RewardConfig::where('name', 'reward')->first();
        $exchange = RewardConfig::where('name', 'exchange')->first();

        if (!$reward && !$exchange) {
            return $this->sendResponse([], 'Reward configuration not found!');
        }

        // Get the reward and exchange values
        $rewardPerDollar = $reward->value;  // Points per dollar spent
        // $exchanges = $exchange->value;      // Exchange rate (dollars equivalent for rewards)

        // Calculate reward points and reward amount (AED equivalent of points)
        $rewardPoints = floor($totalAmount * $rewardPerDollar);
        $rewardAmount = floor($totalAmount / $rewardPerDollar);

        // Initialize the final amount after rewards are applied
        $finalAmount = $totalAmount;

        // Calculate remaining reward amount and adjust the final total
        if ($rewardAmount > $totalAmount) {
            // If reward amount exceeds the total, set final amount to zero and calculate remaining reward amount
            $remainingRewardAmount = $rewardAmount - $totalAmount;
            $finalAmount = 0;

            // Calculate the remaining reward points based on the unused reward amount
            $remainingRewardPoints = $remainingRewardAmount * $rewardPerDollar;
        } else {
            // Otherwise, deduct the reward amount from the total and set no remaining rewards
            $finalAmount = $totalAmount - $rewardAmount;
            $remainingRewardAmount = 0;
            $remainingRewardPoints = 0;
        }

        $res = [
            'total_before_rewards' => (string) $totalAmount,
            'reward_amount_used' => (string) ($totalAmount - $finalAmount), 
            'remaining_reward_amount' => (string) $remainingRewardAmount,   
            'remaining_reward_points' => (int) $remainingRewardPoints,      
            'final_amount' => (string) $finalAmount                        
        ];

        $this->updateUserReward($created_at, $id, ($totalAmount - $finalAmount),($totalAmount - $finalAmount));

        return $res;
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
        ]);
    
        $cart = Card::findOrFail($validated['card_id']);
    
        $duplicatedCard = $cart->replicate(); 
        $duplicatedCard->created_at = now(); 
        $duplicatedCard->updated_at = now();  
        $duplicatedCard->save();     

        $service = Service::find($cart->service_id);
        
        if (CardAttribute::where('card_id', $cart->id)->exists()) {
            $data["service_name"] = $service->name;
            foreach (CardAttribute::where('card_id', $cart->id)->get() as $value) {
                
                $service = ServiceAttributeValue::where('attribute_item_id',$value->attribute_item_id)->first();
                $service_item = ServiceAttributeValueItem::where('id',$service->ser_attr_val_item_id)->first();
                $attribute = Attribute::where('id',$service->attribute_id)->first();
                $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                
                $params['attribute_price']      = $service->attribute_price ?? null;
                $params['attribute_qty']        = $value->attribute_qty ?? null;
                $params['service_type']         = '';
                
                $cardattr = CardAttribute::create($params);
            } 
            
        }
    
        return response()->json([
            'success' => "1",
            'message' => 'Card duplicated successfully.',
            'data' => [
                "card" => $duplicatedCard,
                "card_attribute" => $cardattr
            ],
        ]);
    }
    

    public function store(Request $request)
    {
        if($request->service_id && $request->item_object ){
            $totalAmount = 0;
            $service = Service::find($request->service_id);
            $data['user_id']            = auth()->user()->id;
            $data['service_id']         = (string) $service->id;
            $data['service_name']       = $service->name;
            $data['category_id']        = $request->category_id;         
            $data['note']               = $request->note;
            $data['material_status']    = ($request->material_status)?$request->material_status:"No";
            $data['selected_packages']  = $request->selected_packages;
            $data['service_type']       = '';
            $data['payment_status']     = 'pending';
            $data['is_checkout']        = 'Start';
            $data['accept_user_id']     = Seller::find(SellerService::where('service_id',$service->id)->first()->seller_id)->user_id;
            $data['booking_from']       = $request->booking_from?$request->booking_from:'App';
            
            $material_default = ($request->material_status == "Apply") ? (float) $service->material_price : 0;
            $material_total = (float) 0;
            $material = (float) 0;
            if(isset($request->preffered_days)){
                $data['preffered_days']     = $request->preffered_days;
            }
            if (!empty($request->card_id)) {
                $card = Card::findOrFail($request->card_id); 
                $card->update($data);

                if (isset($card) && $card->id) {
                    CardAttribute::where('card_id', $card->id)->delete();
                }
            } else {
                $card = Card::create($data);
            }
            
            if($card){

                if(isset($request->item_object)){
                    $c = 0;
                    foreach (json_decode($request->item_object) as $key => $value) {
                        $params['card_id']              = $card->id;
                        
                        $service = ServiceAttributeValue::where('attribute_item_id',$value->attribute_item_id)->first();

                        $service_item = ServiceAttributeValueItem::where('id',$service->ser_attr_val_item_id)->first();
                        $attribute = Attribute::where('id',$service->attribute_id)->first();
                        $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                        
                        $params['sub_cate_id']          = $value->sub_category_id ?? null;
                        $params['main_sub_cat_id']      = $value->category_id ?? null;
                        $params['child_cate_id']        = $value->child_category_id ?? null;
                        $params['attribute_id']         = $attribute->id ?? null;
                        $params['attribute_name']       = $attribute->name ?? null;
                        $params['attribute_item_id']    = $value->attribute_item_id ?? null;
                        $params['attribute_item_name']  = $attribute_item->value ?? null;
                        $params['attribute_price']      = $service->attribute_price ?? null;
                        $params['attribute_qty']        = $value->attribute_qty ?? null;
                        $params['service_type']         = '';
                        
                        $attribute_total = $params['attribute_price'] * $params['attribute_qty'];
                        
                        if($attribute->name == 'Hours'){
                            $totalAmount *= (int) $attribute_total; 
                        } else {
                            $totalAmount += $attribute_total; 
                        }
                        
                        preg_match('/\d+/', $attribute_item->value, $matches);

                        if (count($matches) > 0) {
                            $number = (int) $matches[0];
                        
                            if ($attribute->name == 'Hours') {
                                if ($number > 1) {
                                    if($material_total > 0){
                                        $material_total *= ($request->material_status == "Apply" ? $number : 0) 
                                                            * (int) $value->attribute_qty;
                                    } else {
                                        $material_total += ($request->material_status == "Apply" ? $number : 0) 
                                                            * (int) $value->attribute_qty;
                                    }
                                } else {
                                    $c++;
                                    if((int) $value->attribute_qty > 1){
                                        $material_total += (float) $material_default * (float) $value->attribute_qty;
                                    }
                                }
                            } else {
                                if ($number > 1) {
                                    if($material_total > 0){
                                        $material_total *= ($request->material_status == "Apply" ? $number : 0) 
                                                            * (int) $value->attribute_qty;
                                    } else {
                                        $material_total += ($request->material_status == "Apply" ? $number : 0) 
                                                            * (int) $value->attribute_qty;
                                    }
                                } else {
                                    $c++;
                                    if((int) $value->attribute_qty > 1){
                                        
                                        $material_total += (float) $material_default * (float) $value->attribute_qty;
                                    }
                                }
                            }
                        }

                        $cardattr = CardAttribute::create($params);
                    } 
                }
                
                if($c==2){
                    $material_total += $material_default;
                } else {
                    $material_total *= $material_default;
                }
                $material = ($request->material_status == "Apply") ? (float) $material_total : 0;
                
                $cardattr = CardAttribute::where(['card_id' => $card->id])->get();
                $res["sub_total"] = (string) $totalAmount;
                $totalAmount += (int) $material;

                $card->update([
                    'material_charge'   => ($request->material_status == "Apply") ? (string) $material : '0',
                    'amount'            => $totalAmount,
                    'g_total'           => $res["sub_total"]
                ]);

                $services = Service::find($request->service_id);
                $res['material_charge']     = $card->material_charge;
                $res["total_amount"]        = (string) $totalAmount;
                $res['cart_id']             = (string) $card->id;
                $res['service_name']        = $services->name;
                
                return $this->sendResponse($res, 'Item Added');
            } else {
                return $this->sendResponse([], 'Try Again');
            }
            
        } elseif($request->cart_id && $request->alternative_number && $request->dial_code && $request->address_id ) {
            
            $add_res = Address::find($request->address_id);
            $shippingAddress = [];

            if($request->address_id){
                if($add_res){
                    $shippingAddress['user_id']     = $add_res->user_id;
                    $shippingAddress['city_id']     = $add_res->city_id;
                    $shippingAddress['city_name']   = $add_res->city?$add_res->city->name:'';
                    $shippingAddress['address']     = $add_res->address;
                    $shippingAddress['address_type']= $add_res->address_type;
                    $shippingAddress['flat_no']     = $add_res->flat_no;
                    $shippingAddress['building']    = $add_res->building;
                    $shippingAddress['locality']    = $add_res->locality_info?$add_res->locality_info->name:'';
                    $shippingAddress['latitude']    = $add_res->latitude;
                    $shippingAddress['longitude']   = $add_res->longitude;

                    $data['address_id']         = $request->address_id;
                } else {
                    return $this->sendError('Address detials not found.');
                }
            } else {
                return $this->sendError('Address detials not found.');
            }

            $data = [
                'alternative_number' => $request->alternative_number,
                'alternative_dial_code' => $request->dial_code,
                'address_id' => $request->address_id,
            ];

            $card = Card::find($request->cart_id);

            if($card) {
                $card->update($data);
                
                $cardattr = CardAttribute::where(['card_id' => $card->id])->get();

                $services = Service::find($card->service_id);
                $res['sub_total']           = (string) $card->g_total;
                
                // if(!empty($card->selected_packages)){                
                //     $selectedPackageIds = explode(',', $card->selected_packages);
    
                //     $packages = Packages::whereIn('id', $selectedPackageIds)->get();
                //     $package_price = 0;
                //     foreach ($packages as $package) {
                //         $data["packages"][] = ["name"=>$package->name, "price"=>(string)$package->amount]; 
                //         $package_price += (int) $package->amount; 
                //     }
                //     $res["package_price"]       = (string) $package_price;
                // }

                $res['material_charge']     = $card->material_charge;
                $res['amount_total']        = (string) $card->amount;
                $res['cart_id']             = (string) $card->id;
                $res['service_name']        = $services->name;
                // $res['attributes']          = $cardattr;
                return $this->sendResponse($res, 'Card updated successfully.');
            } else {
                return response()->json(['error' => 'Card not found.'], 404);
            }

        } elseif($request->cart_id && $request->slot_id ) {
            
            $data = [
                'slot_id' => $request->slot_id,
            ];

            $card = Card::find($request->cart_id);

            if($card) {
                $card->update($data);
                
                $cardattr = CardAttribute::where(['card_id' => $card->id])->get();


                $services = Service::find($card->service_id);
                $res['sub_total']           = (string) $card->g_total;
                
                // if(!empty($card->selected_packages)){                
                //     $selectedPackageIds = explode(',', $card->selected_packages);
    
                //     $packages = Packages::whereIn('id', $selectedPackageIds)->get();
                //     $package_price = 0;
                //     foreach ($packages as $package) {
                //         $data["packages"][] = ["name"=>$package->name, "price"=>(string)$package->amount]; 
                //         $package_price += (int) $package->amount; 
                //     }
                //     $res["package_price"]       = (string) $package_price;
                // }

                $res['material_charge']     = $card->material_charge;
                $res['amount_total']        = (string) $card->amount;
                $res['cart_id']             = (string) $card->id;
                $res['service_name']        = $services->name;
                
                // $res['card']                = $card;
                // $res['attributes']          = $cardattr;
                return $this->sendResponse($res, 'Card updated successfully.');
            } else {
                return response()->json(['error' => 'Card not found.'], 404);
            }

        } else {
            return $this->sendError('Required field is empty');
        }
    }


    public function get_card(Request $request)
    {
        if($request->user_id){
            $card = Card::where('user_id',$request->user_id)->where('status', '!=' , 'Canceled')->orderBy('id', 'DESC')->get();
            $cards = [];

            foreach ($card as $key => $value) {

                $service = Service::find($value->service_id);

                if(ServiceAttributeValueItem::where('service_id',$value->id)->with('sub_category')->first()->sub_category){
                    $data['sub_cate_yes'] = 'Yes';
                } else {
                    $data['sub_cate_yes'] = 'No';
                }

                $user = User::find($value->user_id);
                $category = Category::find($value->category_id);

                $data['booking_id']     = $value->id;
                $data['tran_id']        = $value->tran_id;
                $data['paymentTranId']  = $value->paymentTranId;
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

                if(is_numeric($value->address_id)){

                    $data['address_id']     = $value->address_id;

                    $building = $value->address?$value->address->building:'';
                    $flat_no = $value->address?$value->address->flat_no:'';
                    $address = $value->address?$value->address->address:'';

                    $latitude = $value->address?$value->address->latitude:'';
                    $longitude = $value->address?$value->address->longitude:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;

                    if($value->address && $value->address->city){
                        $city = $value->address->city->name;
                    } else {
                        $city = "";
                    }

                    $data['city']           = $city;

                    if($value->address && $value->address->locality_info){
                        $locality = $value->address->locality_info->name;
                    } else {
                        $locality = "";
                    }
                    $data['locality']       = $locality;
                    
                    $data['latitude']       = $latitude;
                    $data['longitude']      = $longitude;

                } else {

                    $add_info = json_decode($value->address_id);

                    $data['address_id']     = '';


                    $building = $add_info?$add_info->building:'';
                    $flat_no = $add_info?$add_info->flat_no:'';
                    $address = $add_info?$add_info->address:'';

                    $latitude = $add_info?$add_info->latitude:'';
                    $longitude = $add_info?$add_info->longitude:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;

                    if($add_info && $add_info->city_name){
                        $city = $add_info->city_name;
                    } else {
                        $city = "";
                    }

                    $data['city']           = $city;

                    if($add_info && $add_info->locality){
                        $locality = $add_info->locality;
                    } else {
                        $locality = "";
                    }
                    $data['locality']       = $locality;
                    
                    $data['latitude']       = $latitude;
                    $data['longitude']      = $longitude;

                }


                $data['payment_moad']   = $value->payment_moad;
                $data['payment_status'] = $value->payment_status;
                $data['note']           = $value->note;
                $data['material_charge']= (string) $value->material_charge;
                $data['material_status']= ($value->material_status)?$value->material_status:"No";
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
               
                array_push($cards, $data);
            }
            return $this->sendResponse($cards, 'Card Response');
        } else {
            return $this->sendError('Required field is empty');
        }
    }


    public function get_perticular_card(Request $request)
    {
        if($request->booking_id){
            $card = Card::where('id',$request->booking_id)->get();
            $cards = [];

            foreach ($card as $key => $value) {

                $service = Service::find($value->service_id);
                $user = User::find($value->user_id);
                $category = Category::find($value->category_id);

                $data['booking_id']     = $value->id;
                $data['tran_id']        = $value->tran_id;
                $data['paymentTranId']  = $value->paymentTranId;
                $data['user_id']        = $value->user_id;
                $data['user_name']      = $user?$user->name:'';
                $data['user_email']     = $user?$user->email:'';
                $data['user_mobile']    = $user?$user->phone:'';
                $data['service_id']     = $value->service_id;
                $data['service']        = $service->name;
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
                $data['material_charge']= (string) $value->material_charge;
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

                array_push($cards, $data);
            }
            return $this->sendResponse($cards, 'Card Response');
        } else {
            return $this->sendError('Required field is empty');
        }
    }

    public function card_transection_id_update(Request $request, $card_id='')
    {
        $card = Card::find($card_id);

        if($card){

            $data['tran_id']  = $request->tran_id;

            $card->update($data);

            return $this->sendResponse([], 'Card Updated');

        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function card_checkout_go(Request $request)
    {
        $card = Card::where(['user_id'=>auth()->user()->id])
        ->where('is_checkout', '!=', 'Done')
        ->orderBy('id', 'DESC')
        ->get();
        
        if (!$card->isEmpty()) {
            
            foreach ($card as $key => $value) {
                if(empty($value->address_id)){
                    return $this->sendResponse('Your cart information is missing!', 'step 2');
                }else if(empty($value->slot_id)){
                    return $this->sendResponse('Your cart information is missing!', 'step 3');
                }
            }

            return $this->sendResponse($card, 'Cards Data');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function card_checkout_data(Request $request)
    {
        $card = Card::where(['user_id'=>auth()->user()->id])
        ->where('is_checkout', '!=', 'Done')
        ->orderBy('id', 'DESC')
        ->get();
        
        if($card){
            
            $cards = [];
            $cards_detail = [];
            $total_amount = 0;

            foreach ($card as $key => $value) {
                // Initialize card data array
                $card_data = [
                    'id'                     => $value->id, // Add card ID
                    'user_id'                => $value->user_id,
                    'service_id'             => $value->service_id,
                    'service_name'           => $value->service_name, // Add service name
                    'category_id'            => $value->category_id,
                    'slot_id'                => $value->slot_id,
                    'address_id'             => $value->address_id,
                    'tran_id'                => $value->tran_id,
                    'payment_status'         => $value->payment_status,
                    'alternative_dial_code'  => $value->alternative_dial_code ?? "",
                    'alternative_number'     => $value->alternative_number,
                    'note'                   => $value->note,
                    'date'                   => $value->date,
                    'status'                 => $value->status,
                    'accept_user_id'         => $value->accept_user_id,
                    'amount'                 => $value->amount, // Add card amount
                    'g_total'                => $value->g_total,
                    'material_status'        => $value->material_status,
                    'material_charge'        => $value->material_charge,
                    'is_checkout'            => $value->is_checkout,
                    'payment_collected'      => $value->payment_collected,
                    'service_completed'      => $value->service_completed,
                    'service_completed_date' => $value->service_completed_date,
                    'work_done'              => $value->work_done,
                    'pending_approval_by_admin' => $value->pending_approval_by_admin,
                    'booking_from'           => $value->booking_from,
                    'created_at'             => $value->created_at,
                    'updated_at'             => $value->updated_at,
                    'selected_packages'      => $value->selected_packages,
                    'preferred_days'         => $value->preffered_days,
                ];

                // Card Attributes and Add-ons
                $card_attr = [];
                foreach (CardAttribute::where('card_id', $value->id)->get() as $item) {
                    $params = [
                        'sub_cate_id'         => (string) $item->sub_cate_id,
                        'sub_cate_name'       => $item->sub_cate ? $item->sub_cate->name : '',
                        'main_sub_cat_id'     => $item->main_sub_cat_id,
                        'main_sub_cat_name'   => $item->main_sub_cat ? $item->main_sub_cat->name : '',
                        'child_cate_id'       => (string) $item->child_cate_id,
                        'child_cate'          => $item->child_cate ? $item->child_cate->name : '',
                        'attribute_id'        => $item->attribute_id,
                        'attribute_name'      => $item->attribute_name,
                        'attribute_item_id'   => (string) $item->attribute_item_id,
                        'attribute_item_name' => $item->attribute_item_name,
                        'attribute_qty'       => $item->attribute_qty,
                        'attribute_price'     => $item->attribute_price,
                        'service_type'        => $item->service_type,
                    ];

                    // Add-ons
                    $addons = [];
                    foreach (CardAddon::where('card_id', $value->id)->where('card_attribute_id', $item->id)->get() as $cardaddon) {
                        $addons[] = [
                            'card_id'            => $cardaddon->id,
                            'card_attribute_id'  => $cardaddon->id,
                            'add_on_id'          => $cardaddon->add_on_id,
                            'name'               => $cardaddon->name,
                            'value'              => $cardaddon->value,
                            'percentage'         => $cardaddon->percentage,
                        ];
                    }
                    $params['addon'] = $addons; // Add the addons to the card attribute
                    $card_attr[] = $params; // Add the card attribute to the array
                }

                // Include the card attributes inside the card data
                $card_data['card_attribute'] = $card_attr;

                // Add card data to the main $cards array
                $cards_detail[] = $card_data;

                // Update total amount
                $total_amount += (int) $value->amount;
            }

            $cards['card'] = $cards_detail;

            $cards['sub_total']     = (string) $total_amount;
            $cards['user_balance']  = auth()->user()->wallet_balance;
            $rewardData = $this->get_reward_price($total_amount);

            if ($rewardData !== null) {
                $cards["remaining_reward_points"] = (string) $rewardData['remaining_reward_points'];
                $cards["reward_amount"] = (string) $this->get_reward_price($total_amount)['final_amount'];
                $cards['total']         = (string) ((int) $total_amount - (int) $this->get_reward_price($total_amount)['final_amount']);
            } else {
                $cards["remaining_reward_points"] = '0'; 
                $cards["reward_amount"] = '0'; 
                $cards["total"] = '0'; 
            }
    
    
            return $this->sendResponse($cards, 'Cards Data');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function send_email_notification($card){
        $array['view']      = 'emails.invoice';
        $array['subject']   = 'Your Booking Invoice';
        $array['data']      = $card;
        if($card->user && $card->user->email){
            $res = \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
        }                

        $adminarray['view']      = 'emails.invoice';
        $adminarray['subject']   = 'You Have New Service Booking';
        $adminarray['data']      = $card;
    
        \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));
        // $admins = User::where('role', 'admin')->get();

        // foreach ($admins as $admin) {
        //     Mail::to($admin->email)->send(new \App\Mail\Mail($adminarray));
        // }

        $ser_users = get_seller_info_by_service($card?$card->service_id:'');
        if($ser_users){
            foreach ($ser_users as $key => $val) {
                if(isset($val->device_token)){
                    
                    $token  = $val->device_token;
                            
                    $service = $card->service?$card->service->name:'No Service';
                        
                    $title  = 'New Booking Arrived';
                    $body   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";
                    $text   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";

                    send_notification($token, $title, $body, $text);
                }
            }
        }   
    }

    public function card_payment_update(Request $request)
    {
        $cards = Card::where(['user_id'=>auth()->user()->id])
        ->where('is_checkout', '!=', 'Done')
        ->get();
        $all_subtotal = 0;
        $all_total    = 0;
        if (!$cards->isEmpty()) {
            foreach ($cards as $card) {
                if($card){
                    $data['card_process']   = 'Complete';
                    $data['payment_status'] = "True";
                    $data['payment_type']   = "1";
                    $data['booking_from']   = $request->booking_from?$request->booking_from:'App';
    
                    $subtotal = $card->g_total;
                    $total = $card->amount;

                    $coupon = Coupon::where('id',$request->coupon_id)->where('status','1')->first();
                    if($coupon){
                        $user_coupon['user_id'] = $card->user_id;
                        $user_coupon['coupon_id'] = $card->coupon_id;
                        UserCoupon::create($user_coupon);
    
                        $crd_coupon['card_id']      = $card->id;
                        $crd_coupon['coupon_id']    = $coupon->id;
                        $crd_coupon['code']         = $coupon->code;
                        $crd_coupon['amount']       = $coupon->amount;
                        $crd_coupon['min_amount']   = $coupon->min_amount;
                        $crd_coupon['max_amount']   = $coupon->max_amount;
                        $crd_coupon['type']         = $coupon->type;
                        $crd_coupon['start_date']   = $coupon->start_date;
                        $crd_coupon['end_date']     = $coupon->end_date;
    
                        if(CardCoupon::where('card_id',$request->card_id)->exists()){
                            CardCoupon::where('card_id',$request->card_id)->update($crd_coupon);
                        } else {
                            CardCoupon::create($crd_coupon);
                        }
    
                        $amount = $coupon->amount;
                        if($coupon->type=='Amt'){
                            $total = $subtotal - $amount;
                            $coupon_Amt = $amount;
                        } else {
                            $per = ($amount / 100) * $subtotal;
                            $total = $subtotal - $per;
                            if($per>$coupon->max_amount){
                                $coupon_Amt = $coupon->max_amount;
                            } else {
                                $coupon_Amt = $per;
                            }
                        }
                    } else {
                        $coupon_Amt = '00';
                    }
                    $data['coupon_amt'] = $card->coupon_amt;
                    $data['cod_charge'] = 15;
                    $data['is_checkout'] = 'Done';
                    // $this->send_email_notification($card);
                    
                    if($request->payment_mode == "cash") {
                        $data['amount']     = ((int) $card->g_total + (int) 15);
                    } else {
                        $data['amount']     = ((int) $card->g_total);
                    }
                    
                    $card->update($data);
                    
                    $input=[
                        'customer_id' => auth()->user()->id,
                        'vendor_id'=> SellerService::where("service_id",$card->service_id)->first()->seller_id,
                        'amount'=>$card->amount,
                        'remark'=>"Done",
                        'type'=>'Dr'
                    ];
                    
                    $res=Transection::create($input);

                    $new_data['tran_id'] = $res->id;
                    
                    $card->update($new_data);

                    if($res)
                    {
                        
                        $invite = Invite::where('invite_code', auth()->user()->invite)->first();

                        if ($invite && auth()->user()->is_invite) {
                        
                            $earn_user = User::firstOrCreate(
                                ['id' => $invite->user_id], 
                                ['wallet_balance' => "0"]               
                            );
                            
                            $earn_user->wallet_balance = ($earn_user->wallet_balance ?? "0") + "10"; 
                            $earn_user->save();
                        
                            $wallet = Wallet::firstOrCreate(
                                ['user_id' => $earn_user->id], 
                                ['balance' => 0]               
                            );
                            
                            $wallet->balance = ($wallet->balance ?? 0) + 10; 
                            $wallet->save();
                        }
                        
                        auth()->user()->update([
                            "wallet_balance" => (int) auth()->user()->wallet_balance - (int) $request->amount,
                            "is_invite" => 0
                        ]);    
                        
                        $user_wallet = Wallet::firstOrCreate(
                            ['user_id' => auth()->user()->id], 
                            ['balance' => 0] 
                        );
                        
                        $user_wallet->balance = ($user_wallet->balance ?? 0);
                        $user_wallet->save();
                    }


                } else {
                    return $this->sendError('Invalid booking id');
                }
            }
    
            $res['subtotal']       = (string) $subtotal;
            $res['total']          = (string) $total;
            
            if($total > HomeSetting::select("min_cart_value")->first()->min_cart_value){

               $res['reward'] = $this->handle_reward($res->created_at, $res->id, $total);

            }

            $seller_id = SellerService::where('service_id', $card->service_id)->first()->seller_id;
            $user_id = Seller::find($seller_id)->user_id;
            $seller = User::find($user_id);
            $user = User::find(auth()->user()->id);

            if ($user) {
                $device_tokens = $user->user_device_token;
                $firebase_user_key = $user->firebase_user_key;
            
                if (!empty($device_tokens) && $device_tokens != '0') {
                    if (strlen($device_tokens) > 70) {
                        // Logic when token length exceeds 70
                        $title  = "#" . $card->id;
                        $body   = "Heads up! You have a new booking on Urbanmop Partner App for {$card->service_name}. Don't miss out on extra earnings!";
                        $text   = "A new booking just came in for {$card->service_name} via Urbanmop Partner App. Take action quickly!";

//                        $this->firebaseService->sendNotification($device_tokens, $title, $body, $text);
                        if (!empty($firebase_user_key)) {
                            $notification_data["Notifications/" . $firebase_user_key . "/" . time()] = [
                                "title" => $title,
                                "description" => $body,
                                "notificationType" => 'card_payment_update',
                                "createdAt" => Carbon::now()->setTimezone('GMT')->format('Y-m-d h:i:s'),
                                "cardId" => (string)$card->id,
                                "status" => "1",
                                "url" => "",
                                "imageURL" => '',
                                "read" => "0",
                                "seen" => "0",
                            ];
                            $this->database->getReference()->update($notification_data);

                            send_single_notification(
                                $device_tokens,
                                [
                                    "title" => $title,
                                    "body" => $body,
                                    "icon" => 'myicon',
                                    "sound" => 'default',
                                    "click_action" => "EcomNotification",
                                ],
                                [
                                    "type" => 'card_payment_update',
                                    "notificationID" => time(),
                                    "status" => "1",
                                    "imageURL" => "",
                                    "cardId" => (string)$card->id,
                                ]
                            );
                        }
                    }
                }

                // if (!empty($firebase_user_key) && $firebase_user_key != '0') {
                //     $notification_data["Nottifications/" . $firebase_user_key . "/" . $notification_id] = [
                //         "title" => $title,
                //         "description" => $description,
                //         "notificationType" => $ntype,
                //         "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                //         "orderId" => (string) $request->detailsid,
                //         "url" => "",
                //         "imageURL" => '',
                //         "read" => "0",
                //         "seen" => "0",
                //     ];
                //     $this->database->getReference()->update($notification_data);
                // }
            }

            if (!empty($seller)) {
                $device_tokens = $seller->user_device_token;
            
                if (!empty($device_tokens) && $device_tokens != '0') {
                    if (strlen($device_tokens) > 70) {
                        // Logic when token length exceeds 70
                        $title  = "#" . $card->id;
                        $body   = "Heads up! You have a new booking on Urbanmop Partner App for {$card->service_name}. Don't miss out on extra earnings!";
                        $text   = "A new booking just came in for {$card->service_name} via Urbanmop Partner App. Take action quickly!";

//                        $this->firebaseService->sendNotification($device_tokens, $title, $body, $text);
                        if (!empty($firebase_user_key)) {
                            $notification_data["Notifications/" . $firebase_user_key . "/" . time()] = [
                                "title" => $title,
                                "description" => $body,
                                "notificationType" => 'card_payment_update',
                                "createdAt" => Carbon::now()->setTimezone('GMT')->format('Y-m-d h:i:s'),
                                "cardId" => (string)$card->id,
                                "status" => "1",
                                "url" => "",
                                "imageURL" => '',
                                "read" => "0",
                                "seen" => "0",
                            ];
                            $this->database->getReference()->update($notification_data);

                            send_single_notification(
                                $device_tokens,
                                [
                                    "title" => $title,
                                    "body" => $body,
                                    "icon" => 'myicon',
                                    "sound" => 'default',
                                    "click_action" => "EcomNotification",
                                ],
                                [
                                    "type" => 'card_payment_update',
                                    "notificationID" => time(),
                                    "status" => "1",
                                    "imageURL" => "",
                                    "cardId" => (string)$card->id,
                                ]
                            );
                        }
                    }
                }
            }

            return $this->sendResponse($res, 'Checkout Successfully!');
        }else {
            return $this->sendError('Your Cart is empty!');
        }
        
    }

    public function updateUserReward($created_at, $id, $point, $amount)
    {
        $userId = auth()->user()->id;
    
        // Check if the user exists in the users table
        $userExists = \DB::table('users')->where('id', $userId)->exists();
    
        if (!$userExists) {
            throw new \Exception('User not found in the users table');
        }
    
        // Delete any existing user reward for this user
        // RewardUser::where('user_id', $userId)->delete();
    
        // Create a new reward entry
        $userReward = RewardUser::create([
            'booking_type' => 'Purchase',
            'transection_id' => $id,
            'date' => $created_at,
            'user_id' => $userId,
            'reward_config_id' => 1, 
            'points' => $point,
            'amounts' => $amount
        ]);
    
        return $userReward;
    }
    
    public function all_bookings(Request $request)
    {
        return $request;
    }

    public function get_all_booking(Request $request)
    {
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1); 

        // Check wallet balance condition
        if($request->wallet_balance < 0 || $request->wallet_balance == '0'){
            $wallet = '0';
        } else {
            $wallet = $request->wallet_balance;
        }

        // Convert service_id from string to array
        $serviceIdsArray = explode(',', $request->service_id);

        // Query based on wallet balance condition
        if($wallet == '0'){
            $card = Card::whereIn('service_id', $serviceIdsArray)
                        ->where('status', 'Pending')
                        ->where('payment_status', 'True')
                        ->where('is_checkout', 'Done')
                        ->orderBy('id', 'DESC')
                        ->paginate($limit, ['*'], 'page', $page); 
        } else {
            $card = Card::whereIn('service_id', $serviceIdsArray)
                        ->where('status', 'Pending')
                        ->where('payment_status', 'True')
                        ->where('is_checkout', 'Done')
                        ->orderBy('id', 'DESC')
                        ->paginate($limit, ['*'], 'page', $page); 
        }

        // Initialize the response array
        $cards = [];

        // Check if there are results in the query
        if ($card->count()) {
            foreach ($card as $value) {
                $service = Service::find($value->service_id);
                $user = User::find($value->user_id);
                $category = Category::find($value->category_id);

                $data['id']             = (string) $value->id;
                $data['booking_id']     = $value->tran_id;
                $data['tran_id']        = $value->tabby_payment_response_id ?? $value->paymentTranId ?? '';
                $data['user_id']        = (string) $value->user_id;
                $data['user_name']      = $user ? $user->name : '';
                $data['user_email']     = $user ? $user->email : '';
                $data['user_mobile']    = $user ? $user->phone : '';
                $data['service_id']     = (string) $value->service_id;
                $data['service']        = $service->name;
                $data['image']          = \URL::to('/') . '/uploads/service/' . $service->thumbnail_img;
                $data['category_id']    = (string) $value->category_id;
                $data['category']       = $category ? $category->name : '';
                $data['slot_id']        = $value->slot_id;
                $data['slot']           = $value->slot ? $value->slot->name : '';
                $data['offline_charge'] = $value->offline_charge;
                $data['offline_discount'] = $value->offline_discount;
                $data['status'] = $value->status;

                // Address Logic
                if (is_numeric($value->address_id)) {
                    $data['address_id'] = $value->address_id;
                    $building = $value->address ? $value->address->building : '';
                    $flat_no = $value->address ? $value->address->flat_no : '';
                    $address = $value->address ? $value->address->address : '';
                    $data['address'] = $building . ', ' . $flat_no . ', ' . $address;
                    $data['city'] = $value->address && $value->address->city ? $value->address->city->name : '';
                    $data['locality'] = $value->address && $value->address->locality_info ? $value->address->locality_info->name : '';
                    $data['latitude'] = $value->address ? $value->address->latitude : '';
                    $data['longitude'] = $value->address ? $value->address->longitude : '';
                } else {
                    // For custom address in JSON
                    $add_info = json_decode($value->address_id);
                    $data['address_id'] = '';
                    $building = $add_info ? $add_info->building : '';
                    $flat_no = $add_info ? $add_info->flat_no : '';
                    $address = $add_info ? $add_info->address : '';
                    $data['address'] = $building . ', ' . $flat_no . ', ' . $address;
                    $data['city'] = $add_info && $add_info->city_name ? $add_info->city_name : '';
                    $data['locality'] = $add_info && $add_info->locality ? $add_info->locality : '';
                    $data['latitude'] = $add_info ? $add_info->latitude : '';
                    $data['longitude'] = $add_info ? $add_info->longitude : '';
                }

                $data['payment_moad']   = $value->payment_moad;
                $data['payment_status'] = $value->payment_status;
                $data['note']           = $value->note;
                $data['material_charge']= (string) $value->material_charge;
                $data['material_status']= $value->material_status;
                $data['service_type']   = $value->service_type;
                $data['alternative_dial_code']  = $value->alternative_dial_code;
                $data['alternative_number'] = $value->alternative_number;
                $data['date']           = $value->date;
                $data['tip']            = (string) $value->tip_id;
                $data['coupon_id']      = $value->coupon_id;
                $data['is_checkout']    = $value->is_checkout;

                // Card Attributes and Add-ons
                $card_attr = [];
                foreach (CardAttribute::where('card_id', $value->id)->get() as $item) {
                    $params['sub_cate_id']          = (string) $item->sub_cate_id;
                    $params['sub_cate_name']        = $item->sub_cate ? $item->sub_cate->name : '';
                    $params['main_sub_cat_id']      = $item->main_sub_cat_id;
                    $params['main_sub_cat_name']    = $item->main_sub_cat ? $item->main_sub_cat->name : '';
                    $params['child_cate_id']        = (string) $item->child_cate_id;
                    $params['child_cate']           = $item->child_cate ? $item->child_cate->name : '';
                    $params['attribute_id']         = $item->attribute_id;
                    $params['attribute_name']       = $item->attribute_name;
                    $params['attribute_item_id']    = (string) $item->attribute_item_id;
                    $params['attribute_item_name']  = $item->attribute_item_name;
                    $params['attribute_qty']        = $item->attribute_qty;
                    $params['attribute_price']      = $item->attribute_price;
                    $params['service_type']         = $item->service_type;

                    // Add-ons
                    $addons = [];
                    foreach (CardAddon::where('card_id', $value->id)->where('card_attribute_id', $item->id)->get() as $cardaddon) {
                        $addon['card_id']               = $cardaddon->id;
                        $addon['card_attribute_id']     = $cardaddon->id;
                        $addon['add_on_id']             = $cardaddon->add_on_id;
                        $addon['name']                  = $cardaddon->name;
                        $addon['value']                 = $cardaddon->value;
                        $addon['percentage']            = $cardaddon->percentage;
                        array_push($addons, $addon);
                    }
                    $params['addon'] = $addons;
                    array_push($card_attr, $params);
                }

                $data['coupon']         = $value->coupon ? $value->coupon->code : '';
                $data['coupon_amt']     = $value->coupon_amt;
                $data['subtotal']       = $value->amount;
                $data['total']          = $value->g_total;
                $data['card_attribute'] = $card_attr;
                $data['is_bell'] = "0";

                $cards[] = $data;
            }

            // Return response with pagination info
            return $this->sendResponse($cards, 'All Bookings');
        } else {
            return $this->sendResponse([], 'No data found.');
        }
    }



    public function get_latest_booking(Request $request)
    {
        $card = Card::where('status','Pending')->where('payment_status' , 'Done')->where('is_checkout' , 'Done')->orderBy('id', 'DESC')->paginate(5);
        $cards = [];

        foreach ($card as $key => $value) {

            $service = Service::find($value->service_id);
            $user = User::find($value->user_id);
            $category = Category::find($value->category_id);

                $data['booking_id']     = $value->id;
                $data['tran_id']        = $value->tran_id;
                $data['user_id']        = $value->user_id;
                $data['user_name']      = $user?$user->name:'';
                $data['service_id']     = $value->service_id;
                $data['service']        = $service->name;
                $data['category_id']    = $value->category_id;
                $data['category']       = $category?$category->name:'';
                $data['slot_id']        = $value->slot_id;
                $data['slot']           = $value->slot?$value->slot->name:'';


                if(is_numeric($value->address_id)){

                    $data['address_id']     = $value->address_id;

                    $building = $value->address?$value->address->building:'';
                    $flat_no = $value->address?$value->address->flat_no:'';
                    $address = $value->address?$value->address->address:'';

                    $latitude = $value->address?$value->address->latitude:'';
                    $longitude = $value->address?$value->address->longitude:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;

                    if($value->address && $value->address->city){
                        $city = $value->address->city->name;
                    } else {
                        $city = "";
                    }

                    $data['city']           = $city;

                    if($value->address && $value->address->locality_info){
                        $locality = $value->address->locality_info->name;
                    } else {
                        $locality = "";
                    }
                    $data['locality']       = $locality;
                    
                    $data['latitude']       = $latitude;
                    $data['longitude']      = $longitude;

                } else {

                    $add_info = json_decode($value->address_id);

                    $data['address_id']     = '';


                    $building = $add_info?$add_info->building:'';
                    $flat_no = $add_info?$add_info->flat_no:'';
                    $address = $add_info?$add_info->address:'';

                    $latitude = $add_info?$add_info->latitude:'';
                    $longitude = $add_info?$add_info->longitude:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;

                    if($add_info && $add_info->city_name){
                        $city = $add_info->city_name;
                    } else {
                        $city = "";
                    }

                    $data['city']           = $city;

                    if($add_info && $add_info->locality){
                        $locality = $add_info->locality;
                    } else {
                        $locality = "";
                    }
                    $data['locality']       = $locality;
                    
                    $data['latitude']       = $latitude;
                    $data['longitude']      = $longitude;

                }


                $data['payment_moad']   = $value->payment_moad;
                $data['payment_status'] = $value->payment_status;
                $data['note']           = $value->note;
                $data['material_price'] = $value->material_price;
                $data['material_status']= $value->material_status;
                $data['alternative_dial_code']  = $value->alternative_dial_code;
                $data['alternative_number'] = $value->alternative_number;
                $data['date']           = $value->date;
                $data['tip']            = $value->tip_id;
                $data['coupon_id']      = $value->coupon_id;
                $data['coupon']         = $value->coupon?$value->coupon->code:'';
                $data['coupon_amt']     = $value->coupon_amt;
                $data['subtotal']       = $value->amount;
                $data['total']          = $value->g_total;

            $card_attr = [];

            foreach (CardAttribute::where('card_id',$value->id)->get() as $key => $item) {

                $sub_cat = Category::find($value->sub_cate_id);
                $child_cat = ChildCategory::find($value->child_cate_id);

                $params['sub_cate_id']          = $item->sub_cate_id;
                $params['sub_cate_name']        = $sub_cat?$sub_cat->name:'';
                $params['child_cate_id']        = $item->child_cate_id;
                $params['child_cate']           = $child_cat?$child_cat->name:'';
                $params['attribute_id']         = $item->attribute_id;
                $params['attribute_name']       = $item->attribute_name;
                $params['attribute_item_id']    = $item->attribute_item_id;
                $params['attribute_item_name']  = $item->attribute_item_name;
                $params['attribute_qty']        = $item->attribute_qty;
                $params['attribute_price']      = $item->attribute_price;

                array_push($card_attr,$params);
            }
            $data['card_attribute'] = $card_attr;
            array_push($cards, $data);
        }
        return $this->sendResponse($cards, 'Latest Bookings');
    }

    public function get_my_booking(Request $request)
    {
        $user_id = auth()->user()->id;
        
        // Default values for pagination
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $offset = ($page - 1) * $limit;

        // Fetch the cards with pagination
        $cardsQuery = Card::where(['user_id' => $user_id, 'is_checkout' => 'Done'])
                        ->offset($offset)
                        ->limit($limit)
                        ->orderBy('id', 'DESC')
                        ->get();
        
        $cards = [];

        foreach ($cardsQuery as $key => $value) {

            $service = Service::find($value->service_id);
            $user = User::find($value->user_id);
            $provider_address   = Address::where('user_id', $value->accept_user_id)->latest()->first();
            $user_address       = Address::where('user_id', $user->id)->latest()->first();

            $data['user_latitude'] = (string) ($user_address->latitude ?? '');
            $data['user_longitude'] = (string) ($user_address->longitude ?? '');
            $data['provider_latitude'] = (string) ($provider_address->latitude ?? '');
            $data['provider_longitude'] = (string) ($provider_address->longitude ?? '');
    

            $category = Category::find($value->category_id);

            $data['id']             = (string) $value->id;
            if(isset($value->tabby_payment_response_id)){
                $data['tran_id']    = $value->tabby_payment_response_id;
            } elseif (isset($value->paymentTranId)) {
                $data['tran_id']    = $value->paymentTranId;
            } else {
                $input = [
                    'customer_id' => $value->user_id,
                    'vendor_id' => Seller::where("user_id", $value->accept_user_id)->first()->seller_id ?? null
                ];
                
                $transection = Transection::where($input)->first();
                $data['tran_id'] = $transection ? $transection->id : $value->tran_id;                
            }
            
            $data['user_id']        = (string) $value->user_id;
            $data['user_name']      = $user ? $user->name : '';
            $data['user_email']     = $user ? $user->email : '';
            $data['user_mobile']    = $user ? $user->phone : '';
            $data['service_id']     = (string) $value->service_id;
            $data['accept_user_id'] = $value->accept_user_id;
            $data['service']        = $service->name;
            $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
            $data['category_id']    = $value->category_id;
            $data['category']       = $category ? $category->name : '';
            $data['slot_id']        = $value->slot_id;
            $data['slot']           = $value->slot ? $value->slot->name : '';
            $data['offline_charge'] = $value->offline_charge;
            $data['offline_discount'] = $value->offline_discount;
            
            // Other details (same as before)
            $data['payment_moad']   = $value->payment_moad;
            $data['payment_status'] = $value->payment_status;
            $data['note']           = $value->note;
            $data['material_charge']= (string) $value->material_charge;
            $data['material_status']= $value->material_status;
            $data['service_type']   = $value->service_type;
            $data['alternative_dial_code']  = $value->alternative_dial_code;
            $data['alternative_number'] = $value->alternative_number;
            $data['date']           = $value->date;
            $data['tip']            = $value->tip_id;
            $data['coupon_id']      = $value->coupon_id;
            $data['is_checkout']    = $value->is_checkout;
            $data['status']         = $value->status;
            $data['sub_total']       = $value->g_total;
            $data['amount_total']    = $value->amount;
            $data['pending_approval_by_admin'] = $value->pending_approval_by_admin;

            // Card attributes logic (same as before)
            $card_attr = [];
            foreach (CardAttribute::where('card_id', $value->id)->get() as $item) {
                $sub_cat = Category::find($item->sub_cate_id);
                $child_cat = ChildCategory::find($item->child_cate_id);
    
                $params['sub_cate_id']          = $item->sub_cate_id;
                $params['sub_cate_name']        = $sub_cat ? $sub_cat->name : '';
                $params['main_sub_cat_id']      = $item->main_sub_cat_id;
                $params['main_sub_cat_name']    = $item->main_sub_cat ? $item->main_sub_cat->name : '';
                $params['child_cate_id']        = $item->child_cate_id;
                $params['child_cate']           = $child_cat ? $child_cat->name : '';
                $params['attribute_id']         = $item->attribute_id;
                $params['attribute_name']       = $item->attribute_name;
                $params['attribute_item_id']    = $item->attribute_item_id;
                $params['attribute_item_name']  = $item->attribute_item_name;
                $params['attribute_qty']        = $item->attribute_qty;
                $params['attribute_price']      = $item->attribute_price;
                $params['service_type']         = $item->service_type;
    
                $addons = [];
                foreach (CardAddon::where('card_id', $value->id)->where('card_attribute_id', $item->id)->get() as $cardaddon) {
                    $addon['card_id']               = $cardaddon->id;
                    $addon['card_attribute_id']     = $cardaddon->id;
                    $addon['add_on_id']             = $cardaddon->add_on_id;
                    $addon['name']                  = $cardaddon->name;
                    $addon['value']                 = $cardaddon->value;
                    $addon['percentage']            = $cardaddon->percentage;
                    $addons[] = $addon;
                }
                $params['addon'] = $addons;
                $card_attr[] = $params;
            }
            $data['card_attribute'] = $card_attr;
            
            array_push($cards, $data);
        }

        // Get the total count for pagination
        $totalCount = Card::where('user_id', $user_id)->count();

        // Prepare pagination info
        $pagination = [
            'total' => $totalCount,
            'per_page' => $limit,
            'current_page' => $page,
            'last_page' => ceil($totalCount / $limit)
        ];

        return $this->sendResponse(['cards' => $cards, 'pagination' => $pagination], 'My Jobs');
    }

    public function get_my_job(Request $request)
    {
        $limit = $request->get('limit', 10); 
        $page = $request->get('page', 1); 
    
        // Fetch cards for the current user with pagination
        $card = Card::where('accept_user_id', auth()->user()->id)
                    ->where('status', '!=', 'Canceled')
                    ->where('status', '!=', 'Completed')
                    ->paginate($limit, ['*'], 'page', $page); 
    
        $cards = [];
    
        foreach ($card as $value) {
            $service = Service::find($value->service_id);
            $user = User::find($value->user_id);
            $category = Category::find($value->category_id);
    
            $data = [];  // Initialize $data to avoid leftovers from previous iterations
            $data['booking_id']     = (string) $value->id;
            $data['tran_id']        = $value->tabby_payment_response_id ?? $value->paymentTranId ?? '';
            $data['user_id']        = (string) $value->user_id;
            $data['user_name']      = $user ? $user->name : '';
            $data['user_email']     = $user ? $user->email : '';
            $data['user_mobile']    = $user ? $user->phone : '';
            $data['service_id']     = $value->service_id;
            $data['accept_user_id'] = $value->accept_user_id;
            $data['service']        = $service ? $service->name : '';
            $data['image']          = \URL::to('/') . '/uploads/service/' . ($service->thumbnail_img ?? '');
            $data['category_id']    = $value->category_id;
            $data['category']       = $category ? $category->name : '';
            $data['slot_id']        = $value->slot_id;
            $data['slot']           = $value->slot ? $value->slot->name : '';
            $data['offline_charge'] = $value->offline_charge;
            $data['offline_discount'] = $value->offline_discount;
    
            // Address handling
            if (is_numeric($value->address_id)) {
                $data['address_id']     = $value->address_id;
                $building = $value->address ? $value->address->building : '';
                $flat_no = $value->address ? $value->address->flat_no : '';
                $address = $value->address ? $value->address->address : '';
                $data['address']        = "$building, $flat_no, $address";
                $data['city']           = $value->address->city->name ?? '';
                $data['locality']       = $value->address->locality_info->name ?? '';
                $data['latitude']       = $value->address->latitude ?? '';
                $data['longitude']      = $value->address->longitude ?? '';
            } else {
                $add_info = json_decode($value->address_id);
                $building = $add_info ? $add_info->building : '';
                $flat_no = $add_info ? $add_info->flat_no : '';
                $address = $add_info ? $add_info->address : '';
                $data['address']        = "$building, $flat_no, $address";
                $data['city']           = $add_info->city_name ?? '';
                $data['locality']       = $add_info->locality ?? '';
                $data['latitude']       = $add_info->latitude ?? '';
                $data['longitude']      = $add_info->longitude ?? '';
            }
    
            $data['payment_moad']   = $value->payment_moad;
            $data['payment_status'] = $value->payment_status;
            $data['note']           = $value->note;
            $data['material_charge']= $value->material_charge;
            $data['material_status']= $value->material_status;
            $data['service_type']   = $value->service_type;
            $data['alternative_dial_code']  = $value->alternative_dial_code;
            $data['alternative_number'] = $value->alternative_number;
            $data['date']           = $value->date;
            $data['tip']            = (string) $value->tip_id;
            $data['coupon_id']      = $value->coupon_id;
            $data['is_checkout']    = $value->is_checkout;
            $data['status']         = $value->status;
            $data['pending_approval_by_admin'] = $value->pending_approval_by_admin;
            $data['is_bell'] = "0";
    
            // Card attributes and addons
            $card_attr = [];
            foreach (CardAttribute::where('card_id', $value->id)->get() as $item) {
                $sub_cat = Category::find($item->sub_cate_id);
                $child_cat = ChildCategory::find($item->child_cate_id);
    
                $params = [];
                $params['sub_cate_id']          = $item->sub_cate_id;
                $params['sub_cate_name']        = $sub_cat ? $sub_cat->name : '';
                $params['main_sub_cat_id']      = $item->main_sub_cat_id;
                $params['main_sub_cat_name']    = $item->main_sub_cat ? $item->main_sub_cat->name : '';
                $params['child_cate_id']        = $item->child_cate_id;
                $params['child_cate']           = $child_cat ? $child_cat->name : '';
                $params['attribute_id']         = $item->attribute_id;
                $params['attribute_name']       = $item->attribute_name;
                $params['attribute_item_id']    = $item->attribute_item_id;
                $params['attribute_item_name']  = $item->attribute_item_name;
                $params['attribute_qty']        = $item->attribute_qty;
                $params['attribute_price']      = $item->attribute_price;
                $params['service_type']         = $item->service_type;
    
                $addons = [];
                foreach (CardAddon::where('card_id', $value->id)->where('card_attribute_id', $item->id)->get() as $cardaddon) {
                    $addon = [];
                    $addon['card_id']               = $cardaddon->id;
                    $addon['card_attribute_id']     = $cardaddon->id;
                    $addon['add_on_id']             = $cardaddon->add_on_id;
                    $addon['name']                  = $cardaddon->name;
                    $addon['value']                 = $cardaddon->value;
                    $addon['percentage']            = $cardaddon->percentage;
                    $addons[] = $addon;
                }
                $params['addon'] = $addons;
                $card_attr[] = $params;
            }
    
            $data['coupon']         = $value->coupon ? $value->coupon->code : '';
            $data['coupon_amt']     = $value->coupon_amt;
            $data['subtotal']       = $value->amount;
            $data['total']          = $value->g_total;
            $data['card_attribute'] = $card_attr;
    
            $cards[] = $data;
        }
    
        // Return response with pagination metadata
        return $this->sendResponse($cards, 'My Jobs');
    }    
    
    public function get_active_booking(Request $request)
    {
        $card = Card::where('accept_user_id',auth()->user()->id)->where('status', 'Accept')->orderBy('id', 'DESC')->get();
  
        $cards = [];

        foreach ($card as $key => $value) {

            $service = Service::find($value->service_id);
            $user = User::find($value->user_id);
            $category = Category::find($value->category_id);

            $data['booking_id']     = (string) $value->id;
            if(isset($value->tabby_payment_response_id)){
                $data['tran_id']    = $value->tabby_payment_response_id;
            } elseif (isset($value->paymentTranId)) {
                $data['tran_id']    = $value->paymentTranId;
            } else {
                $data['tran_id']    = '';
            }

            $data['user_id']        = (string) $value->user_id;
            $data['user_name']      = $user?$user->name:'';
            $data['user_email']     = $user?$user->email:'';
            $data['user_mobile']    = $user?$user->phone:'';
            $data['service_id']     = $value->service_id;
            $data['service']        = $service->name;
            $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
            $data['category_id']    = $value->category_id;
            $data['category']       = $category?$category->name:'';
            $data['slot_id']        = $value->slot_id;
            $data['slot']           = $value->slot?$value->slot->name:'';
            $data['offline_charge'] = $value->offline_charge;
            $data['offline_discount'] = $value->offline_discount;
            if(is_numeric($value->address_id)){

                $data['address_id']     = $value->address_id;

                $building = $value->address?$value->address->building:'';
                $flat_no = $value->address?$value->address->flat_no:'';
                $address = $value->address?$value->address->address:'';

                $latitude = $value->address?$value->address->latitude:'';
                $longitude = $value->address?$value->address->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($value->address && $value->address->city){
                    $city = $value->address->city->name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($value->address && $value->address->locality_info){
                    $locality = $value->address->locality_info->name;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            } else {

                $add_info = json_decode($value->address_id);

                $data['address_id']     = '';


                $building = $add_info?$add_info->building:'';
                $flat_no = $add_info?$add_info->flat_no:'';
                $address = $add_info?$add_info->address:'';

                $latitude = $add_info?$add_info->latitude:'';
                $longitude = $add_info?$add_info->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($add_info && $add_info->city_name){
                    $city = $add_info->city_name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($add_info && $add_info->locality){
                    $locality = $add_info->locality;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            }
            $data['payment_moad']   = $value->payment_moad;
            $data['payment_status'] = $value->payment_status;
            $data['note']           = $value->note;
            $data['material_charge']= $value->material_charge;
            $data['material_status']= $value->material_status;
            $data['service_type']   = $value->service_type;
            $data['alternative_dial_code']  = $value->alternative_dial_code;
            $data['alternative_number'] = $value->alternative_number;
            $data['date']           = $value->date;
            $data['tip']            = $value->tip_id;
            $data['coupon_id']      = $value->coupon_id;
            $data['is_checkout']    = $value->is_checkout;
            $data['status']         = $value->status;
            $data['pending_approval_by_admin']         = $value->pending_approval_by_admin;
            
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
           
            array_push($cards, $data);
        }
        return $this->sendResponse($cards, 'Active Bookings');
    }

    public function confirm_details(Request $request)
    {
        if($request->cart_id){

            $data = [];
            $totalAmount = 0;
            $cart = Card::find($request->cart_id);
            $service = Service::find($cart->service_id);
            $material_total = ($cart->material_status == "Apply") ? (int) $cart->material_charge : 0;
            $material = 0;
            if (CardAttribute::where('card_id', $cart->id)->exists()) {
                $data["service_name"] = $service->name;
                foreach (CardAttribute::where('card_id', $cart->id)->get() as $value) {
                   
                    $service = ServiceAttributeValue::where('attribute_item_id',$value->attribute_item_id)->first();
                    $service_item = ServiceAttributeValueItem::where('id',$service->ser_attr_val_item_id)->first();
                    $attribute = Attribute::where('id',$service->attribute_id)->first();
                    $attribute_item = AttributeValue::where('id',$value->attribute_item_id)->first();
                    
                    $data["attribute"][] = ["name"=>$attribute_item->value,"price"=>$service->attribute_price];
                    
                } 
                
            }
            $data["sub_total"] = (string) $cart->g_total;
            
            // if(!empty($cart->selected_packages)){                
            //     $selectedPackageIds = explode(',', $cart->selected_packages);

            //     $packages = Packages::whereIn('id', $selectedPackageIds)->get();

            //     foreach ($packages as $package) {
            //         $data["packages"][] = ["name"=>$package->name, "price"=>(string)$package->amount]; 
            //         $totalAmount += (int) $package->amount; 
            //     }
            // }
            $data["material_status"] = (string) $cart->material_status;
            $data["material"] = (string) $cart->material_charge;
            $data["date"] = $cart->date ?? "";
            $data["time"] = ($cart->slot_id) ? Slot::find($cart->slot_id)->name : "";
            $data["total_amount"] = (string) $cart->amount;
            $data["user"] = auth()->user();
            $data["address"] = optional(Address::where(['user_id' => auth()->user()->id, 'is_active' => 1])->first())->address ?? "";

            return $this->sendResponse($data, 'Edit Section!');
        } else {
            return $this->sendResponse([], 'Missing Fields Required!');
        }
    }

    public function get_completed_booking(Request $request)
    {
        $card = Card::where('accept_user_id',auth()->user()->id)->where('status', 'Completed')->orderBy('id', 'DESC')->get();
  
        $cards = [];

        foreach ($card as $key => $value) {

            $service = Service::find($value->service_id);
            $user = User::find($value->user_id);
            $category = Category::find($value->category_id);

            $data['id']             = $value->id;
            $data['booking_id']     = $value->tran_id;
            if(isset($value->tabby_payment_response_id)){
                $data['tran_id']    = $value->tabby_payment_response_id;
            } elseif (isset($value->paymentTranId)) {
                $data['tran_id']    = $value->paymentTranId;
            } else {
                $data['tran_id']    = '';
            }

            $data['user_id']        = $value->user_id;
            $data['user_name']      = $user?$user->name:'';
            $data['user_email']     = $user?$user->email:'';
            $data['user_mobile']    = $user?$user->phone:'';
            $data['service_id']     = $value->service_id;
            $data['service']        = $service->name;
            $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
            $data['category_id']    = $value->category_id;
            $data['category']       = $category?$category->name:'';
            $data['slot_id']        = $value->slot_id;
            $data['slot']           = $value->slot?$value->slot->name:'';
            $data['offline_charge'] = $value->offline_charge;
            $data['offline_discount'] = $value->offline_discount;
            if(is_numeric($value->address_id)){

                $data['address_id']     = $value->address_id;

                $building = $value->address?$value->address->building:'';
                $flat_no = $value->address?$value->address->flat_no:'';
                $address = $value->address?$value->address->address:'';

                $latitude = $value->address?$value->address->latitude:'';
                $longitude = $value->address?$value->address->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($value->address && $value->address->city){
                    $city = $value->address->city->name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($value->address && $value->address->locality_info){
                    $locality = $value->address->locality_info->name;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            } else {

                $add_info = json_decode($value->address_id);

                $data['address_id']     = '';


                $building = $add_info?$add_info->building:'';
                $flat_no = $add_info?$add_info->flat_no:'';
                $address = $add_info?$add_info->address:'';

                $latitude = $add_info?$add_info->latitude:'';
                $longitude = $add_info?$add_info->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($add_info && $add_info->city_name){
                    $city = $add_info->city_name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($add_info && $add_info->locality){
                    $locality = $add_info->locality;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            }
            $data['payment_moad']   = $value->payment_moad;
            $data['payment_status'] = $value->payment_status;
            $data['note']           = $value->note;
            $data['material_charge']= $value->material_charge;
            $data['material_status']= $value->material_status;
            $data['service_type']   = $value->service_type;
            $data['alternative_dial_code']  = $value->alternative_dial_code;
            $data['alternative_number'] = $value->alternative_number;
            $data['date']           = $value->date;
            $data['tip']            = $value->tip_id;
            $data['coupon_id']      = $value->coupon_id;
            $data['is_checkout']    = $value->is_checkout;
            $data['status']         = $value->status;
            $data['pending_approval_by_admin']         = $value->pending_approval_by_admin;
            
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
           
            array_push($cards, $data);
        }
        return $this->sendResponse($cards, 'Completed Bookings');
    }

    public function get_user_bookings(Request $request)
    {
        $card = Card::where('accept_user_id',$request->vendor_id)->orderBy('id', 'DESC')->get();
  
        $cards = [];

        foreach ($card as $key => $value) {

            $service = Service::find($value->service_id);
            $user = User::find($value->user_id);
            $category = Category::find($value->category_id);

            $data['id']             = $value->id;
            $data['booking_id']     = $value->tran_id;
            if(isset($value->tabby_payment_response_id)){
                $data['tran_id']    = $value->tabby_payment_response_id;
            } elseif (isset($value->paymentTranId)) {
                $data['tran_id']    = $value->paymentTranId;
            } else {
                $data['tran_id']    = '';
            }

            $data['user_id']        = $value->user_id;
            $data['user_name']      = $user?$user->name:'';
            $data['user_email']     = $user?$user->email:'';
            $data['user_mobile']    = $user?$user->phone:'';
            $data['service_id']     = $value->service_id;
            $data['service']        = $service->name;
            $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
            $data['category_id']    = $value->category_id;
            $data['category']       = $category?$category->name:'';
            $data['slot_id']        = $value->slot_id;
            $data['slot']           = $value->slot?$value->slot->name:'';
            $data['offline_charge'] = $value->offline_charge;
            $data['offline_discount'] = $value->offline_discount;
            if(is_numeric($value->address_id)){

                $data['address_id']     = $value->address_id;

                $building = $value->address?$value->address->building:'';
                $flat_no = $value->address?$value->address->flat_no:'';
                $address = $value->address?$value->address->address:'';

                $latitude = $value->address?$value->address->latitude:'';
                $longitude = $value->address?$value->address->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($value->address && $value->address->city){
                    $city = $value->address->city->name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($value->address && $value->address->locality_info){
                    $locality = $value->address->locality_info->name;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            } else {

                $add_info = json_decode($value->address_id);

                $data['address_id']     = '';


                $building = $add_info?$add_info->building:'';
                $flat_no = $add_info?$add_info->flat_no:'';
                $address = $add_info?$add_info->address:'';

                $latitude = $add_info?$add_info->latitude:'';
                $longitude = $add_info?$add_info->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($add_info && $add_info->city_name){
                    $city = $add_info->city_name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($add_info && $add_info->locality){
                    $locality = $add_info->locality;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            }
            $data['payment_moad']   = $value->payment_moad;
            $data['payment_status'] = $value->payment_status;
            $data['note']           = $value->note;
            $data['material_charge']= $value->material_charge;
            $data['material_status']= $value->material_status;
            $data['service_type']   = $value->service_type;
            $data['alternative_dial_code']  = $value->alternative_dial_code;
            $data['alternative_number'] = $value->alternative_number;
            $data['date']           = $value->date;
            $data['tip']            = $value->tip_id;
            $data['coupon_id']      = $value->coupon_id;
            $data['is_checkout']    = $value->is_checkout;
            $data['status']         = $value->status;
            $data['pending_approval_by_admin']         = $value->pending_approval_by_admin;
            
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
           
            array_push($cards, $data);
        }
        return $this->sendResponse($cards, 'My Bookings');
    }

    public function delete_bookings_by_user(Request $request)
    {
        $card = Card::find($request->card_id);
        if($card) {
            $card->delete();
            return $this->sendResponse([], 'Delete item successfully!');
        }
        return $this->sendError('Item already deleted!');
    }

    public function get_bookings_by_user(Request $request)
    {
        $card = Card::where(['user_id'=>auth()->user()->id])
        ->where('is_checkout', '!=', 'Done')
        ->orderBy('id', 'DESC')
        ->get();
     
        $cards = [];
        $total_amount = 0;
        $coupon_amount = 0;
        foreach ($card as $key => $value) {

            $service = Service::find($value->service_id);

            if(ServiceAttributeValueItem::where('service_id',$value->service_id)->with('sub_category')->first()->sub_category){
                $data['sub_cate_yes'] = 'Yes';
            } else {
                $data['sub_cate_yes'] = 'No';
            }

            $user = User::find($value->user_id);
            $category = Category::find($value->category_id);

            $data['id']             = (string) $value->id;
            if(isset($value->tabby_payment_response_id)){
                $data['tran_id']    = $value->tabby_payment_response_id;
            } elseif (isset($value->paymentTranId)) {
                $data['tran_id']    = $value->paymentTranId;
            } else {
                $data['tran_id']    = '';
            }

            $data['user_id']        = (string) $value->user_id;
            $data['user_name']      = $user?$user->name:'';
            $data['user_email']     = $user?$user->email:'';
            $data['user_mobile']    = $user?$user->phone:'';
            $data['service_id']     = (string) $value->service_id;
            $data['service']        = $service->name;
            $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
            $data['category_id']    = (string) $value->category_id;
            $data['category']       = $category?$category->name:'';
            $data['slot_id']        = $value->slot_id ?? "";
            $data['slot']           = $value->slot?$value->slot->name:'';
            $data['offline_charge'] = $value->offline_charge ?? "";
            $data['offline_discount'] = $value->offline_discount ?? "";
            if(is_numeric($value->address_id)){

                $data['address_id']     = $value->address_id;

                $building = $value->address?$value->address->building:'';
                $flat_no = $value->address?$value->address->flat_no:'';
                $address = $value->address?$value->address->address:'';

                $latitude = $value->address?$value->address->latitude:'';
                $longitude = $value->address?$value->address->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($value->address && $value->address->city){
                    $city = $value->address->city->name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($value->address && $value->address->locality_info){
                    $locality = $value->address->locality_info->name;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            } else {

                $add_info = json_decode($value->address_id);

                $data['address_id']     = '';


                $building = $add_info?$add_info->building:'';
                $flat_no = $add_info?$add_info->flat_no:'';
                $address = $add_info?$add_info->address:'';

                $latitude = $add_info?$add_info->latitude:'';
                $longitude = $add_info?$add_info->longitude:'';
                $data['address']        = $building.', '. $flat_no.', '.$address;

                if($add_info && $add_info->city_name){
                    $city = $add_info->city_name;
                } else {
                    $city = "";
                }

                $data['city']           = $city;

                if($add_info && $add_info->locality){
                    $locality = $add_info->locality;
                } else {
                    $locality = "";
                }
                $data['locality']       = $locality;
                
                $data['latitude']       = $latitude;
                $data['longitude']      = $longitude;

            }
            $coupon_amount = $value->coupon_amt;
            $data['payment_moad']   = $value->payment_moad;
            $data['payment_status'] = $value->payment_status;
            $data['note']           = $value->note ?? "";
            $data['material_charge']= $value->material_charge;
            $data['material_status']= $value->material_status;
            $data['service_type']   = $value->service_type;
            $data['alternative_dial_code'] = $value->alternative_dial_code ?? "";
            $data['alternative_number'] = $value->alternative_number ?? "";
            $data['date']           = $value->date ?? "";
            $data['tip']            = $value->tip_id ?? "";
            $data['coupon_id']      = $value->coupon_id ?? "";
            $data['is_checkout']    = $value->is_checkout;
            $data['status']         = $value->status;
            $data['package_ids']    = $value->selected_packages ?? "";
            $data['pending_approval_by_admin'] = $value->pending_approval_by_admin;
            $data['selected_packages'] = [];
            // if($value->selected_packages != null){

            //     $packagesArray = explode(', ', $value->selected_packages);

            //     foreach ($packagesArray as $package_id) {
            //         $package = Packages::find($package_id)->toArray();
    
            //         $data['selected_packages'][] = $package['name'];
            //     }
            // }
            $data['preffered_days'] = $value->preffered_days;
            
            $card_attr = [];
            $sub_total = '00';
            $subtotal = '00';
            $total = '00';
            foreach (CardAttribute::where('card_id',$value->id)->get() as $key => $item) {

                $sub_cat = Category::find($item->sub_cate_id);
                $child_cat = ChildCategory::find($item->child_cate_id);

                $params['sub_cate_id']          = (string) $item->sub_cate_id;
                $params['sub_cate_name']        = $child_cat?$child_cat->name:'';
                $params['main_sub_cat_id']      = (string) $item->main_sub_cat_id;
                $params['main_sub_cat_name']    = $item->main_sub_cat?$item->main_sub_cat->name:'';
                $params['child_cate_id']        = (string) $item->child_cate_id;
                $params['child_cate']           = $item->child_cate?$item->child_cate->name:'';
                $params['attribute_id']         = (string) $item->attribute_id;
                $params['attribute_name']       = $item->attribute_name;
                $params['attribute_item_id']    = (string) $item->attribute_item_id;
                $params['attribute_item_name']  = $item->attribute_item_name;
                $params['attribute_qty']        = (string) $item->attribute_qty;
                $params['attribute_price']      = (string) $item->attribute_price;
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
            $data['subtotal']       = $value->g_total ?? "";
            $data['coupon_amt']     = $value->coupon_amt ?? "";
            $data['total']          = (string)((int)$value->amount-(int)$value->coupon_amt) ?? "";
            $data['card_attribute'] = $card_attr;
        
            if(empty($value->address_id)){
                $data['is_draft']      = "yes";
                $data['step']          = "2";
            }else if(empty($value->slot_id)){
                $data['is_draft']      = "yes";
                $data['step']          = "3";
            } else{
                $data['is_draft']      = "no";
                $data['step']          = "0";
            }
            
            $total_amount += (int) $value->amount;
            $cards['card'][] = $data;
        }
        
        $cards['total_amount']  = $total_amount - (int) $coupon_amount;
        return $this->sendResponse($cards, 'My Bookings');
    }

    public function accept_booking(Request $request)
    {
        $card = Card::find($request->booking_id);
        $user = User::find(auth()->user()->id);
        $seller = Seller::where('user_id',$user->id)->first();
     
        if($user && $seller){
            if($card){
                
                if($card->status == "Accept"){
                    return $this->sendError('This Bookings Is Already Accepted');
                } else {
                    $data['accept_user_id']         = $user->id;
                    $data['accept_user_company_id'] = $seller?$seller->id:'';
                    $data['status']                 = 'Accept';
                    $card->update($data);

                    if($card->user && $card->user->email){
                       
                        $array['view']      = 'emails.booking_confirm_customer';
                        $array['subject']   = 'Your booking has been accepted!';
                        $array['data']      = $card;
                        \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));

                        $user = User::find($card->user_id);

                        if ($user) {
                            $device_tokens = $user->user_device_token;
                        
                            if (!empty($device_tokens) && $device_tokens != '0') {
                                if (strlen($device_tokens) > 70) {
                                    // Logic when token length exceeds 70
                                    $title  = "#" . $card->id;
                                    $body   = "Your booking has been accepted!";
                                    $text   = "Your booking has been accepted!";
            
//                                    $this->firebaseService->sendNotification($device_tokens, $title, $body, $text);
                                    if (!empty($firebase_user_key)) {
                                        $notification_data["Notifications/" . $firebase_user_key . "/" . time()] = [
                                            "title" => $title,
                                            "description" => $body,
                                            "notificationType" => 'accept_booking',
                                            "createdAt" => Carbon::now()->setTimezone('GMT')->format('Y-m-d h:i:s'),
                                            "cardId" => (string)$card->id,
                                            "status" => "1",
                                            "url" => "",
                                            "imageURL" => '',
                                            "read" => "0",
                                            "seen" => "0",
                                        ];
                                        $this->database->getReference()->update($notification_data);

                                        send_single_notification(
                                            $device_tokens,
                                            [
                                                "title" => $title,
                                                "body" => $body,
                                                "icon" => 'myicon',
                                                "sound" => 'default',
                                                "click_action" => "EcomNotification",
                                            ],
                                            [
                                                "type" => 'accept_booking',
                                                "notificationID" => time(),
                                                "status" => "1",
                                                "imageURL" => "",
                                                "cardId" => (string)$card->id,
                                            ]
                                        );
                                    }
                                }
                            }
                        }
            
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
                }                

                return $this->sendResponse([], 'Booking Accepted');
            } else {
                return $this->sendError('Invalid booking id');
            }
        } else {
            return $this->sendError('Vendor not found.');
        }
    }

    public function mark_arrived(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card){
            $data['service_start_datetime'] = date('Y-m-d H:i:s');
            $data['status']         = 'Mark As Arrived';
            $card->update($data);
            return $this->sendResponse([], 'Mark As Arrived');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function booking_started(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card){
            // $data['accept_user_id'] = $request->user_id;
            $data['status']         = 'In Progress';
            $card->update($data);

            $user = User::find($card->user_id);

            if ($user) {
                $device_tokens = $user->user_device_token;
            
                if (!empty($device_tokens) && $device_tokens != '0') {
                    if (strlen($device_tokens) > 70) {
                        // Logic when token length exceeds 70
                        $title  = "#" . $card->id;
                        $body   = "Your booking has been started!";
                        $text   = "Your booking has been started!";

//                        $this->firebaseService->sendNotification($device_tokens, $title, $body, $text);
                        if (!empty($firebase_user_key)) {
                            $notification_data["Notifications/" . $firebase_user_key . "/" . time()] = [
                                "title" => $title,
                                "description" => $body,
                                "notificationType" => 'booking_started',
                                "createdAt" => Carbon::now()->setTimezone('GMT')->format('Y-m-d h:i:s'),
                                "cardId" => (string)$card->id,
                                "status" => "1",
                                "url" => "",
                                "imageURL" => '',
                                "read" => "0",
                                "seen" => "0",
                            ];
                            $this->database->getReference()->update($notification_data);

                            send_single_notification(
                                $device_tokens,
                                [
                                    "title" => $title,
                                    "body" => $body,
                                    "icon" => 'myicon',
                                    "sound" => 'default',
                                    "click_action" => "EcomNotification",
                                ],
                                [
                                    "type" => 'booking_started',
                                    "notificationID" => time(),
                                    "status" => "1",
                                    "imageURL" => "",
                                    "cardId" => (string)$card->id,
                                ]
                            );
                        }
                    }
                }
            }
            return $this->sendResponse([], 'Booking Stated');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function payment_collected(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card){
            $data['payment_collected']  = 'Yes';
            $card->update($data);
            return $this->sendResponse([], 'Payment Collected');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    // Final By Yash sir, Tahir, Moiz, Mohit 04/01/2024
    public function oold_service_completed(Request $request)
    {
        $card = Card::find($request->booking_id);
        
        if($card){
            $data['status']                     = 'Completed';
            $data['service_completed']          = 'Yes';
            $data['payment_collected']          = 'Yes';
            $data['service_completed_date']     = date('Y-m-d H:i:s');
            $card->update($data);

            $card       = Card::find($request->booking_id);
            $setting    = HomeSetting::first();

            $service    = Service::find($card->service_id);

            $um_commission      = $service?$service->um_commission:'0';
            $bank_percentage    = $setting?$setting->bank_percentage:'0';
            $bank_charges       = $setting?$setting->bank_charges:'0';
            
            $total = '0';
            foreach (CardAttribute::where('card_id',$card->id)->get() as $key => $crdatr) {
                $ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
                $total += $ttotal;
            }
            
            foreach (CardAddon::where('card_id',$card->id)->get() as $key => $addon) {
                $attotal = $addon->value;
                $total += $attotal;
            }

            if($card->material_status=='Apply'){
                $total += $card->material_charge;
            }

            // $params['job_value']  = $total;
            
            $total = $card->g_total;
            if($card->payment_moad=='Cash'){
                
                $params['job_value']  = $card->g_total;

                // Step 1
                $jobValue = $card->g_total;
                // $jobValue = $total;

                // Step 2
                if($card && $card->coupon_id){
                    $coupon = CardCoupon::where('card_id',$card->id)->first();
                      if($coupon){
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
                    $params['coupon_amt'] = $coupon_Amt;
                } else {
                    $coupon_Amt = '00';
                }

                // Step 3
                $total += $card?$card->cod_charge:'0';

                // Step 4
                $total += $card?$card->tip_id:'0';

                $online_k_liye = $total;

                // Step 5
                // $um_com_amt = ($um_commission / 100) * $total;
                // $total -= round($um_com_amt,2);
                // $cal_per     = 100 - $um_commission;
                // $um_commission = $cal_per;
                // $um_com_amt  = ($cal_per / 100) * $jobValue;

                // $ftotal      = round($um_com_amt,2);

                $um_com_amt = ($jobValue * $um_commission) / 100;
                $ftotal = round($um_com_amt,2);

                // $ftotal -= $coupon_Amt;

                // Step 6
                $bnk_f_amt   = '0';
                 
                // Step 7 
             
                $net_ear = $total - $ftotal;

            } else {

                $total = '0';
                foreach (CardAttribute::where('card_id',$card->id)->get() as $key => $crdatr) {
                    $ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
                    $total += $ttotal;
                }
                
                foreach (CardAddon::where('card_id',$card->id)->get() as $key => $addon) {
                    $attotal = $addon->value;
                    $total += $attotal;
                }

                if($card->material_status=='Apply'){
                    $total += $card->material_charge;
                }
               
                // Step 1
                $jobValue = $total;

                $params['job_value']  = $jobValue;

                // Step 2
                if($card && $card->coupon_id){
                    $coupon = CardCoupon::where('card_id',$card->id)->first();
                      if($coupon){
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
                    // $total -= $coupon_Amt;
                    $params['coupon_amt'] = $coupon_Amt;
                } else {
                    $coupon_Amt = '00';
                }

                // Step 3
                $total += $card?$card->tip_id:'0';

                // Step 4
                $total -= $coupon_Amt;
                $online_k_liye = $total;

                // Step 5
                // $ftotal      = '0';
                // $cal_per     = 100 - $um_commission;
                // $um_commission = $cal_per;
                // $um_com_amt  = ($cal_per / 100) * $jobValue;
                // $ftotal      = round($um_com_amt,2);
                              
                $um_com_amt = ($jobValue * $um_commission) / 100;
                $ftotal = round($um_com_amt,2);

                // Step 6
                // $bnk_f_amt   = $total * 100 / ($bank_percentage + 100);
                // $f_b_a       = $total - $bnk_f_amt;
                // $_f_bnk_amt  = round($f_b_a,2)+$bank_charges;                

                $bnk_f_amt   = $total * $bank_percentage / 100;
                // $f_b_a       = $total - $bnk_f_amt;
                $_f_bnk_amt  = round($bnk_f_amt,2)+$bank_charges;

                // Step 7       
                $net_ear    = $total - ($ftotal + $_f_bnk_amt);
                 
            }

            $params['card_id']          = $card->id;
            $params['card_total']       = $jobValue;
            
            $params['cash_surcharge']   = $card?$card->cod_charge:'0';
            $params['tip']              = $card?$card->tip_id:'0';
            $params['um_comission']     = $um_commission;
            $params['um_com_amt']       = $ftotal;

            if($card->payment_moad=='Card'){
                $params['bank_fees']        = $bank_percentage;
                $params['bank_fees_amt']    = $_f_bnk_amt;
            } else {
                $params['bank_fees']        = '0';
                $params['bank_fees_amt']    = '0';
            }

            $params['payment_moad']     = $card->payment_moad=='Cash'?'Cash':'Prepaid';
            $params['net_balance']      = $net_ear;                
           
            $vendor_id = $card->accept_user_id;
            $user = User::find($vendor_id);

            if($vendor_id && isset($vendor_id) && $user && isset($user)){
                $vendor_old_balance = $user->wallet_balance;

                if($card->payment_moad=='Cash'){
                    $avl_ven_blc = ($vendor_old_balance-$jobValue)+$net_ear;
                } else {
                    $avl_ven_blc = $vendor_old_balance+$net_ear;
                }
                $user_paramas['wallet_balance'] = $avl_ven_blc;

                $user->update($user_paramas);

                $params['vendor_id'] = $vendor_id;   
                $res = PayOutBalance::create($params);
              
                // return $this->sendResponse($res, 'Booking Successfully Completed');
            } else {
                return $this->sendError('Vendor not found.');
            }

            if($card->user && $card->user->email){

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

                $array['view']      = 'emails.booking_completed';
                $array['subject']   = 'Booking Confirmation with '.$customer.' for '.$service;
                $array['data']      = $card;
                // \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
            }

            if($card->user && $card->user->name && $card->user->phone){
                $customer_name = $card->user->name;
                $message = "Dear ($customer_name), \nThanks for shopping with us! To help improve our services, please click here https://9a4yhhhzdd7.typeform.com/to/FoG6FNhq to rate your experience. Thank you, Urbanmop.com";
                $msg = urlencode($message);
                $mobile = $card->user->phone;
                $res=send_sms_to_mobile($mobile,$msg);
            }
            
            update_booking($card->id);
            return $this->sendResponse([], 'Service Completed');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function old_service_completed(Request $request)
    {
        $card = Card::find($request->booking_id);
        
        if($card){
            $data['status']                     = 'Completed';
            $data['service_completed']          = 'Yes';
            $data['payment_collected']          = 'Yes';
            $data['service_completed_date']     = date('Y-m-d H:i:s');
            $card->update($data);

            $card       = Card::find($request->booking_id);
            $setting    = HomeSetting::first();

            $service    = Service::find($card->service_id);

            $um_commission      = $service?$service->um_commission:'0';
            $bank_percentage    = $setting?$setting->bank_percentage:'0';
            $bank_charges       = $setting?$setting->bank_charges:'0';
            
            $total = '0';
            foreach (CardAttribute::where('card_id',$card->id)->get() as $key => $crdatr) {
                $ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
                $total += $ttotal;
            }
            
            foreach (CardAddon::where('card_id',$card->id)->get() as $key => $addon) {
                $attotal = $addon->value;
                $total += $attotal;
            }

            $params['job_value']  = $total;
            
            $jtotal = $total; 

            $tip_amt = $card?$card->tip_id:'0';

            // Step 1
            $jobValue = $total+$tip_amt;

            // Step 2
            if($card && $card->coupon_id){
                $coupon = CardCoupon::where('card_id',$card->id)->first();
                  if($coupon){
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
                $params['coupon_amt'] = $coupon_Amt;
            }

            // Step 3
            $total -= $card?$card->cod_charge:'0';

            // Step 4
            $total -= $card?$card->tip_id:'0';

            // Step 5
            $um_com_amt = ($um_commission / 100) * $jtotal;
            $total -= round($um_com_amt,2);

            // Step 6
            if($card->payment_moad=='Card'){
                $bnk_f_amt = ($bank_percentage / 100) * $jobValue;
                $total -= round($bnk_f_amt,2)+$bank_charges;
            } else {
                $bnk_f_amt = '0';
            }
             
            // Step 7 
            $net_ear = $total;

            $params['card_id']          = $card->id;
            $params['card_total']       = $jobValue;
            
            $params['cash_surcharge']   = $card?$card->cod_charge:'0';
            $params['tip']              = $card?$card->tip_id:'0';
            $params['um_comission']     = $um_commission;
            $params['um_com_amt']       = round($um_com_amt,2);

            if($card->payment_moad=='Card'){
                $params['bank_fees']        = $bank_percentage;
                $params['bank_fees_amt']    = round($bnk_f_amt,2)+$bank_charges;;
            } else {
                $params['bank_fees']        = '0';
                $params['bank_fees_amt']    = '0';
            }

            $params['payment_moad']     = $card->payment_moad=='Cash'?'Cash':'Prepaid';
            $params['net_balance']      = $net_ear;                
            
            $vendor_id = $card->accept_user_id;
            $user = User::find($vendor_id);

            if($vendor_id && isset($vendor_id) && $user && isset($user)){
                $vendor_old_balance = $user->wallet_balance;

                if($card->payment_moad=='Cash'){
                    $avl_ven_blc = ($vendor_old_balance-$jobValue)+$net_ear;
                } else {
                    $avl_ven_blc = $vendor_old_balance+$net_ear;
                }
                $user_paramas['wallet_balance'] = $avl_ven_blc;

                $user->update($user_paramas);

                $params['vendor_id'] = $vendor_id;   
                $res = PayOutBalance::create($params);
                // return $this->sendResponse($res, 'Booking Successfully Completed');
            } else {
                return $this->sendError('Vendor not found.');
            }

            if($card->user && $card->user->email){

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

                $array['view']      = 'emails.booking_completed';
                $array['subject']   = 'Booking Confirmation with '.$customer.' for '.$service;
                $array['data']      = $card;
                // \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
            }

            if($card->user && $card->user->name && $card->user->phone){
                $customer_name = $card->user->name;
                $message = "Dear ($customer_name), \nThanks for shopping with us! To help improve our services, please click here https://www.urbanmop.com to rate your experience. Thank you, Urbanmop.com";
                $msg = urlencode($message);
                $mobile = $card->user->phone;
                $res=send_sms_to_mobile($mobile,$msg);
            }
            
            update_booking($card->id);
            return $this->sendResponse([], 'Service Completed');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    // Made 05/01/2024 After Client Meeting with Tahir, Moiz
    public function service_completed(Request $request)
    {
        $card = Card::find($request->booking_id);
        
        if($card && $card->status != 'Completed'){
            $data['status']                     = 'Completed';
            $data['service_completed']          = 'Yes';
            $data['payment_collected']          = 'Yes';
            $data['service_completed_date']     = date('Y-m-d H:i:s');
            $card->update($data);

            $card       = Card::find($request->booking_id);
            $setting    = HomeSetting::first();

            $service    = Service::find($card->service_id);

            // $um_commission      = 30;
            $um_commission      = $service?$service->um_commission:'0';
            $bank_percentage    = $setting?$setting->bank_percentage:'0';
            $bank_charges       = $setting?$setting->bank_charges:'0';
            
            $total = '0';
            foreach (CardAttribute::where('card_id',$card->id)->get() as $key => $crdatr) {
                $ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
                $total += $ttotal;
            }
            
            foreach (CardAddon::where('card_id',$card->id)->get() as $key => $addon) {
                $attotal = $addon->value;
                $total += $attotal;
            }

            if($card->material_status=='Apply'){
                $total += $card->material_charge;
            }

            // Step 1
            $jobValue = $total;

            // Step 2
            $tip      = $card?$card->tip_id:'0';
            $jobValue += $tip;

            // Step 3
            $codCharge = $card?$card->cod_charge:'0';
            $jobValue += $codCharge;

            // Step 4
            if($card && $card->coupon_id){
                $coupon = CardCoupon::where('card_id',$card->id)->first();
                  if($coupon){
                    $amount = $coupon->amount;
                    if($coupon->type=='Amt'){
                      $coupon_Amt = $amount;
                    } else {
                      $per = ($amount / 100) * $total;

                      if($per>$coupon->max_amount){
                        $coupon_Amt = $coupon->max_amount;
                      } else {
                        $coupon_Amt = round($per,2);
                      }

                    }
                } else {
                    $coupon_Amt = '00';
                }
            } else {
                $coupon_Amt = '00';
            }

            $jobValue -= $coupon_Amt;

            // Step 5
            if($card->payment_moad=='Cash'){

                $_f_bnk_amt        = '0';
                $bank_percentage   = '0';

            } else {

                $bnk_f_amt   = $jobValue * $bank_percentage / 100;
                $_f_bnk_amt  = round($bnk_f_amt,2)+$bank_charges;

            }

            // Step 6
            $um_com_amt     = ($total * $um_commission) / 100;
            $um_comm_amt    = round($um_com_amt,2);

            // Step 7
            $um_erning = $um_comm_amt + $codCharge + $tip + $_f_bnk_amt;

            // Step 8
            $vendor_erning = $jobValue - $um_erning;

            $params['job_value']        = $total;
            $params['card_total']       = $jobValue;
            $params['tip']              = $card?$card->tip_id:'0';
            $params['coupon_amt']       = $coupon_Amt;
            $params['cash_surcharge']   = $card?$card->cod_charge:'0';
            $params['bank_fees']        = $bank_percentage;
            $params['bank_fees_amt']    = $_f_bnk_amt;            
            $params['um_comission']     = $um_commission;
            $params['um_com_amt']       = $um_comm_amt;
            $params['vendor_earning']   = round($vendor_erning,2);
            $params['um_earning']       = round($um_erning,2);
            $params['card_id']          = $card->id;
            $params['payment_moad']     = $card->payment_moad=='Cash'?'Cash':'Prepaid';            
            
            $vendor_id = $card->accept_user_id;
            $user = User::find($vendor_id);

            if($vendor_id && isset($vendor_id) && $user && isset($user)){
                $vendor_old_balance = $user->wallet_balance;

                if($card->payment_moad=='Cash'){
                    $net_ear = $vendor_old_balance - $um_erning;
                } else {
                    $net_ear = $vendor_old_balance + $vendor_erning;
                }

                $params['net_balance']   = round($vendor_erning,2);    
                
                $user_paramas['wallet_balance'] = round($net_ear,2);

                $user->update($user_paramas);

                $params['vendor_id'] = $vendor_id;  
                
                $res = PayOutBalance::create($params);
              
                // return $this->sendResponse($res, 'Booking Successfully Completed');
            } else {
                return $this->sendError('Vendor not found.');
            }

            if($card->user && $card->user->email){

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

                $array['view']      = 'emails.booking_completed';
                $array['subject']   = 'Booking Confirmation with '.$customer.' for '.$service;
                $array['data']      = $card;
                // \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
            }

            if($card->user && $card->user->name && $card->user->phone){
                $customer_name = $card->user->name;
                $message = "Dear ($customer_name), \nThanks for shopping with us! To help improve our services, please click here https://9a4yhhhzdd7.typeform.com/to/FoG6FNhq to rate your experience. Thank you, Urbanmop.com";
                $msg = urlencode($message);
                $mobile = $card->user->phone;
                // $res=send_sms_to_mobile($mobile,$msg);
            }
            update_booking($card->id);

            $user = User::find($card->user_id);

            if ($user) {
                $device_tokens = $user->user_device_token;
            
                if (!empty($device_tokens) && $device_tokens != '0') {
                    if (strlen($device_tokens) > 70) {
                        // Logic when token length exceeds 70
                        $title  = "#" . $card->id;
                        $body   = "Your booking has been completed!";
                        $text   = "Your booking has been completed!";

//                        $this->firebaseService->sendNotification($device_tokens, $title, $body, $text);
                        if (!empty($firebase_user_key)) {
                            $notification_data["Notifications/" . $firebase_user_key . "/" . time()] = [
                                "title" => $title,
                                "description" => $body,
                                "notificationType" => 'service_complete',
                                "createdAt" => Carbon::now()->setTimezone('GMT')->format('Y-m-d h:i:s'),
                                "cardId" => (string)$card->id,
                                "status" => "1",
                                "url" => "",
                                "imageURL" => '',
                                "read" => "0",
                                "seen" => "0",
                            ];
                            $this->database->getReference()->update($notification_data);

                            send_single_notification(
                                $device_tokens,
                                [
                                    "title" => $title,
                                    "body" => $body,
                                    "icon" => 'myicon',
                                    "sound" => 'default',
                                    "click_action" => "EcomNotification",
                                ],
                                [
                                    "type" => 'service_complete',
                                    "notificationID" => time(),
                                    "status" => "1",
                                    "imageURL" => "",
                                    "cardId" => (string)$card->id,
                                ]
                            );
                        }
                    }
                }
            }

            return $this->sendResponse([], 'Service Completed');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function cod_status(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card){
            if($request->payment_mode){

                if($request->payment_mode=='Card'){

                    if(isset($request->paymentLinkId)){

                            $data['payment_moad']   = $request->payment_mode;
                            $data['paymentLinkId']  = $request->paymentLinkId;
                            $data['paymentTranId']  = $request->paymentLinkId;
                            $data['pending_approval_by_admin'] = $request->flag;
                            $data['payment_collected']  = 'Yes';
                            $card->update($data);
                            return $this->sendResponse($data, 'Payment Updated');

                    } else {

                        return $this->sendError('Required field is missing.');
                        
                    }

                } else {

                    $data['cod_status']     = $request->cod_status;
                    $data['payment_moad']   = $request->payment_mode;
                    $data['pending_approval_by_admin'] = $request->flag;
                    $data['payment_collected']  = 'Yes';
                    $card->update($data);  
                    return $this->sendResponse($data, 'Payment Updated');                  
                    
                }  

            } else {
                return $this->sendError('Required field is missing.');
            }
            
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function work_done(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card){
            $data['work_done']  = 'Yes';
            $card->update($data);
            return $this->sendResponse([], 'Work Done');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function booking_canceled_partner(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card && $card->status != 'Canceled'){
            $data['status']  = 'Pending';
            $card->update($data);

            $array['view']      = 'emails.booking_cancelled';
            $array['subject']   = 'Your Booking Has Been Cancelled';
            $array['data']      = '';
            \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));

            return $this->sendResponse([], 'Your booking is successfully cancelled');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function booking_canceled(Request $request)
    {
        $card = Card::find($request->booking_id);

        if($card && $card->status != 'Canceled'){
            $data['status']  = 'Canceled';
            $card->update($data);

            $array['view']      = 'emails.booking_cancelled';
            $array['subject']   = 'Your Booking Has Been Cancelled';
            $array['data']      = '';
            \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));

            return $this->sendResponse([], 'Your booking is successfully cancelled');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function change_slot_and_date(Request $request)
    {
        $card = Card::where('id',$request->cart_id)->where('status', '!=' , 'Canceled')->first();
        if($card){
            $data['slot_id']   = $request->slot_id;
            $data['date']      = $request->date;
            $card->update($data);

            if($card->user && $card->user->email){
                $array['view']      = 'emails.change_slot_customer';
                $array['subject']   = 'Slot Changed';
                $array['data']      = $card;
                
                // \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
            }

            if($card->vendor && $card->vendor->email){
                $array['view']      = 'emails.change_slot_customer';
                $array['subject']   = 'Slot Changed';
                $array['data']      = $card;
                
                // \Mail::to($card->vendor?$card->vendor->email:'')->send(new \App\Mail\Mail($array));
            }

            $adminarray['view']      = 'emails.change_slot_admin';
            $adminarray['subject']   = 'Slot Changed';
            $adminarray['data']      = $card;
           
            // \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));
            
            return $this->sendResponse([], 'Updated successfully');
        } else {
            return $this->sendError('Invalid booking id');
        }
    }

    public function paymentsuccess(Request $request, $orderid='')
    {
        $request['order_id'] = $orderid;
        return $request;
    }

    public function paymentDone(Request $request)
    {
        if($request->card_id){
            if($request->status=='captured'){
        
                // if($request->transactionId=='null' || $request->transactionId == null || $request->transactionId ==''){
                //     // $data['tran_id']    = 'RND-'.rand();
                //     // 11/03/2024 Yash Sir, Lakshay, Mohit

                //     // $ttId = 'RND-'.rand();
                //     // $card_check = Card::find($request->card_id);

                //     // if($card_check && $card_check->paymentLinkId){

                //     //     $curl = curl_init();

                //     //     curl_setopt_array($curl, array(
                //     //       CURLOPT_URL => 'https://business.mamopay.com/manage_api/v1/links/'.$card_check->paymentLinkId,
                //     //       CURLOPT_RETURNTRANSFER => true,
                //     //       CURLOPT_ENCODING => '',
                //     //       CURLOPT_MAXREDIRS => 10,
                //     //       CURLOPT_TIMEOUT => 0,
                //     //       CURLOPT_FOLLOWLOCATION => true,
                //     //       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //     //       CURLOPT_CUSTOMREQUEST => 'GET',
                //     //       CURLOPT_HTTPHEADER => array(
                //     //         'Authorization: Bearer sk-3b63062a-a66c-40af-b877-7eda10ce1d32'
                //     //       ),
                //     //     ));

                //     //     $response = curl_exec($curl);

                //     //     curl_close($curl);

                //     //     $response = json_decode($response, true);

                //     //     if($response && $response['charges']){

                //     //         if($response['charges'][0] && $response['charges'][0]['status']=='captured'){

                //     //             $data['tran_id'] = $response['charges'][0]['id'];

                //     //         } else {
                //     //             $data['tran_id']    = $ttId;
                //     //         }

                //     //     } else {
                //     //         $data['tran_id']    = $ttId;
                //     //     }


                //     // } else {
                //     //     $data['tran_id']    = $ttId;
                //     // }

                // } else {
                //     $data['tran_id']    = $request->transactionId;
                // }


                $ttId = 'RND-'.rand();
               
                if($request->paymentLinkId){

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://business.mamopay.com/manage_api/v1/links/'.$request->paymentLinkId,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'GET',
                      CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer sk-3b63062a-a66c-40af-b877-7eda10ce1d32'
                      ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    $response = json_decode($response, true);

                    if($response && isset($response['charges'])){

                        if($response['charges'][0] && $response['charges'][0]['status']=='captured'){

                            $data['paymentTranId'] = $response['charges'][0]['id'];

                        } else {
                            $data['paymentTranId']    = $ttId;
                        }

                    } else {
                        $data['paymentTranId']    = $ttId;
                    }


                } else {
                    $data['paymentTranId']    = $ttId;
                }

                // $data['tran_id'] = $request->transactionId;

                $data['paymentLinkId'] = $request->paymentLinkId;
                $data['payment_status'] = 'True';
                $data['is_checkout'] = 'Done';

                Card::where('id',$request->card_id)->update($data);

                $card = Card::find($request->card_id);
                $array['view']      = 'emails.invoice';
                $array['subject']   = 'Your Booking Invoice';
                $array['data']      = $card;
                if($card->user && $card->user->email){
                    $res = \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
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
                    send_sms_to_mobile($mobile,$msg);

                }

                $ser_users = get_seller_info_by_service($card?$card->service_id:'');
                if($ser_users){
                    foreach ($ser_users as $key => $value) {
                        if(isset($value->device_token)){
                            
                            $token  = $value->device_token;
                                       
                            $service = $card->service?$card->service->name:'No Service';
                                   
                            $title  = 'New Booking Arrived';
                            $body   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";
                            $text   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";

                            $data = send_notification($token, $title, $body, $text);
                        }
                    }
                }   
                
                return $this->sendResponse([], 'Payment successfully done');
            } else {
                return $this->sendError('Payment Field Try Again.');
            }
        } else {
            return $this->sendError('Card id is required.');
        }
    }

    public function tabby_success(Request $request)
    {
        if($request->card_id){
            if($request->tabby_payment_status){
    
                $data['tabby_payment_status']       = $request->tabby_payment_status;
                $data['tabby_payment_response_id']  = $request->tabby_payment_id;
                $data['payment_status']             = 'True';
                $data['is_checkout']                = 'Done';

                Card::where('id',$request->card_id)->update($data);

                $card = Card::find($request->card_id);
                $array['view']      = 'emails.invoice';
                $array['subject']   = 'You Have New Service Booking';
                $array['data']      = $card;
                if($card->user && $card->user->email){
                    $res = \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
                }

                $adminarray['view']      = 'emails.invoice';
                $adminarray['subject']   = 'You Have New Service Booking';
                $adminarray['data']      = $card;
               
                \Mail::to('urbanmop.uae@gmail.com')->send(new \App\Mail\Mail($adminarray));

                $ser_users = get_seller_info_by_service($card?$card->service_id:'');
                if($ser_users){
                    foreach ($ser_users as $key => $value) {
                        if(isset($value->device_token)){
                            
                            $token  = $value->device_token;
                                       
                            $service = $card->service?$card->service->name:'No Service';
                                   
                            $title  = 'New Booking Arrived';
                            $body   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";
                            $text   = "Heads up! A new booking just came in Urbanmop Partner App for '.$service.' Don't miss out on this chance to earn extra money.";

                            $data = send_notification($token, $title, $body, $text);
                        }
                    }
                }   
                
                return $this->sendResponse([], 'Payment successfully done');
            } else {
                return $this->sendError('Payment status is required.');
            }
        } else {
            return $this->sendError('Card id is required.');
        }
    }

    public function tabby_failure(Request $request)
    {
        if($request->card_id){
            if($request->tabby_payment_status){
    
                $data['tabby_payment_status']       = $request->tabby_payment_status;
                $data['tabby_payment_response_id']  = $request->tabby_payment_id;
                $data['payment_status']             = 'False';
                $data['is_checkout']                = 'Done';

                Card::where('id',$request->card_id)->update($data);

                $card = Card::find($request->card_id);
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
                
                return $this->sendResponse([], 'Your payment failed. Contact UrbanMop Support');
            } else {
                return $this->sendError('Payment status is required.');
            }
        } else {
            return $this->sendError('Card id is required.');
        }
    }

    public function get_vendor_service($user_id='')
    {
        $seller = Seller::where('user_id',$user_id)->first();
        if($seller){
            $seller_ser = SellerService::where('seller_id',$seller?$seller->id:'')->get();
            $sllr_ser_id = [];
            $datas = [];
            foreach ($seller_ser as $key => $ser_id) {
                array_push($sllr_ser_id, $ser_id->service_id);
            }

            $service = Service::where('status','1')->whereIn('id',$sllr_ser_id)->get();

            foreach ($service as $key => $value) {
                $data['id']     = $value->id;
                $data['title']  = $value->name;
                $data['image']  = \URL::to('/').'/uploads/service/'.$value->thumbnail_img;
                $data['price']  = $value->price;
                array_push($datas, $data);
            }
            return $this->sendResponse($datas, 'Vendor Service');
        } else {
            return $this->sendError('Vendor Not Found.');
        }
    }

    public function financial_calculation(Request $request, $booking_id)
    {
        if($booking_id){
            $card       = Card::find($booking_id);
            $setting    = HomeSetting::first();

            $um_commission      = $setting?$setting->um_commission:'0';
            $bank_percentage    = $setting?$setting->bank_percentage:'0';
            $bank_charges       = $setting?$setting->bank_charges:'0';

            if($card){
                $total = '0';
                foreach (CardAttribute::where('card_id',$card->id)->get() as $key => $crdatr) {
                    $ttotal = $crdatr->attribute_price*$crdatr->attribute_qty;
                    $total += $ttotal;
                }
                
                foreach (CardAddon::where('card_id',$card->id)->get() as $key => $addon) {
                    $attotal = $addon->value;
                    $total += $attotal;
                }

                // Step 1
                $jobValue = $total;

                // Step 2
                if($card && $card->coupon_id){
                    $coupon = CardCoupon::where('card_id',$card->id)->first();
                      if($coupon){
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
                    $params['coupon_amt'] = $coupon_Amt;
                }

                // Step 3
                $total -= $card?$card->cod_charge:'0';

                // Step 4
                $total -= $card?$card->tip_id:'0';

                // Step 5
                $um_com_amt = ($um_commission / 100) * $jobValue;
                $total -= round($um_com_amt,2);

                // Step 6
                $bnk_f_amt = ($bank_percentage / 100) * $jobValue;
                $total -= round($bnk_f_amt,2)+$bank_charges;
                 
                // Step 7 
                $net_ear = $total;

                $params['card_id']          = $card->id;
                $params['card_total']       = $jobValue;
                $params['cash_surcharge']   = $card?$card->cod_charge:'0';
                $params['tip']              = $card?$card->tip_id:'0';
                $params['um_comission']     = $um_commission;
                $params['um_com_amt']       = round($um_com_amt,2);
                $params['bank_fees']        = $bank_percentage;
                $params['bank_fees_amt']    = round($bnk_f_amt,2)+'1';
                $params['payment_moad']     = $card->payment_moad=='Cash'?'Cash':'Prepaid';
                $params['net_balance']      = $net_ear;                
                
                $vendor_id = $card->accept_user_id;
                $user = User::find($vendor_id);

                if($vendor_id && isset($vendor_id) && $user && isset($user)){
                    $vendor_old_balance = $user->wallet_balance;

                    if($card->payment_moad=='Cash'){
                        $avl_ven_blc = ($vendor_old_balance-$jobValue)+$net_ear;
                    } else {
                        $avl_ven_blc = $vendor_old_balance+$net_ear;
                    }
                    $user_paramas['wallet_balance'] = $avl_ven_blc;

                    $user->update($user_paramas);

                    $params['vendor_id'] = $vendor_id;   
                    $res = PayOutBalance::create($params);
                    return $this->sendResponse($res, 'Booking Successfully Completed');
                } else {
                    return $this->sendError('Vendor not found.');
                }

            } else {
                return $this->sendError('No Data Found.');
            }
        } else {
            return $this->sendError('Booking id is required.');
        }
    }

    public function remove_coupon($booking_id)
    {
        if($booking_id){

            $data = Card::find($booking_id);

            if($data){
                $params['coupon_id'] = null;
                $data->update($params);
                return $this->sendResponse([], 'Coupon has been removed');
            } else {
                return $this->sendError('Data not found.');
            }

        } else {
            return $this->sendError('Booking id is required.');
        }
    }

    public function get_card_info($booking_id)
    {
        if($booking_id){

            $value = Card::find($booking_id);

            if($value){

                $service = Service::find($value->service_id);
                
                $user = User::find($value->user_id);
                $provider_address   = Address::where('user_id', $value->accept_user_id)->latest()->first();
                $user_address       = Address::where(['user_id' => auth()->user()->id, 'is_active' => 1])->first();
    
                $data['provider_address'] = (object) ($provider_address);
                $data['user_address'] = (object) ($user_address);
                $data['user_latitude'] = (string) ($user_address->latitude ?? '');
                $data['user_longitude'] = (string) ($user_address->longitude ?? '');
                $data['provider_latitude'] = (string) ($provider_address->latitude ?? '');
                $data['provider_longitude'] = (string) ($provider_address->longitude ?? '');
        
                $category = Category::find($value->category_id);

                $data['id']             = (string) $value->id;
                $data['booking_id']     = (string) $value->id;
                if(isset($value->tabby_payment_response_id)){
                    $data['tran_id']    = (string) $value->tabby_payment_response_id;
                } elseif (isset($value->paymentTranId)) {
                    $data['tran_id']    = (string) $value->paymentTranId;
                } else {
                    $input = [
                        'customer_id' => $value->user_id,
                        'vendor_id' => Seller::where("user_id", $value->accept_user_id)->first()->seller_id ?? null
                    ];
                    
                    $transection = Transection::where($input)->first();
                    $data['tran_id'] = $transection ? (string) $transection->id : $value->tran_id;     
                }

                $data['user_id']        = (string) $value->user_id;
                $data['user_name']      = $user?$user->name:'';
                $data['user_email']     = $user?$user->email:'';
                $data['user_mobile']    = $user?$user->phone:'';
                $data['service_id']     = (string) $value->service_id;
                $data['service']        = $service->name;
                $data['video']          = $service->video ?? "";
                $data['info']           = $service->info ?? "";
                $data['video_title']    = $service->video_title ?? "";
                $data['video_description']  = $service->video_description ?? "";
                $data['image']          = \URL::to('/').'/uploads/service/'.$service->thumbnail_img;
                $data['category_id']    = $value->category_id;
                $data['category']       = $category?$category->name:'';
                $data['slot_id']        = $value->slot_id;
                $data['slot']           = $value->slot?$value->slot->name:'';


                if(is_numeric($value->address_id)){

                    $data['address_id']     = $value->address_id;

                    $building = $value->address?$value->address->building:'';
                    $flat_no = $value->address?$value->address->flat_no:'';
                    $address = $value->address?$value->address->address:'';

                    $latitude = $value->address?$value->address->latitude:'';
                    $longitude = $value->address?$value->address->longitude:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;

                    if($value->address && $value->address->city){
                        $city = $value->address->city->name;
                    } else {
                        $city = "";
                    }

                    $data['city']           = $city;

                    if($value->address && $value->address->locality_info){
                        $locality = $value->address->locality_info->name;
                    } else {
                        $locality = "";
                    }
                    $data['locality']       = $locality;
                    
                    $data['latitude']       = $latitude;
                    $data['longitude']      = $longitude;

                } else {

                    $add_info = json_decode($value->address_id);

                    $data['address_id']     = '';


                    $building = $add_info?$add_info->building:'';
                    $flat_no = $add_info?$add_info->flat_no:'';
                    $address = $add_info?$add_info->address:'';

                    $latitude = $add_info?$add_info->latitude:'';
                    $longitude = $add_info?$add_info->longitude:'';
                    $data['address']        = $building.', '. $flat_no.', '.$address;

                    if($add_info && $add_info->city_name){
                        $city = $add_info->city_name;
                    } else {
                        $city = "";
                    }

                    $data['city']           = $city;

                    if($add_info && $add_info->locality){
                        $locality = $add_info->locality;
                    } else {
                        $locality = "";
                    }
                    $data['locality']       = $locality;
                    
                    $data['latitude']       = $latitude;
                    $data['longitude']      = $longitude;

                }


                $data['payment_moad']   = $value->payment_moad;
                $data['payment_status'] = $value->payment_status;
                $data['note']           = $value->note;
                $data['material_charge']= $value->material_charge;
                $data['material_status']= $value->material_status;
                $data['service_type']   = $value->service_type;
                $data['alternative_dial_code']  = $value->alternative_dial_code;
                $data['alternative_number'] = $value->alternative_number;
                $data['date']           = $value->date;
                $data['tip']            = $value->tip_id;
                $data['coupon_id']      = $value->coupon_id;
                $data['is_checkout']    = $value->is_checkout;
                $data['status']         = $value->status;
                $data['accept_vendor']  = $value->vendor?$value->vendor->name:'';
                $data['accept_vendor_id']  = (string) $value->accept_user_id;
                $data['offline_charge']  = $value->offline_charge;
                $data['offline_discount']  = $value->offline_discount;
                $data['payment_collected']  = $value->payment_collected;
                $data['service_start_datetime']  = $value->service_start_datetime;
                $data['service_completed']  = $value->service_completed;
                $data['service_completed_date']  = $value->service_completed_date;
                $data['work_done']  = $value->work_done;
                $data['cod_status']  = $value->cod_status;
                $data['cod_charge']  = $value->cod_charge;
                $data['material_status']  = $value->material_status;
                $data['material_charge']  = $value->material_charge;
                $data['service_type']  = $value->service_type;
                $data['booking_from']  = $value->booking_from;
                $data['cencal_date']  = $value->cencal_date;
                $data['pending_approval_by_admin']  = $value->pending_approval_by_admin;
                
                $card_attr = [];
               
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
                $data['total']          = $value->g_total;
                $data['card_attribute'] = $card_attr;
                $data['is_bell']        = "0";
               
                return $this->sendResponse($data, 'Card Info');
            } else {
                return $this->sendError('Data not found.');
            }

        } else {
            return $this->sendError('Booking id is required.');
        }
    }

    public function remove_vendor($booking_id)
    {
        if($booking_id){

            $data = Card::find($booking_id);

            if($data){

                if($data->status=='Canceled'){

                    return $this->sendError('This booking is cancelled.');
                    
                } else {

                    if($data->accept_user_id){

                        $params['accept_user_id'] = null;
                        
                        $params['accept_user_company_id'] = null;

                        $params['status'] = 'Pending';
                        
                        $datas['status'] = Card::find($booking_id)->value('status');
                        
                        // Tahir Change 25/01
                        // if($data && $data->user && $data->user->email){

                        //     $user = $data->user?$data->user->name:'';
                        //     $service = $data->service?$data->service->name:'';

                        //     $array['view']      = 'emails.cencal_booking';
                        //     $array['subject']   = 'Booking Cancelled with '.$user.' for '.$service;
                        //     $array['data']      = $data;
                        //     \Mail::to($data->user->email)->send(new \App\Mail\Mail($array));
                        // }

                        if($data && $data->vendor && $data->vendor->email){

                            $user = $data->user?$data->user->name:'';
                            $service = $data->service?$data->service->name:'';

                            $array['view']      = 'emails.cencal_booking';
                            $array['subject']   = 'Booking Cancelled with '.$user.' for '.$service;
                            $array['data']      = $data;
                            \Mail::to($data->vendor->email)->send(new \App\Mail\Mail($array));
                        }

                        $user = $data->user?$data->user->name:'';
                        $service = $data->service?$data->service->name:'';

                        $vendor = User::find($data->accept_user_id);

                        $array['view']      = 'emails.cencal_booking';
                        $array['subject']   = 'Booking Cancelled with '.$user.' for '.$service;
                        $array['data']      = $data;
                        \Mail::to('urban.uae@gmail.com')->send(new \App\Mail\Mail($array));

                        $data->update($params);
                       
                        return $this->sendResponse($datas, 'Vendor removed');

                    } else {

                        return $this->sendError('Vendor not assign.');

                    }

                }

            } else {

                return $this->sendError('Data not found.');

            }

        } else {

            return $this->sendError('Booking id is required.');
            
        }
    }

    public function get_vendor_payment(Request $request)
    {
        $payment = Payment::where('vendor_id',$request->vendor_id)->get();
        return $this->sendResponse($payment, 'Vendor Payment History');

    }

    public function get_coupon_use_count(Request $request)
    {        
        if($request->user_id && $request->coupon_id){
            $count = UserCoupon::where('user_id',$request->user_id)->where('coupon_id',$request->coupon_id)->count();
            return $this->sendResponse($count, 'Coupon Usage Count');
        } else {
             return $this->sendError('Required field is empty.');
        }
    }

    function review(Request $request)
    {
        if($request->booking_id && $request->service_id && $request->rating && $request->opinion){
            $params['booking_id'] = $request->booking_id;
            $params['service_id'] = $request->service_id;
            $params['vendor_id'] = $request->vendor_id ?? null;
            $params['customer_id'] = auth()->user()->id;
            $params['rating'] = $request->rating;
            $params['opinion'] = $request->opinion;

            $res = Review::create($params);
            return $this->sendResponse($res, 'Review Successfully submitted');
        } else {
             return $this->sendError('Required field is empty.');
        }
    }
}
