@extends('web.layout.header')
@section('title','Register as an Urban Mop Vendor service professional Today!')
@section('meta_tags')
<meta name="description" content="Start your journey as an Urban Mop vendor service professional today. Register online and connect with new clients!">
@endsection

<style>
    .ml2 {
            margin-left: 2%;
    }
</style>
@section('content')
<main>
   
    <section class="pt-50  p-relative">
        <div class="container">
            @if (count($errors) > 0)
               <div class = "alert alert-danger">
                  <ul>
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Become a Partner</h5>
                    <form action="{{ url('store/vendor') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Business Information</h5>
                                <h6 class="card-subtitle mb-2 text-muted">We Just need a bit of information about you. Don't worry, this will you only take a minute and will help us to know your business well.</h6>
                                
                                <div class="row mt-4">  

                                    <div class="form-group col-lg-6">
                                        <label>Business Name</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Business Name" name="store_name" value="{{old('store_name')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Concern Person's Name</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Concern Person's Name" name="person_name" value="{{old('person_name')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control mt-2 mb-2" placeholder="Email" name="email" value="{{old('email')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Landline Number</label>
                                        <input type="text" class="form-control mt-2 mb-2" id="lphoneno" placeholder="Landline Number" name="landline_number" value="{{old('landline_number')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control mt-2 mb-2" id="rphoneno" placeholder="Mobile Number" name="mobile_no" value="{{old('mobile_no')}}" required>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Details and Licenses</h5>
                                <h6 class="card-subtitle mb-2 text-muted">We Just need a bit of information about you. Don't worry, this will you only take a minute and will help us to know your business well.</h6>
                                
                                <div class="row mt-4">  

                                    <div class="form-group col-lg-6 mt-2">
                                        <label>Are you VAT registered</label>
                                        <div class="mt-2">
                                           <input class="vat_reg" type="radio" id="is_reg_yes1" onclick="checkVat('Yes')" name="is_registered" value="Yes" checked>
                                           <label class="" for="is_reg_yes1">Yes</label>
                                           <input class="vat_reg" type="radio" id="is_reg_no" onclick="checkVat('No')" name="is_registered" value="No">
                                           <label class="" for="is_reg_no">No</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6 is_registered">
                                        <label>VAT No.</label>
                                        <input type="text" class="form-control mt-2 mb-2 vat_no" name="vat_no" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Upload Business License</label>
                                        <input type="file" class="form-control mt-2 mb-2" name="business_license" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label class="mb-2">Services</label>
                                        <select class="form-control mt-2 mb-2" name="service_id[]" required multiple> 
                                            @foreach($services as $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Flat No</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Flat No" name="flat_no" value="{{old('flat_no')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Building</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Building" name="building" value="{{old('building')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Address</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Address" name="address" value="{{old('address')}}" required>
                                    </div>

                                    <div class="form-group col-lg-6 mt-2">
                                        <label>Address Type</label>
                                        <div class="mt-2">
                                           <input class="" type="radio" id="inlineRadio1" name="address_type" value="Home" checked>
                                           <label class="" for="inlineRadio1">Home</label>
                                           <input class="" type="radio" id="inlineRadio2" name="address_type" value="Office">
                                           <label class="" for="inlineRadio2">Office</label>
                                           <input class="" type="radio" id="inlineRadio3" name="address_type" value="Work">
                                           <label class="" for="inlineRadio3">Work</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>City</label>
                                        <select class="form-control mt-2 mb-2" name="city_id" onchange="getLocality(this.value)" required>
                                            <option value="">Select City</option>
                                            @foreach($city as $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Locality</label>
                                        <select class="form-control mt-2 mb-2 localitylist" name="locality_id" required>
                                            <option value="">Select Locality</option>
                                            
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Bank Information</h5>
                                
                                <div class="row mt-4">  

                                    <div class="form-group col-lg-6">
                                        <label>Bank Name</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Bank Name" name="bank_name" value="{{old('bank_name')}}">
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>AC Holder Name</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="AC Holder Name" name="ac_holder_name" value="{{old('ac_holder_name')}}">
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label>Account No.</label>
                                        <input type="text" class="form-control mt-2 mb-2" placeholder="Account Number" name="ac_number" value="{{old('ac_number')}}">
                                    </div>

                                </div>
                            </div>
                        </div>
                
                        <div class="text-right mt-2">
                            <button class="btn btn-warning btn-sm" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('script')
<script>

    function checkVat(argument) {
        if(argument=='Yes'){
            jQuery('.is_registered').show();
        } else {
            jQuery('.is_registered').hide();
        }
    }
</script>
<script>
  jQuery(document).ready(function() {

      jQuery('.vat_no').on('keypress', function(e) {
          var phone = jQuery('.vat_no').val();
          console.log(phone);
          var regex = new RegExp("^[0-9\b]+$");
          var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
          // for 10 digit number only
          if (phone.length > 14) {
              e.preventDefault();
              return false;
          }
          
          if (regex.test(str)) {
              return true;
          }
          e.preventDefault();
          return false;
      });

   });
</script>
<script>
  jQuery(document).ready(function() {

      jQuery('#rphoneno').on('keypress', function(e) {
          
          var phone = jQuery('#rphoneno').val();
          console.log(phone);
          var regex = new RegExp("^[0-9\b]+$");
          var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
          // for 10 digit number only
          if (phone.length > 8) {
              e.preventDefault();
              return false;
          }
          
          if (regex.test(str)) {
              return true;
          }
          e.preventDefault();
          return false;
      });

   });
</script>
<script>
  jQuery(document).ready(function() {

      jQuery('#lphoneno').on('keypress', function(e) {
          
          var phone = jQuery('#lphoneno').val();
          console.log(phone);
          var regex = new RegExp("^[0-9\b]+$");
          var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
          // for 10 digit number only
          if (phone.length > 8) {
              e.preventDefault();
              return false;
          }
          
          if (regex.test(str)) {
              return true;
          }
          e.preventDefault();
          return false;
      });

   });
</script>
@endsection

 
<style type="text/css">
  

.h1, .h2, .h3, .h4, .h5, .h6{
  color:#6a6d7a !important; 
 }

 
</style>

