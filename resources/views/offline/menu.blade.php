<ul class="nav nav-tabs nav-justified mb-4">

    <li class="nav-item"><a class="nav-link {{ $active=='Customer'? 'active':'' }}" href="{{route('offline.booking')}}">Customer Info</a></li>

    <li class="nav-item"><a class="nav-link {{ $active=='Service'? 'active':'' }}" href="javascript:">Service Details</a></li>

    <li class="nav-item"><a class="nav-link {{ $active=='Schedule'? 'active':'' }}" href="javascript:">Schedule Details</a></li>

    <li class="nav-item"><a class="nav-link {{ $active=='Amount'? 'active':'' }}" href="javascript:">Amount Details</a></li>

    <li class="nav-item"><a class="nav-link {{ $active=='Payment'? 'active':'' }}" href="javascript:">Booking Confirm</a></li>

</ul>
<hr>