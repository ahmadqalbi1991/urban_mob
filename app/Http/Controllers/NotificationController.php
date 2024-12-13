<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\User;

class NotificationController extends Controller
{
    public function index()
    {
        $data['data'] = Notification::where('type','Vendor')->orderBy('id', 'DESC')->get();
        $data['type'] = 'vendor';
        return view('notification.index',$data);
    }

    public function customer()
    {
        $data['data'] = Notification::where('type','Customer')->orderBy('id', 'DESC')->get();
        $data['type'] = 'customer';
        return view('notification.index',$data);
    }

    public function send_noti(Request $request)
    {
        if($request->type=='Vendor'){
            $users = User::where('id','1161')->get();
            // $users = User::where('role','vendor')->where('is_active','1')->where('is_verified','1')->get();
        } else {
            // $users = User::where('role','customer')->where('is_active','1')->where('is_verified','1')->get();
            $users = User::where('id','453')->get();
        }

        foreach ($users as $key => $val) {
            if(isset($val->device_token)){
                
                $token  = $val->device_token;
                           
                $title  = 'Urbanmop';
                $body   = $request->title;
                $text   = $request->description;             
                send_notification($token, $title, $body, $text);
            }
        }
        
        $params['type']         = $request->type;
        $params['title']        = $request->title;
        $params['description']  = $request->description;
        $res = Notification::create($params);

        if($res){

            return redirect()->back()->with('success','Notification send successfully.');

        } else {

            return redirect()->back()->with('error','Something want wrong. Try again.');

        }
    }

    public function delete($id='')
    {

        Notification::whereId($id)->delete();

        return redirect()->back()->with('error','Delete successfully.');
    }
}
