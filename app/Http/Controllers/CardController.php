<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\User;
use App\Seller;
use PDF;
use DB;
use App\Slot;
use App\HomeSetting;
use App\PayOutBalance;
use App\Service;
use App\Payment;
use App\UserCoupon;
use App\CardCoupon;
use App\Category;
use App\ChildCategory;
use App\CardAttribute;
use App\CardAddon;
use App\Coupon;
use App\Review;
use Illuminate\Database\Eloquent\Builder;

class CardController extends Controller
{
    public function index(Request $request)
    {
        if($request->search){
            $bookings = Card::orderBy('id','DESC')->where('tran_id', 'like', '%'.$request->search.'%')->where('is_checkout','Done')->paginate(10);
        } else {
            $bookings = Card::orderBy('id','DESC')->where('card_process','Complete')->where('payment_status', '!=' , 'Draft')->get();
        }
        // $vendors = Seller::where('status','1')->get();
        $vendors = User::where('role','vendor')->where('is_verified','1')->where('verify','True')->get();
        return view('bookings.index',compact('bookings','request','vendors'));
    }

    public function index_new(Request $request)
    {
        $vendors = User::where('role','vendor')->where('is_verified','1')->where('verify','True')->get();
        
        return view('bookings.index_new',compact('vendors'));
        
    }

    public function search_booking(Request $request)
    {
        if($request->booking_id || $request->service_name || $request->customer_name || $request->vendor_name || $request->payment_mode || $request->payment_status || $request->slot_date || $request->service_status || $request->payment_status || $request->pending_approval || $request->customer_number || $request->payment_id){
           
            $query = Card::with(['user','slot','seller']);

                    if(!empty($request->booking_id)) {
                        $query->where('cards.tran_id','LIKE','%'.$request->booking_id.'%');                        
                    }

                    if(!empty($request->service_name)){
                        $query->where('cards.service_name','LIKE','%'.$request->service_name.'%');
                    }

                    if(!empty($request->slot_date)){
                        $query->where('cards.date','LIKE','%'.$request->slot_date.'%');
                    }

                    if(!empty($request->service_status)){
                        $query->where('cards.status','LIKE','%'.$request->service_status.'%');
                    }

                    if(!empty($request->payment_status)){
                        $query->where('cards.payment_status','LIKE','%'.$request->payment_status.'%');
                    }

                    if(!empty($request->payment_id)){
                        $query->orWhere('cards.paymentLinkId','LIKE','%'.$request->payment_id.'%');
                        $query->orWhere('cards.paymentTranId','LIKE','%'.$request->payment_id.'%');
                        $query->orWhere('cards.tabby_payment_response_id','LIKE','%'.$request->payment_id.'%');
                    }

                    if(!empty($request->pending_approval)){
                        if($request->pending_approval=='Approved'){
                            $query->where('cards.service_complete_approval', $request->pending_approval);
                            $query->where('cards.status', 'In Progress');
                        } else {
                            $query->where('cards.service_complete_approval', $request->pending_approval);
                            $query->where('cards.status', 'Completed');
                        }
                    }

                    if(!empty($request->customer_name)){

                        $customer_name = $request->customer_name;

                        $query->whereHas('user', function($q) use ($customer_name) {
                            $q->where('name', 'like', '%' . $customer_name . '%');
                        });
                    }

                    if(!empty($request->customer_number)){

                        $customer_number = $request->customer_number;

                        $query->whereHas('user', function($q) use ($customer_number) {
                            $q->where('phone', 'like', '%' . $customer_number . '%');
                        });
                    }

                    if(!empty($request->vendor_name)){
                        $company_name = $request->vendor_name;

                        $query->whereHas('vendor.seller', function($q) use ($company_name) {
                            $q->where('company_name', 'like', '%' . $company_name . '%');
                        });
                    }

                    if(!empty($request->payment_mode)) {
                        $query->where('cards.payment_moad','LIKE','%'.$request->payment_mode.'%');
                    }

            $bookings =  $query->orderBy('id','DESC')->where('card_process','Complete')->where('payment_status', '!=' , 'Draft')->get();

            $vendors = User::where('role','vendor')->where('is_verified','1')->where('verify','True')->get();
            
            return view('bookings.search',compact('vendors','bookings','request'));
        } else {
            return redirect()->route('bookings');
        }
        
    }

