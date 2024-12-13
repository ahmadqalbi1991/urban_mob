<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rule;

use App\Rules\MatchOldPassword;

use App\User;

use App\ShopDetail;





class ProfileController extends Controller

{

    

    public function __construct()

    {

        $this->middleware('auth');

    }

    public function profile_setting()

    {   

        $user = User::find(Auth::user()->id);

        return view('profile_setting',compact('user'));

    }

    public function profile_save(Request $request)

    {   

        $id=Auth::user()->id;

        $request->validate([

            'name' => 'required|string|max:255',

            'phone' => ['required','digits:10',Rule::unique('users','phone')->ignore($id)]

        ]);

        if($request->hasFile('profile')){
            $imageName = $request->name.'-'.time().'.'.$request->profile->extension(); 
            $path = $request->profile->move(public_path('/uploads/user/'), $imageName);
            $data['profile'] = $imageName;
            User::find($id)->update($data);
        }

        User::find($id)->update([

            'name' => $request->name,

            'phone' => $request->phone,

            'address'=> $request->address,

        ]);   

        return redirect()->route('profile.setting')->with('success','Profile update successfully.');

    }

    public function password_save(Request $request)

    {   

        $id=Auth::user()->id;

        $request->validate([

            'current_password' => ['required', new MatchOldPassword],

            'new_password' => ['required','string','min:5'],

            'confirm_new_password' => ['same:new_password'],

        ]);



        User::find($id)->update([

            'password' =>  Hash::make($request->new_password)

        ]);

   

        return redirect()->route('profile.setting')->with('success','Password update successfully.');

    }

    public function my_profile()

    {   

        $user = User::find(Auth::user()->id);

        $shopDetail=ShopDetail::where('user_id', Auth::user()->id)->first();

        return view('shop/my_profile',compact(['user','shopDetail']));

    }

    public function my_profile_save(Request $request)

    {   

        $id=Auth::user()->id;

        $request->validate([

            'name' => 'required|string|max:255',

            'phone' => ['required','digits:10',Rule::unique('users','phone')->ignore($id)],

            'address' => 'required',

            'city' => 'required'

        ]);

        $data= [

            'name' => $request->name,

            'phone' => $request->phone,

            'address'=> $request->address,

            'city'=> $request->city

            ];

        $res=User::find($id)->update($data);

        if($res){

            return redirect()->route('my.profile')->with('success','Profile update successfully.');

        }

        else{

            return redirect()->route('my.profile')->with('error','Somthing wrong, Try Later!.');

        }

    }

    public function my_password_save(Request $request)

    {   

        $id=Auth::user()->id;

        $request->validate([

            'current_password' => ['required', new MatchOldPassword],

            'new_password' => ['required','string','min:5'],

            'confirm_new_password' => ['same:new_password'],

        ]);



        User::find($id)->update([

            'password' =>  Hash::make($request->new_password)

        ]);

   

        return redirect()->route('my.profile')->with('success','Password update successfully.');

    }

    public function my_shop_save(Request $request)

    {   

        $id=$request->id;

        $request->validate([

            'shop_name' => 'required|string|max:255',

            'shop_email'=>['required','email',Rule::unique('shop_detail','shop_email')->ignore($id)],

            'shop_phone' => ['required','digits:10',Rule::unique('shop_detail','shop_phone')->ignore($id)],

            'address' => 'required',

            'city' => 'required',

            'pincode'=>'required',

            'GSTIN'=>'required',

            'UPI'=>'required',

        ]);

        

        $fileName='';

        if(isset($request->QR) && !empty($request->QR))

        {   

            $request->validate([

                'QR' => 'required|mimes:jpg,png,jpeg,gif|max:2048',

            ]);

            $fileName = time().'.'.$request->QR->extension();  

            $request->QR->move(public_path('uploads/QR'), $fileName);

        }

        else {

            $fileName=$request->qr_image;

        }



        $data= [

            'shop_name' => $request->shop_name,

            'shop_email' => $request->shop_email,

            'shop_phone' => $request->shop_phone,

            'address'=> $request->address,

            'city'=> $request->city,

            'pincode'=>$request->pincode,

            'GSTIN'=>$request->GSTIN,

            'UPI'=>$request->UPI,

            'user_id'=>Auth::user()->id,

            'QR' => $fileName,

            ];



        $shopDetail=ShopDetail::where('user_id', Auth::user()->id)->first();



        if($shopDetail)

        {

            $res=ShopDetail::where('user_id', Auth::user()->id)->update($data);

        }

        else{

            $res=ShopDetail::create($data);

        }



        if($res){

            return redirect()->route('my.profile')->with('success','Shop update successfully.');

        }

        else{

            return redirect()->route('my.profile')->with('error','Somthing wrong, Try Later!.');

        }

    }

}

