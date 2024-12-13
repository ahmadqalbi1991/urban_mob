<ul class="nav nav-tabs nav-justified mb-4">

    <li class="nav-item"><a class="nav-link {{$type==''?'active':''}}" href="{{url('vendor/details/'.$user->id)}}">My Booking</a></li>

    <li class="nav-item"><a class="nav-link {{$type=='start-service'?'active':''}}" href="{{url('vendor/details/'.$user->id.'/start-service')}}">Start Service</a></li>

    <li class="nav-item"><a class="nav-link {{$type=='complete-service'?'active':''}}" href="{{url('vendor/details/'.$user->id.'/complete-service')}}">Completed Service</a></li>

    <li class="nav-item"><a class="nav-link {{$type=='pay-out-history'?'active':''}}" href="{{url('pay-out-history/'.$user->id.'/pay-out-history')}}">Pay Out History</a></li>

    <li class="nav-item"><a class="nav-link {{$type=='payment'?'active':''}}" href="{{url('vendor/payment/'.$user->id.'/payment')}}">Payment History</a></li>

</ul>