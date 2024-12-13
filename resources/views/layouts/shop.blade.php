<!--
 // **************************************************
 // ******* Name: drora
 // ******* Description: Bootstrap 4 Admin Dashboard
 // ******* Version: 1.0.0
 // ******* Released on 2019-02-08 15:41:24
 // ******* Support Email : quixlab.com@gmail.com
 // ******* Support Skype : sporsho9
 // ******* Author: Quixlab
 // ******* URL: https://quixlab.com
 // ******* Themeforest Profile : https://themeforest.net/user/quixlab
 // ******* License: ISC
 // ***************************************************
-->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Go Daily Books</title>
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
    <!-- Datatable -->
    <link href="{{asset('plugins/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">

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
                <a href="{{url('/shop')}}">
                    <b class="logo-abbr"><img class="img-fluid" src="{{asset('images/logo.png')}}" alt="logo"></b>
                    <span class="brand-title"><img class="img-fluid" src="{{asset('images/logo.png')}}" alt="logo"></span>
                </a>
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
                
                <div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0" id="basic-addon1"><i class="icon-magnifier"></i></span>
                        </div>
                        <input type="search" class="border-0" placeholder="Search here">
                        <div class="drop-down animated flipInX d-md-none">
                            <form action="#">
                                <input type="text" class="form-control" placeholder="Search">
                            </form>
                        </div>
                    </div>
                </div>
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
                                <img src="{{asset('images/form-user.png')}}" height="40" width="40" alt="avatar">
                            </div>
                            <div class="drop-down dropdown-profile animated flipInX">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li><a href="{{url('my-profile')}}"><i class="icon-user"></i> <span>My Profile</span></a>
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
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="{{url('/shop')}}" aria-expanded="false">
                            <i class="icon-speedometer"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/shop-items')}}" aria-expanded="false"><i class="icon-star"></i><span class="nav-text">Shop Items</span></a>
                    </li>
                    <li>
                        <a href="{{url('/shop-request')}}" aria-expanded="false"><i class="icon-diamond"></i><span class="nav-text">Customer Request</span></a>
                    </li>
                    <li>
                        <a href="{{url('/my-customers')}}" aria-expanded="false"><i class="icon-user"></i><span class="nav-text">My Customers</span></a>
                    </li>
                    <li><a href="{{url('/package-request')}}" aria-expanded="false"><i class="icon-layers"></i><span class="nav-text">Subscriptions Request</span></a></li>
                    <li><a href="{{url('/my-packages')}}" aria-expanded="false"><i class="icon-layers"></i><span class="nav-text">My Subscriptions</span></a></li>
                     <li><a href="{{url('/package-addons-request')}}" aria-expanded="false"><i class="icon-bag"></i><span class="nav-text">Extra Order Request</span></a></li>
                    <li><a href="{{url('/my-package-addons')}}" aria-expanded="false"><i class="icon-bag"></i><span class="nav-text">My Extra Orders</span></a></li>
                    <li><a href="{{url('/shop-package-leave')}}" aria-expanded="false"><i class="icon-briefcase"></i><span class="nav-text">Leave</span></a></li>
                    <li><a href="{{url('/today-orders')}}" aria-expanded="false"><i class="icon-handbag"></i><span class="nav-text">Today Orders</span></a></li>
                    <li><a href="{{url('/my-orders')}}" aria-expanded="false"><i class="icon-handbag"></i><span class="nav-text">My Orders</span></a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icon-folder"></i><span class="nav-text">Invoice</span></a>
                        <ul aria-expanded="false">
                            <li><a href="{{url('/generate-invoice')}}">Generate Invoice</a></li>
                            <li><a href="{{url('/order-invoice')}}">Invoices</a></li>
                        </ul>
                    </li>
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
            <p>Copyright © <a href="javascript:void(0)" target="_blank">Pearl Like Technology</a> <?=date('Y');?></p>
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
    <script src="{{asset('plugins/datatables/js/jquery.dataTables.min.js')}}"></script>

    <!-- Init files -->
    <!-- <script src="{{asset('js/dashboard/dashboard-1.js')}}"></script> -->
    <script src="{{asset('js/plugins-init/datatables.init.js')}}"></script>

</body>

</html>