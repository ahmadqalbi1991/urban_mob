<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rule;

use App\Rules\MatchOldPassword;

use Spatie\Permission\Models\Role;

use App\User;

use App\ShopDetail;

use App\Item;

use App\ShopItems;

use App\ShopMembers;

use App\Invoice;

use App\Transection;

use App\Package;

use App\Card;

use App\Seller;

use App\Service;

use App\SellerService;

use App\PayOutBalance;

use App\Payment;

use Carbon\Carbon;

use DB;

class UserController extends Controller

{
    public function customers(Request $request)
    {
        // Query to fetch users with the 'customer' role
        $query = \DB::table('users')
            ->where('role', 'customer')
            ->leftJoin('reward_users', 'users.id', '=', 'reward_users.user_id') // Join reward_users table
            ->select('users.*', \DB::raw('SUM(reward_users.points) as reward_points')) // Summing reward points
            ->groupBy('users.id') // Group by user ID to avoid duplicates
            ->orderBy('users.id', 'DESC'); // Order by user ID

        // If there's a search, filter the results
        if ($request->search) {
            $query->where(function($query) use ($request) {
                $query->where('users.name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('users.phone', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Paginate the results
        $perPage = 10; // Number of results per page
        $users = $query->paginate($perPage)->appends($request->all());

        // Return the view with users and the request object
        return view('customers', compact('users', 'request'));
    }

    
    public function users(Request $request)

    {   

        $query = User::select('*')->where('role', 'user');

        if($request->search)

        {

            

            $query->where(function($query) use ($request){

                $query->where('name', 'LIKE', '%'.$request->search.'%')

                      ->orWhere('email', 'LIKE', '%'.$request->search.'%')

                      ->orWhere('phone', 'LIKE', '%'.$request->search.'%');

            });

        }

        $users = $query->orderBy('id','DESC')->paginate(10);

        return view('users',compact('users','request'));

    }

    public function vendors(Request $request)

    {   

        $query = User::select('*')->where('role', 'vendor');

        if($request->search)

        {

            $query->where(function($query) use ($request){

                $query->where('name', 'LIKE', '%'.$request->search.'%')

                      ->orWhere('email', 'LIKE', '%'.$request->search.'%')

                      ->orWhere('phone', 'LIKE', '%'.$request->search.'%');

            });

        }

        $users = $query->orderBy('id','DESC')->get();
        $roles = Role::all();
        return view('vendors',compact('users','request','roles'));

    }

    public function operators(Request $request)

    {   

        $query = User::select('*')->where('role', 'admin');

        if($request->search)

        {

            $query->where(function($query) use ($request){

                $query->where('name', 'LIKE', '%'.$request->search.'%')

                      ->orWhere('email', 'LIKE', '%'.$request->search.'%')

                      ->orWhere('phone', 'LIKE', '%'.$request->search.'%');

            });

        }

        $users = $query->orderBy('id','DESC')->get();
        $roles = Role::all();
        return view('operator',compact('users','request','roles'));

    }

    public function vendor_edit($id='')
    {
        $value = User::find($id);
        $service = Service::where('status','1')->get();
        $seller = Seller::where('user_id',$id)->first();
        $seller_service = SellerService::where('seller_id',$seller?$seller->id:'')->get();
        $seller_service_id = [];
        foreach ($seller_service as $key => $item) {
            array_push($seller_service_id, $item->service_id);
        }
        return view('vendor_edit',compact('value','service','seller_service_id','seller'));
    }

    public function vendor_view($id='')
    {
        $value = User::find($id);
        $seller = Seller::where('user_id',$id)->first();
        $seller_service = SellerService::where('seller_id',$seller?$seller->id:'')->get();
        $seller_service_id = [];
        foreach ($seller_service as $key => $item) {
            if($item->service)
            array_push($seller_service_id, $item->service->name);
        }
        
        return view('vendor_view',compact('value','seller_service_id','seller'));
    }

    public function vendor_add(Request $request)

    {

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',

            'phone' => 'required|unique:users',

            'password' => 'required|string|min:5'

        ]);

  

        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

            'password' => Hash::make($request->password),

            'role'=>'admin',

            'registered_by'=>'Web'

        ]);
        if($request->role){
            $user->assignRole($request->role);
        }

        return redirect()->back()->with('success','Created successfully.');

    }

    public function vendor_update(Request $request, $id)

