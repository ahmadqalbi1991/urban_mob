<div class="card">

    <div class="card-body">

        <div class="row">

            <div class="col-lg-4">

                <div class="media align-items-center mb-4">
                    @if($user->profile)
                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="{{asset('/uploads/user/'.$user->profile)}}" width="80" height="80" alt="Profile">
                    @else
                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="{{asset('images/form-user.png')}}" width="80" height="80" alt="Profile">
                    @endif
                    <div class="media-body">

                        <h3 class="mb-0">{{$user->name}}</h3>

                        <p class="text-muted mb-0">Vendor</p>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <p class="text-muted"></p>

                <p class="text-muted">{{$user->city}}</p>

                <ul class="card-profile__info">

                    <li class="mb-1"><strong class="text-dark mr-4">Mobile : </strong>{{$user->phone}}</li>

                    <li class="mb-1"><strong class="text-dark mr-4">Email : </strong>{{$user->email}}</li>
                    
                </ul>

            </div>

            <div class="col-lg-4">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="bg-grad-3 count-card">My Booking <span class="count">{{App\Card::where('accept_user_id',$user->id)->where('status','Accept')->count()}}</span></div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="bg-grad-2 count-card">Start Service <span class="count">{{App\Card::where('accept_user_id',$user->id)->where('status','In Progress')->count()}}</span></div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="bg-grad-4 count-card">Completed Service <span class="count">{{App\Card::where('accept_user_id',$user->id)->where('service_completed','Yes')->count()}}</span></div>
                    </div>
                    <div class="col-lg-12 mt-2">
                        <div class="bg-grad-1 count-card">Pay Out Balance <span class="count">AED {{price_format($user?$user->wallet_balance:'0')}}</span></div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>