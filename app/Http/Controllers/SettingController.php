<?php

namespace App\Http\Controllers;

use App\HomeSetting;
use App\Slider;
use App\Question;
use App\WebSetting;
use App\RewardConfig;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setting()
    {
        $data['setting'] = HomeSetting::first();
        $data['slider'] = Slider::first();
        return view('setting.setting',$data);
    }

    public function admin_setting()
    {
        $data['setting'] = HomeSetting::first();
        return view('setting.admin_setting',$data);
    }

    public function update(Request $request)
    {
        if($request->admin_logo || $request->admin_side_logo){
            // $this->validate($request,[
            //     'admin_logo' => 'required',
            // ]);
        } else {
           $this->validate($request,[
                'header_logo' => 'required',
                'footer_logo' => 'required',
            ]); 
        }

        if($request->hasFile('header_logo')){
            $imageName = 'header_logo-'.time().'.'.$request->header_logo->extension(); 
            $path = $request->header_logo->move(public_path('/uploads/home/'), $imageName);
            $data['header_logo'] = $imageName;
        }

        if($request->hasFile('footer_logo')){
            $imageName = 'footer_logo-'.time().'.'.$request->footer_logo->extension(); 
            $path = $request->footer_logo->move(public_path('/uploads/home/'), $imageName);
            $data['footer_logo'] = $imageName;
        }

        if($request->hasFile('admin_logo')){
            $imageName = 'admin_logo-'.time().'.'.$request->admin_logo->extension(); 
            $path = $request->admin_logo->move(public_path('/uploads/home/'), $imageName);
            $data['admin_logo'] = $imageName;
        }

        if($request->hasFile('admin_side_logo')){
            $imageName = 'admin_side_logo-'.time().'.'.$request->admin_side_logo->extension(); 
            $path = $request->admin_side_logo->move(public_path('/uploads/home/'), $imageName);
            $data['admin_side_logo'] = $imageName;
        }

        $setting = HomeSetting::first();
        if($setting){
            $setting->update($data);
        } else {
            HomeSetting::create($data);
        }
        
        return redirect()->back()->with('success','Setting updated successfully.');
    }

    public function remove_footer_logo()
    {
        $data['footer_logo'] = '';
        $setting = HomeSetting::first();
        $setting->update($data);
        return redirect()->back()->with('success','Footer logo delete successfully.');
    }

    public function remove_header_logo()
    {
        $data['header_logo'] = '';
        $setting = HomeSetting::first();
        $setting->update($data);
        return redirect()->back()->with('success','Header logo delete successfully.');
    }

    public function remove_admin_logo()
    {
        $data['admin_logo'] = '';
        $setting = HomeSetting::first();
        $setting->update($data);
        return redirect()->back()->with('success','Admin logo delete successfully.');
    }

    public function remove_admin_side_logo()
    {
        $data['admin_side_logo'] = '';
        $setting = HomeSetting::first();
        $setting->update($data);
        return redirect()->back()->with('success','Admin side logo delete successfully.');
    }

    public function remove_first_slider($type)
    {
        if($type=='one'){

            $data['first_slider'] = Null;
            $data['first_title'] = Null;
            $data['first_link'] = Null;
            $data['first_description'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='two') {

            $data['second_slider'] = Null;
            $data['second_title'] = Null;
            $data['second_link'] = Null;
            $data['second_description'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='three') {

            $data['third_slider'] = Null;
            $data['third_title'] = Null;
            $data['third_link'] = Null;
            $data['third_description'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } else {
             return redirect()->back()->with('success','Some thing want wrong.');
        }
         return redirect()->back()->with('success','Delete successfully.');
    }

    public function sliderupdate(Request $request)
    {

        if($request->hasFile('first_slider')){
            $imageName = $request->first_title.'-'.time().'.'.$request->first_slider->extension(); 
            $path = $request->first_slider->move(public_path('/uploads/slider/'), $imageName);
            $data['first_slider'] = $imageName;
        }

        if($request->hasFile('second_slider')){
            $simageName = $request->second_title.'-'.time().'.'.$request->second_slider->extension(); 
            $path = $request->second_slider->move(public_path('/uploads/slider/'), $simageName);
            $data['second_slider'] = $simageName;
        }

        if($request->hasFile('third_slider')){
            $timageName = $request->third_title.'-'.time().'.'.$request->third_slider->extension(); 
            $path = $request->third_slider->move(public_path('/uploads/slider/'), $timageName);
            $data['third_slider'] = $timageName;
        }

        $data['first_title'] = $request->first_title;
        $data['first_link'] = $request->first_link;
        $data['first_description'] = $request->first_description;

        $data['second_title'] = $request->second_title;
        $data['second_link'] = $request->second_link;
        $data['second_description'] = $request->second_description;

        $data['third_title'] = $request->third_title;
        $data['third_link'] = $request->third_link;
        $data['third_description'] = $request->third_description;

        $slider = Slider::first();
        if($slider){
            $slider->update($data);
        } else {
            Slider::create($data);
        }
        
        return redirect()->back()->with('success','Setting updated successfully.');
    }


    public function appsliderupdate(Request $request)
    {

        if($request->hasFile('app_first_slider')){
            $imageName = $request->app_first_title.'-'.time().'.'.$request->app_first_slider->extension(); 
            $path = $request->app_first_slider->move(public_path('/uploads/slider/'), $imageName);
            $data['app_first_slider'] = $imageName;
        }

        if($request->hasFile('app_second_slider')){
            $simageName = $request->app_second_title.'-'.time().'.'.$request->app_second_slider->extension(); 
            $path = $request->app_second_slider->move(public_path('/uploads/slider/'), $simageName);
            $data['app_second_slider'] = $simageName;
        }

        if($request->hasFile('app_third_slider')){
            $timageName = $request->app_third_title.'-'.time().'.'.$request->app_third_slider->extension(); 
            $path = $request->app_third_slider->move(public_path('/uploads/slider/'), $timageName);
            $data['app_third_slider'] = $timageName;
        }

        $data['app_first_title'] = $request->app_first_title;
        $data['first_link'] = $request->first_link;
        $data['first_description'] = $request->first_description;

        $data['app_second_title'] = $request->app_second_title;
        $data['app_second_link'] = $request->app_second_link;
        $data['app_second_description'] = $request->app_second_description;

        $data['app_third_title'] = $request->app_third_title;
        $data['app_third_link'] = $request->app_third_link;
        $data['app_third_description'] = $request->app_third_description;
        
        $slider = Slider::first();
        if($slider){
            $slider->update($data);
        } else {
            Slider::create($data);
        }
        
        return redirect()->back()->with('success','Setting updated successfully.');
    }

    public function remove_app_slider($type)
    {
        if($type=='one'){

            $data['app_first_slider'] = Null;
            $data['app_first_title'] = Null;
            $data['app_first_link'] = Null;
            $data['app_first_description'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='two') {

            $data['app_second_slider'] = Null;
            $data['app_second_title'] = Null;
            $data['app_second_link'] = Null;
            $data['app_second_description'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='three') {

            $data['app_third_slider'] = Null;
            $data['app_third_title'] = Null;
            $data['app_third_link'] = Null;
            $data['app_third_description'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } else {
             return redirect()->back()->with('success','Some thing want wrong.');
        }
         return redirect()->back()->with('success','Delete successfully.');
    }

    public function appsignupsliderupdate(Request $request)
    {

        if($request->hasFile('app_sign_first_slider')){
            $imageName = $request->app_first_title.'-'.time().'.'.$request->app_sign_first_slider->extension(); 
            $path = $request->app_sign_first_slider->move(public_path('/uploads/signup-slider/'), $imageName);
            $data['app_sign_first_slider'] = $imageName;
        }

        if($request->hasFile('app_sign_second_slider')){
            $simageName = $request->app_second_title.'-'.time().'.'.$request->app_sign_second_slider->extension(); 
            $path = $request->app_sign_second_slider->move(public_path('/uploads/signup-slider/'), $simageName);
            $data['app_sign_second_slider'] = $simageName;
        }

        if($request->hasFile('app_sign_third_slider')){
            $timageName = $request->app_third_title.'-'.time().'.'.$request->app_sign_third_slider->extension(); 
            $path = $request->app_sign_third_slider->move(public_path('/uploads/signup-slider/'), $timageName);
            $data['app_sign_third_slider'] = $timageName;
        }

        if($request->hasFile('app_sign_for_slider')){
            $timageName = $request->app_third_title.'-'.time().'.'.$request->app_sign_for_slider->extension(); 
            $path = $request->app_sign_for_slider->move(public_path('/uploads/signup-slider/'), $timageName);
            $data['app_sign_for_slider'] = $timageName;
        }

        $data['app_sign_first_title']   = $request->app_sign_first_title;
        $data['app_sign_first_link']    = $request->app_sign_first_link;

        $data['app_sign_second_title']  = $request->app_sign_second_title;
        $data['app_sign_second_link']   = $request->app_sign_second_link;

        $data['app_sign_third_title']   = $request->app_sign_third_title;
        $data['app_sign_third_link']    = $request->app_sign_third_link;

        $data['app_sign_for_title']     = $request->app_sign_for_title;
        $data['app_sign_for_link']      = $request->app_sign_for_link;
        
        $slider = Slider::first();
        if($slider){
            $slider->update($data);
        } else {
            Slider::create($data);
        }
        
        return redirect()->back()->with('success','Setting updated successfully.');
    }

    public function vendorappsignupsliderupdate(Request $request)
    {

        if($request->hasFile('vendor_app_sign_first_slider')){
            $imageName = $request->vendor_app_sign_first_title.'-'.time().'.'.$request->vendor_app_sign_first_slider->extension(); 
            $path = $request->vendor_app_sign_first_slider->move(public_path('/uploads/signup-slider/'), $imageName);
            $data['vendor_app_sign_first_slider'] = $imageName;
        }

        if($request->hasFile('vendor_app_sign_second_slider')){
            $simageName = $request->vendor_app_sign_second_title.'-'.time().'.'.$request->vendor_app_sign_second_slider->extension(); 
            $path = $request->vendor_app_sign_second_slider->move(public_path('/uploads/signup-slider/'), $simageName);
            $data['vendor_app_sign_second_slider'] = $simageName;
        }

        if($request->hasFile('vendor_app_sign_third_slider')){
            $timageName = $request->vendor_app_sign_third_title.'-'.time().'.'.$request->vendor_app_sign_third_slider->extension(); 
            $path = $request->vendor_app_sign_third_slider->move(public_path('/uploads/signup-slider/'), $timageName);
            $data['vendor_app_sign_third_slider'] = $timageName;
        }

        if($request->hasFile('vendor_app_sign_for_slider')){
            $timageName = $request->vendor_app_sign_for_title.'-'.time().'.'.$request->vendor_app_sign_for_slider->extension(); 
            $path = $request->vendor_app_sign_for_slider->move(public_path('/uploads/signup-slider/'), $timageName);
            $data['vendor_app_sign_for_slider'] = $timageName;
        }

        $data['vendor_app_sign_first_title']   = $request->vendor_app_sign_first_title;
        $data['vendor_app_sign_first_link']    = $request->vendor_app_sign_first_link;

        $data['vendor_app_sign_second_title']  = $request->vendor_app_sign_second_title;
        $data['vendor_app_sign_second_link']   = $request->vendor_app_sign_second_link;

        $data['vendor_app_sign_third_title']   = $request->vendor_app_sign_third_title;
        $data['vendor_app_sign_third_link']    = $request->vendor_app_sign_third_link;

        $data['vendor_app_sign_for_title']     = $request->vendor_app_sign_for_title;
        $data['vendor_app_sign_for_link']      = $request->vendor_app_sign_for_link;
        
        $slider = Slider::first();
        if($slider){
            $slider->update($data);
        } else {
            Slider::create($data);
        }
        
        return redirect()->back()->with('success','Setting updated successfully.');
    }

    public function vendor_remove_sign_slider($type)
    {
        if($type=='one'){

            $data['vendor_app_sign_first_slider'] = Null;
            $data['vendor_app_sign_first_title'] = Null;
            $data['vendor_app_sign_first_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='two') {

            $data['vendor_app_sign_second_slider'] = Null;
            $data['vendor_app_sign_second_title'] = Null;
            $data['vendor_app_sign_second_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='three') {

            $data['vendor_app_sign_third_slider'] = Null;
            $data['vendor_app_sign_third_title'] = Null;
            $data['vendor_app_sign_third_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='for') {

            $data['vendor_app_sign_for_slider'] = Null;
            $data['vendor_app_sign_for_title'] = Null;
            $data['vendor_app_sign_for_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } else {
             return redirect()->back()->with('success','Some thing want wrong.');
        }
         return redirect()->back()->with('success','Delete successfully.');
    }

    public function remove_sign_slider($type)
    {
        if($type=='one'){

            $data['app_sign_first_slider'] = Null;
            $data['app_sign_first_title'] = Null;
            $data['app_sign_first_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='two') {

            $data['app_sign_second_slider'] = Null;
            $data['app_sign_second_title'] = Null;
            $data['app_sign_second_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='three') {

            $data['app_sign_third_slider'] = Null;
            $data['app_sign_third_title'] = Null;
            $data['app_sign_third_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='for') {

            $data['app_sign_for_slider'] = Null;
            $data['app_sign_for_title'] = Null;
            $data['app_sign_for_link'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } else {
             return redirect()->back()->with('success','Some thing want wrong.');
        }
         return redirect()->back()->with('success','Delete successfully.');
    }

    public function homebanner(Request $request)
    {

        if($request->hasFile('home_banner')){
            $imageName = mt_rand(1000000, 9999999).'-'.time().'.'.$request->home_banner->extension(); 
            $path = $request->home_banner->move(public_path('/uploads/banner/'), $imageName);
            $data['home_banner'] = $imageName;
        }

        if($request->hasFile('second_home_banner')){
            $simageName = mt_rand(1000000, 9999999).'-'.time().'.'.$request->second_home_banner->extension(); 
            $path = $request->second_home_banner->move(public_path('/uploads/banner/'), $simageName);
            $data['second_home_banner'] = $simageName;
        }

        if($request->hasFile('third_home_banner')){
            $timageName = mt_rand(1000000, 9999999).'-'.time().'.'.$request->third_home_banner->extension(); 
            $path = $request->third_home_banner->move(public_path('/uploads/banner/'), $timageName);
            $data['third_home_banner'] = $timageName;
        }

        $data['home_banner_link'] = $request->home_link;
        $data['second_home_banner_link'] = $request->second_home_link;
        $data['third_home_banner_link'] = $request->third_home_link;
        
        $slider = Slider::first();
        if($slider){
            $slider->update($data);
        } else {
            Slider::create($data);
        }
        
        return redirect()->back()->with('success','Setting updated successfully.');
    }

    public function remove_home_slider($type)
    {
        if($type=='one'){

            $data['home_banner_link'] = Null;
            $data['home_banner'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='two') {

            $data['second_home_banner_link'] = Null;
            $data['second_home_banner'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } elseif ($type=='three') {

            $data['third_home_banner_link'] = Null;
            $data['third_home_banner'] = Null;

            $slider = Slider::first();
            $slider->update($data);

        } else {
             return redirect()->back()->with('success','Some thing want wrong.');
        }
         return redirect()->back()->with('success','Delete successfully.');
    }

    public function question()
    {
        $data['data'] = Question::paginate(10);
        return view('question.index',$data);
    }

    public function web_setting()
    {
        $data['setting'] = HomeSetting::first();
        $data['rewards'] = RewardConfig::where('name', 'reward')->first();
        return view('web_setting.setting',$data);
    }

    public function update_web_setting(Request $request)
    {
        $data['min_cart_value']     = $request->min_cart_value;
        $data['cash_surcharge']     = $request->cash_surcharge;
        $data['um_commission']      = $request->um_commission;
        $data['bank_percentage']    = $request->bank_percentage;
        $data['bank_charges']       = $request->bank_charges;
        $data['admin_mobile']       = $request->admin_mobile;

        if($request->hasFile('payment_barcode')){
            $timageName = time().'.'.$request->payment_barcode->extension(); 
            $path = $request->payment_barcode->move(public_path('/uploads/payment_barcode/'), $timageName);
            $data['payment_barcode'] = '/uploads/payment_barcode/'.$timageName;
        }

        $reward = RewardConfig::where('name', 'reward')->first();
        $reward->update(['value' => $request->points]);

        $setting = HomeSetting::first();
        $setting->update($data);
        return redirect()->back()->with('success','Update successfully.');
    }

}
