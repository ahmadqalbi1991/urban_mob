@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Vendor</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form action="{{ route('vendor.update',$value->id) }}" method="POST">

                                    <!-- Modal body -->

                                    <div class="modal-body text-left row">

                                        @csrf

                                        <div class="form-group col-lg-6">

                                            <label class="text-dark">Full Name<span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{ $value->name }}" placeholder="" required>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label class="text-dark">Email<span class="text-danger">*</span></label>

                                            <input type="email" class="form-control" name="email" value="{{ $value->email }}" placeholder="" required>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label class="text-dark">Phone<span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="phone" value="{{ $value->phone }}" placeholder="" required>

                                        </div>

                                        <div class="form-group col-lg-6">

                                            <label class="text-dark">Service<span class="text-danger">*</span></label>

                                            <select class="form-control select2" name="service_id[]" required multiple>

                                               <option value="">Select Service</option>
                                                @foreach($service as $ser)
                                                    @if(in_array($ser->id, $seller_service_id))
                                                        <option value="{{$ser->id}}" selected>{{$ser->name}}</option>
                                                    @else
                                                        <option value="{{$ser->id}}">{{$ser->name}}</option>
                                                    @endif
                                                @endforeach

                                            </select>
                                            <input type="hidden" name="seller_id" value="{{$seller?$seller->id:''}}">

                                        </div>

                                    </div>

                                    

                                    <!-- Modal footer -->

                                    <div class="text-right">

                                    <button type="submit" class="btn btn-success bg-grad-4 ">Update</button>

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



       