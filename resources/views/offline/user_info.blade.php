@extends('layouts.dashboard')

@section('content')
<style>
    /* The Modal (background) */
    .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 40%;
    }

    /* The Close Button */
    .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
    }
</style>
        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Customer Info</h3>

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
                                <?php $data['active'] = 'Customer'; ?>
                                    @include('offline.menu',$data)
                                    <form action="{{ route('step2') }}" method="POST">
	                                @csrf
                                        <div class="row">

                                            <div class="col-lg-6">

                                                <div class="form-group">
                                                    
                                                    <label>Mobile No.</label>
                                                    
                                                    <input type="text" class="form-control" list="user_list" id="phoneno" value="{{ $user ? $user->phone : '' }}" onkeypress="getUserList(this.value)" placeholder="Enter Customer Mobile Number">
                                                    <datalist id="user_list">
                                        
                                                    </datalist>
                                                    <span class="text-danger mobile_error"></span>
                                                </div>

                                                <div class="form-group">
                                                    <button class="btn btn-primary" onclick="checkMobile()" type="button">Submit</button>
                                                </div>
                                                                                            
                                            </div>

                                            <div class="col-lg-6">
                                                
                                                <div class="row customer_info">
                                                    <input type="hidden" name="user_id" class="user_id" value="{{ $user ? $user->id : '' }}">
                                                    <div class="col-lg-4 mt-2"><b>Name </b></div>

                                                    <div class="col-lg-8 mt-2"><input type="text" name="user_name" onchange="user_name_update(this.value)" value="{{ $user ? $user->name : '' }}" class="form-control user_name" required></div>

                                                    <div class="col-lg-4 mt-2"><b>Phone No. </b></div>

                                                    <div class="col-lg-8 mt-2"><input type="text" name="user_phone" value="{{ $user ? $user->phone : '' }}" class="form-control user_phone" readonly></div>

                                                    <div class="col-lg-4 mt-2"><b>Email </b></div>

                                                    <div class="col-lg-8 mt-2"><input type="text" name="user_email" onchange="user_email_update(this.value)" value="{{ $user ? $user->email : '' }}" class="form-control user_email" required></div>

                                                    <div class="col-lg-4 mt-2"><b>Address </b></div>

                                                    <div class="col-lg-8 mt-2">
                                                        <select name="address_id" class="form-control select2" required>
                                                            <option value="">Select Address</option>
                                                            @foreach($address as $add)
                                                            <option value="{{ $add->id }}" {{$add->id==Session::get('off_line_address_id')?'selected':''}}>{{ $add->flat_no }} ({{ $add->address_type }}) {{ $add->address }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>  
                                                                                              
                                                <div class="mt-2 text-right"><a href="javascript:" id="myBtn" title="Add Address" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Address</a></div>

                                            </div>

                                        </div>

                                        <div class="text-right mt-4">
                                            <button class="btn btn-primary" type="submit">Next</button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>       

        <!-- Add Address Modal -->

        <div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="text-right">
            <span class="close">&times;</span>
        </div>
        <h5>Add Address</h5>
        <form action="{{ route('add.offline.address') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Current Location</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control live_address" list="browsers" placeholder="Search Current Location" onkeypress="getLiveAddres(this.value)" name="address" required>
                        <datalist id="browsers">
                                    
                        </datalist>
                        <div class="input-group-append">
                        <button class="btn btn-warning getLetLong" type="button" onclick="getLetLong()">Search Location</button>
                        </div>
                        
                    </div>
                    <small class="text-danger addresserror"></small>
                </div>
                @include('offline.address.live_location')
                <br>
                <br>
                @include('offline.address.map')
                <div class="form-group">
                    <label>Flat/Office No.</label>
                    <input type="text" name="flat_no" class="form-control" required placeholder="Flat/Office No.">
                </div>
                <div class="form-group">
                    <label>Building Name</label>
                    <input type="text" name="building" class="form-control" required placeholder="Building Name.">
                </div>
                <div class="form-group">
                    <label>Address Type</label><br>

                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="customRadioInline1" name="address_type" value="Home" checked class="custom-control-input">
                      <label class="custom-control-label" for="customRadioInline1">Home</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="customRadioInline2" name="address_type" value="Office" class="custom-control-input">
                      <label class="custom-control-label" for="customRadioInline2">Office</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                      <input type="radio" id="customRadioInline3" name="address_type" value="Work" class="custom-control-input">
                      <label class="custom-control-label" for="customRadioInline3">Work</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <select class="form-control select2" name="city_id" onchange="getLocality(this.value)" required>
                        <option value="">Select City</option>
                        @foreach($city as $cty)
                        <option value="{{$cty->id}}">{{$cty->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Locality</label>
                    <select class="form-control select2 localitylist" name="locality">
                        <option value="">Select Locality</option>	               			
                    </select>	
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>

    </div>

</div>

        <!-- End Address Modal -->  

        <!--**********************************

            Content body end

        ***********************************-->

@endsection 

@section('script')
<script>
    $(document).ready(function() {
        getLiveLocation();
        $('#phoneno').on('keypress', function(e) {
          
            var phone = jQuery('#phoneno').val();
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
    function checkMobile() {
        var phone = $('#phoneno').val();
        var phone = phone.replace(' ', '');
        $('#phoneno').val(phone);
        
        if(phone){
            $('.mobile_error').text('');
            $.ajax({
                type: 'Get',
                url: "{{ route('create.account') }}",
                data: {
                        phone: phone,
                    },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
               
                    if(data){
                        $('.customer_info').html(data.modal_view);
                        // $('.user_id').val(data.user.id);
                        // $('.user_name').val(data.user.name);
                        // $('.user_email').val(data.user.email);
                        // $('.user_phone').val(data.user.phone);
                    } else {
                        $('.mobile_error').text(data.msg); 
                    }
                    setTimeout(function() {

                        $(".address-select").select2();

                    }, 100);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else {
            $('.mobile_error').text('Please Enter Mobile Number.');
        }
    }
    
</script>

<script>
   function getLocality(city_id) {
      jQuery('.localitylist').html('');  
      jQuery.ajax({
            headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },    
            type: 'Post',
            url: "{{ url('get_locality') }}",
            data: {
               city_id : city_id,
            },
        
            success: function (data) {
                  console.log(data); 
                 jQuery('.localitylist').html('');  

                 jQuery('.localitylist').html(data.modal_view);     

            },
            error: function (data) {
                console.log(data);
            }
      });
   }
</script>

<script>
   function getLiveAddres(requ) {
      jQuery.ajax({
         headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },    
         type: 'Post',
         url: "{{ url('get/live/address') }}",
         data: {
           location : requ,
         },
         dataType: 'json',
         success: function (response) {
              console.log(response);
              jQuery('#browsers').html(response.res);      
         },
         error: function (response) {
             console.log(response);
         }
      });      
   }
</script>

<script>
   function getLetLong() {
      jQuery.ajax({
         headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },    
         type: 'Post',
         url: "{{ url('get/lat/long') }}",
         data: {
           location : jQuery('.live_address').val(),
         },
         dataType: 'json',
         success: function (response) {
              console.log(response);
              if(response.status=='1'){
                 jQuery('#latitude').val(response.latitude);
                 jQuery('#longitude').val(response.longitude);
                 map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: response.latitude, lng: response.longitude }, // Default to centering at (0, 0)
                    zoom: 15 // Adjust the zoom level as desired
                 });

                 // Create a marker at the default location (0, 0)
                 marker = new google.maps.Marker({
                    position: { lat: response.latitude, lng: response.longitude },
                    map: map,
                    draggable: true // Allow the marker to be dragged
                 });

                 // Add an event listener to update the latitude and longitude when the marker is dragged
                 marker.addListener('dragend', function() {
                    updateCoordinates(marker.getPosition());
                 });
               }    
         },
         error: function (response) {
             console.log(response);
         }
      });      
   }   

</script>

<script>
    function getUserList(params) 
    {
        if(params.length>'2'){
            jQuery.ajax({
                headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },    
                type: 'Post',
                url: "{{ url('get/user/list') }}",
                data: {
                    phone : params,
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#user_list').html(response.res);      
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }      
    }
</script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<script>
    function user_name_update(argument) {
       
        jQuery.ajax({
                headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },    
                type: 'Post',
                url: "{{ url('update/user/name') }}",
                data: {
                    user_name : argument,
                    user_id : $('.user_id').val(),
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log(response);
                }
            });
    }
</script>

<script>
    function user_email_update(argument) {
        jQuery.ajax({
                headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },    
                type: 'Post',
                url: "{{ url('update/user/email') }}",
                data: {
                    user_email : argument,
                    user_id : $('.user_id').val(),
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log(response);
                }
            });
    }
</script>



<!-- Map.blade.php script -->
<script>
    jQuery( document ).ready(function() {
        jQuery('.getlocation').click();
    });
  </script>
  <script>
    var map;
    var marker;

    function initMap() {
      var lat_in = jQuery('#latitude').val();
      var log_in = jQuery('#longitude').val();

      // Initialize the map
      map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: lat_in, lng: log_in }, // Default to centering at (0, 0)
        zoom: 15 // Adjust the zoom level as desired
      });

      // Create a marker at the default location (0, 0)
      marker = new google.maps.Marker({
        position: { lat: lat_in, lng: log_in },
        map: map,
        draggable: true // Allow the marker to be dragged
      });

      // Add an event listener to update the latitude and longitude when the marker is dragged
      marker.addListener('dragend', function() {
        updateCoordinates(marker.getPosition());
      });
    }

    function getLiveLocation() {
      // Get the user's current location
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;

          // Update the map center and marker position to the live location
          var liveLocation = new google.maps.LatLng(latitude, longitude);
          map.setCenter(liveLocation);
          marker.setPosition(liveLocation);

          // Update the latitude and longitude inputs
          updateCoordinates(liveLocation);
        }, function() {
          console.log('Geolocation failed.');
        });
      } else {
        console.log('Geolocation is not supported by this browser.');
      }
    }

    function updateCoordinates(latLng) {
      document.getElementById('latitude').value = latLng.lat().toFixed(6);
      document.getElementById('longitude').value = latLng.lng().toFixed(6);
    }

    // Call the initMap() function to initialize the map
    initMap();
  </script>

    <script>
        function sanitizeInput() {
            var inputField = document.getElementById("phoneno");
            inputField.value = inputField.value.replace(/[^0-9]/g, ''); 
        }

        document.getElementById("phoneno").addEventListener("paste", function(e) {
            e.preventDefault();
            var pasteData = (e.clipboardData || window.clipboardData).getData('text');
            this.value = pasteData.replace(/[^0-9]/g, ''); 
        });
    </script>
@endsection



       