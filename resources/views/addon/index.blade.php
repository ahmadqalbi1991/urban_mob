@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Addons</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url('service/attribute/items/list/'.$attribute.'/'.$service) }}" class="btn btn-rounded bg-grad-4 ml-4">

                            <span class="btn-icon-left text-primary">

                                <i class="fa fa-arrow-left color-primary"></i> 

                            </span>Back

                        </a>

                        <a href="{{url('/addon/create/'.$attribute.'/'.$service.'/'.$atr_item)}}">

                            <button type="button" class="btn btn-rounded bg-grad-4 ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-plus color-primary"></i> 

                                </span>
                                Add Addon

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

                                <form method="GET" action="">

                                    <div class="row text-right">

                                        <div class="col-md-6">
                                        </div>

                                        <div class="col-md-4">

                                            <input type="text" class="form-control right-search" name="search" value="{{$request->search}}" placeholder="Search by addon">

                                        </div>

                                        <div class="col-md-2">

                                            <label class="">&nbsp;</label>

                                            <button type="submit" class="btn btn-outline-info bg-grad-4 btn-ft">Search</button>

                                        </div>

                                    </div>

                                </form>

                                <hr>

                                <div class="table-responsive">

                                    <table class="table table-border table-hover table-sm" >

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Logo</th>

                                                <th>Attribute</th>

                                                <th>Name</th>

                                                <th>Price</th>

                                                <th>Percentage</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($addon))

                                            @foreach($addon as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>
                                                    @if($value->icon)
                                                    <img src="{{ asset('/uploads/addon/'.$value->icon) }}" id="preview-image-before-upload" height="50"> 
                                                    @endif
                                                </td>

                                                <td>{{ $value->attribute_item?$value->attribute_item->value:'' }}</td>

                                                <td>{{ $value->name }}</td>
                                                
                                                <td>
                                                    <small><b>Original Price : </b>{{ Session::get('currencies') }} {{ $value->orignal_price }}</small><br>
                                                    <small><b>Discount Price : </b>{{ Session::get('currencies') }} {{ $value->value }}</small>
                                                </td>

                                                <td>{{ $value->percentage }}%</td>
                                                
                                                <td class="text-right">

                                                <a href="{{ route('addon.edit',encrypt($value->id)) }}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>

                                                <a href="{{ route('addon.delete',encrypt($value->id)) }}" onclick="return confirm('Are you sure?')"><button type="button" class="btn btn-outline-primary btn-ft btn-sm" title="Delete" alt="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                                @if ($addon->count() == 0)

                                                <tr class="text-center">

                                                    <td colspan="6">No addons to display.</td>

                                                </tr>

                                                @endif

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Logo</th>

                                                <th>Attribute</th>

                                                <th>Name</th>

                                                <th>Price</th>

                                                <th>Percentage</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                                <div class="text-left float-left mt-1">

                                    <p>Displaying {{$addon->count()}} of {{ $addon->total() }} addons.</p>

                                </div>

                                <div class="text-right float-right">

                                    {{ $addon->appends(request()->all())->links() }}

                                </div>

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



       