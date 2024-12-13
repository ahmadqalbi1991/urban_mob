<?php



namespace App\Http\Controllers\Auth;



use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request; 

use Illuminate\Support\Str;

use Carbon\Carbon;

use App\User;

use Auth;

use Hash;



class AuthController extends Controller

{   



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('guest')->except('logout');

    }



    public function login()

    { 
        if(Auth::check()){

            if(auth()->user()->role=='customer')

            {

                return redirect()->back()->withInput()->with('error', 'Oppes! Customer not allowed, Use mobile app and website!');

            } 

            elseif(auth()->user()->role=='vendor')

            {

                return redirect()->back()->withInput()->with('error', 'Oppes! Vendor not allowed, Use mobile app!');

            } else {

                return redirect()->route('home');

            }

        } else {

            return view('auth/login');
            
        }

    }

    public function authenticate(Request $request)

    {

        $request->validate([

            //'email' => 'required|string|email',

            'email' => 'required|string',

            'password' => 'required|string',

        ]);



        $email=$request->email;

        $password=$request->password;



        if (Auth::attempt(['email' => $email, 'password' => $password]) || Auth::attempt(['phone' => $email, 'password' => $password])) {

            

            if (auth()->user()->is_active !== 1 || auth()->user()->is_verified !== 1)

            {

                return redirect()->back()->withInput()->with('error', 'Oppes! Your account is inactive or unverified');

            }



            //dump(auth()->user());

            

            if(auth()->user()->role=='customer')

            {

                return redirect()->back()->withInput()->with('error', 'Oppes! Customer not allowed, Use mobile app!');

            }

            elseif(auth()->user()->role=='vendor')

            {

                //dump('vendor');

                return redirect()->route('shop'); 

            }

            else

            {	
            	//dump('admin');

                return redirect()->route('home');

            }

            

        }

        return redirect()->back()->withInput()->with('error', 'Oppes! You have entered invalid credentials');

    }



    public function forgot_password()

    {   

        return view('auth.forgot_password');

    }



    public function forgot_password_process(Request $request) {

        $request->validate([

            'email' => 'required|email|exists:users',

        ]);



        $token = Str::random(64);



        DB::table('password_resets')->insert([

            'email' => $request->email, 

            'token' => $token, 

            'created_at' => Carbon::now()

          ]);



        // Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){

        //     $message->to($request->email);

        //     $message->subject('Reset Password (Urbanmop)');

        // });



        return back()->with('message', 'We have e-mailed your password reset link!');

    }



    public function reset_password($token)

    {   

        return view('auth.reset_password',['token' => $token]);

    }



    public function reset_password_process(Request $request)

    {

        $request->validate([

            'email' => 'required|email|exists:users',

            'password' => 'required|string|min:6|confirmed',

            'password_confirmation' => 'required'

        ]);



        $updatePassword = DB::table('password_resets')

                            ->where([

                              'email' => $request->email, 

                              'token' => $request->token

                            ])

                            ->first();



        if(!$updatePassword){

            return back()->withInput()->with('error', 'Invalid token!');

        }



        $user = User::where('email', $request->email)

                    ->update(['password' => Hash::make($request->password)]);



        DB::table('password_resets')->where(['email'=> $request->email])->delete();



        return redirect('login')->with('message', 'Your password has been changed!');

    }



    public function logout() {

      Auth::logout();



      return redirect('login');

    }

}

