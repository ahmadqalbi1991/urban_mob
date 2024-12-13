@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Edit Slot</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{url('slots')}}">

                            <button type="button" class="btn btn-rounded btn-primary ml-4">

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

                                <form action="{{url('slots/'.$slot->id)}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}

                                    <!-- Modal body -->
                                    <div class="row">

                                        <div class="form-group col-lg-4">

                                            <label>Slot Name <span class="text-danger">*</span></label>

                                            <input type="text" class="form-control" name="name" value="{{$slot->name}}" placeholder="Slot Name" required>

                                        </div>

                                        <div class="form-group col-lg-4">

                                            <label>Check In <span class="text-danger">*</span></label>

                                            <input type="time" class="form-control" name="check_in" value="{{$slot->check_in}}" placeholder="Check In" required>

                                        </div>

                                        <div class="form-group col-lg-4">

                                            <label>Check Out <span class="text-danger">*</span></label>

                                            <input type="time" class="form-control" name="check_out" value="{{$slot->check_out}}" placeholder="Check Out" required>

                                        </div>

                                    </div>

                                    <div class="text-center">
                                        
                                        <button type="submit" class="btn btn-success bg-grad-4">Update</button>
                                    
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




       