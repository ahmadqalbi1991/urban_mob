@extends('web.layout.header')
@section('title','Home')
@section('content')

<!-- team area start -->
<section class="tp-team-3-area pt-60 pb-80 p-relative">
   <div class="container">
      <h3 class="mb-30 text-left">Services</h3>
      <div class="row">
         @if($services && count($services))
            @foreach($services as $key => $service)
            <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
                <a href="{{ route('service.details', $service->slug) }}">
                    <div class="tp-team-2-thumb mb-30">
                        @if($service && $service->thumbnail_img)
                        <img src="{{ url('uploads/service/'.$service->thumbnail_img) }}" alt="{{$service->name}}" title="{{$service->name}}">
                        @else
                        <img src="{{ url('web/Banner-not-found.jpg') }}" alt="{{$service->name}}" title="{{$service->name}}">
                        @endif
                        <div class="tp-team-2-inner">
                            <h5 class="text-white">{{$service->name}}</h5>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
         @else
         <h5 class="text-center">No Service</h5>
         @endif
      </div>
   </div>
</section>
<!-- team area end -->

@endsection