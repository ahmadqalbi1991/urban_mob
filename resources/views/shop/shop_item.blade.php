@extends('layouts.shop')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Shop Item</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <a href="{{route('shop.items')}}"><button type="button" class="btn btn-rounded btn-primary"><span class="btn-icon-left text-primary"><i class="fa fa-long-arrow-left color-primary"></i> </span>Back</button></a>
                    </div>
                </div>
            <div class="container-fluid">
                @include('flash_msg')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="row">
                                <div class="col-3"></div>
                                <div class="col-6 text-center">
                                    <img width="100" height="100" alt="{{ $item->name }}" class="mr-3" src="{{itemImagePath($item->icon)}}" /> <span style="font-size:17px;font-weight: 600;">{{ $item->name }} ({{ $item->brand }})</span>
                                </div>
                                <div class="col-3"></div>
                            </div>
                                <h4>Add Items Pack</h4>
                                <form action="{{ route('shop.item.store',$item->id) }}" method="POST">
                                <div id="wrapper-box">
                                    <div class="form-group row mt-3" id="row-1">
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control"  placeholder="Enter Pack Quantity" name="quantity[]" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="unit[]" required>
                                                <option value="">-select unit-</option>
                                                <option value="Pcs">Pcs</option>
                                                <option value="G">G</option>
                                                <option value="KG">KG</option>
                                                <option value="ML">ML</option>
                                                <option value="L">L</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" placeholder="Enter Price" name="price[]" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">$</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="custom-control custom-switch mt-2">
                                                <input type="checkbox" class="custom-control-input" id="switch1" name="available[]" checked disabled>
                                                <label class="custom-control-label" for="switch1">Item Available &nbsp;&nbsp;
                                                <button type="button" onclick="add_box()" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></buttton>
                                                </label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @csrf
                                <input type="hidden" name="box_count" value="1" id="box_count" />
                                <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                                <h4 class="mt-3">Update Items Pack</h4>
                                @if($shop_items)
                                <form action="{{ route('shop.item.update',$item->id) }}" method="POST">
                                    @foreach($shop_items as $key=>$value)
                                        <div id="wrapper-box">
                                            <div class="form-group row mt-3" id="row-1">
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control"  placeholder="Enter Quantity" name="quantity[]" value="{{$value->quantity}}" required>
                                                </div>
                                                <div class="col-sm-3">
                                                    <select class="form-control" name="unit[]" required>
                                                        <option value="">-select unit-</option>
                                                        <option value="Pcs" {{ $value->unit == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                                        <option value="G" {{ $value->unit == 'G' ? 'selected' : '' }}>G</option>
                                                        <option value="KG" {{ $value->unit == 'KG' ? 'selected' : '' }}>KG</option>
                                                        <option value="ML" {{ $value->unit == 'ML' ? 'selected' : '' }}>ML</option>
                                                        <option value="L" {{ $value->unit == 'L' ? 'selected' : '' }}>L</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control" placeholder="Enter Price" name="price[]" value="{{$value->price}}" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">$</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="custom-control custom-switch mt-2">
                                                        <input type="checkbox" class="custom-control-input" id="switches{{$key}}" name="available[{{$key}}]" {{ $value->is_available == 1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="switches{{$key}}">Item Available &nbsp;&nbsp;
                                                        <!-- <button type="button"  class="btn btn-sm btn-warning"><i class="fa fa-trash"></i></buttton> -->
                                                        </label>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="shop_item_id[]" value="{{$value->id}}" />
                                    @endforeach
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                                @else
                                <h6>No items available!</h6>
                                @endif
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

       