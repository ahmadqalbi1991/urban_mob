@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Add Page</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url()->previous() }}">

                            <button type="button" class="btn btn-rounded btn-primary ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-arrow-left color-primary"></i> 

                                </span>
                                Back

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

                                <form action="{{ route('page.store') }}" method="POST" enctype="multipart/form-data">

                                    @csrf

                                    <div class="form-group">

                                        <label>Title<span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="title" placeholder="Title" required>
                                        <input type="hidden" name="setting_from" value="{{ $frm }}">

                                    </div>

                                    <div class="form-group">

                                        <label>Description<span class="text-danger">*</span></label>

                                        <textarea class="form-control summernote" name="description" placeholder="Description"></textarea>

                                    </div>

                                    <div class="text-center">
                                        <input type="submit" class="btn btn-primary" value="Submit">
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



       