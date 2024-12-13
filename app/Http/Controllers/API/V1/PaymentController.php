<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
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
use Kreait\Firebase\Database;

class PaymentController extends BaseController
{
    public function __construct(Database $database)
    {
        $this->database = $database;
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

    public function paySuccess(Request $request)
    {
        return $this->sendResponse($request, 'Payment success');
    }

    public function payFailure(Request $request)
    {
        $card = Card::where('id',$request->booking_id)->first();

        if($card){

            $data['paymentTranId']  = $request->transactionId;
            $data['paymentLinkId']  = $request->paymentLinkId;
            $data['payment_status'] = 'False';
            $data['is_checkout']    = 'Done';

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

            return $this->sendResponse($request, 'Payment failure');

        } else {

            return $this->sendResponse($request, 'Booking not found.');
        }        
    }

    public function testNoti() {
//        if (!empty($user->firebase_user_key)) {
            $notification_data["Notifications/test_urban/" . time()] = [
                "title" => 'Test urban',
                "description" => 'This is not',
                "notificationType" => 'win_campaign',
                "createdAt" => gmdate("d-m-Y H:i:s", '2500'),
                "orderId" => "2",
                "productId" => "100",
                "productAttrId" => "85",
                "status" => "1",
                "url" => "",
                "imageURL" => '',
                "read" => "0",
                "seen" => "0",
            ];
            $this->database->getReference()->update($notification_data);
//        }
        send_single_notification(
            'dYzNqipCSB-KgySV6zsccz:APA91bGurLWnUNBQudYDmnLi9F9jfqXiLRFXAK_q9a_zaPXM_zMa6vFwJ5eUFJgz7jOfUyF2wSdIXi3MQroYcof2aPTXMV6vWNV0UNv4SrkL1S_7ZCHLmyMshZMQGCSSrqpSKl5YD4Xw',
            [
                "title" => 'Test ',
                "body" => 'This is test notification',
                "icon" => 'myicon',
                "sound" => 'default',
                "click_action" => "EcomNotification",
            ],
            [
                "type" => 'test_noti',
                "notificationID" => time(),
                "status" => "1",
                "imageURL" => "",
            ]
        );
    }
}
