<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Item;
use App\ShopItems;
use App\ShopMembers;
use App\Package;
use App\PackageItem;
use App\PackageAddons;
use App\PackageAddonItems;
use App\PackageLeave;

class ShopController extends Controller
{

    public function index()
    {   
        //die('shop-index');
        $total_items = ShopItems::where('user_id',Auth::id())->count();
        $total_customers = ShopMembers::where('vendor_id', Auth::id())->where('request_status', 'Accept')->count();
        $total_packages = Package::where('vendor_id',Auth::id())->where('package_status','Accept')->count();
        $total_payment=vendorPayment(Auth::id());
        return view('shop.shop_home',compact('total_items','total_customers','total_packages','total_payment'));
    }
    public function shop_items()
    {   
        $items = Item::active()->get();
        $uniq_items = ShopItems::where('user_id',Auth::id())->select('item_id')->distinct()->get();
        $shop_items=array();
        if(!empty($uniq_items))
        {
            foreach($uniq_items as $key=>$value)
            {   
                $shop_items[$key]=Item::where('id',$value->item_id)->first();
                $shop_items[$key]['packings']=ShopItems::where('user_id',Auth::id())->where('item_id',$value->item_id)->Paginate('10');
            }
        }
        return view('shop.shop_items', compact(['items','shop_items']));
    }
    public function shop_item_process(Request $request)
    {

        if($request->item)
        {
            return redirect()->route('shop.item',$request->item)
                ->with('info','Shop Item Add/Update here.');
        }
        else
        {
            return redirect()->route('shop.items')
                ->with('Error','Shop Item not selected.');
        }
    }
    public function shop_item($item_id)
    {   
        $item = Item::find($item_id);
        $shop_items = ShopItems::where('user_id',Auth::id())->where('item_id',$item_id)->get();
        return view('shop.shop_item', compact(['item','shop_items']));
    }
    public function shop_item_store(Request $request,$item_id)
    {   
        if($request)
        {   
            $data=[]; $res=false;
            for ($x = 0; $x < count($request->unit); $x++) {

                if($request->price[$x])
                {
                    $data[]=array(
                    "user_id"=>Auth::id(),
                    "item_id"=>$item_id,
                    "quantity"=>$request->quantity[$x],
                    "unit"=>$request->unit[$x],
                    "price"=>$request->price[$x],
                    "is_available"=>1,
                    );
                }
              }
            if($data)
            {
                $res=ShopItems::insert($data);
            }
            if($res){
                return redirect()->route('shop.item',$item_id)
                            ->with('success','Item pack saved successfully.');
            }
            else{
                return redirect()->route('shop.item',$item_id)
                            ->with('error','Something is wrong, Try Later.');
            }
        }
        return redirect()->route('shop.item',$item_id)
        ->with('error','Something is wrong, Try Later.');
    }
    public function shop_item_update(Request $request,$item_id)
    {   
        //dd($request);
        $data=array();
        $result=false;
        if($request)
        {   
            for ($x = 0; $x < count($request->shop_item_id); $x++) {

                if($request->price[$x])
                {
                    $data=array(
                    "user_id"=>Auth::id(),
                    "item_id"=>$item_id,
                    "quantity"=>$request->quantity[$x],
                    "unit"=>$request->unit[$x],
                    "price"=>$request->price[$x],
                    "is_available"=>( isset($request->available[$x]) && $request->available[$x]=='on')? 1 : 0,
                    );

                    $res=ShopItems::where('id',$request->shop_item_id[$x])->update($data);
                    if($res)
                    {
                        $result=true; 
                    }
                }
              }
            
            if($result){
                return redirect()->route('shop.item',$item_id)
                            ->with('success','Item pack saved successfully.');
            }
            else{
                return redirect()->route('shop.item',$item_id)
                            ->with('error','Something is wrong, Try Later.');
            }
        }
        return redirect()->route('shop.item',$item_id)
        ->with('error','Something is wrong, Try Later.');
    }
    public function shop_request()
    {   
        $ShopMembers = new ShopMembers();
        $new_list =$ShopMembers->getShopMembers(Auth::id(),'Pending');
        $list =$ShopMembers->getShopMembers(Auth::id(),'Decline');
        return view('shop.shop_request',compact(['new_list','list']));
    }
    public function request_status($request_id,$status)
    {
        $request = ShopMembers::find($request_id);
        $request->request_status = $status;
        if($request->request_status=='Accept')
        {
            $request->is_active = 1;
        }
        $res=$request->save();
        if($res){
            return redirect()->route('shop.request')
                        ->with('success','Request status changed successfully.');
        }
        else{
            return redirect()->route('shop.request')
                        ->with('error','Something is wrong, Try Later.');
        }
    }
    public function package_request()
    {
        $new_list = Package::where('vendor_id',Auth::id())->where('package_status','Pending')->with('customer')->get();
        $list = Package::where('vendor_id',Auth::id())->where('package_status','Cancel')->orWhere('package_status','Reject')->with('customer')->get();
        return view('shop.package_request', compact(['new_list','list']));
    }
    public function my_packages()
    {
        $packages = Package::where('vendor_id',Auth::id())->where('package_status','Accept')->with('customer')->get();
        //dd($packages);
        return view('shop.my_packages', compact(['packages']));
    }
    public function my_customers()
    {   
        $ShopMembers = new ShopMembers();
        $list =$ShopMembers->getShopMembers(Auth::id(),'Accept');
        return view('shop.my_customers',compact('list'));
    }
    public function package_request_status($request_id,$status)
    {
        $request = Package::find($request_id);
        $request->package_status = $status;
        if($request->package_status=='Accept')
        {
            $request->is_active = 1;
        }
        $res=$request->save();
        if($res){
            return redirect()->route('shop.package.request')
                        ->with('success','Request status changed successfully.');
        }
        else{
            return redirect()->route('shop.package.request')
                        ->with('error','Something is wrong, Try Later.');
        }
    }
    public function package_items($id)
    {   
        $id=decrypt($id);
        $package = Package::where('id',$id)->with('items','customer','leave')->first();
        //dd($package);
        if($package){
            return view('shop.package_items',compact('package'));
        }
        else{
            return redirect()->route('shop')
                        ->with('warning','Something is wrong, Try Later!');
        }
    }
    public function package_addons_request()
    {
        $new_list = PackageAddons::where('vendor_id',Auth::id())->where('status','Pending')->with('customer')->get();
        $list = PackageAddons::where('vendor_id',Auth::id())->where('status','Cancel')->orWhere('status','Reject')->with('customer')->get();
        return view('shop.package_addons_request', compact(['new_list','list']));
    }
    public function my_package_addons()
    {
        $addons = PackageAddons::where('vendor_id',Auth::id())->where('status','Accept')->with('customer')->get();
        //dd($addons);
        return view('shop.my_package_addons', compact(['addons']));
    }
    public function addon_request_status($request_id,$status)
    {
        $request = PackageAddons::find($request_id);
        $request->status = $status;
        if($request->status=='Accept')
        {
            $request->is_active = 1;
        }
        $res=$request->save();
        if($res){
            return redirect()->route('shop.addon.request')
                        ->with('success','Request status changed successfully.');
        }
        else{
            return redirect()->route('shop.addon.request')
                        ->with('error','Something is wrong, Try Later.');
        }
    }
    public function package_addon_items($id)
    {
        $id=decrypt($id);
        $addon = PackageAddons::where('id',$id)->with('items','customer')->first();
        //dd($addon);
        if($addon){
            return view('shop.package_addon_items',compact('addon'));
        }
        else{
            return redirect()->route('shop')
                        ->with('warning','Something is wrong, Try Later!');
        }
    }
    public function shop_package_leave()
    {   
       
        $vendor_id=Auth::id();
        $list = PackageLeave::whereHas('package', function($q) use($vendor_id){
            $q->where('vendor_id', '=', $vendor_id);
        })->with('package')->orderBy('leave_date','DESC')->get();

        return view('shop.package_leave', compact(['list']));
    }
}
