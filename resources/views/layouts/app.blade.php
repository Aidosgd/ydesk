<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}" />
    <meta name="keywords" content="{{ $seo['keywords'] }}" />

    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    @include('parts.header')

    @yield('content')

    @include('parts.footer')

    <!-- Scripts -->
    {{--<script src="/js/app.js"></script>--}}
    <!-- modernizr js -->
    <script src="/js/vendor/modernizr-2.6.2.min.js"></script>
    <!-- jquery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- owl carouserl js -->
    <script src="/js/owl.carousel.min.js"></script>
    <!-- bootstrap js -->

    <script src="/js/bootstrap.min.js"></script>
    <!-- wow js -->
    <script src="/js/wow.min.js"></script>
    <!-- slider js -->
    <script src="/js/slider.js"></script>
    <script src="/js/jquery.fancybox.js"></script>
    <!-- template main js -->
    <script src="/js/main.js"></script>
</body>
</html>
