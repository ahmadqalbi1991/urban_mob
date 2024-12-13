<?php

namespace App\Http\Controllers;

use App\User;
use App\Card;
use App\Seller;
use App\Service;
use App\HomeSetting;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function partner_details_report(Request $request)
    {
        if($request->service || $request->from_date || $request->to_date){
            $users = get_seller_info_by_service($request->service);
            $user_ids = [];
            foreach ($users as $key => $user) {
                array_push($user_ids, $user->id);
            }
   
            $query = User::where('role','vendor')->select();
                                  if($request->from_date && $request->to_date)
                                  {
                                    $query->whereBetween('created_at',[$request->from_date, $request->to_date]);
                                  }
                                  if($request->service)
                                  {
                                    $query->whereIn('id',$user_ids);
                                  }
                 $data['users'] = $query->where('is_active','1')->where('is_verified','1')->orderBy('id', 'DESC')->get();

            $data['service_id'] = $request->service;
            $data['from_date']  = $request->from_date;
            $data['to_date']    = $request->to_date;
           
        } else {
            $data['users'] = User::where('role','vendor')->where('is_active','1')->where('is_verified','1')->orderBy('id', 'DESC')->get();
            $data['service_id'] = '';
            $data['from_date']  = '';
            $data['to_date']    = '';
        }
        $data['service'] = Service::where('status','1')->get();
    
        return view('reports.partner_detail_report',$data);
    }

    public function customer_details_report(Request $request)
    { 
        if($request->last_booking || $request->from_date || $request->to_date){
            $cards = Card::where('payment_status','True')->whereDate('created_at', '=', $request->last_booking)->get();
            $user_ids = [];
           
            foreach ($cards as $key => $value) {
                array_push($user_ids, $value->user_id);
            }

            $query = User::where('role','customer')->select();
                                  if($request->from_date && $request->to_date)
                                  $query->whereBetween('created_at',[$request->from_date, $request->to_date]);
                                  if($request->last_booking)
                                  $query->whereIn('id',$user_ids);
                 $data['users'] = $query->where('is_active','1')->where('is_verified','1')->orderBy('users.id', 'DESC')->get();
            
            $data['last_booking'] = $request->last_booking;
            $data['from_date']    = $request->from_date;
            $data['to_date']      = $request->to_date;
        } else {
            $data['users'] = User::where('role','customer')->where('is_active','1')->where('is_verified','1')->orderBy('users.id', 'DESC')->get();
            $data['last_booking'] = '';
            $data['from_date'] = '';
            $data['to_date'] = '';
        }
        
        return view('reports.customer_detail_report',$data);
    }

    public function revenue_bookings_report(Request $request)
    {
        if(isset($request) && $request->method || $request->status || $request->company_name || $request->service || $request->from_date || $request->to_date){
            $user_ids = [];
            if($request->company_name){
                $seller = Seller::where('company_name', 'like', '%'.$request->company_name.'%')->get();
               
                foreach ($seller as $key => $sel) {
                    array_push($user_ids, $sel->user_id);
                }
                $data['company_name'] = $request->company_name;
            } else {
               $data['company_name'] = ''; 
            }
            $query = Card::select();
                        $query->where('card_process','Complete');
                        if($request->method){
                            $query->where('payment_moad', 'like', '%'.$request->method.'%');
                        }
                        
                        if($request->status)
                        $query->where('payment_status', 'like', '%'.$request->status.'%');
                        if($request->service)
                        $query->where('service_id', 'like', '%'.$request->service.'%');
                        if($request->from_date && $request->to_date)
                        $query->whereBetween('created_at',[$request->from_date, $request->to_date]);
                        if(count($user_ids))
                        $query->whereIn('accept_user_id',$user_ids);

            $data['bookings'] = $query->orderBy('id', 'DESC')->get();

            $data['method'] = $request->method;
            $data['status'] = $request->status;
            $data['service_id'] = $request->service;
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;

        } else {
            $data['bookings'] = Card::where('card_process','Complete')->orderBy('id', 'DESC')->get();
            $data['service_id'] = '';
            $data['company_name'] = '';
            $data['method'] = '';
            $data['status'] = '';
            $data['from_date'] = '';
            $data['to_date'] = '';
        }
        $data['service'] = Service::where('status','1')->get();
        return view('reports.revenue_bookings_report',$data);
    }

    public function partner_settlement_report(Request $request)
    {
        if($request->service || $request->company || $request->from_date || $request->to_date){

            $users = get_seller_info_by_service($request->service);
            $user_ids = [];
            foreach ($users as $key => $user) {
                array_push($user_ids, $user->id);
            }
            $c_user_ids = [];
            if($request->company_name){
                if($user_ids){
                    $seller = Seller::where('company_name', 'like', '%'.$request->company_name.'%')->whereIn('user_id',$user_ids)->get();
                } else {
                    $seller = Seller::where('company_name', 'like', '%'.$request->company_name.'%')->get();
                }
                
               
                foreach ($seller as $key => $sel) {
                    array_push($c_user_ids, $sel->user_id);
                }
                $data['company_name'] = $request->company_name;
            } else {
               $data['company_name'] = ''; 
            }

            $query = Card::select();
                        $query->where('card_process','Complete');
                        if($request->from_date && $request->to_date)
                        $query->whereBetween('created_at',[$request->from_date, $request->to_date]);
                        if($request->company_name && count($c_user_ids))
                        $query->whereIn('accept_user_id',$c_user_ids);
                        else if(count($user_ids))
                        $query->whereIn('accept_user_id',$user_ids);

            $data['bookings'] = $query->orderBy('id', 'DESC')->get();

            $data['service_id'] = $request->service;
            $data['company_name'] = $request->company_name;
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
        } else {
            $data['bookings'] = Card::where('card_process','Complete')->orderBy('id', 'DESC')->get();
            $data['service_id'] = '';
            $data['company_name'] = '';
            $data['from_date'] = '';
            $data['to_date'] = '';
        }
        $data['service'] = Service::where('status','1')->get();
        $data['setting'] = HomeSetting::first();
        return view('reports.partner_settlement_report',$data);
    }
}