    {  
     
        $request->validate([

            'name' => 'required|string|max:255',

            // 'email' => ['required','string','email','max:255',Rule::unique('users','email')->ignore($request->id)],

            'phone' => ['required','digits:9',Rule::unique('users','phone')->ignore($id)]

        ]);

        

        if(isset($request->password) && !empty($request->password))

        {   

            $request->validate([

                'password' => 'required|string|min:5'

            ]);

        }

        $input=[

            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

       ];

       if(isset($request->password) && !empty($request->password))

        {   

            $input['password']=Hash::make($request->password);

        }

        
        User::where('id', $id)->update($input);

        $user = User::find($id);

        if($request->role){
            $user->assignRole($request->role);
        }
        
        if($request->service_id){
            if($request->seller_id){
                SellerService::where('seller_id',$request->seller_id)->delete();
            }
           
            foreach ($request->service_id as $key => $ser_id) {
                $params['seller_id'] = $request->seller_id;              
                $params['service_id'] = $ser_id; 
                SellerService::create($params);            
            }
        }
        if($request->from=='Operator'){
            return back()->with('success','Operator update successfully.');
        } else {
            return redirect()->route('vendors')->with('success','Vendor update successfully.');
        }
        

    }

    public function customer_add(Request $request)

    {

        

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',

            'phone' => 'required|unique:users',

            'password' => 'required|string|min:5'

        ]);

  

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

            'password' => Hash::make($request->password),

            'role'=>'customer',

            'registered_by'=>'Web'

        ]);

   

        return redirect()->route('customers')->with('success','Customer created successfully.');

    }

    public function user_add(Request $request)

    {

        

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',

            'phone' => 'required|unique:users',

            'password' => 'required|string|min:5'

        ]);

  

        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

            'password' => Hash::make($request->password),

            'role'=>'user',

            'registered_by'=>'Web'

        ]);

   

        return redirect()->route('users')->with('success','User created successfully.');

    }

    public function customer_update(Request $request,$id)

    {   

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => ['required','string','email','max:255',Rule::unique('users','email')->ignore($id)],

            'phone' => ['required','digits:9',Rule::unique('users','phone')->ignore($id)]

        ]);

       
        if($request->hasFile('profile')){
            $imageName = time().'.'.$request->profile->extension(); 
            $path = $request->profile->move(public_path('/uploads/user/'), $imageName);
            $input['profile'] = $imageName;
        }
        
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['phone'] = $request->phone;
        $input['gender'] = $request->gender;
        $input['DOB'] = $request->DOB;
      
        User::where('id', $id)->update($input);

   

        return back()->with('success','Update successfully.');

    }

    public function vendor_status($user_id,$status)

    {

        $user = User::find($user_id);

        $user->is_active = $status;

        $res=$user->save();

        if($res){

            return redirect()->route('vendors')

                        ->with('success','Vendor status changed successfully.');

        }

        else{

            return redirect()->route('vendors')

                        ->with('error','Something is wrong, Try Later.');

        }

    }

    public function vendor_verified($user_id)

    {

        $user = User::find($user_id);

        $user->is_verified = 1;

        $res=$user->save();

        if($res){

            if($user && $user->email){

                $array['view']      = 'emails.vendor_active';
                $array['subject']   = 'Congratulation! Your UrbanMop partner account is activated.';
                $array['data']      = '';
                \Mail::to($user?$user->email:'')->send(new \App\Mail\Mail($array));
            }

            if($user && $user->phone){
                $message = "Congratulation! Your Urbanmop partner account is successfully verified and activated. You can now login to the partner app using your registered mobile number. For any assistance contact Urbanmop helpline at 052 618 8291 \ 058 581 4007 or send email at booking@urbanmop.com";
                $msg = urlencode($message);
                $mobile = $user->phone;
                $res=send_sms_to_mobile($mobile,$msg);
            }

            return redirect()->back()->with('success','verified successfully.');

        }

        else{

            return redirect()->back()->with('error','Something is wrong, Try Later.');

        }

    }

    public function customer_status($user_id,$status)

    {

        $user = User::find($user_id);

        $user->is_active = $status;

        $res=$user->save();

        if($res){

            return back()->with('success','Status changed successfully.');

        }

        else{

            return back()->with('error','Something is wrong, Try Later.');

        }

    }

    public function customer_verified($user_id)

    {

        $user = User::find($user_id);

        $user->is_verified = 1;

        $res=$user->save();

        if($res){

            return back()->with('success','Verified successfully.');

        }

        else{

            return back()->with('error','Something is wrong, Try Later.');

        }

    }

    public function customer_detail($user_id)

    {

        $user_id=decrypt($user_id);

        $user = User::select('*')->where('role', 'customer')->where('id', $user_id)->first();

        if($user){



                $ShopMembers = new ShopMembers();

                $myVendors =$ShopMembers->getShopVendors($user_id,'Accept');



            return view('customer_detail',compact('user','myVendors'));

        }

        else{

            return redirect()->route('customers')->with('warning','Something is wrong, Try Later!');

        }

    }

    public function vendor_detail($user_id)

    {   

        $user_id=decrypt($user_id);

        $user = User::select('*')->where('role', 'vendor')->where('id', $user_id)->first();
       
        // if($user->can('user-create')){
        //     return 'if';
        // } else {
        //     return 'else';
        // }

        if($user){

            $shopDetail=ShopDetail::select('*')->where('user_id', $user_id)->first();

            $uniq_items = ShopItems::where('user_id',$user_id)->select('item_id')->distinct()->get();

            $shop_items=array();

            if(!empty($uniq_items))

            {

                foreach($uniq_items as $key=>$value)

                {   

                    $shop_items[$key]=Item::where('id',$value->item_id)->first();

                    $shop_items[$key]['packings']=ShopItems::where('user_id',$user_id)->where('item_id',$value->item_id)->get();

                }

            }

            $ShopMembers = new ShopMembers();

            $myCustomers =$ShopMembers->getShopMembers($user_id,'Accept');



            //dd($myCustomers);

            return view('vendor_detail',compact(['user','shopDetail','shop_items','myCustomers']));

        }

        else{

            return redirect()->route('vendors')->with('warning','Something is wrong, Try Later!');

        }

    }

    public function shop_customer_detail($user_id)

    {

        $user_id=decrypt($user_id);

        $user = User::select('*')->where('role', 'customer')->where('id', $user_id)->first();

        if($user){



            $invoices=Invoice::where('vendor_id',Auth::id())->where('customer_id',$user_id)->orderBy('id','DESC')->get();

            $transections=Transection::where('vendor_id',Auth::id())->where('customer_id',$user_id)->orderBy('id','DESC')->get();

            $balance=getWallet(Auth::id(),$user_id);

            $balance = -($balance);

            // dd($help);

            $package=Package::where('vendor_id',Auth::id())->where('customer_id',$user_id)->first();



            return view('shop.customer_detail',compact('user','invoices','transections','balance','package'));

        }

        else{

            return redirect()->route('customers')->with('warning','Something is wrong, Try Later!');

        }

    }


    public function delete($user_id)
    {
        $user_id=decrypt($user_id);
        User::whereId($user_id)->delete();
        return back()->with('error','Delete successfully.');
    }

    public function vendor_profile($id='',$type='')
    {
        $data['user'] = User::find($id);
        $data['type'] = $type;

        if($type=='start-service'){

            $data['in_progress_booking'] = Card::where('accept_user_id',$id)->where('status','In Progress')->paginate(10);
            return view('vendor.start_service',$data);

        } elseif ($type=='complete-service') {

            $data['service_completed'] = Card::where('accept_user_id',$id)->where('service_completed','Yes')->paginate(10);
            return view('vendor.complete_service',$data);

        } else {

            $data['today_booking'] = Card::where('accept_user_id',$id)->where('status', '!=' , 'Canceled')->paginate(10);
            return view('vendor_detail',$data);

        } 

        return back();
    } 

    public function pay_out_history($id='',$type='')
    {
        $data['user'] = User::find($id);
        $data['type'] = $type;
        $data['data'] = PayOutBalance::where('vendor_id',$id)->orderBy('id','DESC')->paginate(10);
        return view('vendor.pay_out_history',$data);
    }

    public function vendor_payment($id='',$type='')
    {
        $data['data'] = Payment::where('vendor_id',$id)->orderBy('id','DESC')->paginate(10);
        $data['type'] = $type;
        $data['user'] = User::find($id);
        return view('vendor.payment',$data);
    }

    public function create_vendor_payment($id='',$type='')
    {
        $data['type'] = $type;
        $data['user'] = User::find($id);

        return view('vendor.payment_create',$data);
    }

    public function store_vendor_payment(Request $request)
    {
        $request->validate([

            'amount' => 'required',
            'moad' => 'required',

        ]);

        if($request->amount<=$request->remaning_amt){
            $amount = $request->amount;
            $user = User::find($request->vendor_id);
            if($user){
                $wallet_balance = $user->wallet_balance-$amount;

                $params['transaction_id']   = $request->transaction_no;
                $params['amount']           = $amount;
                $params['moad']             = $request->moad;
                $params['vendor_id']        = $request->vendor_id;
                $params['transaction_date'] = $request->transaction_date;
                $return = Payment::create($params);

                $userparams['wallet_balance'] = $wallet_balance;
                $user->update($userparams);

                if($user && $user->email && $return){
                    $array['view']      = 'emails.payout';
                    $array['subject']   = 'Payout Summary from '.date('d-m-Y').' - UrbanMop';
                    $array['data']      = $user;
                    \Mail::to($user?$user->email:'')->send(new \App\Mail\Mail($array));
                }

                return redirect('vendor/payment/'.$request->vendor_id.'/payment')->with('success','Payment Successfully Initiated.');
            } else {
                return redirect()->with('warning','Vendor Not Found.');
            }
        } else {
            return redirect()->with('warning','Enter Less Then Amount of Payout Balance.');
        }
    }

}

