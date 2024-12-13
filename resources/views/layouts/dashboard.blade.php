



<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Urbanmop</title>

    <!-- Favicon icon -->

    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">

    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">

    <!-- Datatable -->

    <link href="{{asset('plugins/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

    <!-- Custom Stylesheet -->

    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <link href="{{asset('css/custom.css')}}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />

    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css" rel="stylesheet" />

    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />

    <style type="text/css">
        thead{
            font-size: 12px;
        }
        tfoot{
            font-size: 13px;
        }
        .search-field {
            height: 96%;
        }
        .bg-soft-dark {
            background-color: var(--soft-dark) !important;
        }
        .badge-md {
            height: 24px;
            width: 24px;
            font-size: 0.75rem;
        }
        .badge-inline {
            width: auto;
        }
        .select2-selection {
            height: 34px !important;
        }
        .select2-selection__rendered {
            margin-top: 2px !important;
        } 
        .select2-selection__arrow {
            margin-top: 2px !important;
        }
        .mt-13 {
            margin-top: 13%;
        }
        .mt-14 {
            margin-top: 14%;
        }
        .mt-10 {
            margin-top: 10%;
        }
        .mt-29 {
            margin-top: 29px;
        }
    </style>

    <style>
        .switch {
          position: relative;
          display: inline-block;
          width: 50px;
          height: 24px;
        }

        .switch input { 
          opacity: 0;
          width: 0;
          height: 0;
        }

        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
        }

        .slider:before {
          position: absolute;
          content: "";
          height: 18px;
          width: 18px;
          left: 4px;
          bottom: 3px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }

        input:checked + .slider {
          background-color: #95cc47;
        }

        input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
          -webkit-transform: translateX(26px);
          -ms-transform: translateX(26px);
          transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }

        .slider.round:before {
          border-radius: 50%;
        }

        .bg-grad-1 {
            background-color: #875fc0;
            background-image: linear-gradient(315deg, #875fc0 0%, #5346ba 74%);
        }

        .bg-grad-3 {
            background-color: #47c5f4;
            background-image: linear-gradient(315deg, #47c5f4 0%, #6791d9 74%);
        }

        .bg-grad-2 {
            background-color: #eb4786;
            background-image: linear-gradient(315deg, #eb4786 0%, #b854a6 74%);
        }

        .bg-grad-4 {
            background-color: #ffb72c;
            background-image: linear-gradient(315deg, #ffb72c 0%, #f57f59 74%);
            color: white;
        }

        .right-search {
            margin-left: 28%;
        }

        .btn-primary {
            background-color: #ffb72c;
            background-image: linear-gradient(315deg, #ffb72c 0%, #f57f59 74%);
            color: white;
        }

        .input-group-text {
/*             background: #ee0d0d; */
/*             color: #fff; */
/*             border: 1px solid #ee0d0d; */
/*            border-radius: 0px !important;*/
        }
        .serviceattr {
            width: 925px !important; 
        }
        
        table.dataTable thead>tr>th.sorting:before{
            display: none;
        }
        table.dataTable thead>tr>th.sorting:after{
            display: none;
        }
        .metismenu {
            margin-top: 20% !important;
        }
        .bg-grey {
            background-color: lightgray;
        }
        .borders {
            border-bottom: revert;
            border-top: revert;
            border-right: revert;
            border-left: revert;
            
        }
    </style>

</head>



<body>



    <!--*******************

        Preloader start

    ********************-->

    <div id="preloader">

        <div class="loader"></div>

    </div>

    <!--*******************

        Preloader end

    ********************-->



    

    <!--**********************************

        Main wrapper start

    ***********************************-->

    <div id="main-wrapper">



        <!--**********************************

            Nav header start

        ***********************************-->

        <div class="nav-header">

            <div class="brand-logo">

                <?php $setting = App\HomeSetting::first(); ?>

                @if($setting->admin_logo)
                
                    <a href="{{url('dashboard')}}">
                    
                        <b class="logo-abbr"><img class="img-fluid" src="{{asset('/uploads/home/'.$setting->admin_side_logo)}}" alt="logo"></b>

                        <span class="brand-title"><img class="img-fluid mb-4 ml-4" src="{{asset('/uploads/home/'.$setting->admin_side_logo)}}" alt="logo"></span>
                    
                    </a>

                @endif

            </div>

            <div class="nav-control">

                <div class="hamburger">

                    <span class="toggle-icon"><i class="icon-menu"></i></span>

                </div>

            </div>

        </div>

        <!--**********************************

            Nav header end

        ***********************************-->



        <!--**********************************

            Header start

        ***********************************-->

        <div class="header">    

            <div class="header-content clearfix">


                <div class="header-right">

                    <ul class="clearfix">

                        <li class="icons d-none d-md-flex">

                            <a href="javascript:void(0)" class="window_fullscreen-x">

                                <i class="icon-frame"></i>

                            </a>

                        </li>

                        <li class="icons">

                            <div class="user-img c-pointer-x">

                                <span class="activity active"></span>
                                @if(Auth::user()->profile)
                                <img src="{{asset('/uploads/user/'.Auth::user()->profile)}}" height="40" width="40" alt="avatar">
                                @else
                                <img src="{{asset('images/form-user.png')}}" height="40" width="40" alt="avatar">
                                @endif

                            </div>

                            <div class="drop-down dropdown-profile animated flipInX">

                                <div class="dropdown-content-body">

                                    <ul>

                                        <li><a href="{{url('profile-setting')}}"><i class="icon-user"></i> <span>Profile Setting</span></a>

                                        </li>

                                        <li><a href="{{url('logout')}}"><i class="icon-key"></i> <span>Logout</span></a>

                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </li>

                    </ul>

                </div>





            </div>

        </div>

        <!--**********************************

            Header end ti-comment-alt

        ***********************************-->



        <!--**********************************

            Sidebar start

        ***********************************-->

        <div class="nk-sidebar">           

            <div class="nk-nav-scroll mt-4">

                <ul class="metismenu" id="menu">
                    @can('dashboard.dashboard')
                    <li>

                        <a href="{{url('/dashboard')}}" aria-expanded="false">

                            <i class="icon-speedometer"></i><span class="nav-text">Dashboard</span>

                        </a>

                    </li>
                    @endcan
                    <!-- <li>

                        <a href="{{url('/items')}}" aria-expanded="false"><i class="icon-diamond"></i><span class="nav-text">Items</span></a>

                    </li> -->
                    @can('operator.manage')
                    <li>

                        <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icon-user"></i><span class="nav-text">Manage Operator</span></a>

                        <ul aria-expanded="false">
                            @can('operator.manage')
                            <li><a href="{{url('/operators')}}">Operators</a></li>
                            @endcan

                            @if(Auth::check() && Auth::user()->getRoleNames() && Auth::user()->getRoleNames()[0]=='Admin' || Auth::user()->getRoleNames()[0]=='admin' && Auth::user()->role=='Admin' || Auth::user()->role=='admin') 
                            <li><a href="{{url('/manage/role')}}">Roles</a></li>
                            @endif
                            
                        </ul>

                    </li>
                    @endcan
                   
                    <li>

                        <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icon-bag"></i><span class="nav-text">Manage Services</span></a>

                        <ul aria-expanded="false">
                            @can('service.manage')
                            <li><a href="{{url('service')}}">All Services</a></li>
                            @endcan
                            @can('service.create')
                            <li><a href="{{url('service/create')}}">Add New Service</a></li>
                            @endcan
                            @can('category.manage')
                            <li>
                                <a class="has-arrow" href="javascript:void()" aria-expanded="false"><span class="nav-text">Categories</span></a>

                                <ul aria-expanded="false">
                                   
                                    <li><a href="{{route('category')}}">Categories</a></li>
                                    @can('subcategory.manage')
                                    <li><a href="{{route('subcategory')}}">Sub Category</a></li>
                                    @endcan
                                    @can('childcategory.manage')
                                    <li><a href="{{url('child-category')}}">Child Category</a></li>
                                    @endcan

                                </ul>
                            </li>   
                            @endcan
                            @can('master.slot')
                            <li><a href="{{url('slots')}}">Slot Master</a></li>
                            @endcan
                            @can('attribute.manage')
                            <li><a href="{{route('attribute')}}">Attributes</a></li>
                            @endcan
                            
                            
                        </ul>

                    </li>

                    @can('vendor.manage')
                    <li><a href="{{route('vendors')}}" aria-expanded="false"><i class="icon-user"></i><span class="nav-text">Vendors</span></a></li>
                    @endcan
                    @can('booking.manage')
                    <li><a href="{{route('bookings')}}" aria-expanded="false"> <i class="fa fa-book" aria-hidden="true"></i> <span class="nav-text">Service Bookings</span></a></li>
                    @endcan
                    @can('coupon.manage')
                    <li><a href="{{route('coupons')}}" aria-expanded="false"><i class="fa fa-gift" aria-hidden="true"></i><span class="nav-text">Coupons</span></a></li>
                    @endcan
                    <!-- <li><a href="{{route('question')}}" aria-expanded="false"><i class="icon-list"></i><span class="nav-text">Question</span></a></li> -->

                    @can('customer.manage')
                    <li><a href="{{route('customers')}}" aria-expanded="false"><i class="icon-user"></i><span class="nav-text">Customers</span></a></li>
                    @endcan
                    @can('notification.manage')
                    <li><a href="{{url('vendor/notifications')}}" aria-expanded="false"><i class="icon-settings"></i><span class="nav-text">Notifications</span></a></li>
                    @endcan
                    @can('User')
                    <!-- <li><a href="{{route('users')}}" aria-expanded="false"><i class="icon-user"></i><span class="nav-text">Users</span></a></li> -->
                    @endcan

                    @can('offlinebooking.create')
                    <li><a href="{{route('offline.booking')}}" aria-expanded="false"><i class="icon-settings"></i><span class="nav-text">Offline Booking</span></a></li>
                    @endcan

                    @can('offlinebooking.list')
                    <li><a href="{{route('offline.bookings')}}" aria-expanded="false"><i class="icon-list"></i><span class="nav-text">Offline Bookings List</span></a></li>
                    @endcan

                    <li><a href="{{route('review')}}" aria-expanded="false"><i class="icon-list"></i><span class="nav-text">Review</span></a></li>


                    <li>

                        <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icon-layers"></i><span class="nav-text">Manage Blog</span></a>

                        <ul aria-expanded="false">
                            @can('blogcategory.manage')
                            <li><a href="{{url('blog-category')}}">Blog Categories</a></li>
                            @endcan
                            @can('blog.manage')
                            <li><a href="{{ url('blog') }}">Blogs</a></li>
                            @endcan
                        </ul>

                    </li>

                    @can('report.manage')
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false"> <i class="fa fa-file" aria-hidden="true"></i> <span class="nav-text">Report</span></a>
                        <ul aria-expanded="false">
                            @can('partner.report')
                            <li><a href="{{url('partner/details/report')}}">Partner Details</a></li>
                            @endcan
                            @can('customer.report')
                            <li><a href="{{url('customer/details/report')}}">Customer Details</a></li>
                            @endcan
                            @can('revenue.report')
                            <li><a href="{{url('revenue/bookings/report')}}">Revenue Bookings</a></li>
                            @endcan
                            @can('settlement.report')
                            <li><a href="{{url('partner/settlement/report')}}">Partner Settlement</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcan

                    @can('setting.manage')
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icon-settings"></i><span class="nav-text">Settings</span></a>
                        <ul aria-expanded="false">
                           <!--  <li><a href="{{url('/setting1')}}">Common Setting</a></li>
                            <li><a href="{{url('/setting2')}}">Content Setting</a></li> -->
                            @can('customer.setting')
                            <li><a href="{{url('/setting3')}}">Customer Setting</a></li>
                            @endcan
                            @can('vendor.setting')
                            <li><a href="{{url('/vendor/setting3')}}">Vendor Setting</a></li>
                            @endcan
                            @can('home.setting')
                            <li><a href="{{route('home.setting')}}">Home Setting</a></li>
                            @endcan
                            @can('admin.setting')
                            <li><a href="{{route('admin.setting')}}">Admin Setting</a></li>
                            @endcan
                            @can('web.setting')
                            <li><a href="{{url('web-settings')}}">Web Setting</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcan

                    

                    <!-- <li><a href="{{url('/customers')}}" aria-expanded="false"><i class="icon-user"></i><span class="nav-text">Customers</span></a></li> -->

                   <!--  <li><a href="{{url('/member/request')}}" aria-expanded="false"><i class="icon-screen-smartphone"></i><span class="nav-text">Members</span></a></li>

                    <li><a href="{{url('/packages')}}" aria-expanded="false"><i class="icon-layers"></i><span class="nav-text">Subscriptions</span></a></li>

                    <li><a href="{{url('/addons')}}" aria-expanded="false"><i class="icon-briefcase"></i><span class="nav-text">Add-ons (Extra Orders)</span></a></li>

                    <li><a href="{{url('/package-leave')}}" aria-expanded="false"><i class="icon-settings"></i><span class="nav-text">Leaves</span></a></li>

                    <li><a href="{{url('/all-orders')}}" aria-expanded="false"><i class="icon-handbag"></i><span class="nav-text">Orders</span></a></li>

                    <li><a href="{{url('/all-invoices')}}" aria-expanded="false"><i class="icon-bag"></i><span class="nav-text">Invoices</span></a></li> -->



                </ul>

            </div>

        </div>

        <!--**********************************

            Sidebar end

        ***********************************-->

        @yield('content')

         <!--**********************************

            Footer start

        ***********************************-->

        <div class="footer">

            <div class="copyright">

            <p>Copyright © <a href="javascript:void(0)" target="_blank">Urban Mop</a> <?=date('Y');?></p>

            </div>

        </div>

        <!--**********************************

            Footer end

        ***********************************-->





    </div>

    <!--**********************************

        Main wrapper end

    ***********************************-->



    <!--**********************************

        Scripts

    ***********************************-->

    <script src="{{asset('plugins/common/common.min.js')}}"></script>

    <script src="{{asset('js/custom.min.js')}}"></script>

    <script src="{{asset('js/settings.js')}}"></script>

    <script src="{{asset('js/quixnav.js')}}"></script>

    <script src="{{asset('js/styleSwitcher.js')}}"></script>





    <script src="{{asset('plugins/d3v3/index.js')}}"></script>

    <script src="{{asset('plugins/topojson/topojson.min.js')}}"></script>

    <script src="{{asset('plugins/datamaps/datamaps.world.min.js')}}"></script>



    <script src="{{asset('plugins/jqueryui/js/jquery-ui.min.js')}}"></script>

    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>



    <!-- Datatable -->

    <!-- <script src="{{asset('plugins/datatables/js/jquery.dataTables.min.js')}}"></script> -->

    <!--  New Datatable -->
<!--     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',
                stateSave: true,
                "bDestroy": true,
            } );
        } );
    </script> -->
     <!-- Validetor -->

    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>



    <!-- Init files -->

    <!-- <script src="{{asset('js/dashboard/dashboard-1.js')}}"></script> -->

    <!-- <script src="{{asset('js/plugins-init/datatables.init.js')}}"></script> -->

    <script src="{{asset('js/plugins-init/jquery.validate-init.js')}}"></script>



     <!-- tinymce -->

    <script src="{{asset('plugins/tinymce/tinymce.min.js')}}"></script>  

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
    
    <script type="text/javascript">
        $(document).ready(function() {
        $('.select2').select2();
    });
    </script>

    <script type="text/javascript">
      
        $(document).ready(function (e) {
         
           
           $('#image').change(function(){
                    
            let reader = new FileReader();
         
            reader.onload = (e) => { 
         
              $('#preview-image-before-upload').attr('src', e.target.result); 
            }
         
            reader.readAsDataURL(this.files[0]); 
           
           });
           
        });
         
    </script> 

    <script type="text/javascript">
      
        $(document).ready(function (e) {
         
           
           $('#image_second').change(function(){
                    
            let reader = new FileReader();
         
            reader.onload = (e) => { 
         
              $('#preview-image-before-upload-second').attr('src', e.target.result); 
            }
         
            reader.readAsDataURL(this.files[0]); 
           
           });
           
        });
     
    </script> 

    <script>

        tinymce.init({

            selector: ".summernote",

            height: 400,

            menubar: false,

            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',

            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | forecolor backcolor removeformat | alignleft aligncenter alignright alignjustify | insertfile image media link | outdent indent |  numlist bullist | pagebreak | charmap emoticons | fullscreen  preview print | ltr rtl | codesample code',

            toolbar_sticky: true,

            toolbar_mode: 'sliding',

            emoticons_append: {

                custom_mind_explode: {

                  keywords: ['brain', 'mind', 'explode', 'blown'],

                  char: '🤯'

                }

              },



            /* enable title field in the Image dialog*/

            image_title: true,

            /* enable automatic uploads of images represented by blob or data URIs*/

            automatic_uploads: true,

            /*

                URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)

                images_upload_url: 'postAcceptor.php',

                here we add custom filepicker only to Image dialog

            */

            file_picker_types: 'image',

            /* and here's our custom image picker*/

            file_picker_callback: function (cb, value, meta) {

                var input = document.createElement('input');

                input.setAttribute('type', 'file');

                input.setAttribute('accept', 'image/*');



                /*

                Note: In modern browsers input[type="file"] is functional without

                even adding it to the DOM, but that might not be the case in some older

                or quirky browsers like IE, so you might want to add it to the DOM

                just in case, and visually hide it. And do not forget do remove it

                once you do not need it anymore.

                */



                input.onchange = function () {

                var file = this.files[0];



                var reader = new FileReader();

                reader.onload = function () {

                    /*

                    Note: Now we need to register the blob in TinyMCEs image blob

                    registry. In the next release this part hopefully won't be

                    necessary, as we are looking to handle it internally.

                    */

                    var id = 'blobid' + (new Date()).getTime();

                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;

                    var base64 = reader.result.split(',')[1];

                    var blobInfo = blobCache.create(id, file, base64);

                    blobCache.add(blobInfo);



                    /* call the callback and populate the Title field with the file name */

                    cb(blobInfo.blobUri(), { title: file.name });

                };

                reader.readAsDataURL(file);

                };



                input.click();

            },

            //content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'

    });

    $().ready(function(){

      $('.alert').delay(2000);
       $('.alert').hide(3000);
    })

  </script>



<script>
        function clsAlphaNoOnly (e) {  // Accept only alpha numerics, no special characters 
            var regex = new RegExp("^[a-zA-Z0-9 ]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }

            e.preventDefault();
            return false;
        }
</script>

@yield('script')
</body>



</html>