@extends('layouts.dashboard')
<style>
    .qty-w {
        width: 35% !important;
    }

    .attr_w {
        width: 40% !important;
    }
</style>
@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Service</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="custom-tab-2">
                                    <?php $data['active'] = 'Service'; ?>
                                    @include('offline.menu',$data)
                                    <form action="{{ route('step3') }}" method="post">
                                    @csrf
                                        <div class="row">

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Service (Category)</label>
                                                    <select class="form-control select2 service" name="service_id">
                                                        <option value="">Select Service</option>
                                                        @foreach($services as $key => $service)
                                                        <option value="{{$service->id}}">{{$service->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Sub Category</label>
                                                    <select class="form-control select2 sub_category" name="sub_category_id">
                                                        <option value="">Select Sub Category</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Child Category</label>
                                                    <select class="form-control select2 child_category" name="child_category_id">
                                                        <option value="">Select Child Category</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 mt-4">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col" class="attr_w">Attribute</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Qty</th>
                                                            <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="attribute_list">
                                                        
                                                    </tbody>
                                                </table>

                                                <div class="card" id="Materialscharge" style="background: #d3d3d385;">
                                                   
                                                </div>

                                            </div>


                                            <div class="col-lg-12 mt-4">
                                                
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Attribute</th>
                                                            <th scope="col" class="attr_w">Attribute Item</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Qty</th>
                                                            <th scope="col" class="text-right">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="cart_list">
                                                        @include('offline.service.add_to_cart_list')
                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-lg-6">
                                                <div class="text-left">
                                                    <a href="{{ route('offline.booking') }}"><button class="btn btn-primary" type="button">Back</button></a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="text-right">
                                                    <button class="btn btn-primary" type="submit">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

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

@section('script')
<script>
    let service = 'normal';
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let Servic_id = '';
    let Sub_cate_id = '';
    let Child_cate_id = '';
    $('.service').on('change', function() {
        if(this.value){   
            $('.attribute_list').html(''); 
            $('.cart_list').html(''); 
            $('#Materialscharge').html('');      
            Servic_id = this.value;    
            $.ajax({
                type: 'Post',
                url: "{{ route('get.sub.catgeory.service') }}",
                data: {
                        _token: CSRF_TOKEN,
                        service_id: this.value,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.status){
                        $('.sub_category').html(data.modal_view);  
                        service = data.service;
                    }                                     
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });
</script>

<script>
    $('.sub_category').on('change', function() {
        if(this.value){
            $('.attribute_list').html('');   
            $('.child_category').html(''); 
            Sub_cate_id = this.value;
            $.ajax({
                type: 'Post',
                url: "{{ route('get.sub.child.catgeory.service') }}",
                data: {
                        _token: CSRF_TOKEN,
                        service_id: $('.service').val(),
                        sub_category_id: this.value,
                        service : service,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.status){
                        if(data.from=='child'){
                            $('.child_category').html(data.modal_view);   
                        } else {
                            $('.attribute_list').html(data.modal_view);  
                        }
                        
                    }                                     
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });
</script>

<script>
    $('.child_category').on('change', function() {
        if(this.value){
            Child_cate_id = this.value;
            $.ajax({
                type: 'Post',
                url: "{{ route('get.sub.child.attribute') }}",
                data: {
                        _token: CSRF_TOKEN,
                        service_id: $('.service').val(),
                        sub_category_id: $('.sub_category').val(),
                        child_category_id: this.value,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.status){
                        $('.attribute_list').html(data.modal_view);   
                    }                                     
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });
</script>

<script>
    let Global_last_attr_id = '';
    function calculateMaterial(params) 
    {
        var attribute_detail_id = params.id;
        if(attribute_detail_id){
            Global_last_attr_id = attribute_detail_id;
            $.ajax({
                type: 'Post',
                url: "{{ route('get.material.price') }}",
                data: {
                        _token: CSRF_TOKEN,
                        service_id: $('.service').val(),
                        sub_category_id: $('.sub_category').val(),
                        attribute_detail_id: attribute_detail_id,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);                    
                    if(data.status){
                        $('#Materialscharge').html(data.modal_view);                           
                        addToCart(attribute_detail_id, 'maid');
                    }  
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }
</script>

<script>
    function calculate_material(req) {
        addToCart(Global_last_attr_id, 'maid');        
    }
</script>

<script>
    function increaseValue(atrid) {
        var numberid = 'number'+atrid;
        var checkboxid = '#checkbox'+atrid;
        var value = parseInt(document.getElementById(numberid).value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        document.getElementById(numberid).value = value;

        if($(checkboxid).prop('checked')==true){
            addToCart(atrid, 'Normal')
        }
    }

    function decreaseValue(atrid) {
        var numberid = 'number'+atrid;
        var checkboxid = '#checkbox'+atrid;
        var value = parseInt(document.getElementById(numberid).value, 10);
        value = isNaN(value) ? 0 : value;
        value < 1 ? value = 1 : '';
        value--;
        document.getElementById(numberid).value = value;

        if($(checkboxid).prop('checked')==true){
            addToCart(atrid, 'Normal')
        }
    }
</script>

<script>
    function addToCart(attr_id, service_type) {
     
        var numberid = '#number'+attr_id;
        if(service_type=='maid'){
            if($('.yes').prop('checked')==true){
                var materialscharge = 'Yes';
                var material_charge = $('.material_charge').val();
            } else {
                var materialscharge = 'NO';
                var material_charge = '0';
            }
        } else {
            var materialscharge = 'NO';
            var material_charge = '0';
        }
        
        if(attr_id){
            $.ajax({
                type: 'Post',
                url: "{{ route('add.attr.in.cart') }}",
                data: {
                        _token          : CSRF_TOKEN,
                        service_id      : $('.service').val(),
                        sub_category_id : $('.sub_category').val(),
                        child_category_id : Child_cate_id,
                        attribute_id    : attr_id,
                        qty             : $(numberid).val(),
                        service         : service_type,
                        materialscharge : materialscharge,
                        material_charge : material_charge,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);                    
                    if(data.status){
                        $('.cart_list').html(data.modal_view);   
                    } else {
                        alert('Try Again');
                    }  
                },
                error: function (data) {
                    console.log(data);
                    alert('Try Again');
                }
            });
        }
    }
</script>

<script>
    function removeAttr(attr_id) {
       
        if(attr_id){
            $.ajax({
                type: 'Post',
                url: "{{ route('remove.attr.in.cart') }}",
                data: {
                        _token          : CSRF_TOKEN,
                        attribute_id    : attr_id,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);                    
                    if(data.status){
                        $('.cart_list').html(data.modal_view);   
                    } else {
                        alert('Try Again');
                    }  
                },
                error: function (data) {
                    console.log(data);
                    alert('Try Again');
                }
            });
        }
    }
</script>
@endsection



       