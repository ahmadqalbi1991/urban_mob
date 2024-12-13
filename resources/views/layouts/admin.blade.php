<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">



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

    <!-- Custom Stylesheet -->

    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <style type="text/css">

    body {

    background-color: #130101;

    z-index: 900;

        position:relative;

    }

    #particles-js {

       position: absolute;

      width: 100%;

      height: 100%;

    }

</style>

</head>



<body class="h-100">

    <div id="preloader">

        <div class="loader"></div>

    </div>

    <div id="particles-js"></div>

     @yield('content')    

    <!-- Common JS -->

    <script src="{{asset('plugins/common/common.min.js')}}"></script>

    <!-- Custom script -->

    <script src="{{asset('js/custom.min.js')}}"></script>

    <script src="{{asset('js/particles.min.js')}}">  </script>

    <script src="{{asset('js/ParticlesJS.js')}}"></script>

</body>



</html>