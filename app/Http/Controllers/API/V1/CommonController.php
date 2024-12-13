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
use App\CardCoupon;
use App\Review;

class CommonController extends BaseController
{
    function get_rating(Request $request)
    {
        if($request->service_id || $request->vendor_id){

            if($request->service_id){
                $service_review = Review::where('service_id',$request->service_id)->get();

                $s_sum = 0;
                foreach($service_review as $value){
                $s_sum += $value->rating;
                }
                $s_avg = $s_sum / count($service_review);

                $data['service_rating'] = $s_avg;
                
            }

            if($request->vendor_id){
                $vendor_review = Review::where('vendor_id',$request->vendor_id)->get();
            
                $v_sum = 0;
                foreach($vendor_review as $value){
                $v_sum += $value->rating;
                }
                $v_avg = $v_sum / count($vendor_review);

                $data['vendor_rating'] = $v_avg;
            }

            return $this->sendResponse($data, 'Ratings');

        } else {
            return $this->sendError('Required field is empty.');
        }
    }


    function resend_payment_link($booking_id='')
    {
        $card = Card::find($booking_id);

        if($card){
           
            if($card){
                if($card->user && $card->user->email){
                    $array['view']      = 'emails.invoice_offline_booking';
                    $array['subject']   = 'Your Booking Invoice';
                    $array['data']      = $card;
                    
                    \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
                }
    
                $tran_id = $card->tran_id;
                
                $message = "Congratulation! You have successfully booked service with UrbanMop. \nYour booking ID is $tran_id \nView booking on UrbanMop \nhttps://www.urbanmop.com \nFor any assistance contact UrbanMop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com. \nYour Payment Link $card->payment_link";

                $msg = urlencode($message);
                if($card->user && $card->user->phone){
    
                    $mobile = $card->user->phone;
                    $res=send_sms_to_mobile($mobile,$msg);
    
                }

                return $this->sendResponse([], 'Payment link send on mail.');

            } else {
                return $this->sendError('This booking payment mode is Cash.');
            }

        } else {
          
            return $this->sendError('Try again booking not found.');
        }
    }

    function service_complete_approval($booking_id='')
    {
        $card = Card::find($booking_id);

        if($card){

            if($card && $card->service_complete_approval=='Not Approved'){

                $params['service_complete_approval'] = 'Approved';
                $card->update($params);
                return $this->sendResponse([], 'Booking approved successfully.');

            } else {
                return $this->sendError('This booking already approved.');
            }

        } else {

            return $this->sendError('Try again booking not found.');

        }
    }
}
