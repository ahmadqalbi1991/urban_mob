@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Attribute Items</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ url('service/attributes/'.$service_id) }}" class="btn btn-rounded bg-grad-4 ml-4">

                            <span class="btn-icon-left text-primary">

                                <i class="fa fa-arrow-left color-primary"></i> 

                            </span>Back

                        </a>                        

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <form method="GET" action="" class="d-none">

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

                                                <th>Service</th>

                                                <th>Attribute</th>

                                                <th>Attribute Item</th>

                                                <th>Price</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($service_atr))

                                            @foreach($service_atr as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->service?$value->service->name:'' }}</td>

                                                <td>{{ $value->attribute?$value->attribute->name:'' }}</td>

                                                <td>{{ $value->attributeItem?$value->attributeItem->value:'' }}</td>

                                                <td>{{ Session::get('currencies') }} {{ $value->attribute_price }}</td>

                                                <td class="text-right">

                                                    <a href="{{ url('manage/service/attribute/addon/'.$attribute_id.'/'.$value->service->id.'/'.$value->attributeItem->id) }}"><button type="button" class="btn btn-outline-info btn-ft btn-sm" title="Addon" alt="Addon">Addon</button></a>

                                                </td>

                                            </tr>

                                            @endforeach

                                                @if ($service_atr->count() == 0)

                                                <tr class="text-center">

                                                    <td colspan="6">No attribute items to display.</td>

                                                </tr>

                                                @endif

                                            @endif

                                        </tbody>

                                        <tfoot>

                                            <tr>

                                                <th>#</th>

                                                <th>Service</th>

                                                <th>Attribute</th>

                                                <th>Attribute Item</th>

                                                <th>Price</th>

                                                <th class="text-right">Action</th>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                                <div class="text-left float-left mt-1">

                                    <p>Displaying {{$service_atr->count()}} of {{ $service_atr->total() }} attribute items.</p>

                                </div>

                                <div class="text-right float-right">

                                    {{ $service_atr->appends(request()->all())->links() }}

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



       