    public function get_bookings(Request $request)
    {
        $columns = array( 
                            0 => 'No', 
                            1 => 'BookingId', 
                            2 => 'SlotDate', 
                            3 => 'CompanyName',
                            4 => 'CustomerName',
                            5 => 'ServiceName',
                            6 => 'ServiceStatus',
                            7 => 'PaymentType',
                            8 => 'PaymentStatus',
                        );
  
        $totalData = Card::orderBy('id','DESC')->where('card_process','Complete')->where('payment_status', '!=' , 'Draft')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = '';
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $datas = Card::where('card_process','Complete')->where('payment_status', '!=' , 'Draft')->offset($start)
                         ->limit($limit)
                         ->orderBy('id', 'DESC')
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $datas =  Card::with('vendor')->with('user')->with('service')->where('card_process','Complete')->where('payment_status', '!=' , 'Draft')->where('booking_from','LIKE',"%{$search}%")
                            ->orWhere('tran_id', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy('id', 'DESC')
                            ->get();

            $totalFiltered = Card::with('vendor')->with('user')->with('service')->where('card_process','Complete')->where('payment_status', '!=' , 'Draft')->where('booking_from','LIKE',"%{$search}%")
                            ->orWhere('tran_id', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->count();


        }


        $response = array();
        if(!empty($datas))
        {
            foreach ($datas as $key => $data)
            {
                
                if($data->vendor && $data->vendor->seller){
                    $company_name = $data->vendor->seller->company_name;
                } else {
                    $company_name = 'No Company';
                }

                if($data->status=='Accept'){
                    $status = '<span class="text-success">Accepted</span>';
                } elseif ($data->status=='Completed') {
                    $status = '<span class="text-success">Completed</span>';
                } elseif ($data->status=='Mark As Arrived') {
                    $status = '<span class="text-info">Mark As Arrived</span>';
                } elseif ($data->status=='Canceled') {
                    $status = '<span class="text-danger">Canceled</span>';
                } elseif ($data->status=='In Progress') {
                    $status = '<span class="text-warning">In Progress</span>';
                } else {
                    $status = '<span class="text-warning">Pending</span>';
                }
                $pay_mode = '';
                if($data->payment_moad=='Cash'){
                    if($data->payment_collected=='Yes'){
                        $pay_mode = '(<small class="text-success">Paid</small>)';
                    } else {
                        $pay_mode = '(<small class="text-warning">Pending</small>)';
                    }
                }

                $booking_completed = route('booking.completed',$data->id);
                $view =  route('booking.view',$data->id);
                $delete =  route('booking.delete',$data->id);
                $cencal =  route('booking.cencal',$data->id);
                $changeslot = route('change.booking.date.time',encrypt($data->id));

                $buttons = '';

                if($data->service_complete_approval =='Approved' && $data->status !=='Completed'){
                    $buttons .= "<a href='{$booking_completed}' onclick='return confirm('Do you want to complete this service?')'><button type='button' class='btn btn-outline-dark btn-ft btn-sm mr-5' title='Service Completed' alt='Service Completed'><i class='fa fa-paper-plane' aria-hidden='true'></i></button></a>";
                } 
                if($data->status !=='Canceled' && $data->status !=='Completed'){
                    $buttons .= "<button type='button' class='btn btn-outline-warning btn-ft btn-sm mr-5' onclick='changeVendor({$data})' data-toggle='modal' data-target='#exampleModal' title='Change Vendor' alt='Change Vendor'><i class='fa fa-cog' aria-hidden='true'></i></button>";
                }

                $buttons .= "<a href='{$view}' target='_blank'><button type='button' class='btn btn-outline-info btn-ft btn-sm mr-5' title='Edit' alt='Edit'><i class='fa fa-eye' aria-hidden='true'></i></button></a>";

                if($data->booking_from=='Offline' && $data->status !=='Completed'){
                    $buttons .= "<a href='javascript:' onclick='payPayment({$data})' data-toggle='modal' data-target='#payPayment'><button type='button' class='btn btn-outline-info btn-ft btn-sm mr-5' title='Pay Payment' alt='Pay Payment'><i class='fa fa-money' aria-hidden='true'></i></button></a>";
                }

                if($data->status !=='Completed'){
                    $buttons .= "<a href='{$delete}' onclick='return confirm('Are you sure?')'><button type='button' class='btn btn-outline-primary btn-ft btn-sm mr-5' title='Delete' alt='Delete'><i class='fa fa-trash-o' aria-hidden='true'></i></button></a>";
                }

                if($data->status !=='Canceled' && $data->status !=='Completed'){
                    $buttons .= "<a href='{$cencal}' onclick='return confirm('Do you want to cancel the booking?')'><button type='button' class='btn btn-outline-primary btn-ft btn-sm mr-5' title='Cancel Booking' alt='Cancel Booking'><i class='fa fa-ban' aria-hidden='true'></i></button></a>";
                }

                if($data->booking_from=='Offline' && $data->status !=='Canceled' && $data->status !=='Completed'){

                    $buttons .= "<a href='{$changeslot}' target='_blank'><button type='button' class='btn btn-outline-dark btn-ft btn-sm mr-5' title='Change Booking Slot and Date' alt='Change Booking Slot and Date'><i class='fa fa-calendar' aria-hidden='true'></i></button></a>";
                }

                if(isset($data->slot) && $data->slot->name){
                    $slot_name = $data->slot->name;
                } else {
                    $slot_name = '';
                }

                if($data->payment_moad=='Card'){
                    if(isset($data->payment_type)){
                        $pay_mode_type = $data->payment_type;
                    } else {
                        $pay_mode_type = '';
                    }
                } else {
                    $pay_mode_type = '';
                }
                $nestedData['No']                   = ++$key;
                $nestedData['BookingId']            = '<strong> Source -'. $data->booking_from .'</strong><br>'.$data->tran_id. '<br><strong>' . date('d F Y', strtotime($data->created_at)) . '</strong>';
                $nestedData['SlotDate']             = '<strong> <u>'.$data->date.'</u></strong><br>'. $slot_name;
                $nestedData['CompanyName']          = $company_name;
                $nestedData['CustomerName']         = $data->user?$data->user->name:'';
                if(isset($data->service_name))
                $nestedData['ServiceName']          = $data->service_name;
                else
                $nestedData['ServiceName']          = $data->service?$data->service->name:''; 
                $nestedData['ServiceStatus']        = $status;

                if($data->payment_moad=='Card' && isset($data->payment_type))
                $nestedData['PaymentType']          = $data->payment_moad.' ('.$pay_mode_type.')';
                else
                $nestedData['PaymentType']          = $data->payment_moad.' '.$pay_mode;  
                 
                $nestedData['PaymentStatus']        = $data->payment_status;
                $nestedData['options']              = $buttons;
                $response[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $response   
                    );
            
        echo json_encode($json_data); 
    }

    public function draft_bookings(Request $request)
    {
        $bookings = Card::orderBy('id','DESC')->where('card_process','Complete')->where('payment_status', 'Draft')->get();
        return view('bookings.draft',compact('bookings','request'));
    }
    public function view($id='')
    {
        $card = Card::find($id);
        return view('bookings.show',compact('card'));
    }

    public function delete($id='')
    {
        $card = Card::find($id);

        
        $data['user_id']        = $card->user_id;
        $data['service_id']        = $card->service_id;
        $data['category_id']        = $card->category_id;
        $data['slot_id']        = $card->slot_id;
        $data['address_id']        = $card->address_id;
        $data['tran_id']        = $card->tran_id;
        $data['paymentTranId']        = $card->paymentTranId;
        $data['paymentLinkId']        = $card->paymentLinkId;
        $data['payment_moad']        = $card->payment_moad;
        $data['payment_status']        = $card->payment_status;
        $data['note']        = $card->note;
        $data['alternative_number']        = $card->alternative_number;
        $data['tip_id']        = $card->tip_id;
        $data['coupon_id']        = $card->coupon_id;
        $data['status']        = $card->status;
        $data['accept_user_id']        = $card->accept_user_id;
        $data['amount']        = $card->amount;
        $data['coupon_amt']        = $card->coupon_amt;
        $data['g_total']        = $card->g_total;
        $data['before_coupon_amt']        = $card->before_coupon_amt;
        $data['payment_collected']        = $card->payment_collected;
        $data['service_start_datetime']        = $card->service_start_datetime;
        $data['service_completed']        = $card->service_completed;
        $data['service_completed_date']        = $card->service_completed_date;
        $data['work_done']        = $card->work_done;
        $data['cod_status']        = $card->cod_status;
        $data['material_status']        = $card->material_status;
        $data['material_charge']        = $card->material_charge;
        $data['card_process']        = $card->card_process;
        $data['service_type']        = $card->service_type;
        $data['is_checkout']        = $card->is_checkout;
        $data['is_login']        = $card->is_login;
        $data['booking_from']        = $card->booking_from;
        $data['cencal_date']        = $card->cencal_date;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        \DB::table('delete_bookings')->insert($data);

        $card->delete();

        return redirect()->back()->with('error','Booking deleted successfully.');
    }

    public function invoice($id='')
    {
        $card = Card::find($id);
        $pdf = PDF::loadView('bookings.invoice', compact('card'));
        
        return $pdf->download('Order-Invoice.pdf');

        $card = Card::find($id);
        return view('bookings.invoice',compact('card'));
    }

    public function change_vendor(Request $request)
    {
        $card= Card::find($request->booking_id);
        $seller = Seller::where('user_id',$request->vendor_id)->first();

        if($card && $card->accept_user_id != $request->vendor_id){

            if($seller){

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
            } else {
                return redirect()->back()->with('error','Vendor not found.');
            }

            return redirect()->back()->with('success','Vendor change successfully.');

        } else {

            return redirect()->back()->with('error','This vendor is already assigned.');

        }
        
    }

    public function cencal_booking($id)
    {
        $card = Card::find($id);
       
        if($card){

            $params['cencal_date'] = date('Y-m-d');
            $params['status'] = 'Canceled';
            $card->update($params);

            if($card && $card->user && $card->user->email){

                $array['view']      = 'emails.booking_cancelled';
                $array['subject']   = 'Your booking has been Cancelled!';
                $array['data']      = $card;
                \Mail::to($card->user->email)->send(new \App\Mail\Mail($array));
            }

            if($card){
                $array['view']      = 'emails.booking_cancelled';
                $array['subject']   = 'Your booking has been Cancelled!';
                $array['data']      = $card;
                \Mail::to('urban.uae@gmail.com')->send(new \App\Mail\Mail($array));
            }
            

            return redirect()->back()->with('success','Booking cancelled successfully.'); 

        } else {

            return redirect()->back()->with('error','Booking not found.');

        }
    }

    public function service_completed($booking_id)
    {
        $card = Card::find($booking_id);
        
        if($card){
            
            $card       = Card::find($booking_id);
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
            // 26/03/2024 Offline module changes
            $offline_charge = $card?$card->offline_charge:'0';
            $jobValue += $offline_charge;

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
            // 26/03/2024 Offline module changes
            $offline_discount = $card?$card->offline_discount:'0';
            $jobValue -= $offline_discount;

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
            $um_erning = $um_comm_amt + $codCharge + $offline_charge + $tip + $_f_bnk_amt;
            
            // Step 8
            $vendor_erning = $jobValue - $um_erning;

            $params['job_value']        = $total;
            $params['card_total']       = $jobValue;
            $params['tip']              = $card?$card->tip_id:'0';
            $params['coupon_amt']       = $coupon_Amt;
            $params['offline_discount'] = $offline_discount;
            $params['cash_surcharge']   = $card?$card->cod_charge:'0';
            $params['offline_charge']   = $offline_charge;
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
                return redirect()->back()->with('error','Vendor not found.');
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
                \Mail::to($card->user?$card->user->email:'')->send(new \App\Mail\Mail($array));
            }

            if($card->user && $card->user->name && $card->user->phone){
                $customer_name = $card->user->name;
                $message = "Dear ($customer_name), \nThanks for shopping with us! To help improve our services, please click here https://9a4yhhhzdd7.typeform.com/to/FoG6FNhq to rate your experience. Thank you, Urbanmop.com";
                $msg = urlencode($message);
                $mobile = $card->user->phone;
                $res=send_sms_to_mobile($mobile,$msg);
            }

            $data['status']                     = 'Completed';
            $data['service_completed']          = 'Yes';
            $data['payment_collected']          = 'Yes';
            $data['work_done']                  = 'Yes';
            $data['service_completed_date']     = date('Y-m-d H:i:s');
            $card->update($data);
            update_booking($card->id);
            return redirect()->back()->with('success','Service Completed successfully.'); 

        } else {
           
            return redirect()->back()->with('error','Invalid booking id');
        }
    }

    function review()
    {
        $data['review'] = Review::orderBy('id','DESC')->get();

        return view('review.index',$data);
    }

    public function change_slot_date($id='')
    {
        $id = decrypt($id);

        $data['card'] = Card::find($id);

        $data['slots'] = Slot::all();

        $data['from'] = 'Live';

        return view('offline.reschedule',$data);
    }
}
