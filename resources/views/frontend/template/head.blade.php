<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>TheValue.com</title>

    <script
    src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
    crossorigin="anonymous"></script>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{ asset('assets/css/ie10-viewport-bug-workaround.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/navbar-static-top.css') }}" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="{{ asset('assets/js/ie8-responsive-file-warning.js') }}"></script><![endif]-->
    <script src="{{ asset('assets/js/ie-emulation-modes-warning.js') }}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://use.fontawesome.com/13a5048f89.js"></script>

    <!-- custom -->
    <link href="{{ asset('css/web.css') }}" rel="stylesheet">

    <script src="{{ asset('js/js.cookie.js') }}"></script>
    <script src="{{ asset('js/general.js') }}?refresh=20170410"></script>

    <!-- Google GA -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-91745739-1', 'auto');
        ga('send', 'pageview');
    </script>

    <meta property="og:site_name" content="{{ $fbMeta['site_name'] }}">
    <meta property="og:url" content="{{ $fbMeta['url'] }}">
    <meta property="og:type" content="{{ $fbMeta['type'] }}">
    <meta property="og:title" content="{{ $fbMeta['title'] }}">
    <meta property="og:description" content="{{ $fbMeta['description'] }}">
    <meta property="og:image" content="{{ $fbMeta['image'] }}">
    <meta property="fb:app_id" content="{{ $fbMeta['app_id'] }}">
    {{--<meta property="fb:admins" content="1136380453091512">--}}


</head>