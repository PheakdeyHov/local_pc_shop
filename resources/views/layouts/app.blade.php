<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Test Offline</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="{{url('assets/css/bootstrap/bootstrap.min.css')}}">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="{{url('assets/icons/bootstrap-icons/font/bootstrap-icons.css')}}">

    <!-- SWEETALERT2 -->
    <link rel="stylesheet" href="{{url('assets/css/sweetalert2/sweetalert2.min.css')}}">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{url('assets/css/style.css')}}">

    <!-- SELECT2 CSS -->
    <link rel="stylesheet" href="{{url('assets/css/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/select2/select2-bootstrap-5-theme.min.css')}}">
</head>
<body>

    @yield('contents')
    @yield('scripts')


    <!-- SCRIPT BOOTSTRAP -->
    <script src="{{url('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>

    <!-- SCRIPT JQUERY -->
    <script src="{{url('assets/js/jquery-3.7.1.min.js')}}"></script>

    <!-- SCRIPT SWEETALERT2 -->
    <script src="{{url('assets/js/sweetalert2/sweetalert2.min.js')}}"></script>

    <!-- SCRIPT JS -->
    <script src="{{url('assets/js/main.js')}}"></script>

    <!-- SELECT2 SCRIPT -->
    <script src="{{url('assets/js/select2/select2.min.js')}}"></script>
</body>
</html>
