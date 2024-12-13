@extends('layouts.dashboard')

@section('content')

<style>
    .mt-18 {
        margin-top: 4%;
    }
    .print {
            margin-right: 5%;
    }
</style>

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1"> Booking ID - {{$card->tran_id}}</h3>
                            
                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url()->previous() }}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-arrow-left color-primary"></i> 

                                </span>Back

                            </button>

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-sm-2">
                                        <b>User : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->user?$card->user->name:''}}
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Service : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->service?$card->service->name:''}}
                                    </div>

                                </div>

                                <div class="row mt-2"> 

                                    <div class="col-sm-2">
                                        <b>Category : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->category?$card->category->name:'No Category'}}
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Slot Date : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{ date('d F Y', strtotime($card->date))}}
                                    </div>

                                </div>

                                <div class="row mt-2">   

                                    <div class="col-sm-2">
                                        <b>Payment Mode : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->payment_moad?$card->payment_moad:''}}
                                        @if($card->payment_moad=='Cash')
                                            @if($card->payment_collected=='Yes')
                                                <small class="text-success">(Paid)</small>
                                            @else
                                                <small class="text-warning">(Pending)</small>
                                            @endif
                                        @else
                                            
                                        @endif
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <b>Slot : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->slot?$card->slot->name:''}}
                                    </div>

                                </div>

                                <div class="row mt-2">   

                                    <div class="col-sm-2">
                                        <b>Alternative No. : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->alternative_number?'+971':''}}{{$card->alternative_number}}
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Creation Date : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{date('d F Y', strtotime($card->created_at))}}
                                    </div>
                                    
                                </div>

                                <div class="row mt-2">   

                                    <div class="col-sm-2">
                                        <b>Contact No. : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        @if($card->user && $card->user->phone)
                                            +971{{$card->user->phone}}
                                        @endif
                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <b>Booking Instructions : </b>
                                    </div>
                                    <div class="col-sm-4">
                                        {{$card->note}}
                                    </div>   
                                    
                                </div>                                

                            </div>

                        </div>

                        <div class="card">
                            
                            <div class="card-body">
                                
                                <form action="{{ route('update.time.slot') }}" method="Post">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $card->id }}">
                                    <input type="hidden" name="from" value="{{ $from }}">
                                    
                                    <div class="row">
                                    
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Slot Date</label>
                                                <input type="date" name="date" class="form-control" value="{{ $card->date }}" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Choose The Preferred Time Slot</label>
                                                <select name="slot_id" class="form-control select2" required>
                                                    <option value="">Select Slot</option>
                                                    @foreach($slots as $slot)
                                                    <option value="{{$slot->id}}" {{ $card->slot_id==$slot->id?'selected':'' }}>{{$slot->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        
                                        <button type="submit" class="btn btn-success">Update</button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!--**********************************

            Content body end

        ***********************************-->

@endsection      



       