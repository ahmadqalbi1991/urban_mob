<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\User;

use App\Item;

use App\ShopMembers;

use Carbon\Carbon;

use App\Package;

use App\PackageItem;

use App\PackageLeave;

use App\PackageAddons;

use App\PackageAddonItems;

// use App\Item;

use App\Order;

use App\OrderItem;

use App\Setting;

use App\Service;

use App\Card;

use Session;



class DashboardController extends Controller

{   

    public function __construct()

    {

        //die('--DashboardController');

        //$this->middleware('auth');

    }

    public function index()

    {   

        $currencies = DB::table('currencies')->where('default', '1')->first();

        if($currencies){
            Session::put('currencies', $currencies->symbol);
        } else {
            Session::put('currencies', '');
        }
        
        $users = User::select('*')->where('role', 'customer')->orWhere('role', 'vendor')->orWhere('role', 'user')->orderBy('id', 'desc')->Paginate('10');

        $service = Service::count();

        $orders = Card::where('status','Accept')->count();

        $total_vendors = User::where('role', 'vendor')->count();

        $total_users = User::where('role', 'user')->count();

        return view('home',compact('users','total_vendors','total_users','service','orders'));

    }



    public function member_request()

    {   

        $list = ShopMembers::with('customer','vendor')->get();

        return view('member_request',compact(['list']));

    }



    public function settings()

    {

        $settings['thought']=DB::table('settings')->where('type','thought-of-the-day')->get();

        $settings['video']=DB::table('settings')->where('type','banner-video')->get();

        $settings['ads']=DB::table('settings')->where('type','advertisement')->get();

        $settings['actions']=DB::table('settings')->where('type','call-to-action')->get();

        $settings['pages']=DB::table('settings')->where('type','page')->get();

        $settings['common']=DB::table('settings')->where('type','common-setting')->get();

        return view('settings',compact('settings'));

    }

    public function setting1()

    {

        $settings['video']=DB::table('settings')->where('type','banner-video')->get();

        $settings['ads']=DB::table('settings')->where('type','advertisement')->get();

        $settings['common']=DB::table('settings')->where('type','common-setting')->get();

        return view('setting1',compact('settings'));

    }

    public function setting2()

    {

        $settings['thought']=DB::table('settings')->where('type','thought-of-the-day')->get();

        $settings['actions']=DB::table('settings')->where('type','call-to-action')->get();

        $settings['app-actions-1']=DB::table('settings')->where('type','app-call-to-action-1')->get();

        $settings['app-actions-2']=DB::table('settings')->where('type','app-call-to-action-2')->get();

        $settings['app-actions-3']=DB::table('settings')->where('type','app-call-to-action-3')->get();

        return view('setting2',compact('settings'));

    }

    public function vendor_setting3()

    {
        $settings['pages']=DB::table('settings')->where('setting_from','Vendor')->get();

        return view('setting3_vendor',compact('settings'));
    }

    public function setting3()

    {
        $settings['pages']=DB::table('settings')->where('setting_from','Customer')->get();

        return view('setting3',compact('settings'));
    }

    public function page_create()
    {
        $data['frm'] = 'Customer';
        return view('add_page',$data);
    }

    public function vendor_page_create()
    {
        $data['frm'] = 'Vendor';
        return view('add_page',$data);
    }

    public function store_page(Request $request)
    {        
        $data['title'] = strtolower(str_replace(' ','-',$request->title));
        $data['type'] = 'page';
        $data['description'] = $request->description;
        $data['setting_from'] = $request->setting_from;
        Setting::create($data);
        if($request->setting_from=='Customer'){
            return redirect('setting3');
        } else {
            return redirect('vendor/setting3');
        }
        
    }

    public function setting_save(Request $request,$id,$type)

    {

       

        $res=DB::table('settings')->where('type', $type)->where('id', $id)->update(array('description' => $request->description)); 

       

        if($res){

            return redirect()->route('settings')

                        ->with('success','Setting saved successfully.');

        }

        else{

            return redirect()->route('settings')

                        ->with('error','Something is wrong, Try Later.');

        }

    }

    public function setting_update(Request $request)

    {

        $id = $request->input('id');

        $description = $request->input('description');

        $res=DB::table('settings')->where('id', $id)->update(array('description' => $description)); 

        $response=array();

        //if($res){

                $response['success']=true;

                $response['message']='Setting saved successfully!';

        // }

        // else{

        //     $response['success']=false;

        //     $response['message']='Nothing changed!';

        // }

        return response()->json($response);

    }

    public function setting_ads_save(Request $request,$id)

    {   



        $file=$request->old_file;

        if(isset($request->file) && !empty($request->file))

        {   

            $request->validate([

                'file' => 'required|mimes:jpg,png,jpeg,gif|max:2048',

            ]);

            $new_file = 'ads-'.time().'.'.$request->file->extension();  

            $upload=$request->file->move(public_path('uploads/ads'), $new_file);

            if($upload)

            {

                $file='uploads/ads/'.$new_file;

            }

        }



        $res=DB::table('settings')->where('type', 'advertisement')->where('id', $id)->update(array('file' => $file)); 

        if($res){

            return redirect()->route('setting1')

                        ->with('success','Advertisement saved successfully.');

        }

        else{

            return redirect()->route('setting1')

                        ->with('error','Something is wrong, Try Later.');

        }

    }



    public function setting_video_save(Request $request,$id)

    {   



        $file=$request->old_file;

        if(isset($request->file) && !empty($request->file))

        {   

            $request->validate([

                'file' => 'required|mimes:mp4|max:15360',

            ]);

            $new_file = time().'.'.$request->file->extension();  

            $upload=$request->file->move(public_path('uploads'), $new_file);

            if($upload)

            {

                $file='uploads/'.$new_file;

            }

        }



        $res=DB::table('settings')->where('type', 'banner-video')->where('id', $id)->update(array('file' => $file)); 

        if($res){

            return redirect()->route('setting1')

                        ->with('success','Banner Video saved successfully.');

        }

        else{

            return redirect()->route('setting1')

                        ->with('error','Something is wrong, Try Later.');

        }

    }



    public function test()

    {

            // $vendorId=3;

            // $query=Package::where('vendor_id',$vendorId);

            // $query->where('is_active',1);

            // $query->where('package_status','Accept');

            // $query->where(function ($query) {

            //     $query->where('package_type','Daily');

            //     $query->whereDate('start_date','<=', Carbon::today());

            // });

            // $query->orWhere(function ($query) {

            //     $query->where('package_type','Weekly');

            //     $query->whereDate('start_date','<=', Carbon::today());

            //     $query->where('week_day', Carbon::today()->format('l'));

            // });



            // $query=Package::where('is_active',1);

            // $query->where('package_status','Accept');

            // $query->where(function ($query) {

            //     $query->where('package_type','Alternate');

            //     $query->whereDate('start_date','<=', Carbon::today());

            // });

            // $packages=$query->with('items')->get();



            // dd($packages);



            echo Carbon::now()->addDays(2)->format('l');

                       

    }

    

}

